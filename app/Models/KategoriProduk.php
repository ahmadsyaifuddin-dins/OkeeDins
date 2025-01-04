<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    use HasFactory;

    protected $table = 'kategori_produk'; // Nama tabel
    protected $primaryKey = 'id'; // Primary key

    protected $fillable = [
        'slug',
        'nama_kategori',
        'deskripsi',
    ];

    // Relasi ke tabel produk
    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_id', 'id');
    }
}
