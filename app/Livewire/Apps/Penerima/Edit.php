<?php

namespace App\Livewire\Apps\Penerima;


use Illuminate\Http\Request;
use Livewire\Component;

use App\Models\Profile;

class Edit extends Component
{
    public $UserId;
    public $nama_lengkap;
    public $nik;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $alamat;
    public $desa;
    public $nama_ibu;

    public function mount(Request $request)
    {
        $this->UserId = $request->query('UserId');
        $this->loadProfileData();
    }

    public function loadProfileData()
    {
        $profile = Profile::find($this->UserId);

        if ($profile) {
            $this->nama_lengkap = $profile->nama_lengkap;
            $this->nik = $profile->nik;
            $this->tempat_lahir = $profile->tempat_lahir;
            $this->tanggal_lahir = $profile->tanggal_lahir;
            $this->alamat = $profile->alamat;
            $this->desa = $profile->desa;
            $this->nama_ibu = $profile->nama_ibu;
        }
    }

    public function save()
    {
        Profile::where('id', $this->UserId)
            ->update([
                'nama_lengkap' => $this->nama_lengkap,
                'nik' => $this->nik,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'alamat' => $this->alamat,
                'desa' => $this->desa,
                'nama_ibu' => $this->nama_ibu,
            ]);

        session()->flash('success', 'Berhasil Update Data');
        
        return redirect()->route(session('active_role') . '.PenerimaDatalist');
    }

    public function render()
    {
        $Pemenangan = Profile::where('profiles.id', $this->UserId)
                                    ->select('*') // Sesuaikan dengan kolom yang ingin diambil
                                    ->first();
        return view('livewire.apps.penerima.edit',['pemenangan' => $Pemenangan]);
    }
}
