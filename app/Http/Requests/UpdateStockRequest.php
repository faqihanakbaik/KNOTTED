<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only admin can update stock
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'variation_id' => ['required', 'exists:product_variations,id'],
            'stock_quantity' => ['required', 'integer', 'min:0', 'max:9999'],
            'reason' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'variation_id.required' => 'Product variation is required.',
            'variation_id.exists' => 'Selected variation does not exist.',
            'stock_quantity.required' => 'Stock quantity is required.',
            'stock_quantity.min' => 'Stock quantity cannot be negative.',
            'stock_quantity.max' => 'Stock quantity exceeds maximum limit.',
            'reason.required' => 'Please provide a reason for stock adjustment.',
        ];
    }
}
