<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    use LivewireAlert;

    public $email = '';
    public $password = '';
    public $remember = false;
    public ?string $gRecaptcha = null; // token reCAPTCHA dari JS

    protected function rules(): array
    {
        return [
            'email'      => ['required', 'email'],
            'password'   => ['required', 'string'],
            'gRecaptcha' => ['required'], // token wajib
        ];
    }

    protected array $messages = [
        'gRecaptcha.required' => 'Mohon selesaikan reCAPTCHA.',
    ];

    public function login()
    {
       

        $key = 'login:' . strtolower($this->email) . '|' . request()->ip();
        RateLimiter::hit($key, 900);
        
        logger('RateLimiter hit: '.RateLimiter::attempts($key).' attempts for '.$key);
        
        if (RateLimiter::tooManyAttempts($key, 30)) {
            // hitung sisa waktu
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);

            // 🔹 kirim event ke browser untuk redirect
            $this->dispatch('throttleRedirect', minutes: $minutes);

            // stop eksekusi lebih lanjut
            return;
        }

        $this->validate();

        // Verifikasi token ke Google
        try {
            $verify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => config('services.recaptcha.secret'),
                'response' => $this->gRecaptcha,
                'remoteip' => request()->ip(),
            ])->json();
        } catch (\Throwable $e) {
            $this->dispatchBrowserEvent('reset-recaptcha');
            throw ValidationException::withMessages([
                'gRecaptcha' => 'Tidak dapat menghubungi layanan reCAPTCHA. Coba lagi.',
            ]);
        }

        if (!data_get($verify, 'success')) {
            $this->dispatchBrowserEvent('reset-recaptcha');
            throw ValidationException::withMessages([
                'gRecaptcha' => 'Captcha tidak valid.',
            ]);
        }

        // Autentikasi pengguna
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::clear($key);

            $user = Auth::user();
            if (!session()->has('active_role')) {
                session(['active_role' => $user->role]);
            }

            return redirect()->intended(session('active_role') . '/dashboard');
        }

        RateLimiter::hit($key);
        $this->dispatch('reset-recaptcha');

        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.layoutGuest');
    }
}
