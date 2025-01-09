<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PelangganMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // Menjalankan segala Proses di Market hanya bisa dilakukan ketika sudah Login sebagai pelanggan 
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'Pelanggan') {
            return $next($request);
        }

        return redirect()->route('home.index');
    }
}