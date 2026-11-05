<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\PaymentService;

class UploadPaymentProofRequest extends FormRequest
{
    public function authorize(): bool
    {
        $order = $this->route('order');
        
        // Customer can only upload proof for their own orders
        return auth()->check()
            && auth()->user()->role === 'customer'
            && $order->user_id === auth()->id()
            && PaymentService::canUploadProof($order);
    }

    public function rules(): array
    {
        return [
            'payment_proof' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120', // 5MB
                'dimensions:min_width=300,min_height=300', // Ensure readability
            ],
        ];
    }

    /**
     * Additional validation for payment deadline.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $order = $this->route('order');
            
            if (PaymentService::isPaymentExpired($order)) {
                $validator->errors()->add(
                    'payment_proof',
                    'Payment deadline has passed. Please contact admin for assistance.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'payment_proof.required' => 'Please upload your payment proof.',
            'payment_proof.image' => 'Payment proof must be an image file.',
            'payment_proof.mimes' => 'Accepted formats: JPG, PNG, WebP.',
            'payment_proof.max' => 'Image size must not exceed 5MB.',
            'payment_proof.dimensions' => 'Image must be at least 300x300 pixels for readability.',
        ];
    }
}
