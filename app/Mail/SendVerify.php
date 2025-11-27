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

class SendVerify extends VerifyEmailBase
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

    // use Queueable, SerializesModels;

    // public $pesan;
    // /**
    //  * Create a new message instance.
    //  */
    // public function __construct($pesan)
    // {
    //     //
    //     $this->pesan = strval($pesan);
    // }

    // public function build($notifiable)
    // {
    //   $verificationUrl = $this->verificationUrl($notifiable);

    //     return $this->view('livewire.pendaftar.verify-email', ['url' => $verificationUrl])
    //                 ->layout('layouts.layoutGuest')
    //                 ->with('pesan', $this->pesan)
    //                 ->subject('Email Verification Subject');
    // }

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
                    ->markdown('livewire.pendaftar.verify-email', ['url' => $verificationUrl])
                    ->subject('Email Verification');  // menggunakan custom view
    }
}
