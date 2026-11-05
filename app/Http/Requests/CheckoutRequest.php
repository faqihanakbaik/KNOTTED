<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only authenticated customers can checkout
        return auth()->check() && auth()->user()->role === 'customer';
    }

    public function rules(): array
    {
        return [
            // Payment method
            'payment_method' => ['required', 'in:bank_transfer'],
            
            // Shipping information
            'shipping_address' => ['required', 'string', 'max:500'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_province' => ['required', 'string', 'max:100'],
            'shipping_postal_code' => ['required', 'string', 'max:10'],
            'shipping_phone' => ['required', 'string', 'regex:/^[0-9+\-\(\)\s]+$/', 'max:20'],
            'recipient_name' => ['nullable', 'string', 'max:255'],
            
            // Shipping cost (will be validated against calculation)
            'shipping_cost' => ['required', 'numeric', 'min:0'],
            
            // Order notes
            'notes' => ['nullable', 'string', 'max:1000'],
            
            // IMPORTANT: We do NOT accept total_amount from client
            // It will be calculated server-side to prevent tampering
        ];
    }

    /**
     * Additional validation to ensure cart is valid.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $cart = auth()->user()->cart;
            
            // Check cart exists and has items
            if (!$cart || $cart->items->isEmpty()) {
                $validator->errors()->add('cart', 'Your cart is empty.');
                return;
            }
            
            // Verify all items have stock
            foreach ($cart->items as $item) {
                if (!$item->hasAvailableStock()) {
                    $validator->errors()->add(
                        'cart',
                        "Insufficient stock for {$item->getDisplayName()}."
                    );
                }
            }
            
            // Validate shipping cost is reasonable (basic sanity check)
            if ($this->shipping_cost > 500000) { // Max 500k IDR
                $validator->errors()->add(
                    'shipping_cost',
                    'Shipping cost seems unusually high. Please contact support.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in' => 'Invalid payment method selected.',
            'shipping_address.required' => 'Shipping address is required.',
            'shipping_phone.regex' => 'Invalid phone number format.',
            'shipping_postal_code.required' => 'Postal code is required.',
        ];
    }
}
