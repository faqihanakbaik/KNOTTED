<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine if the user can view any products.
     */
    public function viewAny(?User $user): bool
    {
        // Anyone can view product list (including guests)
        return true;
    }

    /**
     * Determine if the user can view the product.
     */
    public function view(?User $user, Product $product): bool
    {
        // Anyone can view a single product
        // But inactive products are only visible to admin
        if (!$product->is_active) {
            return $user && $user->role === 'admin';
        }
        
        return true;
    }

    /**
     * Determine if the user can create products.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can update the product.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can delete the product.
     */
    public function delete(User $user, Product $product): bool
    {
        // Only admin can delete
        // But cannot delete if there are existing orders
        if ($user->role !== 'admin') {
            return false;
        }
        
        // Check if product has orders
        return !$product->orderItems()->exists();
    }

    /**
     * Determine if the user can restore the product.
     */
    public function restore(User $user, Product $product): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can permanently delete the product.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can manage stock for the product.
     */
    public function manageStock(User $user, Product $product): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can manage variations for the product.
     */
    public function manageVariations(User $user, Product $product): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can manage images for the product.
     */
    public function manageImages(User $user, Product $product): bool
    {
        return $user->role === 'admin';
    }
}
