<?php

namespace App\Http\Controllers;

use App\Jobs\ImportExcelJob;
use Illuminate\Support\Facades\Auth;
use App\Models\Pemenangan;
use App\Models\Profile;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Validateqr extends Controller
{

    public function BiodataSave(Request $request,$id_pendaftaran){
        $model = Pemenangan::where('id', $id_pendaftaran)->first();
        if ($model) {
            $validated = $request->validate([
                'nik' => 'required|max:16',  // Ensures NIK is required and no longer than 16 characters
            ]);
            $profile = Profile::where('id', $model->profile_id)->first();
            $profile->nama_lengkap = $request->nama_lengkap;
            $profile->nik = $request->nik;
            $profile->tempat_lahir = $request->tempat_lahir;
            $profile->tanggal_lahir = $request->tanggal_lahir;
            $profile->alamat = $request->alamat;
            $profile->desa = $request->desa;
            $profile->nama_ibu = $request->nama_ibu;
            $profile->save();

            
            return redirect()->route(session('active_role') . '.PeriodFlagging', ['id_pendaftar' => $id_pendaftaran,'id_periode' => $model->periode])
            ->with(['success' => 'Berhasil divalidasi']);

            
        } else {
            return redirect(session('active_role') . '/dashboard/')->with(['error' => 'Data tidak ditemukan']);
        }

    }

    public function ValidasiSave(Request $request,$id_pendaftaran){
        // dd(auth()->user()->email);
        $model = Pemenangan::where('id', $id_pendaftaran)->first();
        if ($model) {
            
                date_default_timezone_set('Asia/Jakarta');
                $model->verif_teller = $request->verif_teller;
                $model->id_verif_teller = auth()->user()->email;
                $model->tanggal_verif_teller = date('Y-m-d H:i:s');
                $model->no_rekening = $request->no_rekening;
                $model->save();

                $profile = Profile::where('id', $model->profile_id)->first();

                if (!$profile) {
                    return redirect()->back()->with('error', 'Profile tidak ditemukan!');
                }
                // Simpan gambar KTP jika ada
                if ($request->has('ktp_image')) {
                    $ktpImage = $request->input('ktp_image');
                    $ktpPath = $this->saveBase64Image($ktpImage, 'ktp');
                    $profile->fotoktp = $ktpPath;
                }
        
                // Menyimpan Foto Diri jika ada
                if ($request->has('foto_diri_image')) {
                    $fotoDiriImage = $request->input('foto_diri_image');
                    $fotoDiriPath = $this->saveBase64Image($fotoDiriImage, 'foto_diri');
                    $profile->fotodiri = $fotoDiriPath;
                }

                // Simpan perubahan ke Profile
                $profile->save();
                session()->flash('message', 'Berhasil divalidasi penyaluran kepadar : '.$profile->nama_lengkap);
                return redirect()->route(session('active_role') . '.PeriodScanQrcode', ['periode' => $model->periode]);

     
        } else {
            return redirect(session('active_role') . '/dashboard/')->with(['error' => 'Data tidak ditemukan']);
        }
    }

    private function saveBase64Image($base64Image, $folder)
    {
        // Memisahkan data URI
        $image_parts = explode(';', $base64Image);
        $image_base64 = explode(',', $image_parts[1])[1];

        // Mengonversi base64 menjadi file gambar
        $imageName = Str::uuid() . '.jpg';
        $imagePath = storage_path('app/public/uploads/' . $folder . '/' . $imageName);

        file_put_contents($imagePath, base64_decode($image_base64));

        return 'uploads/' . $folder . '/' . $imageName;
    }

    public function idqr(Request $request)
    {
        $pendaftar = $request->input('pendaftar');
        $periode = $request->input('periode');
        $model['pendaftar'] = Pemenangan::where('id', $pendaftar)->where('periode', $periode)->first();
        if ($model['pendaftar']) {
                return response()->json(['success' => true,'redirectUrl' => url(strtolower(session('active_role')).'/apps/qr/validate/'.$pendaftar.'/'.$periode), 'message' => 'Data processed :'.$pendaftar]);
        } else {
            // Handle jika data tidak ditemukan, misalnya redirect atau tampilkan pesan
            return response()->json(['success' => false, 'message' => 'Not Found :'.$pendaftar]);
        }

    }

    public function validateKacer($id_pendaftaran,$id_periode){
        // echo $id_pendaftaran;
        // $users = Pendaftaran::where('id', $id_pendaftaran)->where('id_periode', $id_periode)->first();

        // // $users = Pendaftaran::query()->where('pendaftaran.id', $id_pendaftaran)->first();
        // if (!$users) {
        //     return redirect(strtolower(auth()->user()->role) . '/dashboard/')->with(['error' => 'Data tidak ditemukan']);
        // }
        // $Profile = Profile::query()->join('users', 'users.id', '=', 'profile.id_users')->where('id_users', $users->id_user)->first();
        // $forms = $users->PendaftaranInputan;

        // $model['user'] = User::find($users->id_user);

        // return view('teller.validasi', [
        //     'judul' => 'Pendaftar : ' . $Profile->name . '-' . $Profile->nomer_induk,
        //     'users' => $users,
        //     'forms' => $forms,
        //     'model' => $model,
        //     'Profile' => $Profile,
        //     'id_pendaftaran' => $id_pendaftaran,
        //     'action' => url(strtolower(auth()->user()->role) . '/kacer/validasi-save/' . $id_pendaftaran),
        // ]);
    }

}