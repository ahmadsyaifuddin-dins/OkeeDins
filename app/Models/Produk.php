<?php

namespace App\Models;

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
}
