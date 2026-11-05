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
        Schema::table('products', function (Blueprint $table) {
            // Add SKU field if doesn't exist
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku', 100)->unique()->nullable()->after('slug');
            }
            
            // Add product type field
            if (!Schema::hasColumn('products', 'product_type')) {
                $table->enum('product_type', ['ready_stock', 'made_to_order'])
                    ->default('ready_stock')
                    ->after('description');
            }
            
            // Add lead time field
            if (!Schema::hasColumn('products', 'lead_time_days')) {
                $table->integer('lead_time_days')->nullable()->after('product_type')
                    ->comment('Production days for made-to-order items');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sku', 'product_type', 'lead_time_days']);
        });
    }
};
