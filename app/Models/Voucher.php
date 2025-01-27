<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{

    protected $table = 'vouchers';

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'min_purchase',
        'max_uses',
        'used_count',
        'is_active',
        'valid_from',
        'valid_until'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'used_count' => 'integer'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'voucher_user')
            ->withPivot('order_id', 'discount_amount', 'used_at')
            ->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Orders::class);
    }

    public function isValid()
    {
        $now = now();
        return $this->is_active &&
            $now->between($this->valid_from, $this->valid_until) &&
            ($this->max_uses === null || $this->used_count < $this->max_uses);
    }

    public function calculateDiscount($totalAmount)
    {
        if (!$this->isValid() || $totalAmount < $this->min_purchase) {
            return 0;
        }

        if ($this->type === 'fixed') {
            return $this->value;
        }

        // Untuk tipe persentase
        return ($totalAmount * $this->value) / 100;
    }
}
