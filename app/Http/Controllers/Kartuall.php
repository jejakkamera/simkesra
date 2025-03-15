<?php

namespace App\Http\Controllers;

use App\Jobs\ImportExcelJob;
use Illuminate\Support\Facades\Auth;
use App\Models\Pemenangan;
use App\Models\Profile;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class Kartuall extends Controller
{
    public $uuid;
    public $profile;

    public function index()
    {   
        $this->uuid = request()->query('UserId');
        $this->profile = Pemenangan::join('profiles', 'profiles.id', '=', 'pemenangan.profile_id')->join('wilayah_kec', 'profiles.kode_kecamatan', '=', 'wilayah_kec.id_wil')
                                    
                                    ->select('pemenangan.*', 'profiles.*','wilayah_kec.*','pemenangan.id as uuid') // Sesuaikan dengan kolom yang ingin diambil
                                    ->with(['period', 'skema']) // Load relasi period dan skema
                                    ->get();
        return view('livewire.apps.penerima.bantuan.kartu',[
            'profiles' => $this->profile
        ]);
    }
}
