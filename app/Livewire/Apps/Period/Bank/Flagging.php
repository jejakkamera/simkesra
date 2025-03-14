<?php

namespace App\Livewire\Apps\Period\Bank;

use Livewire\Component;
use App\Models\Pemenangan;

class Flagging extends Component
{
    public $id_pendaftar;
    public $id_periode;

    public function mount($id_pendaftar, $id_periode)
    {
        $this->id_pendaftar = $id_pendaftar;
        $this->id_periode = $id_periode;
    }

    public function render()
    {
        $Pemenangan = Pemenangan::join('profiles', 'profiles.id', '=', 'pemenangan.profile_id')->join('wilayah_kec', 'profiles.kode_kecamatan', '=', 'wilayah_kec.id_wil')
                                    ->where('pemenangan.id', $this->id_pendaftar)
                                    ->where('pemenangan.periode', $this->id_periode)
                                    ->select('pemenangan.*', 'profiles.*','wilayah_kec.*','pemenangan.id as uuid') // Sesuaikan dengan kolom yang ingin diambil
                                    ->with(['period', 'skema']) // Load relasi period dan skema
                                    ->first();
                                    // dd($Pemenangan);
                                    return view('livewire.apps.period.bank.flagging',['pemenangan' => $Pemenangan,'updatebiodata'=>url(strtolower(session('active_role') ) . '/apps/biodata-save/' . $this->id_pendaftar),'action'=> url(strtolower(session('active_role') ) . '/apps/validasi-save/' . $this->id_pendaftar),]);
    }

}
