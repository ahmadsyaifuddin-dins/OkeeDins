<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';

    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'status',
        'payment_status',
        'payment_date'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
}
