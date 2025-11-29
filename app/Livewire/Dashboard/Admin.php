<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\DashboardInformation;
use App\Models\Period;
use App\Models\Pemenangan;
use App\Models\UserBantuan;

class Admin extends Component
{
    public $pictures;
    public $downloads;
    public array $bantuanPerYear = [];
    public array $activeRoles = [];
    public bool $showBantuanByYear = false;
    public array $periodOptions = [];
    public $selectedPeriod = null;
    public array $penerimaBySkema = [];
    public array $penerimaPerPeriode = [];
    public array $yearlySummary = [];
    public bool $hasSkemaChartData = false;
    public bool $hasPeriodChartData = false;

    public function mount()
    {
        // Pisahkan berdasarkan type
        $this->pictures = DashboardInformation::where('type', 'show_picture')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $this->downloads = DashboardInformation::where('type', 'download')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $this->activeRoles = auth()->user()?->getRoleNames()?->toArray() ?? [];

        $this->showBantuanByYear = in_array('admin', $this->activeRoles, true);
        $this->bantuanPerYear = $this->showBantuanByYear ? $this->fetchBantuanPerYear() : [];
        $this->yearlySummary = $this->showBantuanByYear ? $this->summarizeBantuanPerYear($this->bantuanPerYear) : [];

        $this->periodOptions = $this->fetchPeriodOptions();
        $this->selectedPeriod = $this->periodOptions[0]['id'] ?? null;
        $this->penerimaBySkema = $this->selectedPeriod ? $this->fetchPenerimaBySkema($this->selectedPeriod) : $this->emptyChart();
        $this->penerimaPerPeriode = $this->fetchPenerimaPerPeriode();
        $this->hasSkemaChartData = $this->chartHasValue($this->penerimaBySkema);
        $this->hasPeriodChartData = $this->chartHasValue($this->penerimaPerPeriode);
        $this->dispatchChartPayload();
    }

    public function render()
    {
        return view('livewire.dashboard.admin');
    }

    public function updatedSelectedPeriod($periodId): void
    {
        if (!$periodId) {
            $this->penerimaBySkema = $this->emptyChart();
            $this->hasSkemaChartData = false;
            $this->dispatchChartPayload();
            return;
        }

        $this->penerimaBySkema = $this->fetchPenerimaBySkema((int) $periodId);
        $this->hasSkemaChartData = $this->chartHasValue($this->penerimaBySkema);
        $this->dispatchChartPayload();
    }


    protected function fetchBantuanPerYear(): array
    {
        $data = UserBantuan::selectRaw('YEAR(created_at) as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderByDesc('year')
            ->take(5)
            ->get();

        if ($data->isEmpty()) {
            return [];
        }

        $max = max($data->pluck('total')->toArray());

        return $data->map(function ($row) use ($max) {
            $year = $row->year ?? 'Tidak diketahui';
            $total = (int) $row->total;
            $percentage = $max > 0 ? round(($total / $max) * 100) : 0;

            return [
                'year' => $year,
                'total' => $total,
                'percentage' => $percentage,
            ];
        })->toArray();
    }

    protected function summarizeBantuanPerYear(array $dataset): array
    {
        if (empty($dataset)) {
            return [];
        }

        $latest = $dataset[0];
        $previous = $dataset[1] ?? null;
        $difference = $previous ? $latest['total'] - $previous['total'] : null;

        $trend = null;
        if ($difference !== null) {
            if ($difference > 0) {
                $trend = 'Naik ' . number_format($difference) . ' penerima dibanding ' . $previous['year'];
            } elseif ($difference < 0) {
                $trend = 'Turun ' . number_format(abs($difference)) . ' penerima dibanding ' . $previous['year'];
            } else {
                $trend = 'Tetap dibanding ' . $previous['year'];
            }
        }

        return [
            'latest_year' => $latest['year'],
            'latest_total' => $latest['total'],
            'trend' => $trend,
        ];
    }

    protected function fetchPeriodOptions(): array
    {
        return Period::select('id', 'name_period')
            ->orderByDesc('start_date')
            ->get()
            ->map(fn ($period) => [
                'id' => (int) $period->id,
                'name' => $period->name_period ?? 'Periode ' . $period->id,
            ])
            ->toArray();
    }

    protected function fetchPenerimaBySkema(int $periodId): array
    {
        $result = Pemenangan::selectRaw('bantuan.judul as label, COUNT(*) as total')
            ->join('bantuan', 'bantuan.id', '=', 'pemenangan.idbantuan')
            ->where('pemenangan.status', 'Disetujui')
            ->where('pemenangan.periode', $periodId)
            ->groupBy('bantuan.judul')
            ->orderByDesc('total')
            ->get();

        if ($result->isEmpty()) {
            return $this->emptyChart();
        }

        return [
            'labels' => $result->pluck('label')->toArray(),
            'values' => $result->pluck('total')->map(fn ($value) => (int) $value)->toArray(),
            'total_value' => (int) $result->sum('total'),
        ];
    }

    protected function fetchPenerimaPerPeriode(): array
    {
        $result = Pemenangan::selectRaw('periods.name_period as label, COUNT(*) as total, periods.start_date')
            ->join('periods', 'periods.id', '=', 'pemenangan.periode')
            ->where('pemenangan.status', 'Disetujui')
            ->groupBy('periods.id', 'periods.name_period', 'periods.start_date')
            ->orderByDesc('periods.start_date')
            ->take(6)
            ->get()
            ->sortBy('start_date');

        if ($result->isEmpty()) {
            return $this->emptyChart();
        }

        return [
            'labels' => $result->pluck('label')->toArray(),
            'values' => $result->pluck('total')->map(fn ($value) => (int) $value)->toArray(),
            'total_value' => (int) $result->sum('total'),
        ];
    }

    protected function dispatchChartPayload(): void
    {
        $this->dispatch('dashboard-chart-update', [
            'bySkema' => $this->penerimaBySkema,
            'byPeriod' => $this->penerimaPerPeriode,
        ]);
    }

    protected function emptyChart(): array
    {
        return [
            'labels' => [],
            'values' => [],
            'total_value' => 0,
        ];
    }

    protected function chartHasValue(array $chart): bool
    {
        $values = $chart['values'] ?? [];
        return collect($values)->sum() > 0;
    }
}
