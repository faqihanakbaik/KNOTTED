<?php

namespace App\Policies;

use App\Models\CustomOrder;
use App\Models\User;

class CustomOrderPolicy
{
    /**
     * Determine if the user can view any custom orders.
     */
    public function viewAny(User $user): bool
    {
        // Both customers and admin can view custom orders
        return $user->role === 'customer' || $user->role === 'admin';
    }

    /**
     * Determine if the user can view the custom order.
     */
    public function view(User $user, CustomOrder $customOrder): bool
    {
        // Customer can only view their own custom orders
        if ($user->role === 'customer') {
            return $customOrder->user_id === $user->id;
        }
        
        // Admin can view all custom orders
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can create custom orders.
     */
    public function create(User $user): bool
    {
        // Only customers can create custom orders
        return $user->role === 'customer';
    }

    /**
     * Determine if the user can update the custom order.
     */
    public function update(User $user, CustomOrder $customOrder): bool
    {
        // Customer can update their own pending custom orders
        if ($user->role === 'customer') {
            return $customOrder->user_id === $user->id 
                && $customOrder->status === 'pending';
        }
        
        // Admin can update any custom order
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can delete the custom order.
     */
    public function delete(User $user, CustomOrder $customOrder): bool
    {
        // Customer can delete their own pending custom orders
        if ($user->role === 'customer') {
            return $customOrder->user_id === $user->id 
                && $customOrder->status === 'pending';
        }
        
        // Admin can delete any custom order
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can respond to the custom order.
     */
    public function respond(User $user, CustomOrder $customOrder): bool
    {
        // Only admin can respond with quotations
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can approve the custom order offer.
     */
    public function approveOffer(User $user, CustomOrder $customOrder): bool
    {
        // Only the order owner can approve offers
        return $user->role === 'customer' 
            && $customOrder->user_id === $user->id
            && $customOrder->status === 'quoted';
    }

    /**
     * Determine if the user can reject the custom order offer.
     */
    public function rejectOffer(User $user, CustomOrder $customOrder): bool
    {
        // Only the order owner can reject offers
        return $user->role === 'customer' 
            && $customOrder->user_id === $user->id
            && $customOrder->status === 'quoted';
    }

    /**
     * Determine if the user can view reference images.
     */
    public function viewReferenceImages(User $user, CustomOrder $customOrder): bool
    {
        // Order owner or admin can view reference images
        if ($user->role === 'customer') {
            return $customOrder->user_id === $user->id;
        }
        
        return $user->role === 'admin';
    }
}
