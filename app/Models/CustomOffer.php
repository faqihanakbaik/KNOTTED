<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomOffer extends Model
{
    protected $fillable = [
        'custom_order_id',
        'offered_by', // user_id (customer or admin)
        'offered_by_role', // customer, admin
        'offered_price',
        'notes',
        'status', // pending, accepted, rejected, countered, withdrawn
    ];

    protected $casts = [
        'offered_price' => 'decimal:2',
    ];

    // Relationships
    public function customOrder(): BelongsTo
    {
        return $this->belongsTo(CustomOrder::class);
    }

    public function offeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'offered_by');
    }

    // Helpers
    public function isFromAdmin(): bool
    {
        return $this->offered_by_role === 'admin';
    }

    public function isFromCustomer(): bool
    {
        return $this->offered_by_role === 'customer';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
