<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admin can create products
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:products,slug'],
            'description' => ['required', 'string', 'min:20'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'category_id' => ['required', 'exists:categories,id'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku'],
            'product_type' => ['required', 'in:ready_stock,pre_order,custom_only'],
            'lead_time_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'is_active' => ['boolean'],
            
            // Product images
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB
            'primary_image_index' => ['nullable', 'integer', 'min:0'],
            
            // Product variations
            'variations' => ['nullable', 'array'],
            'variations.*.color_name' => ['required_with:variations', 'string', 'max:100'],
            'variations.*.color_hex' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'variations.*.size_label' => ['nullable', 'string', 'max:50'],
            'variations.*.stock_quantity' => ['required_with:variations', 'integer', 'min:0'],
            'variations.*.price_adjustment' => ['nullable', 'numeric'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'description.min' => 'Product description must be at least 20 characters.',
            'price.min' => 'Price cannot be negative.',
            'category_id.exists' => 'Selected category does not exist.',
            'product_type.in' => 'Invalid product type selected.',
            'images.max' => 'You can upload maximum 5 images.',
            'images.*.image' => 'Each file must be a valid image.',
            'images.*.max' => 'Each image must not exceed 5MB.',
            'variations.*.color_hex.regex' => 'Color hex must be in format #RRGGBB.',
        ];
    }
}
