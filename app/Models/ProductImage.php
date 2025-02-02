<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'gambar_tambahan';
    
    protected $fillable = [
        'produk_id',
        'image_path',
        'is_thumbnail'
    ];

    protected $casts = [
        'is_thumbnail' => 'boolean'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
