<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Verify extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    public function render()
    {
        return view('livewire.auth.verify-email')->layout('layouts.layoutGuest');
    }
}
