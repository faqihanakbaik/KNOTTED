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
        Schema::table('orders', function (Blueprint $table) {
            // Payment tracking
            if (!Schema::hasColumn('orders', 'payment_proof_path')) {
                $table->string('payment_proof_path')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'payment_proof_uploaded_at')) {
                $table->timestamp('payment_proof_uploaded_at')->nullable()->after('payment_proof_path');
            }
            if (!Schema::hasColumn('orders', 'payment_verified_at')) {
                $table->timestamp('payment_verified_at')->nullable()->after('payment_proof_uploaded_at');
            }
            if (!Schema::hasColumn('orders', 'payment_verified_by')) {
                $table->foreignId('payment_verified_by')->nullable()->after('payment_verified_at')
                    ->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('orders', 'payment_rejection_reason')) {
                $table->text('payment_rejection_reason')->nullable()->after('payment_verified_by');
            }
            
            // Recipient information
            if (!Schema::hasColumn('orders', 'recipient_name')) {
                $table->string('recipient_name')->nullable()->after('shipping_phone');
            }
            
            // Order completion
            if (!Schema::hasColumn('orders', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('status');
            }
            
            // Cancellation tracking
            if (!Schema::hasColumn('orders', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('completed_at');
            }
            if (!Schema::hasColumn('orders', 'cancelled_by')) {
                $table->foreignId('cancelled_by')->nullable()->after('cancelled_at')
                    ->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('orders', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable()->after('cancelled_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['payment_verified_by']);
            $table->dropForeign(['cancelled_by']);
            $table->dropColumn([
                'payment_proof_path',
                'payment_proof_uploaded_at',
                'payment_verified_at',
                'payment_verified_by',
                'payment_rejection_reason',
                'recipient_name',
                'completed_at',
                'cancelled_at',
                'cancelled_by',
                'cancellation_reason',
            ]);
        });
    }
};
