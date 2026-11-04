@extends('layouts.app')

@section('title', 'My Orders - KNOTTED')

@section('content')
<div class="bg-cream py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-4xl font-bold text-espresso mb-8">My Orders</h1>

        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-sm border border-oat overflow-hidden">
                        <!-- Order Header -->
                        <div class="bg-cream/50 px-6 py-4 flex flex-wrap justify-between items-center gap-4">
                            <div>
                                <p class="text-sm text-sage">Order Number</p>
                                <p class="font-bold text-espresso">{{ $order->order_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-sage">Order Date</p>
                                <p class="font-semibold text-espresso">{{ $order->created_at->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-sage">Total</p>
                                <p class="font-bold text-mocha">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="px-6 py-4">
                            <div class="space-y-3">
                                @foreach($order->items->take(3) as $item)
                                    <div class="flex gap-4">
                                        <img src="{{ asset('images/products/' . $item->product->image) }}" 
                                             alt="{{ $item->product_name }}" 
                                             class="w-16 h-16 object-cover rounded"
                                             onerror="this.src='https://via.placeholder.com/100x100/F5F0E8/342C28?text=Product'">
                                        <div class="flex-1">
                                            <p class="font-semibold text-espresso">{{ $item->product_name }}</p>
                                            <p class="text-sm text-sage">Qty: {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                        <p class="font-semibold text-mocha">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                @endforeach
                                @if($order->items->count() > 3)
                                    <p class="text-sm text-sage">+ {{ $order->items->count() - 3 }} more items</p>
                                @endif
                            </div>
                        </div>

                        <!-- Order Actions -->
                        <div class="px-6 py-4 bg-cream/30 flex gap-3">
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="btn-secondary text-sm">
                                View Details
                            </a>
                            @if($order->payment_method === 'bank_transfer' && $order->payment_status === 'unpaid')
                                <a href="{{ route('customer.orders.show', $order->id) }}" class="btn-primary text-sm">
                                    Upload Payment Proof
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <svg class="mx-auto h-24 w-24 text-mocha mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h2 class="font-serif text-3xl font-bold text-espresso mb-4">No orders yet</h2>
                <p class="text-mocha mb-8">Start shopping to see your orders here</p>
                <a href="{{ route('shop.index') }}" class="btn-primary inline-block text-lg px-8 py-4">
                    Browse Products
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
