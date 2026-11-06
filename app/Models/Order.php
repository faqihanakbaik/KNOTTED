<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'payment_status',
        'subtotal',
        'shipping_cost',
        'total_amount',
        'payment_method',
        'payment_proof_path',
        'payment_proof_uploaded_at',
        'payment_verified_at',
        'payment_verified_by',
        'payment_rejection_reason',
        'customer_name',
        'customer_email',
        'customer_phone',
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'city',
        'province',
        'district',
        'postal_code',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code',
        'shipping_phone',
        'notes',
        'completed_at',
        'cancelled_at',
        'cancelled_by',
        'cancellation_reason',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'payment_proof_uploaded_at' => 'datetime',
        'payment_verified_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function paymentVerifiedBy()
    {
        return $this->belongsTo(User::class, 'payment_verified_by');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    // Generate unique order number using OrderNumberService
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = \App\Services\OrderNumberService::generate();
            }
        });
    }

    // Status check helpers
    public function isPending()
    {
        return $this->status === 'pending_payment';
    }

    public function isVerifying()
    {
        return $this->status === 'verifying_payment';
    }

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function isShipping()
    {
        return $this->status === 'shipping';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isPaymentFailed()
    {
        return $this->status === 'payment_failed';
    }

    // Payment helpers
    public function hasPaymentProof()
    {
        return !empty($this->payment_proof_path);
    }

    public function isPaymentVerified()
    {
        return !empty($this->payment_verified_at) && $this->status !== 'payment_failed';
    }

    // Formatting helpers
    public function getFormattedTotal()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getFormattedSubtotal()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedShippingCost()
    {
        return 'Rp ' . number_format($this->shipping_cost, 0, ',', '.');
    }

    public function getStatusLabel()
    {
        return \App\Services\PaymentService::getStatusLabel($this->status);
    }

    // Address helpers
    public function getFullAddress()
    {
        return implode(', ', array_filter([
            $this->shipping_address,
            $this->shipping_city,
            $this->shipping_province,
            $this->shipping_postal_code,
        ]));
    }

    // Scopes
    public function scopePendingPayment($query)
    {
        return $query->where('status', 'pending_payment');
    }

    public function scopeVerifying($query)
    {
        return $query->where('status', 'verifying_payment');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
