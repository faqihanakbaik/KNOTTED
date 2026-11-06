<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'payment_date',
        'payment_proof',
        'status',
        'notes',
        'admin_notes',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isUnderReview()
    {
        return $this->status === 'under_review';
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isVerified()
    {
        return $this->status === 'paid' || $this->status === 'verified';
    }
}
