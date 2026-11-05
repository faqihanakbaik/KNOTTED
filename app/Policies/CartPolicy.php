<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;

class CartPolicy
{
    /**
     * Determine if the user can view the cart.
     */
    public function view(User $user, Cart $cart): bool
    {
        // User can only view their own cart
        return $user->id === $cart->user_id && $user->role === 'customer';
    }

    /**
     * Determine if the user can update the cart.
     */
    public function update(User $user, Cart $cart): bool
    {
        // User can only update their own cart
        return $user->id === $cart->user_id && $user->role === 'customer';
    }

    /**
     * Determine if the user can add items to the cart.
     */
    public function addItem(User $user, Cart $cart): bool
    {
        // User can only add to their own cart
        return $user->id === $cart->user_id && $user->role === 'customer';
    }

    /**
     * Determine if the user can remove items from the cart.
     */
    public function removeItem(User $user, Cart $cart): bool
    {
        // User can only remove from their own cart
        return $user->id === $cart->user_id && $user->role === 'customer';
    }

    /**
     * Determine if the user can clear the cart.
     */
    public function clear(User $user, Cart $cart): bool
    {
        // User can only clear their own cart
        return $user->id === $cart->user_id && $user->role === 'customer';
    }

    /**
     * Determine if the user can checkout the cart.
     */
    public function checkout(User $user, Cart $cart): bool
    {
        // User can only checkout their own cart
        // Cart must not be empty
        return $user->id === $cart->user_id 
            && $user->role === 'customer'
            && $cart->items()->count() > 0;
    }
}
