<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'Administrator') {
            return $next($request);
        }

        // Jika bukan Administrator, logout dan redirect ke login admin
        Auth::logout();
        return redirect()->route('admin.login')
            ->with('error', 'Anda harus login sebagai Administrator.');
    }
}
