<?php

namespace App\Providers;

use App\Models\Orders;
use App\Models\Wishlist;
use App\Models\Transaction;
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
                $bayarCount = Orders::where('user_id', Auth::id())
                    ->where('status', 'pending')
                    ->where('payment_method', 'transfer')
                    ->count();

                $view->with([
                    'wishlistCount' => $wishlistCount,
                    'bayarCount' => $bayarCount
                ]);
            } else {
                $view->with([
                    'wishlistCount' => 0,
                    'bayarCount' => 0
                ]);
            }
        });

        // View::composer('*', function ($view) {
        //     $view->with('produk', function ($produk) {
        //         return $produk->productImages->count() > 0;
        //     });
        // });
    }
}
