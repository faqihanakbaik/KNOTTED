<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomOrderImage extends Model
{
    protected $fillable = [
        'custom_order_id',
        'image_path',
        'storage_path', // For Supabase Storage path
    ];

    // Relationships
    public function customOrder(): BelongsTo
    {
        return $this->belongsTo(CustomOrder::class);
    }

    // Helpers
    public function getUrlAttribute(): string
    {
        // Will be replaced with Supabase signed URL logic later
        return $this->image_path ? asset('storage/' . $this->image_path) : '';
    }
}
