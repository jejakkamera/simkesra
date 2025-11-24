<?php

namespace App\Livewire\Apps\Period\Validator;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\UserBantuan;

class ViewDashboard extends Component
{
    public string $periode;
    public ?array $data = [];

    public function mount(Request $request)
    {
        $this->periode = $request->query('periode');
    }

    public function render()
    {
        // ============================================================
        // 1️⃣ Ambil bantuan milik user (agar scope sesuai wilayahnya)
        // ============================================================
        $filterIds = UserBantuan::query()
            ->join('bantuan', 'bantuan.id', '=', 'user_bantuan.bantuan_id')
            ->leftJoin('kelurahans as k', 'k.id', '=', 'user_bantuan.bantuan_kelurahan_id')
            ->where('user_id', auth()->user()->id)
            ->select(
                'bantuan.id as bantuan_id',
                'bantuan.judul',
                'bantuan.wilayah',
                'user_bantuan.bantuan_kelurahan_id',
                'k.id_kelurahan',
                'k.kemendagri_kelurahan_nama as nama_kelurahan'
            )
            ->get();

        $bantuanWilayah  = $filterIds->whereNotNull('wilayah');
        $bantuanNasional = $filterIds->whereNull('wilayah');

        $bantuanIds = $filterIds->pluck('bantuan_id')->unique()->values()->all();
        $kelurahanWilayahIds = $bantuanWilayah->pluck('id_kelurahan')->filter()->unique()->values()->all();
        $hasNasional = $bantuanNasional->isNotEmpty();

        // ============================================================
        // 2️⃣ Hitung data chart
        // ============================================================
        $profileCountsWilayah = DB::table('pemenangan as pm')
            ->join('profiles as p', 'p.id', '=', 'pm.profile_id')
            ->select('p.id_kelurahan', DB::raw('COUNT(DISTINCT p.id) as total'))
            ->whereIn('pm.idbantuan', $bantuanWilayah->pluck('bantuan_id'))
            ->whereIn('p.id_kelurahan', $kelurahanWilayahIds)
            ->groupBy('p.id_kelurahan')
            ->pluck('total', 'p.id_kelurahan');

        $profileCountsNasional = DB::table('pemenangan as pm')
            ->join('profiles as p', 'p.id', '=', 'pm.profile_id')
            ->select('pm.idbantuan', DB::raw('COUNT(DISTINCT p.id) as total'))
            ->whereIn('pm.idbantuan', $bantuanNasional->pluck('bantuan_id'))
            ->groupBy('pm.idbantuan')
            ->pluck('total', 'pm.idbantuan');

        $chartData = collect();

        foreach ($bantuanWilayah as $row) {
            $chartData->push([
                'label' => "{$row->judul} - {$row->nama_kelurahan} (Kec. {$row->wilayah})",
                'total' => $profileCountsWilayah[$row->id_kelurahan] ?? 0,
            ]);
        }

        foreach ($bantuanNasional as $row) {
            $chartData->push([
                'label' => "{$row->judul} - Karawang",
                'total' => $profileCountsNasional[$row->bantuan_id] ?? 0,
            ]);
        }

        $labels = $chartData->pluck('label');
        $counts = $chartData->pluck('total');

        // ============================================================
        // 3️⃣ DETEKSI PERBEDAAN DATA ANTAR PERIODE (HANYA SESUAI WILAYAH USER)
        // ============================================================
        $perubahanData = DB::table('pemenangan as pm')
            ->join('profiles as p', 'p.id', '=', 'pm.profile_id')
            ->join('periods as per', 'pm.periode', '=', 'per.id')
            ->join('bantuan as b', 'pm.idbantuan', '=', 'b.id')
            ->leftJoin('kelurahans as k', 'p.id_kelurahan', '=', 'k.id_kelurahan')
            ->select(
                'p.nik',
                'p.nama_lengkap',
                DB::raw('GROUP_CONCAT(DISTINCT per.name_period ORDER BY per.start_date SEPARATOR ", ") as daftar_periode'),
                DB::raw('GROUP_CONCAT(DISTINCT b.judul ORDER BY b.judul SEPARATOR ", ") as daftar_bantuan'),
                DB::raw('GROUP_CONCAT(DISTINCT k.kemendagri_kelurahan_nama ORDER BY k.kemendagri_kelurahan_nama SEPARATOR ", ") as daftar_kelurahan'),
                DB::raw('COUNT(DISTINCT per.id) as total_periode'),
                DB::raw('COUNT(DISTINCT b.id) as total_bantuan'),
                DB::raw('COUNT(DISTINCT k.id_kelurahan) as total_kelurahan')
            )
            // ->whereIn('pm.idbantuan', $bantuanIds)
            ->when(!$hasNasional && !empty($kelurahanWilayahIds), function ($q) use ($kelurahanWilayahIds) {
                $q->whereIn('p.id_kelurahan', $kelurahanWilayahIds);
            })
            ->groupBy('p.nik', 'p.nama_lengkap')
            ->havingRaw('
                COUNT(DISTINCT b.id) > 1
                OR COUNT(DISTINCT k.id_kelurahan) > 1
            ')
            ->orderBy('p.nama_lengkap')
            ->get();

        return view('livewire.apps.period.validator.view-dashboard', [
            'filterIds' => $filterIds,
            'labels' => $labels,
            'counts' => $counts,
            'perubahanData' => $perubahanData,
        ]);
    }
}
