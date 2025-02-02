<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'produk_id',
        'quantity',
        'price',
        'discount',
        'subtotal'
    ];

    public function produk() // Sesuaikan nama relasi
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function order()
    {
        return $this->belongsTo(Orders::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
