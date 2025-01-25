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
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (auth()->user()->role !== 'Pelanggan') {
            return redirect()->route('home.index')->with('error', 'Akses ditolak. Anda bukan pelanggan.');
        }

        return $next($request);
    }
}