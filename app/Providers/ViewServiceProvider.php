<?php

namespace App\Providers;

use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
                $view->with('wishlistCount', $wishlistCount);
            } else {
                $view->with('wishlistCount', 0);
            }
        });
    }
}
