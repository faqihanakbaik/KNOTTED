<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add variation support to cart_items
        Schema::table('cart_items', function (Blueprint $table) {
            if (!Schema::hasColumn('cart_items', 'variation_id')) {
                $table->foreignId('variation_id')->nullable()->after('product_id')
                    ->constrained('product_variations')->onDelete('cascade');
            }
            
            // Add snapshot fields for selected variation details
            if (!Schema::hasColumn('cart_items', 'selected_color')) {
                $table->string('selected_color', 100)->nullable()->after('variation_id');
            }
            
            if (!Schema::hasColumn('cart_items', 'selected_size')) {
                $table->string('selected_size', 100)->nullable()->after('selected_color');
            }
            
            // Customer note for this item
            if (!Schema::hasColumn('cart_items', 'notes')) {
                $table->text('notes')->nullable()->after('price');
            }
        });
        
        // Add variation support to order_items
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'variation_id')) {
                $table->foreignId('variation_id')->nullable()->after('product_id')
                    ->constrained('product_variations')->onDelete('set null');
            }
            
            // Snapshot fields - these preserve the exact state at time of order
            if (!Schema::hasColumn('order_items', 'sku_snapshot')) {
                $table->string('sku_snapshot', 100)->nullable()->after('product_name');
            }
            
            if (!Schema::hasColumn('order_items', 'color_snapshot')) {
                $table->string('color_snapshot', 100)->nullable()->after('sku_snapshot');
            }
            
            if (!Schema::hasColumn('order_items', 'size_snapshot')) {
                $table->string('size_snapshot', 100)->nullable()->after('color_snapshot');
            }
            
            // Customer note for this item
            if (!Schema::hasColumn('order_items', 'notes')) {
                $table->text('notes')->nullable()->after('subtotal');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['variation_id']);
            $table->dropColumn(['variation_id', 'selected_color', 'selected_size', 'notes']);
        });
        
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['variation_id']);
            $table->dropColumn(['variation_id', 'sku_snapshot', 'color_snapshot', 'size_snapshot', 'notes']);
        });
    }
};
