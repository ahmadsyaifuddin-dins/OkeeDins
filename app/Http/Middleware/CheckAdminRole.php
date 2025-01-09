<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'Administrator') {
            session()->flash('error', 'Maaf Administrator anda Admin bukan Pelanggan :)');
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}