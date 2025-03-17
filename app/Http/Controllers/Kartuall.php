<?php

namespace App\Http\Controllers;

use App\Jobs\ImportExcelJob;
use Illuminate\Support\Facades\Auth;
use App\Models\Pemenangan;
use App\Models\UserBantuan;
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
        $pivotQuery = Pemenangan::query()
            ->join('profiles', 'profiles.id', '=', 'pemenangan.profile_id')
            ->join('wilayah_kec', 'profiles.kode_kecamatan', '=', 'wilayah_kec.id_wil')
            ->join('bantuan', 'bantuan.id', '=', 'pemenangan.idbantuan') 
            ->select(
                'pemenangan.*',
                'profiles.nama_lengkap',
                'profiles.nik',
                'profiles.tempat_mengajar',
                'profiles.desa',
                'wilayah_kec.nm_wil',
                'bantuan.judul as bantuan_judul',
                'bantuan.nominal as bantuan_nominal',
                'bantuan.wilayah as bantuan_wilayah',
                'pemenangan.id as uuid'
            );
        // $pivotQuery = Pemenangan::join('profiles', 'profiles.id', '=', 'pemenangan.profile_id')->
        //                         join('wilayah_kec', 'profiles.kode_kecamatan', '=', 'wilayah_kec.id_wil')
        //                             ->select('pemenangan.*', 'profiles.*','wilayah_kec.*','pemenangan.id as uuid') // Sesuaikan dengan kolom yang ingin diambil
        //                             ->with(['period', 'skema']);

        if(session('active_role')=='unit'){
            $filterIds = UserBantuan::query()
                ->where('user_id', auth()->user()->id)
                ->pluck('bantuan_id') // Ambil kolom yang dipakai buat filtering
                ->toArray();

                $pivotQuery->whereIn('bantuan.id', $filterIds);
        } 
        $this->profile = $pivotQuery->get();
        return view('livewire.apps.penerima.bantuan.kartu',[
            'profiles' => $this->profile
        ]);
    }
}
