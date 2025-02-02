<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'order_number',
        'total_amount',
        'voucher_discount',
        'voucher_id',
        'qty',
        'payment_method',
        'payment_status',
        'status',
        'notes',
        'payment_proof'
    ];

    // Tambahkan accessor untuk format status yang lebih mudah dibaca
    public function getStatusLabelAttribute()
    {
        return [
            'pending' => 'Menunggu konfirmasi penjual',
            'awaiting payment' => 'Menunggu Pembayaran diverifikasi',
            // 'waiting_payment' => 'Menunggu Pembayaran Dikonfirmasi',
            'payment_rejected' => 'Pembayaran ditolak',
            'confirmed' => 'Pesanan dikonfirmasi',
            'processing' => 'Diproses',
            'delivered' => 'Dalam pengantaran',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ][$this->status] ?? ucfirst($this->status);
    }

    // Tambahkan accessor untuk format payment_status yang lebih mudah dibaca
    public function getPaymentStatusLabelAttribute()
    {
        return [
            'paid' => 'Lunas',
            'unpaid' => 'Belum Lunas'
        ][$this->payment_status] ?? ucfirst($this->payment_status);
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


    // Accessors & Mutators untuk menghitung total harga awal dan total diskon
    public function getTotalOriginalPriceAttribute()
    {
        return $this->orderItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    public function getTotalDiscountAttribute()
    {
        return $this->total_original_price - $this->total_amount;
    }

    // Define status constants for better code readability
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_AWAITING_PAYMENT = 'awaiting payment';
    const STATUS_PAYMENT_REJECTED = 'payment rejected';
    const STATUS_PROCESSING = 'processing';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

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

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'order_id');
    }

    // public function shipping()
    // {
    //     return $this->belongsTo(Shipping::class);
    // }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }

    public function items()
    {
        return $this->orderItems();
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
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

    // Sisi User
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_CONFIRMED => 'bg-blue-100 text-blue-800',
            self::STATUS_AWAITING_PAYMENT => 'bg-blue-100 text-blue-800',
            self::STATUS_PAYMENT_REJECTED => 'bg-red-100 text-red-800',
            self::STATUS_PROCESSING => 'bg-blue-100 text-blue-800',
            self::STATUS_DELIVERED => 'bg-indigo-100 text-indigo-800',
            self::STATUS_COMPLETED => 'bg-green-100 text-green-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getStatusColorAttribute()
    {
        return [
            'pending' => 'text-yellow-600',
            'awaiting payment' => 'text-blue-600',
            'payment_rejected' => 'text-red-600',
            'confirmed' => 'text-blue-600',
            'processing' => 'text-blue-600',
            'delivered' => 'text-indigo-600',
            'completed' => 'text-green-600',
            'cancelled' => 'text-red-600'
        ][$this->status] ?? 'text-gray-600';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        return $this->payment_status === self::PAYMENT_PAID 
            ? 'bg-green-100 text-green-800' 
            : 'bg-red-100 text-red-800';
    }

    // Sisi Admin
    public function getStatusBadgeAdminAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING, self::STATUS_AWAITING_PAYMENT => 'bg-warning',
            self::STATUS_CONFIRMED, self::STATUS_PROCESSING, self::STATUS_DELIVERED => 'bg-info',
            self::STATUS_COMPLETED => 'bg-success',
            self::STATUS_PAYMENT_REJECTED, self::STATUS_CANCELLED => 'bg-danger',
            default => 'bg-secondary'
        };
    }

         // Tambahkan accessor untuk warna status
    public function getStatusColorAdminAttribute()
    {
        return match ($this->status) {
            'pending', 'awaiting payment' => 'secondary',
            'confirmed', 'processing', 'delivered' => 'primary',
            'completed' => 'success',
            default => 'danger',
        };
    }

    public function getPaymentStatusBadgeAdminAttribute()
    {
        return $this->payment_status === self::PAYMENT_PAID 
            ? 'bg-success' 
            : 'bg-danger';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeAwaitingPayment($query)
    {
        return $query->where('status', self::STATUS_AWAITING_PAYMENT);
    }

    public function scopePaymentRejected($query)
    {
        return $query->where('status', self::STATUS_PAYMENT_REJECTED);
    }

    public function scopeProcess($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
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
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed()
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isAwaitingPayment()
    {
        return $this->status === self::STATUS_AWAITING_PAYMENT;
    }

    public function isPaymentRejected()
    {
        return $this->status === self::STATUS_PAYMENT_REJECTED;
    }

    public function isProcessing()
    {
        return $this->status === self::STATUS_PROCESSING;
    }

    public function isDelivered()
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function canBeCancelled()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function canBeProcessed()
    {
        return $this->status === self::STATUS_PENDING && $this->isPaid();
    }

    // Method to update order status
    public function updateStatus($status)
    {
        if (in_array($status, [self::STATUS_PENDING, self::STATUS_PAYMENT_REJECTED, self::STATUS_AWAITING_PAYMENT, self::STATUS_CONFIRMED, self::STATUS_PROCESSING, self::STATUS_DELIVERED, self::STATUS_COMPLETED, self::STATUS_CANCELLED])) {
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
