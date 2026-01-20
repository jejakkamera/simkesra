<?php

namespace App\Livewire\Apps\Period\Validator;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\UserBantuan;
use App\Models\Period;

class PemenanganReport extends Component
{
    public ?string $periode = null;
    public array $pivotData = [];

    public function mount(): void
    {
        $this->periode = request()->query('periode') ?? (string) Period::active()->value('id');

        if (!$this->periode) {
            session()->flash('error', '⚠️ Tidak ada periode aktif saat ini.');
            $this->pivotData = [];
            return;
        }

        /* ─────────────────────────────────────────────
         * 1️⃣  Ambil daftar bantuan milik user
         * ───────────────────────────────────────────── */
        // Join ke bantuan_kelurahan dulu karena bantuan_kelurahan_id mereferensi bantuan_kelurahan.id
        $filterIds = UserBantuan::query()
            ->join('bantuan', 'bantuan.id', '=', 'user_bantuan.bantuan_id')
            ->leftJoin('bantuan_kelurahan as bk', 'bk.id', '=', 'user_bantuan.bantuan_kelurahan_id')
            ->leftJoin('kelurahans as k', 'k.id', '=', 'bk.kelurahan_id')
            ->where('user_id', auth()->id())
            ->select(
                'bantuan.id as bantuan_id',
                'bantuan.judul',
                'bantuan.wilayah',
                'k.id_kelurahan',
                'k.kemendagri_kelurahan_nama as nama_kelurahan'
            )
            ->get();

        if ($filterIds->isEmpty()) {
            session()->flash('error', '⚠️ Anda belum memiliki skema bantuan aktif.');
            $this->pivotData = [];
            return;
        }

        $bantuanWilayah  = $filterIds->whereNotNull('wilayah');
        $bantuanNasional = $filterIds->whereNull('wilayah');

        $bantuanIds = $filterIds->pluck('bantuan_id')->unique()->toArray();
        $kelurahanWilayahIds = $bantuanWilayah->pluck('id_kelurahan')->filter()->unique()->toArray();
        $hasNasional = $bantuanNasional->isNotEmpty();

        /* ─────────────────────────────────────────────
         * 2️⃣  Base data = DISTINCT profil dari pemenangan
         *     (tanpa batas periode)
         * ───────────────────────────────────────────── */
        $baseProfiles = DB::table('pemenangan as pm')
            ->join('profiles as p', 'p.id', '=', 'pm.profile_id')
            ->leftJoin('kelurahans as k', 'k.id_kelurahan', '=', 'p.id_kelurahan')
            ->leftJoin('wilayah_kec as wk', 'wk.id_wil', '=', 'k.id_kec')
            ->whereIn('pm.idbantuan', $bantuanIds)
            ->when(!$hasNasional && !empty($kelurahanWilayahIds), function ($q) use ($kelurahanWilayahIds) {
                $q->whereIn('p.id_kelurahan', $kelurahanWilayahIds);
            })
            ->select(
                'p.id as profile_id',
                'p.nik',
                'p.nama_lengkap',
                'k.kemendagri_kelurahan_nama as kelurahan',
                'wk.nm_wil as kecamatan'
            )
            ->groupBy('p.id', 'p.nik', 'p.nama_lengkap', 'k.kemendagri_kelurahan_nama', 'wk.nm_wil')
            ->get();

        /* ─────────────────────────────────────────────
         * 3️⃣  Ambil pemenangan periode aktif (status)
         * ───────────────────────────────────────────── */
        $current = DB::table('pemenangan as pm')
            ->join('profiles as p', 'p.id', '=', 'pm.profile_id')
            ->where('pm.periode', $this->periode)
            ->whereIn('pm.idbantuan', $bantuanIds)
            ->when(!$hasNasional && !empty($kelurahanWilayahIds), function ($q) use ($kelurahanWilayahIds) {
                $q->whereIn('p.id_kelurahan', $kelurahanWilayahIds);
            })
            ->select(
                'p.id as profile_id',
                'pm.status',
                'pm.created_at',
                DB::raw('GROUP_CONCAT(DISTINCT pm.idbantuan) as bantuan_ids')
            )
            ->groupBy('p.id', 'pm.status', 'pm.created_at')
            ->get()
            ->groupBy('profile_id');

        $judulById = $filterIds->pluck('judul', 'bantuan_id');

        /* ─────────────────────────────────────────────
         * 4️⃣  Susun pivot data per profil
         * ───────────────────────────────────────────── */
        $this->pivotData = $baseProfiles->map(function ($p) use ($current, $judulById) {
            $ajuan = $current->get($p->profile_id)?->first();

            if ($ajuan) {
                $status  = $ajuan->status ?? 'Diajukan';
                $tanggal = $ajuan->created_at ? date('d-m-Y H:i', strtotime($ajuan->created_at)) : '-';

                $judul = collect(explode(',', $ajuan->bantuan_ids))
                    ->filter()
                    ->unique()
                    ->map(fn($id) => $judulById[$id] ?? 'Skema#' . $id)
                    ->implode(', ');
            } else {
                $status  = '-';
                $tanggal = '-';
                $judul   = '-';
            }

            return [
                'nik'           => $p->nik,
                'nama_lengkap'  => $p->nama_lengkap,
                'kecamatan'     => $p->kecamatan ?? '-',
                'kelurahan'     => $p->kelurahan ?? '-',
                'bantuan'       => $judul,
                'status'        => $status,
                'tanggal_ajuan' => $tanggal,
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.apps.period.validator.pemenangan-report', [
            'pivotData' => $this->pivotData,
            'periode'   => $this->periode,
        ]);
    }
}
