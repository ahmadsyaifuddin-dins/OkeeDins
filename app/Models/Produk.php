<?php

namespace App\Models;

use Illuminate\Contracts\Cache\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{

    protected $casts = [
        'diskon' => 'float'
    ];

    // Tambahkan mutator untuk memastikan nilai diskon selalu float
    protected function setDiskonAttribute($value)
    {
        $this->attributes['diskon'] = floatval(str_replace(',', '.', $value));
    }

    use HasFactory;

    protected $table = 'produk'; // Nama tabel
    protected $primaryKey = 'id'; // Primary key

    protected $fillable = [
        'id',
        'slug',
        'gambar',
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'diskon',
        'harga_diskon',
        'kategori_id',
        'recommended'
    ];

    // Relasi ke tabel kategori_produk
    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_id', 'id');
    }

    public function order_items()
    {
        return $this->hasMany(OrderItems::class, 'produk_id', 'id');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'produk_id', 'id');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'produk_id');
    }

    // public function thumbnail()
    // {
    //     return $this->hasOne(ProductImage::class)->where('is_thumbnail', true);
    // }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'produk_id', 'id');
    }
}
