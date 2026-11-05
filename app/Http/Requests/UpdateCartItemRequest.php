<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Customer can only update their own cart items
        return auth()->check() 
            && auth()->user()->role === 'customer'
            && $this->route('cartItem')->cart->user_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Additional validation for stock availability.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $cartItem = $this->route('cartItem');
            
            if ($cartItem && !$cartItem->hasAvailableStock()) {
                $validator->errors()->add(
                    'quantity',
                    'Insufficient stock for the requested quantity.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'quantity.min' => 'Quantity must be at least 1.',
            'quantity.max' => 'Quantity cannot exceed 100 units.',
        ];
    }
}
