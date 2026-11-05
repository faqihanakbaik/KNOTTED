<?php

namespace App\Services;

use App\Models\Order;
use App\Models\CustomOrder;
use App\Models\Product;
use App\Models\StoreSetting;

/**
 * WhatsApp Click-to-Chat Integration Service
 * 
 * Generates wa.me links with pre-filled contextual messages
 * for various customer touchpoints throughout the application.
 * 
 * Per PRD requirements, this enables:
 * - Product inquiry (Ask Artisan)
 * - Order support
 * - Custom order discussion
 * - General customer support
 */
class WhatsAppService
{
    /**
     * Get the admin WhatsApp number from store settings or env.
     */
    public static function getAdminNumber(): ?string
    {
        // Try store settings first
        $setting = StoreSetting::where('key', 'whatsapp_admin_number')->first();
        
        if ($setting && $setting->value) {
            return self::normalizePhoneNumber($setting->value);
        }
        
        // Fallback to environment variable
        $envNumber = env('WHATSAPP_ADMIN_NUMBER');
        
        if ($envNumber) {
            return self::normalizePhoneNumber($envNumber);
        }
        
        return null;
    }
    
    /**
     * Normalize phone number for WhatsApp.
     * Removes spaces, dashes, parentheses and ensures proper format.
     * 
     * Examples:
     * - +62 812-3456-7890 → 6281234567890
     * - 0812-3456-7890 → 6281234567890
     * - 812 3456 7890 → 6281234567890
     */
    public static function normalizePhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters except +
        $phone = preg_replace('/[^\d+]/', '', $phone);
        
        // If starts with 0, replace with country code (Indonesia: 62)
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // Remove + if present
        $phone = str_replace('+', '', $phone);
        
        // Ensure it starts with country code
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }
    
    /**
     * Generate WhatsApp Click-to-Chat link.
     * 
     * @param string $message Pre-filled message (will be URL encoded)
     * @param string|null $phone Override admin number (optional)
     * @return string|null WhatsApp link or null if number not configured
     */
    public static function generateLink(string $message, ?string $phone = null): ?string
    {
        $phone = $phone ?? self::getAdminNumber();
        
        if (!$phone) {
            return null;
        }
        
        $encodedMessage = urlencode($message);
        
        return "https://wa.me/{$phone}?text={$encodedMessage}";
    }
    
    /**
     * Generate product inquiry message.
     * Used in product detail page "Ask Artisan" button.
     */
    public static function productInquiry(Product $product): ?string
    {
        $message = "Hello KNOTTED Admin,\n\n";
        $message .= "I'm interested in this product:\n\n";
        $message .= "Product: {$product->name}\n";
        $message .= "SKU: {$product->sku}\n";
        $message .= "Price: Rp " . number_format($product->price, 0, ',', '.') . "\n\n";
        $message .= "Could you provide more information?\n\n";
        $message .= "Thank you!";
        
        return self::generateLink($message);
    }
    
    /**
     * Generate order confirmation message.
     * Used after successful order creation.
     */
    public static function orderConfirmation(Order $order): ?string
    {
        $message = "Hello KNOTTED Admin,\n\n";
        $message .= "I've just placed an order:\n\n";
        $message .= "Order Number: {$order->order_number}\n";
        $message .= "Total: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n";
        $message .= "Payment Method: " . ucfirst(str_replace('_', ' ', $order->payment_method)) . "\n\n";
        $message .= "Please confirm the next steps.\n\n";
        $message .= "Thank you!";
        
        return self::generateLink($message);
    }
    
    /**
     * Generate order support message.
     * Used in order detail page for customer support.
     */
    public static function orderSupport(Order $order): ?string
    {
        $message = "Hello KNOTTED Admin,\n\n";
        $message .= "I need assistance with my order:\n\n";
        $message .= "Order Number: {$order->order_number}\n";
        $message .= "Order Date: {$order->created_at->format('d M Y')}\n";
        $message .= "Status: " . ucfirst($order->status) . "\n\n";
        $message .= "Could you help me?\n\n";
        $message .= "Thank you!";
        
        return self::generateLink($message);
    }
    
    /**
     * Generate custom order discussion message.
     * Used in custom order portal.
     */
    public static function customOrderDiscussion(CustomOrder $customOrder): ?string
    {
        $message = "Hello KNOTTED Admin,\n\n";
        $message .= "I'd like to discuss my custom order request:\n\n";
        $message .= "Request Number: {$customOrder->request_number}\n";
        $message .= "Product: {$customOrder->product_name}\n";
        $message .= "Type: " . ucfirst(str_replace('_', ' ', $customOrder->request_type)) . "\n\n";
        $message .= "Could we discuss the details?\n\n";
        $message .= "Thank you!";
        
        return self::generateLink($message);
    }
    
    /**
     * Generate general support message.
     * Used in FAQ, Contact page, and general support links.
     */
    public static function generalSupport(): ?string
    {
        $message = "Hello KNOTTED Admin,\n\n";
        $message .= "I need assistance. Could you help me?\n\n";
        $message .= "Thank you!";
        
        return self::generateLink($message);
    }
    
    /**
     * Generate custom message with link.
     * For flexible use cases.
     */
    public static function customMessage(string $message): ?string
    {
        return self::generateLink($message);
    }
    
    /**
     * Check if WhatsApp is configured.
     * Useful for showing/hiding WhatsApp features in UI.
     */
    public static function isConfigured(): bool
    {
        return self::getAdminNumber() !== null;
    }
    
    /**
     * Get a user-friendly message when WhatsApp is not configured.
     */
    public static function getNotConfiguredMessage(): string
    {
        return 'WhatsApp support is currently unavailable. Please use the contact form or email us instead.';
    }
}
