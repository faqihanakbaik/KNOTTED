<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add indexes to improve query performance
     */
    public function up(): void
    {
        // Products table indexes
        Schema::table('products', function (Blueprint $table) {
            $table->index('slug'); // For product detail lookups
            $table->index('category_id'); // For category filtering
            $table->index('is_active'); // For active product queries
            $table->index('is_featured'); // For featured products
            $table->index(['is_active', 'is_featured']); // Composite for homepage
            $table->index('created_at'); // For sorting by latest
        });

        // Product Variations table indexes
        Schema::table('product_variations', function (Blueprint $table) {
            $table->index('product_id'); // For eager loading
            $table->index('stock_quantity'); // For stock queries
            $table->index(['product_id', 'stock_quantity']); // Composite for inStock scope
        });

        // Orders table indexes
        Schema::table('orders', function (Blueprint $table) {
            $table->index('user_id'); // For customer order history
            $table->index('status'); // For filtering by status
            $table->index(['status', 'created_at']); // Composite for admin dashboard
            $table->index('order_number'); // For search by order number
            $table->index('created_at'); // For date range filtering
        });

        // Order Items table indexes
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id'); // For eager loading
            $table->index('product_id'); // For best sellers query
        });

        // Payments table indexes
        Schema::table('payments', function (Blueprint $table) {
            $table->index('order_id'); // For order-payment lookup
            $table->index('status'); // For pending payments filter
            $table->index(['status', 'created_at']); // Composite for verification queue
        });

        // Cart Items table indexes
        Schema::table('cart_items', function (Blueprint $table) {
            $table->index('cart_id'); // For cart item retrieval
            $table->index('variation_id'); // For product lookup
        });

        // Categories table indexes
        Schema::table('categories', function (Blueprint $table) {
            $table->index('slug'); // For category page lookups
        });

        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->index('role'); // For customer count queries
            $table->index('email'); // Already unique, but explicit index
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Products
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['is_active', 'is_featured']);
            $table->dropIndex(['created_at']);
        });

        // Product Variations
        Schema::table('product_variations', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['stock_quantity']);
            $table->dropIndex(['product_id', 'stock_quantity']);
        });

        // Orders
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['order_number']);
            $table->dropIndex(['created_at']);
        });

        // Order Items
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['product_id']);
        });

        // Payments
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['status', 'created_at']);
        });

        // Cart Items
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex(['cart_id']);
            $table->dropIndex(['variation_id']);
        });

        // Categories
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['slug']);
        });

        // Users
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['email']);
        });
    }
};
