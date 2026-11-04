@extends('layouts.app')

@section('title', 'Checkout - KNOTTED')

@section('content')
<div class="bg-cream py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-4xl font-bold text-espresso mb-8">Checkout</h1>

        <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            
            <!-- Checkout Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information -->
                <div class="bg-white rounded-lg p-6 shadow-sm border border-oat">
                    <h2 class="font-serif text-2xl font-bold text-espresso mb-6">Customer Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name', $user->name) }}" required class="form-input">
                            @error('customer_name')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', $user->email) }}" required class="form-input">
                            @error('customer_email')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="form-label">Phone Number *</label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone', $user->phone) }}" required class="form-input">
                            @error('customer_phone')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-lg p-6 shadow-sm border border-oat">
                    <h2 class="font-serif text-2xl font-bold text-espresso mb-6">Shipping Address</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Complete Address *</label>
                            <textarea name="shipping_address" rows="3" required class="form-input">{{ old('shipping_address', $user->address) }}</textarea>
                            @error('shipping_address')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">City *</label>
                                <input type="text" name="city" value="{{ old('city', $user->city) }}" required class="form-input">
                                @error('city')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Postal Code</label>
                                <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="form-input">
                                @error('postal_code')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Order Notes (Optional)</label>
                            <textarea name="notes" rows="2" class="form-input" placeholder="Special instructions for your order">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-lg p-6 shadow-sm border border-oat">
                    <h2 class="font-serif text-2xl font-bold text-espresso mb-6">Payment Method</h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-start p-4 border-2 border-oat rounded-lg cursor-pointer hover:border-mocha transition">
                            <input type="radio" name="payment_method" value="bank_transfer" checked class="mt-1 text-mocha focus:ring-mocha">
                            <div class="ml-3">
                                <p class="font-semibold text-espresso">Bank Transfer</p>
                                <p class="text-sm text-sage mt-1">Transfer to our bank account and upload payment proof</p>
                            </div>
                        </label>

                        <label class="flex items-start p-4 border-2 border-oat rounded-lg cursor-pointer hover:border-mocha transition">
                            <input type="radio" name="payment_method" value="cod" class="mt-1 text-mocha focus:ring-mocha">
                            <div class="ml-3">
                                <p class="font-semibold text-espresso">Cash on Delivery (COD)</p>
                                <p class="text-sm text-sage mt-1">Pay when you receive your order</p>
                            </div>
                        </label>
                    </div>
                    @error('payment_method')
                        <p class="form-error mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg p-6 shadow-sm border border-oat sticky top-24">
                    <h2 class="font-serif text-2xl font-bold text-espresso mb-6">Order Summary</h2>
                    
                    <div class="space-y-3 mb-6 pb-6 border-b border-oat">
                        @foreach($cart->items as $item)
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="font-semibold text-espresso text-sm">{{ $item->product->name }}</p>
                                    <p class="text-xs text-sage">Qty: {{ $item->quantity }}</p>
                                </div>
                                <p class="text-sm font-semibold text-mocha">
                                    Rp {{ number_format($item->getSubtotal(), 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between">
                            <span class="text-espresso">Subtotal</span>
                            <span class="font-semibold">Rp {{ number_format($cart->getTotalAmount(), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-espresso">Shipping</span>
                            <span class="text-sage text-sm">Free</span>
                        </div>
                    </div>

                    <div class="flex justify-between mb-6 text-lg pt-4 border-t border-oat">
                        <span class="font-bold text-espresso">Total</span>
                        <span class="font-bold text-mocha">Rp {{ number_format($cart->getTotalAmount(), 0, ',', '.') }}</span>
                    </div>

                    <button type="submit" class="w-full btn-primary text-lg py-4">
                        Place Order
                    </button>

                    <p class="text-xs text-sage text-center mt-4">
                        By placing your order, you agree to our terms and conditions
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
