<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderNumberService
{
    /**
     * Generate a unique order number in format: #KNT-YYYY-XXXX
     * 
     * Format:
     * - KNT: KNOTTED brand prefix
     * - YYYY: Current year
     * - XXXX: Sequential 4-digit number (padded with zeros)
     * 
     * Example: #KNT-2026-0001, #KNT-2026-0125
     * 
     * This method is transaction-safe and handles concurrent requests.
     */
    public static function generate(): string
    {
        return DB::transaction(function () {
            $year = date('Y');
            $prefix = "#KNT-{$year}-";
            
            // Get the last order number for this year with row-level locking
            $lastOrder = Order::where('order_number', 'LIKE', "{$prefix}%")
                ->lockForUpdate() // PostgreSQL row-level lock
                ->orderBy('order_number', 'DESC')
                ->first();
            
            if ($lastOrder) {
                // Extract the sequence number from the last order
                $lastNumber = (int) substr($lastOrder->order_number, -4);
                $nextNumber = $lastNumber + 1;
            } else {
                // First order of the year
                $nextNumber = 1;
            }
            
            // Handle overflow (more than 9999 orders in a year)
            if ($nextNumber > 9999) {
                // Option 1: Add another digit
                $sequence = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            } else {
                // Standard 4-digit sequence
                $sequence = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
            
            $orderNumber = $prefix . $sequence;
            
            // Verify uniqueness (paranoid check)
            $attempts = 0;
            while (Order::where('order_number', $orderNumber)->exists() && $attempts < 10) {
                $nextNumber++;
                $sequence = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $orderNumber = $prefix . $sequence;
                $attempts++;
            }
            
            if ($attempts >= 10) {
                throw new \RuntimeException('Unable to generate unique order number after 10 attempts');
            }
            
            return $orderNumber;
        });
    }
    
    /**
     * Validate order number format.
     */
    public static function isValid(string $orderNumber): bool
    {
        // Format: #KNT-YYYY-XXXX (at least 4 digits)
        return preg_match('/^#KNT-\d{4}-\d{4,}$/', $orderNumber) === 1;
    }
    
    /**
     * Extract year from order number.
     */
    public static function extractYear(string $orderNumber): ?int
    {
        if (!self::isValid($orderNumber)) {
            return null;
        }
        
        preg_match('/#KNT-(\d{4})-\d+/', $orderNumber, $matches);
        return isset($matches[1]) ? (int) $matches[1] : null;
    }
    
    /**
     * Extract sequence number from order number.
     */
    public static function extractSequence(string $orderNumber): ?int
    {
        if (!self::isValid($orderNumber)) {
            return null;
        }
        
        preg_match('/#KNT-\d{4}-(\d+)/', $orderNumber, $matches);
        return isset($matches[1]) ? (int) $matches[1] : null;
    }
}
