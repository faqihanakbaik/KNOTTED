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
        // Update orders table
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('status');
            }
            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone')->nullable()->after('customer_email');
            }
            if (!Schema::hasColumn('orders', 'recipient_phone')) {
                $table->string('recipient_phone')->nullable()->after('recipient_name');
            }
            if (!Schema::hasColumn('orders', 'city')) {
                $table->string('city')->nullable()->after('shipping_address');
            }
            if (!Schema::hasColumn('orders', 'province')) {
                $table->string('province')->nullable()->after('city');
            }
            if (!Schema::hasColumn('orders', 'district')) {
                $table->string('district')->nullable()->after('province');
            }
            if (!Schema::hasColumn('orders', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('district');
            }
        });

        // Update payments table
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'payment_date')) {
                $table->date('payment_date')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('payments', 'payment_proof') && Schema::hasColumn('payments', 'proof_image')) {
                $table->renameColumn('proof_image', 'payment_proof');
            } elseif (!Schema::hasColumn('payments', 'payment_proof')) {
                $table->string('payment_proof')->nullable()->after('payment_date');
            }
            if (!Schema::hasColumn('payments', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = ['payment_status', 'customer_name', 'customer_email', 'customer_phone', 'recipient_phone', 'city', 'province', 'district', 'postal_code'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            $columns = ['payment_date', 'payment_proof', 'admin_notes'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
