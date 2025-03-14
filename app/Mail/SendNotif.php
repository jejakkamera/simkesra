<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendNotif extends Mailable
{
    use Queueable, SerializesModels;

    public $pesan;
    /**
     * Create a new message instance.
     */
    public function __construct($pesan)
    {
        //
        $this->pesan = strval($pesan);
    }

    public function build()
    {
        return $this->view('livewire.pendaftar.sendnotifmassage')
                    ->with('pesan', $this->pesan)
                    ->subject('New Manual Payment');
    }

}

// $payment = Payment::where('user_id',Auth::user()->id)->first();
// view('livewire.pendaftar.account-va',['payment'=>$payment ]);
