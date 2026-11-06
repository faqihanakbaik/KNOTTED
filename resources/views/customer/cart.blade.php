@extends('layouts.app')

@section('title', 'Shopping Cart - KNOTTED')

@section('content')
<div class="bg-cream py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-4xl font-bold text-espresso mb-8">Shopping Cart</h1>

        @if($cart && $cart->items->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cart->items as $item)
                        <div class="bg-white rounded-lg p-6 shadow-sm border border-oat flex gap-6">
                            <img src="{{ $item->product->image }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="w-24 h-24 object-cover rounded"
                                 onerror="this.src='https://via.placeholder.com/150x150/F5F0E8/342C28?text=Product'">
                            
                            <div class="flex-1">
                                <h3 class="font-serif text-lg font-bold text-espresso mb-1">
                                    <a href="{{ route('product.show', $item->product->slug) }}" class="hover:text-mocha">
                                        {{ $item->product->name }}
                                    </a>
                                </h3>
                                <p class="text-sm text-sage mb-3">{{ $item->product->category->name }}</p>
                                <p class="text-mocha font-semibold text-lg mb-4">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>

                                <div class="flex items-center gap-4">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <label class="text-sm text-espresso">Qty:</label>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                               min="1" max="{{ $item->product->stock }}" 
                                               class="w-16 px-2 py-1 border-2 border-oat rounded text-center focus:border-mocha">
                                        <button type="submit" class="text-sm text-mocha hover:text-espresso font-semibold">Update</button>
                                    </form>

                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-semibold">Remove</button>
                                    </form>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="font-bold text-xl text-espresso">
                                    Rp {{ number_format($item->getSubtotal(), 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg p-6 shadow-sm border border-oat sticky top-24">
                        <h2 class="font-serif text-2xl font-bold text-espresso mb-6">Order Summary</h2>
                        
                        <div class="space-y-3 mb-6 pb-6 border-b border-oat">
                            <div class="flex justify-between">
                                <span class="text-espresso">Subtotal ({{ $cart->getTotalItems() }} items)</span>
                                <span class="font-semibold">Rp {{ number_format($cart->getTotalAmount(), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-espresso">Shipping</span>
                                <span class="text-sage text-sm">Calculated at checkout</span>
                            </div>
                        </div>

                        <div class="flex justify-between mb-6 text-lg">
                            <span class="font-bold text-espresso">Total</span>
                            <span class="font-bold text-mocha">Rp {{ number_format($cart->getTotalAmount(), 0, ',', '.') }}</span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="block w-full btn-primary text-center mb-3">
                            Proceed to Checkout
                        </a>

                        <a href="{{ route('shop.index') }}" class="block w-full text-center btn-secondary">
                            Continue Shopping
                        </a>

                        <form action="{{ route('cart.clear') }}" method="POST" class="mt-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full text-sm text-red-600 hover:text-red-800 font-semibold">
                                Clear Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-20">
                <svg class="mx-auto h-24 w-24 text-mocha mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <h2 class="font-serif text-3xl font-bold text-espresso mb-4">Your cart is empty</h2>
                <p class="text-mocha mb-8">Start shopping and add items to your cart</p>
                <a href="{{ route('shop.index') }}" class="btn-primary inline-block text-lg px-8 py-4">
                    Browse Products
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
