<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Order Service
 * 
 * Manages the complete order lifecycle:
 * - Order creation from cart
 * - Stock validation and reservation
 * - Order status transitions
 * - Order cancellation
 * - Order history
 */
class OrderService
{
    /**
     * Create order from cart with stock validation.
     * 
     * @throws \Exception if validation fails
     */
    public static function createFromCart(Cart $cart, array $shippingData, string $paymentMethod): Order
    {
        return DB::transaction(function () use ($cart, $shippingData, $paymentMethod) {
            // Validate cart is not empty
            if ($cart->items->isEmpty()) {
                throw new \Exception('Cart is empty. Please add items before checkout.');
            }
            
            // Validate all items have available stock
            foreach ($cart->items as $item) {
                if (!$item->hasAvailableStock()) {
                    throw new \Exception("Insufficient stock for {$item->getDisplayName()}. Please update your cart.");
                }
            }
            
            // Calculate totals
            $subtotal = 0;
            foreach ($cart->items as $item) {
                $subtotal += $item->getEffectiveSubtotal();
            }
            
            $shippingCost = $shippingData['shipping_cost'] ?? 0;
            $totalAmount = $subtotal + $shippingCost;
            
            // Create order
            $order = Order::create([
                'user_id' => $cart->user_id,
                'status' => 'pending_payment',
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'payment_method' => $paymentMethod,
                'shipping_address' => $shippingData['address'],
                'shipping_city' => $shippingData['city'],
                'shipping_province' => $shippingData['province'],
                'shipping_postal_code' => $shippingData['postal_code'],
                'shipping_phone' => $shippingData['phone'],
                'recipient_name' => $shippingData['recipient_name'] ?? $cart->user->name,
                'notes' => $shippingData['notes'] ?? null,
            ]);
            
            // Create order items from cart items
            foreach ($cart->items as $cartItem) {
                OrderItem::createFromCartItem($cartItem, $order->id);
                
                // Reserve stock
                if ($cartItem->variation_id) {
                    InventoryService::reserveStock($cartItem->variation_id, $cartItem->quantity);
                }
            }
            
            // Clear cart
            $cart->items()->delete();
            
            return $order;
        });
    }
    
    /**
     * Cancel order and restore stock.
     */
    public static function cancelOrder(Order $order, string $reason, ?int $cancelledBy = null): bool
    {
        return DB::transaction(function () use ($order, $reason, $cancelledBy) {
            // Check if order can be cancelled
            if (!in_array($order->status, ['pending_payment', 'payment_failed', 'processing'])) {
                throw new \Exception('Order cannot be cancelled at this stage.');
            }
            
            // Restore stock for all items
            foreach ($order->items as $item) {
                if ($item->variation_id) {
                    InventoryService::restoreStock($item->variation_id, $item->quantity);
                }
            }
            
            // Update order status
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => $cancelledBy,
                'cancellation_reason' => $reason,
            ]);
            
            return true;
        });
    }
    
    /**
     * Update order status with validation.
     */
    public static function updateStatus(Order $order, string $newStatus): bool
    {
        $validTransitions = self::getValidStatusTransitions($order->status);
        
        if (!in_array($newStatus, $validTransitions)) {
            throw new \Exception("Cannot transition from {$order->status} to {$newStatus}.");
        }
        
        $order->update(['status' => $newStatus]);
        
        // Handle status-specific actions
        if ($newStatus === 'completed') {
            $order->update(['completed_at' => now()]);
            
            // Confirm stock deduction (move from reserved to sold)
            foreach ($order->items as $item) {
                if ($item->variation_id) {
                    InventoryService::confirmSale($item->variation_id, $item->quantity);
                }
            }
        }
        
        return true;
    }
    
    /**
     * Get valid status transitions from current status.
     */
    public static function getValidStatusTransitions(string $currentStatus): array
    {
        return match($currentStatus) {
            'pending_payment' => ['verifying_payment', 'cancelled'],
            'verifying_payment' => ['payment_confirmed', 'payment_failed'],
            'payment_confirmed' => ['processing'],
            'payment_failed' => ['pending_payment', 'cancelled'],
            'processing' => ['shipping', 'cancelled'],
            'shipping' => ['completed'],
            'completed' => [], // Terminal state
            'cancelled' => [], // Terminal state
            default => [],
        };
    }
    
    /**
     * Get order history for a user.
     */
    public static function getUserOrders(User $user, ?string $status = null, int $perPage = 10)
    {
        $query = $user->orders()->with(['items.product', 'items.variation']);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
    
    /**
     * Get order statistics for admin dashboard.
     */
    public static function getOrderStatistics(?\DateTime $startDate = null, ?\DateTime $endDate = null): array
    {
        $query = Order::query();
        
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
        
        $total = $query->count();
        $pending = (clone $query)->where('status', 'pending_payment')->count();
        $verifying = (clone $query)->where('status', 'verifying_payment')->count();
        $processing = (clone $query)->where('status', 'processing')->count();
        $shipping = (clone $query)->where('status', 'shipping')->count();
        $completed = (clone $query)->where('status', 'completed')->count();
        $cancelled = (clone $query)->where('status', 'cancelled')->count();
        
        $totalRevenue = (clone $query)
            ->whereIn('status', ['completed'])
            ->sum('total_amount');
        
        $pendingRevenue = (clone $query)
            ->whereIn('status', ['processing', 'shipping'])
            ->sum('total_amount');
        
        return [
            'total_orders' => $total,
            'pending_payment' => $pending,
            'verifying_payment' => $verifying,
            'processing' => $processing,
            'shipping' => $shipping,
            'completed' => $completed,
            'cancelled' => $cancelled,
            'total_revenue' => $totalRevenue,
            'pending_revenue' => $pendingRevenue,
        ];
    }
    
    /**
     * Check if user can cancel their own order.
     */
    public static function userCanCancel(Order $order): bool
    {
        return in_array($order->status, ['pending_payment', 'payment_failed']);
    }
    
    /**
     * Check if admin can cancel order.
     */
    public static function adminCanCancel(Order $order): bool
    {
        return !in_array($order->status, ['completed', 'cancelled']);
    }
    
    /**
     * Get orders that need admin attention.
     */
    public static function getOrdersNeedingAttention()
    {
        return Order::with(['user', 'items.product'])
            ->where('status', 'verifying_payment')
            ->orWhere(function ($query) {
                $query->where('status', 'pending_payment')
                    ->where('created_at', '<', now()->subHours(12)); // Approaching deadline
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
