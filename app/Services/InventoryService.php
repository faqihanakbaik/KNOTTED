<?php

namespace App\Services;

use App\Models\ProductVariation;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

/**
 * Inventory Service
 * 
 * Manages stock for product variations:
 * - Stock validation
 * - Stock reservation (during checkout)
 * - Stock confirmation (after order completion)
 * - Stock restoration (on cancellation)
 * - Low stock alerts
 */
class InventoryService
{
    // Low stock threshold
    const LOW_STOCK_THRESHOLD = 5;
    
    /**
     * Check if variation has enough stock.
     */
    public static function hasStock(int $variationId, int $quantity): bool
    {
        $variation = ProductVariation::find($variationId);
        
        if (!$variation) {
            return false;
        }
        
        return $variation->stock_quantity >= $quantity;
    }
    
    /**
     * Reserve stock for an order (reduces available stock).
     * Called during checkout.
     */
    public static function reserveStock(int $variationId, int $quantity): bool
    {
        return DB::transaction(function () use ($variationId, $quantity) {
            $variation = ProductVariation::lockForUpdate()->find($variationId);
            
            if (!$variation) {
                throw new \Exception('Product variation not found.');
            }
            
            if ($variation->stock_quantity < $quantity) {
                throw new \Exception("Insufficient stock for {$variation->getFullName()}. Available: {$variation->stock_quantity}, Requested: {$quantity}");
            }
            
            $variation->decrement('stock_quantity', $quantity);
            
            // Update product total stock if needed
            self::updateProductTotalStock($variation->product_id);
            
            return true;
        });
    }
    
    /**
     * Restore stock (e.g., when order is cancelled).
     */
    public static function restoreStock(int $variationId, int $quantity): bool
    {
        return DB::transaction(function () use ($variationId, $quantity) {
            $variation = ProductVariation::lockForUpdate()->find($variationId);
            
            if (!$variation) {
                return false;
            }
            
            $variation->increment('stock_quantity', $quantity);
            
            // Update product total stock if needed
            self::updateProductTotalStock($variation->product_id);
            
            return true;
        });
    }
    
    /**
     * Confirm sale (marks stock as sold - for analytics).
     * Called when order status becomes 'completed'.
     * This doesn't change stock_quantity (already reduced on reserve),
     * but can be used for tracking sold units.
     */
    public static function confirmSale(int $variationId, int $quantity): bool
    {
        $variation = ProductVariation::find($variationId);
        
        if (!$variation) {
            return false;
        }
        
        // Increment sold counter if you have one
        // $variation->increment('sold_quantity', $quantity);
        
        // For now, just return true (stock already reduced on reserve)
        return true;
    }
    
    /**
     * Manually adjust stock (admin function).
     */
    public static function adjustStock(int $variationId, int $newQuantity, string $reason): bool
    {
        return DB::transaction(function () use ($variationId, $newQuantity, $reason) {
            $variation = ProductVariation::lockForUpdate()->find($variationId);
            
            if (!$variation) {
                throw new \Exception('Product variation not found.');
            }
            
            $oldQuantity = $variation->stock_quantity;
            
            $variation->update([
                'stock_quantity' => $newQuantity,
            ]);
            
            // Log the adjustment (you can create StockLog model for this)
            // StockLog::create([
            //     'variation_id' => $variationId,
            //     'old_quantity' => $oldQuantity,
            //     'new_quantity' => $newQuantity,
            //     'adjustment' => $newQuantity - $oldQuantity,
            //     'reason' => $reason,
            // ]);
            
            // Update product total stock
            self::updateProductTotalStock($variation->product_id);
            
            return true;
        });
    }
    
    /**
     * Update product's total stock from all variations.
     */
    public static function updateProductTotalStock(int $productId): void
    {
        $totalStock = ProductVariation::where('product_id', $productId)
            ->sum('stock_quantity');
        
        Product::where('id', $productId)->update([
            'stock' => $totalStock,
        ]);
    }
    
    /**
     * Get variations with low stock.
     */
    public static function getLowStockVariations(int $threshold = null)
    {
        $threshold = $threshold ?? self::LOW_STOCK_THRESHOLD;
        
        return ProductVariation::with(['product'])
            ->where('stock_quantity', '<=', $threshold)
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity', 'asc')
            ->get();
    }
    
    /**
     * Get out of stock variations.
     */
    public static function getOutOfStockVariations()
    {
        return ProductVariation::with(['product'])
            ->where('stock_quantity', '<=', 0)
            ->get();
    }
    
    /**
     * Check if variation is low stock.
     */
    public static function isLowStock(int $variationId): bool
    {
        $variation = ProductVariation::find($variationId);
        
        if (!$variation) {
            return false;
        }
        
        return $variation->stock_quantity > 0 
            && $variation->stock_quantity <= self::LOW_STOCK_THRESHOLD;
    }
    
    /**
     * Check if variation is out of stock.
     */
    public static function isOutOfStock(int $variationId): bool
    {
        $variation = ProductVariation::find($variationId);
        
        if (!$variation) {
            return true;
        }
        
        return $variation->stock_quantity <= 0;
    }
    
    /**
     * Get inventory summary for admin dashboard.
     */
    public static function getInventorySummary(): array
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        
        $totalVariations = ProductVariation::count();
        $inStockVariations = ProductVariation::where('stock_quantity', '>', 0)->count();
        $lowStockVariations = ProductVariation::where('stock_quantity', '<=', self::LOW_STOCK_THRESHOLD)
            ->where('stock_quantity', '>', 0)
            ->count();
        $outOfStockVariations = ProductVariation::where('stock_quantity', '<=', 0)->count();
        
        $totalStockUnits = ProductVariation::sum('stock_quantity');
        
        return [
            'total_products' => $totalProducts,
            'active_products' => $activeProducts,
            'total_variations' => $totalVariations,
            'in_stock_variations' => $inStockVariations,
            'low_stock_variations' => $lowStockVariations,
            'out_of_stock_variations' => $outOfStockVariations,
            'total_stock_units' => $totalStockUnits,
        ];
    }
    
    /**
     * Bulk update stock from array.
     * Useful for admin bulk operations.
     * 
     * @param array $stockData [variation_id => new_quantity]
     */
    public static function bulkUpdateStock(array $stockData, string $reason = 'Bulk stock update'): array
    {
        $results = [
            'success' => [],
            'failed' => [],
        ];
        
        foreach ($stockData as $variationId => $newQuantity) {
            try {
                self::adjustStock($variationId, $newQuantity, $reason);
                $results['success'][] = $variationId;
            } catch (\Exception $e) {
                $results['failed'][$variationId] = $e->getMessage();
            }
        }
        
        return $results;
    }
}
