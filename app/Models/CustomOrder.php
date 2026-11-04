<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CustomOrder extends Model
{
    protected $fillable = [
        'user_id',
        'request_type', // modify_existing, new_design
        'product_name',
        'category_id',
        'description',
        'color',
        'size',
        'quantity',
        'notes',
        'customer_expected_price',
        'admin_quoted_price',
        'final_agreed_price',
        'estimated_days',
        'status', // pending, quoted, negotiating, accepted, rejected, cancelled, converted
        'order_id', // Null until converted
    ];

    protected $casts = [
        'customer_expected_price' => 'decimal:2',
        'admin_quoted_price' => 'decimal:2',
        'final_agreed_price' => 'decimal:2',
        'quantity' => 'integer',
        'estimated_days' => 'integer',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(CustomOrderImage::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(CustomOffer::class)->orderBy('created_at', 'desc');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeNegotiating($query)
    {
        return $query->whereIn('status', ['quoted', 'negotiating']);
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    // Helpers
    public function canNegotiate(): bool
    {
        return in_array($this->status, ['pending', 'quoted', 'negotiating']);
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }
}
