<?php

namespace App\Livewire\Apps\Penerima\Bantuan;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Jobs\ImportExcelJob;
use Illuminate\Support\Facades\Auth;
use App\Models\Pemenangan;
use App\Models\Profile;
use Livewire\Attributes\On;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class Kartu extends Component
{
    use WithFileUploads;
    public $uuid;
    public $profile;

    public function mount()
    {
        $this->uuid = request()->query('UserId');
        $this->profile = Pemenangan::join('profiles', 'profiles.id', '=', 'pemenangan.profile_id')->join('wilayah_kec', 'profiles.kode_kecamatan', '=', 'wilayah_kec.id_wil')
                                    ->where('pemenangan.id', $this->uuid)
                                    ->select('pemenangan.*', 'profiles.*','wilayah_kec.*') // Sesuaikan dengan kolom yang ingin diambil
                                    ->with(['period', 'skema']) // Load relasi period dan skema
                                    ->first();

    }

    public function render()
    {   
        return view('livewire.apps.penerima.bantuan.kartu',[
            'profile' => $this->profile
        ]);
    }
}
