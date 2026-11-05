<?php

namespace App\Services;

use App\Models\Order;
use App\Models\StoreSetting;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;

/**
 * Payment Service
 * 
 * Manages payment workflow for KNOTTED orders:
 * - Bank transfer instructions
 * - Payment proof upload
 * - Payment verification
 * - Status transitions
 * - Payment deadline management
 */
class PaymentService
{
    // Payment status constants
    const STATUS_PENDING = 'pending_payment';
    const STATUS_VERIFYING = 'verifying_payment';
    const STATUS_CONFIRMED = 'payment_confirmed';
    const STATUS_FAILED = 'payment_failed';
    
    // Payment deadline (hours after order creation)
    const PAYMENT_DEADLINE_HOURS = 24;
    
    /**
     * Get bank account information from store settings.
     */
    public static function getBankAccounts(): array
    {
        $setting = StoreSetting::where('key', 'bank_accounts')->first();
        
        if ($setting && $setting->value) {
            return json_decode($setting->value, true);
        }
        
        // Default bank accounts (fallback)
        return [
            [
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'account_holder' => 'KNOTTED STORE',
            ],
            [
                'bank_name' => 'Mandiri',
                'account_number' => '0987654321',
                'account_holder' => 'KNOTTED STORE',
            ],
        ];
    }
    
    /**
     * Calculate payment deadline for an order.
     */
    public static function calculatePaymentDeadline(Order $order): Carbon
    {
        return $order->created_at->addHours(self::PAYMENT_DEADLINE_HOURS);
    }
    
    /**
     * Check if payment deadline has passed.
     */
    public static function isPaymentExpired(Order $order): bool
    {
        $deadline = self::calculatePaymentDeadline($order);
        return now()->isAfter($deadline);
    }
    
    /**
     * Get remaining time until payment deadline.
     * Returns null if expired.
     */
    public static function getRemainingTime(Order $order): ?string
    {
        if (self::isPaymentExpired($order)) {
            return null;
        }
        
        $deadline = self::calculatePaymentDeadline($order);
        $diff = now()->diffInMinutes($deadline);
        
        $hours = floor($diff / 60);
        $minutes = $diff % 60;
        
        if ($hours > 0) {
            return "{$hours} jam {$minutes} menit";
        }
        
        return "{$minutes} menit";
    }
    
    /**
     * Upload payment proof and update order status.
     */
    public static function uploadPaymentProof(Order $order, UploadedFile $file): bool
    {
        try {
            // Check if payment is still valid
            if (self::isPaymentExpired($order)) {
                throw new \Exception('Payment deadline has passed. Please contact admin.');
            }
            
            // Check current status
            if (!in_array($order->status, [self::STATUS_PENDING, self::STATUS_FAILED])) {
                throw new \Exception('Payment proof can only be uploaded for pending or failed payments.');
            }
            
            // Upload image
            $path = ImageUploadService::uploadPaymentProof($file, $order->order_number);
            
            // Update order
            $order->update([
                'payment_proof_path' => $path,
                'payment_proof_uploaded_at' => now(),
                'status' => self::STATUS_VERIFYING,
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    /**
     * Admin verifies payment (approve).
     */
    public static function verifyPayment(Order $order, int $adminId): bool
    {
        if ($order->status !== self::STATUS_VERIFYING) {
            throw new \Exception('Only orders in verification status can be verified.');
        }
        
        $order->update([
            'status' => self::STATUS_CONFIRMED,
            'payment_verified_at' => now(),
            'payment_verified_by' => $adminId,
        ]);
        
        // Move to processing
        $order->update(['status' => 'processing']);
        
        return true;
    }
    
    /**
     * Admin rejects payment.
     */
    public static function rejectPayment(Order $order, int $adminId, string $reason): bool
    {
        if ($order->status !== self::STATUS_VERIFYING) {
            throw new \Exception('Only orders in verification status can be rejected.');
        }
        
        $order->update([
            'status' => self::STATUS_FAILED,
            'payment_verified_at' => now(),
            'payment_verified_by' => $adminId,
            'payment_rejection_reason' => $reason,
        ]);
        
        return true;
    }
    
    /**
     * Cancel expired orders automatically.
     * Should be run via scheduled task.
     */
    public static function cancelExpiredOrders(): int
    {
        $expiredOrders = Order::where('status', self::STATUS_PENDING)
            ->where('created_at', '<', now()->subHours(self::PAYMENT_DEADLINE_HOURS))
            ->get();
        
        $count = 0;
        
        foreach ($expiredOrders as $order) {
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => 'Payment deadline exceeded (auto-cancelled)',
            ]);
            
            // Restore stock for all items
            foreach ($order->items as $item) {
                if ($item->variation_id) {
                    InventoryService::restoreStock($item->variation_id, $item->quantity);
                }
            }
            
            $count++;
        }
        
        return $count;
    }
    
    /**
     * Get payment instructions for display to customer.
     */
    public static function getPaymentInstructions(Order $order): array
    {
        $bankAccounts = self::getBankAccounts();
        $deadline = self::calculatePaymentDeadline($order);
        $remainingTime = self::getRemainingTime($order);
        
        return [
            'order_number' => $order->order_number,
            'total_amount' => $order->total_amount,
            'bank_accounts' => $bankAccounts,
            'deadline' => $deadline->format('d M Y H:i'),
            'remaining_time' => $remainingTime,
            'is_expired' => self::isPaymentExpired($order),
            'instructions' => [
                'Transfer ke salah satu rekening di atas',
                'Gunakan nominal EXACT sesuai total pembayaran',
                'Upload bukti transfer dalam ' . self::PAYMENT_DEADLINE_HOURS . ' jam',
                'Pembayaran akan diverifikasi oleh admin',
                'Pesanan akan diproses setelah pembayaran dikonfirmasi',
            ],
        ];
    }
    
    /**
     * Check if order can accept payment proof upload.
     */
    public static function canUploadProof(Order $order): bool
    {
        return in_array($order->status, [self::STATUS_PENDING, self::STATUS_FAILED])
            && !self::isPaymentExpired($order);
    }
    
    /**
     * Get payment status label in Indonesian.
     */
    public static function getStatusLabel(string $status): string
    {
        return match($status) {
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_VERIFYING => 'Verifikasi Pembayaran',
            self::STATUS_CONFIRMED => 'Pembayaran Dikonfirmasi',
            self::STATUS_FAILED => 'Pembayaran Ditolak',
            'processing' => 'Sedang Diproses',
            'shipping' => 'Dalam Pengiriman',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Status Tidak Dikenal',
        };
    }
}
