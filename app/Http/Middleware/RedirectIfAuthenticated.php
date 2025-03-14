<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        if (Auth::check() && session()->has('active_role')) {
            $activeRole = session('active_role');
            if ($activeRole) {
                return redirect()->intended(session('active_role') . '/dashboard');
            } else {
                // Jika tidak ada activeRole, logout
                Auth::logout();
                session()->flush();
                return redirect('/');
            }
        }

        return $next($request);
    }
}
