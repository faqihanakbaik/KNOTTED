<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variations', function (Blueprint $table) {
            // Check if column 'stock' exists and 'stock_quantity' doesn't
            if (Schema::hasColumn('product_variations', 'stock') && 
                !Schema::hasColumn('product_variations', 'stock_quantity')) {
                $table->renameColumn('stock', 'stock_quantity');
            }
            // If both don't exist, add stock_quantity
            elseif (!Schema::hasColumn('product_variations', 'stock_quantity')) {
                $table->integer('stock_quantity')->default(0)->after('value');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_variations', function (Blueprint $table) {
            if (Schema::hasColumn('product_variations', 'stock_quantity')) {
                $table->renameColumn('stock_quantity', 'stock');
            }
        });
    }
};
