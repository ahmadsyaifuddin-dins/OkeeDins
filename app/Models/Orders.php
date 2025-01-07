<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;


    protected $fillable = [
        'order_number',
        'user_id',
        'sub_total',
        'shipping_id',
        'coupon',
        'total_amount',
        'qty',
        'payment_method',
        'payment_status',
        'status',
        'address1',
        'payment_proof'
    ];

    // Tambahkan accessor untuk format status yang lebih mudah dibaca
    public function getStatusLabelAttribute()
    {
        return [
            'pending' => 'Menunggu Pembayaran',
            'processing' => 'Diproses',
            'completed' => 'Selesai',
            'Cancelled' => 'Dibatalkan'
        ][$this->status] ?? ucfirst($this->status);
    }

    protected $casts = [
        'payment_method' => 'string',
        'payment_status' => 'string',
        'status' => 'string',
        'sub_total' => 'float',
        'coupon' => 'float',
        'total_amount' => 'float',
        'quantity' => 'integer',
    ];

    // Define status constants for better code readability
    const STATUS_Pending = 'pending';
    const STATUS_AWAITING_PAYMENT = 'awaiting_payment';
    const STATUS_PROCESS = 'processing';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'Cancelled';

    const PAYMENT_PAID = 'paid';
    const PAYMENT_UNPAID = 'unpaid';

    const PAYMENT_METHOD_COD = 'Cash On Delivery';
    const PAYMENT_METHOD_TRANSFER = 'Transfer';
    const PAYMENT_METHOD_MIDTRANS = 'Midtrans';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    // public function shipping()
    // {
    //     return $this->belongsTo(Shipping::class);
    // }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }

    // Accessors & Mutators
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getFormattedSubTotalAttribute()
    {
        return 'Rp ' . number_format($this->sub_total, 0, ',', '.');
    }

    public function getFormattedCouponAttribute()
    {
        return $this->coupon ? 'Rp ' . number_format($this->coupon, 0, ',', '.') : '-';
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            self::STATUS_Pending => 'bg-warning',
            self::STATUS_PROCESS => 'bg-info',
            self::STATUS_DELIVERED => 'bg-success',
            self::STATUS_CANCELLED => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    public function getPaymentStatusBadgeAttribute()
    {
        return $this->payment_status === self::PAYMENT_PAID ? 'bg-success' : 'bg-danger';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_Pending);
    }

    public function scopeProcess($query)
    {
        return $query->where('status', self::STATUS_PROCESS);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_PAID);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_UNPAID);
    }

    // Helper Methods
    public function isPaid()
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public function isUnpaid()
    {
        return $this->payment_status === self::PAYMENT_UNPAID;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_Pending;
    }

    public function isProcessing()
    {
        return $this->status === self::STATUS_PROCESS;
    }

    public function isDelivered()
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function canBeCancelled()
    {
        return $this->status === self::STATUS_Pending;
    }

    public function canBeProcessed()
    {
        return $this->status === self::STATUS_Pending && $this->isPaid();
    }

    // Method to update order status
    public function updateStatus($status)
    {
        if (in_array($status, [self::STATUS_Pending, self::STATUS_PROCESS, self::STATUS_DELIVERED, self::STATUS_CANCELLED])) {
            $this->update(['status' => $status]);
            return true;
        }
        return false;
    }

    // Method to update payment status
    public function updatePaymentStatus($status)
    {
        if (in_array($status, [self::PAYMENT_PAID, self::PAYMENT_UNPAID])) {
            $this->update(['payment_status' => $status]);
            return true;
        }
        return false;
    }

    // Boot method for any model events
    protected static function boot()
    {
        parent::boot();

        // Generate order number before creating
        static::creating(function ($order) {
            $order->order_number = $order->order_number ?? 'ORD-' . uniqid();
        });
    }
}
