<?php

namespace App\Livewire\Apps\Period\Bank;

use Livewire\Component;
use App\Models\Pemenangan;
use Illuminate\Http\Request;

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
        $this->pivotData = Pemenangan::query()
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
                    'pemenangan.tanggal_verif_teller',
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
                ->where('periode', $this->periode )
                ->get();


        return view('livewire.apps.period.bank.pivot',['pemenangan' =>$this->pivotData]);
    }
}
