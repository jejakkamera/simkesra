<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Mail\SendKelulusan;
use Illuminate\Support\Facades\Mail;
use App\Models\UserTest;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function bulknotif()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $usersTest = UserTest::with('user')->get();
        // Looping dan kirim email ke setiap pengguna berdasarkan email dari relasi user
        foreach ($usersTest as $userTest) {
            Mail::to($userTest->user->email)->send(new SendKelulusan($userTest));
        }
        die("Email terkirim");
    }

}
