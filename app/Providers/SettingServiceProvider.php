<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        view()->composer('*', function ($view) {
            $view->with('appSettings', [
                'app_name' => Setting::get('app_name', 'OkeDins'),
                'app_logo' => Setting::get('app_logo'),
                'favicon' => Setting::get('favicon'),
                'office_address' => Setting::get('office_address'),
                'email' => Setting::get('email'),
                'phone' => Setting::get('phone'),
                'footer_text' => Setting::get('footer_text')
            ]);
        });
    }
}
