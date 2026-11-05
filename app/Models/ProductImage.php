<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the product that owns this image.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the full URL for this image from Supabase Storage.
     * This would integrate with Supabase Storage service.
     */
    public function getPublicUrl(): string
    {
        // TODO: Implement Supabase Storage URL generation
        // For now, return the image path
        return $this->image_path;
    }

    /**
     * Scope to get only primary images.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to get images ordered by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Auto-set as primary if this is the first image for the product.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($image) {
            // If this is the first image for the product, make it primary
            if (!$image->product->images()->exists()) {
                $image->is_primary = true;
                $image->sort_order = 0;
            }
        });

        static::created(function ($image) {
            // If this image is set as primary, unset other primary images
            if ($image->is_primary) {
                $image->product->images()
                    ->where('id', '!=', $image->id)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }
        });
    }
}
