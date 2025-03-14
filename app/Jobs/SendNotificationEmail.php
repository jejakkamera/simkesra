<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendNotif;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pesan;
    protected $admins;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pesan, $admins)
    {
        $this->pesan = $pesan;
        $this->admins = $admins;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->admins as $admin) {
            Mail::to($admin->email)->send(new SendNotif($this->pesan));
        }
    }
}
