<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only admin can update products
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        $productId = $this->route('product');
        
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', "unique:products,slug,{$productId}"],
            'description' => ['sometimes', 'required', 'string', 'min:20'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0', 'max:99999999.99'],
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'sku' => ['sometimes', 'nullable', 'string', 'max:100', "unique:products,sku,{$productId}"],
            'product_type' => ['sometimes', 'required', 'in:ready_stock,pre_order,custom_only'],
            'lead_time_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'is_active' => ['boolean'],
            
            // Product images
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'primary_image_index' => ['nullable', 'integer', 'min:0'],
            'delete_image_ids' => ['nullable', 'array'],
            'delete_image_ids.*' => ['integer', 'exists:product_images,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'description.min' => 'Product description must be at least 20 characters.',
            'price.min' => 'Price cannot be negative.',
            'category_id.exists' => 'Selected category does not exist.',
        ];
    }
}
