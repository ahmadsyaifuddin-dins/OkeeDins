<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Model;


class User extends Authenticatable
{

    use HasFactory;

    // Menyatakan bahwa primary key menggunakan `user_id`
    protected $primaryKey = 'user_id';

    // Tentukan jika menggunakan auto-increment
    public $incrementing = true;

    // Tentukan tipe data primary key jika tidak integer
    protected $keyType = 'int';


    use HasApiTokens, HasFactory, Notifiable;


    /** * Indicates if the model should be timestamped. * * @var bool */
    // public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'alamat',
        'tgl_lahir',
        'jenis_kelamin',
        'telepon',
        'makanan_fav',
        'photo',
        'role',
        'type_char',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // 'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'password' => 'hashed',
    ];
    protected $attributes = [
        'remember_token' => '',
    ];
}
