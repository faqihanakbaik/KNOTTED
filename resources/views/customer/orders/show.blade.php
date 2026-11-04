@extends('layouts.app')

@section('title', 'Order Details - KNOTTED')

@section('content')
<div class="bg-cream py-12 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center text-mocha hover:text-espresso mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Orders
        </a>

        <h1 class="font-serif text-4xl font-bold text-espresso mb-8">Order Details</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-lg p-6 shadow-sm border border-oat">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="font-serif text-2xl font-bold text-espresso">{{ $order->order_number }}</h2>
                            <p class="text-sage">Placed on {{ $order->created_at->format('d F Y, H:i') }}</p>
                        </div>
                        <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-oat">
                        <div>
                            <p class="text-sm text-sage mb-1">Payment Method</p>
                            <p class="font-semibold text-espresso">{{ $order->payment_method === 'bank_transfer' ? 'Bank Transfer' : 'Cash on Delivery' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-sage mb-1">Payment Status</p>
                            <p class="font-semibold text-espresso">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg p-6 shadow-sm border border-oat">
                    <h3 class="font-serif text-xl font-bold text-espresso mb-4">Order Items</h3>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex gap-4 pb-4 border-b border-oat last:border-0">
                                <img src="{{ asset('images/products/' . $item->product->image) }}" 
                                     alt="{{ $item->product_name }}" 
                                     class="w-20 h-20 object-cover rounded"
                                     onerror="this.src='https://via.placeholder.com/150x150/F5F0E8/342C28?text=Product'">
                                <div class="flex-1">
                                    <p class="font-semibold text-espresso">{{ $item->product_name }}</p>
                                    <p class="text-sm text-sage">Quantity: {{ $item->quantity }}</p>
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
                <div class="bg-white rounded-lg p-6 shadow-sm border border-oat">
                    <h3 class="font-serif text-xl font-bold text-espresso mb-4">Shipping Address</h3>
                    <p class="font-semibold text-espresso">{{ $order->customer_name }}</p>
                    <p class="text-sage">{{ $order->customer_phone }}</p>
                    <p class="text-espresso mt-2">{{ $order->shipping_address }}</p>
                    <p class="text-espresso">{{ $order->city }}{{ $order->postal_code ? ', ' . $order->postal_code : '' }}</p>
                    @if($order->notes)
                        <div class="mt-4 pt-4 border-t border-oat">
                            <p class="text-sm font-semibold text-sage mb-1">Notes:</p>
                            <p class="text-espresso">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Summary & Payment -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Order Summary -->
                <div class="bg-white rounded-lg p-6 shadow-sm border border-oat">
                    <h3 class="font-serif text-xl font-bold text-espresso mb-4">Order Summary</h3>
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-sage">Subtotal</span>
                            <span class="font-semibold text-espresso">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sage">Shipping</span>
                            <span class="text-sage text-sm">Free</span>
                        </div>
                    </div>
                    <div class="flex justify-between pt-4 border-t border-oat text-lg">
                        <span class="font-bold text-espresso">Total</span>
                        <span class="font-bold text-mocha">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Payment Upload -->
                @if($order->payment_method === 'bank_transfer' && $order->payment_status === 'unpaid')
                    <div class="bg-sage/10 rounded-lg p-6 border border-sage">
                        <h3 class="font-semibold text-espresso mb-3">Bank Transfer Instructions</h3>
                        <div class="text-sm text-espresso space-y-2 mb-4">
                            <p><strong>Bank:</strong> BCA</p>
                            <p><strong>Account:</strong> 1234567890</p>
                            <p><strong>Name:</strong> KNOTTED Store</p>
                            <p><strong>Amount:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                        
                        <form action="{{ route('customer.orders.payment-proof', $order->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label class="form-label">Upload Payment Proof</label>
                            <input type="file" name="payment_proof" accept="image/*" required class="form-input mb-3">
                            <button type="submit" class="w-full btn-primary">Submit Payment Proof</button>
                        </form>
                    </div>
                @elseif($order->payment_status === 'pending_verification')
                    <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                        <p class="text-sm text-yellow-800">
                            <strong>Payment Proof Uploaded</strong><br>
                            Waiting for admin verification
                        </p>
                    </div>
                @elseif($order->payment_status === 'paid')
                    <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                        <p class="text-sm text-green-800">
                            <strong>✓ Payment Verified</strong><br>
                            Your payment has been confirmed
                        </p>
                    </div>
                @endif

                <!-- Actions -->
                @if($order->isPending())
                    <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Cancel this order?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full btn-danger">Cancel Order</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
