<?php
namespace App\Livewire\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordVerif extends Controller
{
    public function verifyResetPassword($email, $token)
    {
        // Decode email yang di-base64
        $email = base64_decode($email);

        // Cari user berdasarkan email
        $user = User::where('email', $email)->first();
        // dd($user);
        // Cek apakah user dan token valid
        if (!$user || $user->remember_token !== $token) {
            return redirect()->route('auth-login-basic')->with('error', 'Invalid token or email.');
        }
            $user->password = Hash::make($user->remember_token);
            $user->remember_token = null; // Reset token agar tidak bisa digunakan lagi
            $user->save();

        return redirect()->route('auth-login-basic')->with('message', 'Your password has been reset. You can now log in whit new password.');
    }


}
