<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // foreach ($roles as $role) {
        //     if (Auth::check() && Auth::user()->role == $role) {
        //         // return $next($request);
        //         return redirect()->route('market'); // Arahkan ke halaman lain

        //     }
        //     return $next($request);
        // }
        // // Auth::logout();
        // // return redirect()->route('login')->with('status', 'You are not authorized to access this page.');

        // Cek apakah pengguna sudah login dan memiliki salah satu peran yang dicegah
        if (Auth::check() && in_array(Auth::user()->role, $roles)) {
            return redirect()->route('market'); // Arahkan ke halaman lain
        }

        // Jika peran pengguna tidak dicegah, lanjutkan ke request berikutnya
        return $next($request);
    }
}
