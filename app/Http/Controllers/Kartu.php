<?php

namespace App\Http\Controllers;

use App\Jobs\ImportExcelJob;
use Illuminate\Support\Facades\Auth;
use App\Models\Pemenangan;
use App\Models\Profile;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class Kartu extends Controller
{
    public $uuid;
    public $profile;

    public function index($id)
    {   
        $this->uuid = request()->query('UserId');
        $this->profile = Pemenangan::join('profiles', 'profiles.id', '=', 'pemenangan.profile_id')->join('wilayah_kec', 'profiles.kode_kecamatan', '=', 'wilayah_kec.id_wil')
                                    ->where('pemenangan.id', $id)
                                    ->select('pemenangan.*', 'profiles.*','wilayah_kec.*','pemenangan.id as uuid') // Sesuaikan dengan kolom yang ingin diambil
                                    ->with(['period', 'skema']) // Load relasi period dan skema
                                    ->first();
        return view('livewire.apps.penerima.bantuan.kartu',[
            'profile' => $this->profile
        ]);
    }
}
