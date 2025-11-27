<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class SendForgotPassword extends Mailable
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
     use Queueable, SerializesModels;

    public $email;

    public function __construct($email)
    {
        $this->email = strval($email);
    }

    public function build()
    {
        // $verificationUrl = $this->verificationUrl($notifiable);
        $user = User::where('email', $this->email)->first();
        $url= url('auth/forgot-password-verif/'.base64_encode($user->email).'/'.$user->remember_token);
        return $this
                    ->markdown('livewire.pendaftar.forgot-password', ['url' => $url,'token'=>$user->remember_token])
                    ->subject('Reset Password');  // menggunakan custom view
    }
}
