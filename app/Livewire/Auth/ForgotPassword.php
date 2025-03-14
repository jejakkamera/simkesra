<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendForgotPassword;
use Illuminate\Http\Request;

class ForgotPassword extends Component
{
    public $email;

    public function sendResetLink()
    {
        $this->validate(['email' => 'required|email']);

        // Cek apakah email ada di database
        $user = User::where('email', $this->email)->first();

        if ($user) {
            // Generate password baru
            $newPassword = Str::random(8);
            $user->remember_token = $newPassword;
            $user->save();
            // Kirim email reset dengan password baru

            // Send the email
            Mail::to($this->email)->send(new SendForgotPassword($this->email));

            return back()->with('message', 'A reset password link has been sent to your email.');
        } else {
            $this->addError('email', 'Email not found.');
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')->layout('layouts.layoutGuest');
    }
}
