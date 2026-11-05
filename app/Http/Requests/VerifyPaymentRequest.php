<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only admin can verify payments
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'action' => ['required', 'in:approve,reject'],
            'rejection_reason' => ['required_if:action,reject', 'string', 'max:500'],
        ];
    }

    /**
     * Additional validation for order status.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $order = $this->route('order');
            
            if ($order->status !== 'verifying_payment') {
                $validator->errors()->add(
                    'action',
                    'This order is not awaiting payment verification.'
                );
            }
            
            if (!$order->hasPaymentProof()) {
                $validator->errors()->add(
                    'action',
                    'No payment proof has been uploaded for this order.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Please specify verification action (approve/reject).',
            'action.in' => 'Invalid verification action.',
            'rejection_reason.required_if' => 'Rejection reason is required when rejecting payment.',
        ];
    }
}
