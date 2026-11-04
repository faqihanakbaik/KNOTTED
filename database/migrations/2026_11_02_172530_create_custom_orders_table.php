<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('request_type', ['modify_existing', 'new_design']);
            $table->string('product_name');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->text('description');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->integer('quantity')->default(1);
            $table->text('notes')->nullable();
            $table->decimal('customer_expected_price', 10, 2)->nullable();
            $table->decimal('admin_quoted_price', 10, 2)->nullable();
            $table->decimal('final_agreed_price', 10, 2)->nullable();
            $table->integer('estimated_days')->nullable();
            $table->enum('status', ['pending', 'quoted', 'negotiating', 'accepted', 'rejected', 'cancelled', 'converted'])->default('pending');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_orders');
    }
};
