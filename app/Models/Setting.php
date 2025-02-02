<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'app_name',
        'app_logo',
        'favicon',
        'office_address',
        'email',
        'phone',
        'footer_text'
    ];

    public static function get($key, $default = null)
    {
        $setting = self::first();
        return $setting ? $setting->$key : $default;
    }
}
