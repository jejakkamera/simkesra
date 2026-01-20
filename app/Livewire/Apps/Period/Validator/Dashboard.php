<?php

namespace App\Livewire\Apps\Period\Validator;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\UserBantuan;
use App\Models\Period;

class Dashboard extends Component
{
    public ?string $periode = null;
    public int $totalProfiles = 0;
    public int $totalPemenang = 0;
    public float $persentase = 0;

   public function render()
    {
        // =========================================
        // 1️⃣ Ambil periode dari query atau default ke periode aktif
        // =========================================
        $this->periode = request()->query('periode') ?? (string) \App\Models\Period::active()->value('id');
        
        // Jika tidak ada periode aktif, hentikan eksekusi dengan nilai default
        if (!$this->periode) {
            $this->totalProfiles = 0;
            $this->totalPemenang = 0;
            $this->persentase = 0;

            session()->flash('error', '⚠️ Tidak ada periode aktif saat ini.');
            return view('livewire.apps.period.validator.dashboard', [
                'totalProfiles' => 0,
                'totalPemenang' => 0,
                'persentase' => 0,
                'periode' => null,
            ]);
        }

        // =========================================
        // 2️⃣ Ambil bantuan milik user
        // =========================================
        // Join ke bantuan_kelurahan dulu karena bantuan_kelurahan_id mereferensi bantuan_kelurahan.id
        $filterIds = \App\Models\UserBantuan::query()
            ->join('bantuan', 'bantuan.id', '=', 'user_bantuan.bantuan_id')
            ->leftJoin('bantuan_kelurahan as bk', 'bk.id', '=', 'user_bantuan.bantuan_kelurahan_id')
            ->leftJoin('kelurahans as k', 'k.id', '=', 'bk.kelurahan_id')
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
        
            
        // Pisahkan bantuan wilayah vs nasional
        $bantuanWilayah  = $filterIds->whereNotNull('wilayah');
        $bantuanNasional = $filterIds->whereNull('wilayah');

        $bantuanIds = $filterIds->pluck('bantuan_id')->unique()->toArray();
        $kelurahanWilayahIds = $bantuanWilayah->pluck('id_kelurahan')->filter()->unique()->toArray();
        $hasNasional = $bantuanNasional->isNotEmpty();
            
        // Jika tidak ada bantuan, hentikan
        if (empty($bantuanIds)) {
            $this->totalProfiles = 0;
            $this->totalPemenang = 0;
            $this->persentase = 0;

            session()->flash('error', '⚠️ Anda belum memiliki skema bantuan yang aktif.');
            return view('livewire.apps.period.validator.dashboard', [
                'totalProfiles' => 0,
                'totalPemenang' => 0,
                'persentase' => 0,
                'periode' => $this->periode,
            ]);
        }

        // =========================================
        // 3️⃣ Hitung total profil unik (penerima) — tanpa batas periode
        // =========================================
        $this->totalProfiles = \DB::table('pemenangan as pm')
            ->join('profiles as p', 'p.id', '=', 'pm.profile_id')
            ->whereIn('pm.idbantuan', $bantuanIds)
            ->when(!$hasNasional && !empty($kelurahanWilayahIds), function ($q) use ($kelurahanWilayahIds) {
                $q->whereIn('p.id_kelurahan', $kelurahanWilayahIds);
            })
            ->select('p.id','p.nama_lengkap')
            ->count(\DB::raw('DISTINCT p.id')); // ❗️unik per profil
        // =========================================
        // 4️⃣ Hitung total pemenangan unik di periode aktif
        // =========================================
        $this->totalPemenang = \DB::table('pemenangan as pm')
            ->join('profiles as p', 'p.id', '=', 'pm.profile_id')
            ->where('pm.periode', $this->periode)
            ->whereIn('pm.idbantuan', $bantuanIds)
            ->when(!$hasNasional && !empty($kelurahanWilayahIds), function ($q) use ($kelurahanWilayahIds) {
                $q->whereIn('p.id_kelurahan', $kelurahanWilayahIds);
            })
            ->count(\DB::raw('DISTINCT p.id')); // ❗️unik per profil juga

        // =========================================
        // 5️⃣ Hitung persentase capaian
        // =========================================
        $this->persentase = $this->totalProfiles > 0
            ? round(($this->totalPemenang / $this->totalProfiles) * 100, 2)
            : 0;

        // =========================================
        // 6️⃣ Render View
        // =========================================
        return view('livewire.apps.period.validator.dashboard', [
            'totalProfiles' => $this->totalProfiles,
            'totalPemenang' => $this->totalPemenang,
            'persentase' => $this->persentase,
            'periode' => $this->periode,
        ]);
    }



    #[\Livewire\Attributes\On('dashboard')]
    public function dashboard()
    {
        $this->redirectRoute(session('active_role') . '.PeriodDashboardBank', [
            'periode' => $this->periode,
        ]);
    }

    #[\Livewire\Attributes\On('penerima')]
    public function penerima()
    {
        $this->redirectRoute(session('active_role') . '.PemenanganDatalist', [
            'periode' => $this->periode,
        ]);
    }

    #[\Livewire\Attributes\On('ajukan')]
    public function ajukan()
    {
        $this->redirectRoute(session('active_role') . '.PemenanganCreate', [
            'periode' => $this->periode,
        ]);
    }
    #[\Livewire\Attributes\On('pivotFlaging')]
    public function pivotFlaging()
    {
        $this->redirectRoute(session('active_role') . '.PemenanganReport', [
            'periode' => $this->periode,
        ]);
    }
}
