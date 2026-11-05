<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ProductVariation;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only authenticated customers can add to cart
        return auth()->check() && auth()->user()->role === 'customer';
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'variation_id' => ['nullable', 'exists:product_variations,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Additional validation after basic rules pass.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->variation_id) {
                $variation = ProductVariation::find($this->variation_id);
                
                if ($variation && !$variation->hasStock($this->quantity)) {
                    $validator->errors()->add(
                        'quantity',
                        "Insufficient stock. Only {$variation->stock_quantity} units available."
                    );
                }
                
                // Verify variation belongs to product
                if ($variation && $variation->product_id != $this->product_id) {
                    $validator->errors()->add(
                        'variation_id',
                        'Selected variation does not belong to this product.'
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Product is required.',
            'product_id.exists' => 'Selected product does not exist.',
            'quantity.min' => 'Quantity must be at least 1.',
            'quantity.max' => 'Quantity cannot exceed 100 units per item.',
        ];
    }
}
