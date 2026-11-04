<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone',
        'address_line',
        'city',
        'province',
        'postal_code',
        'is_default',
        'label', // Home, Office, etc.
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helpers
    public function getFullAddressAttribute(): string
    {
        return "{$this->address_line}, {$this->city}, {$this->province} {$this->postal_code}";
    }
}
