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
use Illuminate\Http\Request;

ini_set('max_execution_time', 300);
ini_set('memory_limit', '2048M');

class Kartuall extends Controller
{
    public $uuid;
    public $profile;

    public function tandaterima(Request $request)
    {   
        $validated = $request->validate([
            'kecamatan_id' => 'required|exists:wilayah_kec,id_wil',
        ]);

        // Ambil datanya
        $kecamatanId = $validated['kecamatan_id'];
        $this->uuid = request()->query('UserId');
        $pivotQuery = Pemenangan::query()
                ->join('profiles', 'profiles.id', '=', 'pemenangan.profile_id')
                ->join('wilayah_kec', 'profiles.kode_kecamatan', '=', 'wilayah_kec.id_wil')
                ->join('bantuan', 'bantuan.id', '=', 'pemenangan.idbantuan') 
                ->where('wilayah_kec.id_wil', $kecamatanId)
                ->orderBy('profiles.desa','ASC')
                ->orderBy('bantuan.judul','ASC') // lalu urut bantuan
                ->orderBy('profiles.nama_lengkap','ASC') // lalu urut nama
                ->select(
                    'pemenangan.*',
                    'profiles.nama_lengkap',
                    'profiles.nik',
                    'profiles.tempat_mengajar',
                    'profiles.desa',
                    'profiles.alamat',
                    'wilayah_kec.nm_wil',
                    'bantuan.judul as bantuan_judul',
                    'bantuan.nominal as bantuan_nominal',
                    'bantuan.wilayah as bantuan_wilayah',
                    'pemenangan.id as uuid'
                )
                ->get()
                ->groupBy('desa'); 

            return view('livewire.apps.penerima.bantuan.beritaacara',compact('pivotQuery'));
            
    }

    public function indexkec(Request $request){
        $validated = $request->validate([
            'kecamatan_id' => 'required',
            'status_cetak' => 'required',
        ]);

         // Ambil parameter filter
        $kecamatanId = $validated['kecamatan_id'];
        $statusCetak = $validated['status_cetak'];

        // Query utama
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

        // Filter kecamatan jika tidak memilih "all"
        if ($kecamatanId !== 'all') {
            $pivotQuery->where('wilayah_kec.id_wil', $kecamatanId);
        }

        // Filter status cetak jika tidak memilih "all"
        if ($statusCetak !== 'all') {
            $pivotQuery->where('pemenangan.verif_teller', $statusCetak === 'sudah' ? 'Selesai' : '-');
        }

        // Filter berdasarkan role "unit"
        if (session('active_role') == 'unit') {
            $filterIds = UserBantuan::query()
                ->where('user_id', auth()->user()->id)
                ->pluck('bantuan_id')
                ->toArray();

            $pivotQuery->whereIn('bantuan.id', $filterIds);
        } 

        // Ambil hasil query
        $this->profile = $pivotQuery->get();
        $this->profile = $pivotQuery->get();
        return view('livewire.apps.penerima.bantuan.kartu',[
            'profiles' => $this->profile
        ]);
    }

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
