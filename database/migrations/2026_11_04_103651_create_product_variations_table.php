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
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Color information
            $table->string('color_name', 100)->nullable();
            $table->string('hex_code', 7)->nullable(); // e.g., #FF5733
            
            // Size information
            $table->string('size_label', 100)->nullable(); // e.g., "Petite", "Standard", "Relaxed", "Custom"
            
            // Stock management
            $table->integer('stock_count')->default(0);
            
            // Optional: Additional variation details
            $table->decimal('price_adjustment', 10, 2)->default(0.00); // Price difference from base product
            $table->string('sku_suffix', 50)->nullable(); // e.g., "-PNK-S"
            
            $table->timestamps();
            
            // Unique constraint: one product cannot have duplicate color+size combination
            $table->unique(['product_id', 'color_name', 'size_label'], 'product_color_size_unique');
            
            // Indexes for performance
            $table->index('product_id');
            $table->index('stock_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};
