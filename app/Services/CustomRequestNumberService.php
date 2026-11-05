<?php

namespace App\Services;

use App\Models\CustomOrder;
use Illuminate\Support\Facades\DB;

class CustomRequestNumberService
{
    /**
     * Generate a unique custom request number in format: #CR-YYYY-XXX
     * 
     * Format:
     * - CR: Custom Request prefix
     * - YYYY: Current year
     * - XXX: Sequential 3-digit number (padded with zeros)
     * 
     * Example: #CR-2026-001, #CR-2026-125
     * 
     * This method is transaction-safe and handles concurrent requests.
     */
    public static function generate(): string
    {
        return DB::transaction(function () {
            $year = date('Y');
            $prefix = "#CR-{$year}-";
            
            // Get the last custom request number for this year with row-level locking
            $lastRequest = CustomOrder::where('request_number', 'LIKE', "{$prefix}%")
                ->lockForUpdate() // PostgreSQL row-level lock
                ->orderBy('request_number', 'DESC')
                ->first();
            
            if ($lastRequest) {
                // Extract the sequence number from the last request
                $lastNumber = (int) substr($lastRequest->request_number, -3);
                $nextNumber = $lastNumber + 1;
            } else {
                // First request of the year
                $nextNumber = 1;
            }
            
            // Handle overflow (more than 999 requests in a year)
            if ($nextNumber > 999) {
                // Add another digit
                $sequence = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            } else {
                // Standard 3-digit sequence
                $sequence = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
            
            $requestNumber = $prefix . $sequence;
            
            // Verify uniqueness (paranoid check)
            $attempts = 0;
            while (CustomOrder::where('request_number', $requestNumber)->exists() && $attempts < 10) {
                $nextNumber++;
                $sequence = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                $requestNumber = $prefix . $sequence;
                $attempts++;
            }
            
            if ($attempts >= 10) {
                throw new \RuntimeException('Unable to generate unique custom request number after 10 attempts');
            }
            
            return $requestNumber;
        });
    }
    
    /**
     * Validate custom request number format.
     */
    public static function isValid(string $requestNumber): bool
    {
        // Format: #CR-YYYY-XXX (at least 3 digits)
        return preg_match('/^#CR-\d{4}-\d{3,}$/', $requestNumber) === 1;
    }
    
    /**
     * Extract year from request number.
     */
    public static function extractYear(string $requestNumber): ?int
    {
        if (!self::isValid($requestNumber)) {
            return null;
        }
        
        preg_match('/#CR-(\d{4})-\d+/', $requestNumber, $matches);
        return isset($matches[1]) ? (int) $matches[1] : null;
    }
    
    /**
     * Extract sequence number from request number.
     */
    public static function extractSequence(string $requestNumber): ?int
    {
        if (!self::isValid($requestNumber)) {
            return null;
        }
        
        preg_match('/#CR-\d{4}-(\d+)/', $requestNumber, $matches);
        return isset($matches[1]) ? (int) $matches[1] : null;
    }
}
