<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variation_id',
        'product_name',
        'sku_snapshot',
        'color_snapshot',
        'size_snapshot',
        'quantity',
        'price',
        'subtotal',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    /**
     * Get display name for this order item (includes snapshot details).
     * This preserves the exact product state at time of purchase.
     */
    public function getDisplayName(): string
    {
        $name = $this->product_name;
        
        $details = [];
        if ($this->color_snapshot) {
            $details[] = $this->color_snapshot;
        }
        if ($this->size_snapshot) {
            $details[] = $this->size_snapshot;
        }
        
        if (!empty($details)) {
            $name .= ' (' . implode(', ', $details) . ')';
        }
        
        return $name;
    }

    /**
     * Get full SKU for this order item.
     */
    public function getFullSku(): string
    {
        return $this->sku_snapshot ?? 'N/A';
    }

    /**
     * Create an order item from a cart item (with snapshots).
     * This preserves the exact state at time of purchase.
     */
    public static function createFromCartItem($cartItem, $orderId)
    {
        $product = $cartItem->product;
        $variation = $cartItem->variation;
        
        return self::create([
            'order_id' => $orderId,
            'product_id' => $cartItem->product_id,
            'variation_id' => $cartItem->variation_id,
            'product_name' => $product->name,
            'sku_snapshot' => $variation ? $variation->getFullSku() : ($product->sku ?? 'N/A'),
            'color_snapshot' => $cartItem->selected_color,
            'size_snapshot' => $cartItem->selected_size,
            'quantity' => $cartItem->quantity,
            'price' => $cartItem->getEffectivePrice(),
            'subtotal' => $cartItem->getEffectiveSubtotal(),
            'notes' => $cartItem->notes,
        ]);
    }
}
