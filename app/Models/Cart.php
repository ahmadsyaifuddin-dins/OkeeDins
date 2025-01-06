<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'cart'; // Nama tabel

    protected $fillable = [
        'user_id',
        'produk_id',
        'quantity',
        'price',
        'amount',
        'status'
    ];

    // Relationship dengan Product
    public function product()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }

    // Relationship dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship dengan Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scope untuk mengambil cart yang statusnya masih 'new'
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    // Method untuk menghitung total amount
    public function updateAmount()
    {
        $this->amount = $this->price * $this->quantity;
        $this->save();
    }

    // Method untuk mengecek stok produk cukup
    public function hasEnoughStock()
    {
        return $this->product->stok >= $this->quantity;
    }
}
