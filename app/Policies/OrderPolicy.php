<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;

class OrderPolicy
{
    /**
     * Determine if the user can view any orders.
     */
    public function viewAny(User $user): bool
    {
        // Customers see their own orders, admins see all
        return $user->role === 'customer' || $user->role === 'admin';
    }

    /**
     * Determine if the user can view the order.
     */
    public function view(User $user, Order $order): bool
    {
        // Customers can only view their own orders
        if ($user->role === 'customer') {
            return $order->user_id === $user->id;
        }
        
        // Admin can view all orders
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can create orders.
     */
    public function create(User $user): bool
    {
        // Only customers can create orders via checkout
        return $user->role === 'customer';
    }

    /**
     * Determine if the user can update the order.
     */
    public function update(User $user, Order $order): bool
    {
        // Only admin can update orders directly
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can delete the order.
     */
    public function delete(User $user, Order $order): bool
    {
        // Orders cannot be deleted, only cancelled
        return false;
    }

    /**
     * Determine if the user can cancel the order.
     */
    public function cancel(User $user, Order $order): bool
    {
        // Customer can cancel their own pending orders
        if ($user->role === 'customer' && $order->user_id === $user->id) {
            return OrderService::userCanCancel($order);
        }
        
        // Admin can cancel any non-completed order
        if ($user->role === 'admin') {
            return OrderService::adminCanCancel($order);
        }
        
        return false;
    }

    /**
     * Determine if the user can upload payment proof for the order.
     */
    public function uploadPaymentProof(User $user, Order $order): bool
    {
        // Only the order owner can upload payment proof
        return $user->role === 'customer' 
            && $order->user_id === $user->id
            && in_array($order->status, ['pending_payment', 'payment_failed']);
    }

    /**
     * Determine if the user can verify payment for the order.
     */
    public function verifyPayment(User $user, Order $order): bool
    {
        // Only admin can verify payments
        return $user->role === 'admin' && $order->status === 'verifying_payment';
    }

    /**
     * Determine if the user can update order status.
     */
    public function updateStatus(User $user, Order $order): bool
    {
        // Only admin can change order status
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can view payment proof.
     */
    public function viewPaymentProof(User $user, Order $order): bool
    {
        // Order owner or admin can view payment proof
        if ($user->role === 'customer') {
            return $order->user_id === $user->id;
        }
        
        return $user->role === 'admin';
    }
}
