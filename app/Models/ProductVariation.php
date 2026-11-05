<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'color_name',
        'hex_code',
        'size_label',
        'stock_quantity',
        'price_adjustment',
        'sku_suffix',
    ];

    protected $casts = [
        'stock_quantity' => 'integer',
        'price_adjustment' => 'decimal:2',
    ];

    /**
     * Get the product that owns this variation.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get cart items using this variation.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'variation_id');
    }

    /**
     * Get order items using this variation.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variation_id');
    }

    /**
     * Check if this variation is in stock.
     */
    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    /**
     * Check if this variation has enough stock for the requested quantity.
     */
    public function hasStock(int $quantity): bool
    {
        return $this->stock_quantity >= $quantity;
    }

    /**
     * Get the final price for this variation.
     */
    public function getFinalPrice(): float
    {
        return $this->product->price + $this->price_adjustment;
    }

    /**
     * Get the full SKU including product SKU and suffix.
     */
    public function getFullSku(): string
    {
        $productSku = $this->product->sku ?? 'PROD';
        return $productSku . ($this->sku_suffix ?? '');
    }

    /**
     * Reduce stock by the given quantity.
     */
    public function reduceStock(int $quantity): bool
    {
        if (!$this->hasStock($quantity)) {
            return false;
        }

        $this->stock_quantity -= $quantity;
        return $this->save();
    }

    /**
     * Increase stock by the given quantity.
     */
    public function increaseStock(int $quantity): bool
    {
        $this->stock_quantity += $quantity;
        return $this->save();
    }

    /**
     * Scope to get only in-stock variations.
     */
    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    /**
     * Scope to get low-stock variations (stock <= 5).
     */
    public function scopeLowStock($query, int $threshold = 5)
    {
        return $query->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', $threshold);
    }

    /**
     * Scope to get out-of-stock variations.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', '<=', 0);
    }
}
