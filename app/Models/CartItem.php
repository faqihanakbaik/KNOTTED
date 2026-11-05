<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'variation_id',
        'selected_color',
        'selected_size',
        'quantity',
        'price',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // Relationships
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    // Helper methods
    public function getSubtotal()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Get the effective price for this cart item.
     * If variation exists, use variation's final price, otherwise use stored price.
     */
    public function getEffectivePrice()
    {
        if ($this->variation) {
            return $this->variation->getFinalPrice();
        }
        
        return $this->price;
    }

    /**
     * Get the effective subtotal for this cart item.
     */
    public function getEffectiveSubtotal()
    {
        return $this->getEffectivePrice() * $this->quantity;
    }

    /**
     * Check if the selected variation still has enough stock.
     */
    public function hasAvailableStock(): bool
    {
        if ($this->variation) {
            return $this->variation->hasStock($this->quantity);
        }
        
        // Fallback to product-level stock
        if ($this->product) {
            return $this->product->stock >= $this->quantity;
        }
        
        return false;
    }

    /**
     * Get display name for this cart item (includes variation details).
     */
    public function getDisplayName(): string
    {
        $name = $this->product->name ?? 'Product';
        
        $details = [];
        if ($this->selected_color) {
            $details[] = $this->selected_color;
        }
        if ($this->selected_size) {
            $details[] = $this->selected_size;
        }
        
        if (!empty($details)) {
            $name .= ' (' . implode(', ', $details) . ')';
        }
        
        return $name;
    }
}
