@extends('layouts.app')

@section('title', 'Order Details - KNOTTED')

@section('content')
<div class="bg-cream py-12 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center text-mocha hover:text-espresso mb-6 transition">
            <iconify-icon icon="solar:arrow-left-bold" class="mr-2"></iconify-icon>
            Back to Orders
        </a>

        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="font-serif text-3xl font-bold text-espresso mb-2">Order {{ $order->order_number }}</h1>
                <p class="text-sage">Placed on {{ $order->created_at->format('d F Y, H:i') }}</p>
            </div>
            @if($order->status === 'pending_payment')
                <span class="badge badge-pending">Pending Payment</span>
            @elseif($order->status === 'under_review' || $order->payment_status === 'under_review')
                <span class="badge badge-pending">Payment Verification</span>
            @elseif($order->status === 'processing')
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <iconify-icon icon="solar:settings-bold"></iconify-icon>
                    Processing
                </span>
            @elseif($order->status === 'shipped')
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                    <iconify-icon icon="solar:delivery-bold"></iconify-icon>
                    Shipped
                </span>
            @elseif($order->status === 'completed')
                <span class="badge badge-completed">Completed</span>
            @elseif($order->status === 'cancelled' || $order->payment_status === 'rejected')
                <span class="badge badge-cancelled">{{ ucfirst($order->status) }}</span>
            @endif
        </div>

        <!-- Order Tracking Timeline -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 md:p-8 mb-8">
            <h2 class="font-serif text-2xl font-bold text-espresso mb-8">Order Status</h2>
            
            @php
                $statuses = [
                    ['key' => 'pending_payment', 'label' => 'Pending Payment', 'icon' => 'solar:wallet-bold', 'desc' => 'Waiting for payment'],
                    ['key' => 'under_review', 'label' => 'Payment Verification', 'icon' => 'solar:document-text-bold', 'desc' => 'Verifying payment proof'],
                    ['key' => 'paid', 'label' => 'Paid', 'icon' => 'solar:check-circle-bold', 'desc' => 'Payment confirmed'],
                    ['key' => 'processing', 'label' => 'Processing', 'icon' => 'solar:settings-bold', 'desc' => 'Preparing your order'],
                    ['key' => 'shipped', 'label' => 'Shipped', 'icon' => 'solar:delivery-bold', 'desc' => 'On the way'],
                    ['key' => 'completed', 'label' => 'Completed', 'icon' => 'solar:star-bold', 'desc' => 'Order received'],
                ];

                // Determine current step
                $currentStep = 0;
                if ($order->status === 'pending_payment' || ($order->payment_status === 'pending' && !$order->payment)) {
                    $currentStep = 0;
                } elseif ($order->payment_status === 'under_review' || ($order->payment && $order->payment->status === 'under_review')) {
                    $currentStep = 1;
                } elseif ($order->payment_status === 'paid' && $order->status === 'pending_payment') {
                    $currentStep = 2;
                } elseif ($order->status === 'processing') {
                    $currentStep = 3;
                } elseif ($order->status === 'shipped') {
                    $currentStep = 4;
                } elseif ($order->status === 'completed') {
                    $currentStep = 5;
                }
            @endphp

            <!-- Desktop Timeline -->
            <div class="hidden md:block">
                <div class="relative">
                    <!-- Progress Line -->
                    <div class="absolute top-8 left-0 right-0 h-1 bg-gray-200">
                        <div class="h-full bg-mocha transition-all duration-500" style="width: {{ ($currentStep / 5) * 100 }}%"></div>
                    </div>

                    <!-- Steps -->
                    <div class="relative flex justify-between">
                        @foreach($statuses as $index => $status)
                            <div class="flex flex-col items-center" style="width: 16.66%;">
                                <!-- Icon Circle -->
                                <div class="w-16 h-16 rounded-full flex items-center justify-center mb-3 transition-all duration-300 {{ $index <= $currentStep ? 'bg-mocha text-white' : 'bg-gray-200 text-gray-400' }}">
                                    <iconify-icon icon="{{ $status['icon'] }}" style="font-size: 1.75rem;"></iconify-icon>
                                </div>
                                <!-- Label -->
                                <p class="font-semibold text-center text-sm mb-1 {{ $index <= $currentStep ? 'text-mocha' : 'text-gray-400' }}">
                                    {{ $status['label'] }}
                                </p>
                                <p class="text-xs text-gray-500 text-center">{{ $status['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Mobile Timeline -->
            <div class="md:hidden space-y-4">
                @foreach($statuses as $index => $status)
                    <div class="flex items-start gap-4">
                        <!-- Icon -->
                        <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 {{ $index <= $currentStep ? 'bg-mocha text-white' : 'bg-gray-200 text-gray-400' }}">
                            <iconify-icon icon="{{ $status['icon'] }}" style="font-size: 1.5rem;"></iconify-icon>
                        </div>
                        <!-- Content -->
                        <div class="flex-1 {{ $index < count($statuses) - 1 ? 'border-l-2 border-gray-200 pb-6 -mt-2 pl-4' : '' }}">
                            <p class="font-semibold {{ $index <= $currentStep ? 'text-mocha' : 'text-gray-400' }}">
                                {{ $status['label'] }}
                            </p>
                            <p class="text-sm text-gray-500">{{ $status['desc'] }}</p>
                            @if($index === $currentStep)
                                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium bg-mocha/10 text-mocha">Current Status</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Payment Rejection Notice -->
            @if($order->payment_status === 'rejected' && $order->payment && $order->payment->admin_notes)
                <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <iconify-icon icon="solar:close-circle-bold" class="text-red-600 text-2xl flex-shrink-0"></iconify-icon>
                        <div>
                            <p class="font-semibold text-red-900 mb-1">Payment Rejected</p>
                            <p class="text-sm text-red-700">{{ $order->payment->admin_notes }}</p>
                            <a href="{{ route('customer.payments.upload', $order->id) }}" class="inline-block mt-3 px-4 py-2 rounded-lg text-sm font-medium bg-red-600 text-white hover:bg-red-700 transition">
                                Upload New Payment Proof
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Items & Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-serif text-xl font-bold text-espresso mb-4">Order Items</h3>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex gap-4 pb-4 border-b border-gray-100 last:border-0">
                                @if($item->product && $item->product->image)
                                    <img src="{{ $item->product->image }}" 
                                         alt="{{ $item->product_name }}" 
                                         class="w-20 h-20 object-cover rounded-lg"
                                         onerror="this.src='https://via.placeholder.com/150x150/F5F0E8/342C28?text=Product'">
                                @else
                                    <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <iconify-icon icon="solar:box-bold" class="text-gray-400 text-2xl"></iconify-icon>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <p class="font-semibold text-espresso">{{ $item->product_name }}</p>
                                    @if($item->size_snapshot)
                                        <p class="text-sm text-mocha flex items-center gap-1 mt-1">
                                            <iconify-icon icon="solar:ruler-bold" class="text-sm"></iconify-icon>
                                            Size: {{ $item->size_snapshot }}
                                        </p>
                                    @endif
                                    @if($item->sku_snapshot)
                                        <p class="text-xs text-gray-500 mt-1">SKU: {{ $item->sku_snapshot }}</p>
                                    @endif
                                    <p class="text-sm text-gray-500 mt-2">Quantity: {{ $item->quantity }}</p>
                                    <p class="text-sm text-mocha">Rp {{ number_format($item->price, 0, ',', '.') }} each</p>
                                </div>
                                <p class="font-bold text-mocha">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-serif text-xl font-bold text-espresso mb-4 flex items-center gap-2">
                        <iconify-icon icon="solar:map-point-bold"></iconify-icon>
                        Shipping Address
                    </h3>
                    <div class="space-y-2">
                        <p class="font-semibold text-espresso">{{ $order->recipient_name ?? $order->customer_name }}</p>
                        <p class="text-gray-700">{{ $order->recipient_phone ?? $order->customer_phone }}</p>
                        <p class="text-espresso mt-3">{{ $order->shipping_address }}</p>
                        @if($order->district)
                            <p class="text-espresso">{{ $order->district }}, {{ $order->city }}</p>
                        @else
                            <p class="text-espresso">{{ $order->city }}</p>
                        @endif
                        @if($order->province)
                            <p class="text-espresso">{{ $order->province }}{{ $order->postal_code ? ', ' . $order->postal_code : '' }}</p>
                        @elseif($order->postal_code)
                            <p class="text-espresso">{{ $order->postal_code }}</p>
                        @endif
                    </div>
                    @if($order->notes)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-sm font-semibold text-gray-600 mb-1">Notes:</p>
                            <p class="text-espresso">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Summary & Actions -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-serif text-xl font-bold text-espresso mb-4">Order Summary</h3>
                    <div class="space-y-3 mb-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold text-espresso">Rp {{ number_format($order->subtotal ?? $order->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-semibold text-espresso">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between pt-4 border-t border-gray-200">
                        <span class="font-bold text-espresso">Total</span>
                        <span class="font-bold text-xl text-mocha">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200 text-sm space-y-1">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Method</span>
                            <span class="font-medium text-espresso">
                                {{ $order->payment_method === 'bank_transfer' ? 'Bank Transfer' : 'QRIS' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Status</span>
                            <span class="font-medium {{ $order->payment_status === 'paid' ? 'text-green-600' : ($order->payment_status === 'rejected' ? 'text-red-600' : 'text-orange-600') }}">
                                {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Payment Actions -->
                @if($order->payment_status === 'pending' && !$order->payment)
                    <div class="bg-amber-50 rounded-lg p-6 border border-amber-200">
                        <div class="flex items-start gap-3 mb-4">
                            <iconify-icon icon="solar:info-circle-bold" class="text-amber-600 text-2xl flex-shrink-0"></iconify-icon>
                            <div>
                                <h4 class="font-semibold text-amber-900 mb-1">Payment Required</h4>
                                <p class="text-sm text-amber-700">Please upload your payment proof to proceed with your order</p>
                            </div>
                        </div>
                        <a href="{{ route('customer.payments.upload', $order->id) }}" class="btn-primary w-full text-center">
                            <iconify-icon icon="solar:upload-bold" class="inline-block mr-2"></iconify-icon>
                            Upload Payment Proof
                        </a>
                    </div>
                @elseif($order->payment_status === 'under_review')
                    <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                        <div class="flex items-start gap-3">
                            <iconify-icon icon="solar:clock-circle-bold" class="text-blue-600 text-2xl flex-shrink-0"></iconify-icon>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-1">Payment Under Review</h4>
                                <p class="text-sm text-blue-700">Your payment proof is being verified by our admin. This usually takes a few hours.</p>
                            </div>
                        </div>
                    </div>
                @elseif($order->payment_status === 'paid')
                    <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                        <div class="flex items-start gap-3">
                            <iconify-icon icon="solar:check-circle-bold" class="text-green-600 text-2xl flex-shrink-0"></iconify-icon>
                            <div>
                                <h4 class="font-semibold text-green-900 mb-1">Payment Confirmed</h4>
                                <p class="text-sm text-green-700">Your payment has been verified successfully!</p>
                            </div>
                        </div>
                    </div>
                @elseif($order->payment_status === 'rejected')
                    <div class="bg-red-50 rounded-lg p-6 border border-red-200">
                        <div class="flex items-start gap-3 mb-4">
                            <iconify-icon icon="solar:danger-circle-bold" class="text-red-600 text-2xl flex-shrink-0"></iconify-icon>
                            <div>
                                <h4 class="font-semibold text-red-900 mb-1">Payment Rejected</h4>
                                <p class="text-sm text-red-700">Please upload a new payment proof</p>
                            </div>
                        </div>
                        <a href="{{ route('customer.payments.upload', $order->id) }}" class="btn-primary w-full text-center bg-red-600 hover:bg-red-700">
                            <iconify-icon icon="solar:upload-bold" class="inline-block mr-2"></iconify-icon>
                            Re-upload Payment
                        </a>
                    </div>
                @endif

                <!-- Cancel Order -->
                @if($order->status === 'pending_payment' && $order->payment_status !== 'under_review')
                    <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full btn-secondary border-2 border-red-300 text-red-600 hover:bg-red-50">
                            <iconify-icon icon="solar:close-circle-bold" class="inline-block mr-2"></iconify-icon>
                            Cancel Order
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
