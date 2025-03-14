<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Login extends Component
{
    public $email;
    public $password;
    public $remember = false;
    use LivewireAlert;
    protected $rules = [
        'email' => 'required',
        'password' => 'required|min:8',
    ];

    public function login()
    {
        $this->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->flash('success', 'Hello!', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => false,
            ]);
            $user = Auth::user();
            if (!session()->has('active_role')) {
                session(['active_role' => $user->role]);
            }

            return redirect()->intended(session('active_role') . '/dashboard');
        } else {
            session()->flash('error', 'Email or password is incorrect.');
        }
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.layoutGuest');
    }
}
