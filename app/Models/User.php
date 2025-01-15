<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use App\Models\Address;
use App\Models\Cart;
// use App\Models\Pesanan;

class User extends Authenticatable  implements MustVerifyEmail
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users'; // Nama tabel

    // Menyatakan bahwa primary key menggunakan `id`
    protected $primaryKey = 'id';

    // Tentukan jika menggunakan auto-increment
    public $incrementing = true;

    // Tentukan tipe data primary key jika tidak integer
    protected $keyType = 'int';


    /** * Indicates if the model should be timestamped. * * @var bool */
    public $timestamps = true;
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
        'last_login_ip'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
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

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function order()
    {
        return $this->hasMany(Orders::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}