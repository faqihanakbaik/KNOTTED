<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only authenticated customers can create custom orders
        return auth()->check() && auth()->user()->role === 'customer';
    }

    public function rules(): array
    {
        return [
            'request_type' => ['required', 'in:custom_design,color_change,size_adjustment'],
            'product_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:50', 'max:2000'],
            'color_preference' => ['nullable', 'string', 'max:200'],
            'size_details' => ['nullable', 'string', 'max:200'],
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'deadline_date' => ['nullable', 'date', 'after:today'],
            'budget_min' => ['nullable', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0', 'gte:budget_min'],
            
            // Reference images
            'reference_images' => ['nullable', 'array', 'max:3'],
            'reference_images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120', // 5MB
            ],
            
            // Contact
            'contact_phone' => ['required', 'string', 'regex:/^[0-9+\-\(\)\s]+$/', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'request_type.required' => 'Please select a custom order type.',
            'product_name.required' => 'Product name is required.',
            'description.min' => 'Please provide at least 50 characters description.',
            'description.max' => 'Description is too long (max 2000 characters).',
            'quantity.min' => 'Minimum order quantity is 1.',
            'quantity.max' => 'Maximum order quantity is 50 for custom orders.',
            'deadline_date.after' => 'Deadline must be in the future.',
            'budget_max.gte' => 'Maximum budget must be greater than or equal to minimum budget.',
            'reference_images.max' => 'You can upload maximum 3 reference images.',
            'reference_images.*.max' => 'Each image must not exceed 5MB.',
            'contact_phone.regex' => 'Invalid phone number format.',
        ];
    }
}
