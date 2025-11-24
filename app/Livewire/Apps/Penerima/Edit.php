<?php

namespace App\Livewire\Apps\Penerima;


use Illuminate\Http\Request;
use Livewire\Component;

use App\Models\Profile;
use App\Models\Kelurahan;
use App\Models\WilayahKec;

class Edit extends Component
{
    public $UserId;
    public $nama_lengkap;
    public $nik;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $alamat;
    public $nama_ibu;

    public $id_kec = null;
    public $id_kelurahan = null;
    public $kecamatans = [];
    public $kelurahans = [];

    protected $listeners = ['updatedIdKec'];


    public function mount(Request $request)
    {
        $this->UserId = $request->query('UserId');
        $this->loadProfileData();
        $this->kecamatans = WilayahKec::where('id_induk_wilayah', '022100')
                                    ->orderBy('nm_wil', 'asc')
                                    ->get();

    }

    public function updatedIdKec($value)
    {
        // dd('test'); 
        // logger("Kecamatan berubah ke: ".$value);

        // Update daftar kelurahan berdasarkan kecamatan terpilih
        $this->kelurahans = Kelurahan::where('id_kec', $value)
            ->orderBy('kemendagri_kelurahan_nama', 'asc')
            ->get();
        
        // Kosongkan kelurahan sebelumnya jika ganti kecamatan
        $this->id_kelurahan = null;

        $this->dispatch('$refresh');

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
            $this->nama_ibu = $profile->nama_ibu;
            $this->id_kelurahan = $profile->id_kelurahan;

            $kel = Kelurahan::where('id_kelurahan', $profile->id_kelurahan)->first();
            $this->id_kec = $kel ? $kel->id_kec : null;

            // Jika sudah ada kecamatan, muat kelurahan yang sesuai
            if ($this->id_kec) {
                $this->kelurahans = Kelurahan::where('id_kec', $this->id_kec)->orderBy('kemendagri_kelurahan_nama')->get();
            }
        }
    }

     public function save()
    {
        $kelurahan = Kelurahan::where('id_kelurahan', $this->id_kelurahan)->first();

        Profile::where('id', $this->UserId)
            ->update([
                'nama_lengkap' => $this->nama_lengkap,
                'nik' => $this->nik,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'alamat' => $this->alamat,
                'id_kelurahan' => $this->id_kelurahan,
                'desa' => $kelurahan ? $kelurahan->kemendagri_kelurahan_nama : null,
                'kode_kecamatan' => $this->id_kec,
                'nama_ibu' => $this->nama_ibu,
            ]);

        session()->flash('success', 'Berhasil Update Data');
        return redirect()->route(session('active_role') . '.PenerimaDatalist');
    }

    public function render()
    {
        $Pemenangan = Profile::find($this->UserId);
        return view('livewire.apps.penerima.edit', [
            'pemenangan' => $Pemenangan,
            'kecamatans' => $this->kecamatans,
            'kelurahans' => $this->kelurahans,
        ]);
    }
}
