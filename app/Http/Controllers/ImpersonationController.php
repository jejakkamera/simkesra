<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function leave(Request $request): RedirectResponse
    {
        if (!session()->has('impersonator_id')) {
            return redirect()->back();
        }

        $adminId   = session('impersonator_id');
        $adminRole = session('impersonator_role', 'admin');

        session()->forget(['impersonator_id', 'impersonator_name', 'impersonator_role']);

        $admin = User::find($adminId);

        if ($admin) {
            Auth::login($admin);
            session(['active_role' => $adminRole]);

            return redirect('/' . trim($adminRole, '/') . '/dashboard');
        }

        Auth::logout();
        session()->flush();

        return redirect()->route('auth-login-basic');
    }
}
