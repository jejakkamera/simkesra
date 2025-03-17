<?php

namespace App\Livewire\Apps\Period\Bank;

use Livewire\Component;
use App\Models\Pemenangan;
use App\Models\User;
use App\Models\UserBantuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class Pivot extends Component
{
    public $periode;
    public $pivotData;
    public function mount(Request $request)
    {
        $this->periode = request()->query('periode');
    }

    public function goBackOnce()
    {
        $this->redirectRoute(session('active_role') . '.PeriodDashboardBank', ['periode' => $this->periode]);
    }

    public function render()
    {


        $pivotQuery = Pemenangan::query()
            ->join('profiles', 'profiles.id', '=', 'pemenangan.profile_id') // Join dengan tabel departments
                ->join('wilayah_kec', 'wilayah_kec.id_wil', '=', 'profiles.kode_kecamatan') // Join dengan tabel departments       
            ->join('periods', 'periods.id', '=', 'pemenangan.periode') // Join dengan tabel departments
            ->join('bantuan', 'bantuan.id', '=', 'pemenangan.idbantuan') // Join dengan tabel departments
            ->select(
                'pemenangan.id as uuid',
                'pemenangan.no_rekening',
                'pemenangan.jenis_rekening',
                'pemenangan.tipe_rekening',
                'pemenangan.id_verif_teller',
                DB::raw('DATE_FORMAT(pemenangan.tanggal_verif_teller, "%Y-%m-%d") as tanggal_verif_teller'),
                'pemenangan.verif_teller',
                'periods.name_period',
                'profiles.tempat_mengajar',
                'profiles.nama_lengkap',
                'profiles.nik',
                'profiles.desa',
                'wilayah_kec.nm_wil',
                'bantuan.judul',
                'bantuan.nominal',
                'bantuan.wilayah',
            )
            ->where('periode', $this->periode );

        if(session('active_role')=='unit'){
            $filterIds = UserBantuan::query()
                ->where('user_id', auth()->user()->id)
                ->pluck('bantuan_id') // Ambil kolom yang dipakai buat filtering
                ->toArray();

                $pivotQuery->whereIn('bantuan.id', $filterIds);
        } 
        $this->pivotData = $pivotQuery->get();


        return view('livewire.apps.period.bank.pivot',['pemenangan' =>$this->pivotData]);
    }
}
