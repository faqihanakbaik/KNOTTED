<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'product_type', // ready_stock or made_to_order
        'lead_time_days',
        'stock', // Deprecated: will be removed in favor of variations
        'image',
        'images',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'images' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'lead_time_days' => 'integer',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessor for image attribute
    public function getImageAttribute($value)
    {
        if (!$value) {
            return null;
        }

        // If it's already a full URL (http/https), return as is
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        // If it starts with /, it's already a path from public root
        if (str_starts_with($value, '/')) {
            return $value;
        }

        // Otherwise, prepend /images/products/ for backwards compatibility
        return '/images/products/' . $value;
    }

    // Helper methods
    public function isInStock()
    {
        // If product has variations, check if any variation has stock
        if ($this->variations()->exists()) {
            return $this->variations()->inStock()->exists();
        }
        
        // Otherwise, check product-level stock (deprecated)
        return $this->stock > 0 && $this->is_active;
    }

    public function getTotalStock()
    {
        // If product has variations, sum up variation stock
        if ($this->variations()->exists()) {
            return $this->variations()->sum('stock_quantity');
        }
        
        // Otherwise, return product-level stock (deprecated)
        return $this->stock;
    }

    public function getPrimaryImage()
    {
        return $this->productImages()->primary()->first();
    }

    public function getAllImages()
    {
        return $this->productImages()->ordered()->get();
    }

    public function isMadeToOrder()
    {
        return $this->product_type === 'made_to_order';
    }

    public function isReadyStock()
    {
        return $this->product_type === 'ready_stock';
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                // Products with variations
                $q->whereHas('variations', function($v) {
                    $v->where('stock_quantity', '>', 0);
                })
                // OR products without variations but with stock
                ->orWhere(function($q2) {
                    $q2->whereDoesntHave('variations')
                       ->where('stock', '>', 0);
                });
            });
    }

    public function scopeMadeToOrder($query)
    {
        return $query->where('product_type', 'made_to_order');
    }

    public function scopeReadyStock($query)
    {
        return $query->where('product_type', 'ready_stock');
    }

    // Auto-generate slug and SKU
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            
            if (empty($product->sku)) {
                // Generate SKU: KNT-CATEGORY-XXXX
                $categoryCode = $product->category 
                    ? strtoupper(substr($product->category->slug, 0, 3))
                    : 'GEN';
                $random = strtoupper(Str::random(4));
                $product->sku = "KNT-{$categoryCode}-{$random}";
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}
