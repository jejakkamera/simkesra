<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$role): Response
    {
      $activeRole = session('active_role');
      if ($activeRole !== $role) {
                // Jika role tidak sesuai, kembalikan ke halaman sebelumnya atau tampilkan pesan kesalahan
                return redirect()->route('pages-misc-error')->withErrors(['error' => 'You do not have the required role to access this page.']);
      }

        return $next($request);
    }
}
