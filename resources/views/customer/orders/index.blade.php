@extends('layouts.app')

@section('title', 'My Orders - KNOTTED')

@section('content')
<div class="bg-cream py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="font-serif text-4xl font-bold text-espresso">My Orders</h1>
            <a href="{{ route('shop.index') }}" class="btn-secondary">
                <iconify-icon icon="solar:shop-bold" class="inline-block mr-2"></iconify-icon>
                Continue Shopping
            </a>
        </div>

        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                        <!-- Order Header -->
                        <div class="bg-gray-50 px-6 py-4 flex flex-wrap justify-between items-center gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Order Number</p>
                                <p class="font-bold text-espresso">{{ $order->order_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Order Date</p>
                                <p class="font-semibold text-espresso">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Total</p>
                                <p class="font-bold text-lg text-mocha">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                @php
                                    $statusDisplay = '';
                                    $statusClass = '';
                                    $statusIcon = '';
                                    
                                    if ($order->status === 'pending_payment' || ($order->payment_status === 'pending' && !$order->payment)) {
                                        $statusDisplay = 'Pending Payment';
                                        $statusClass = 'bg-amber-100 text-amber-800';
                                        $statusIcon = 'solar:wallet-bold';
                                    } elseif ($order->payment_status === 'under_review' || ($order->payment && $order->payment->status === 'under_review')) {
                                        $statusDisplay = 'Payment Verification';
                                        $statusClass = 'bg-blue-100 text-blue-800';
                                        $statusIcon = 'solar:document-text-bold';
                                    } elseif ($order->payment_status === 'rejected') {
                                        $statusDisplay = 'Payment Rejected';
                                        $statusClass = 'bg-red-100 text-red-800';
                                        $statusIcon = 'solar:close-circle-bold';
                                    } elseif ($order->status === 'processing') {
                                        $statusDisplay = 'Processing';
                                        $statusClass = 'bg-purple-100 text-purple-800';
                                        $statusIcon = 'solar:settings-bold';
                                    } elseif ($order->status === 'shipped') {
                                        $statusDisplay = 'Shipped';
                                        $statusClass = 'bg-indigo-100 text-indigo-800';
                                        $statusIcon = 'solar:delivery-bold';
                                    } elseif ($order->status === 'completed') {
                                        $statusDisplay = 'Completed';
                                        $statusClass = 'bg-green-100 text-green-800';
                                        $statusIcon = 'solar:check-circle-bold';
                                    } elseif ($order->status === 'cancelled') {
                                        $statusDisplay = 'Cancelled';
                                        $statusClass = 'bg-gray-100 text-gray-800';
                                        $statusIcon = 'solar:close-square-bold';
                                    }
                                @endphp
                                
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-medium {{ $statusClass }}">
                                    <iconify-icon icon="{{ $statusIcon }}"></iconify-icon>
                                    {{ $statusDisplay }}
                                </span>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="px-6 py-4">
                            <div class="space-y-3">
                                @foreach($order->items->take(2) as $item)
                                    <div class="flex gap-4">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ $item->product->image }}" 
                                                 alt="{{ $item->product_name }}" 
                                                 class="w-16 h-16 object-cover rounded-lg"
                                                 onerror="this.src='https://via.placeholder.com/100x100/F5F0E8/342C28?text=Product'">
                                        @else
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <iconify-icon icon="solar:box-bold" class="text-gray-400 text-2xl"></iconify-icon>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <p class="font-semibold text-espresso">{{ $item->product_name }}</p>
                                            @if($item->size_snapshot)
                                                <p class="text-xs text-mocha">Size: {{ $item->size_snapshot }}</p>
                                            @endif
                                            <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                        <p class="font-semibold text-mocha">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                @endforeach
                                @if($order->items->count() > 2)
                                    <p class="text-sm text-gray-500 font-medium">+ {{ $order->items->count() - 2 }} more items</p>
                                @endif
                            </div>
                        </div>

                        <!-- Order Actions -->
                        <div class="px-6 py-4 bg-gray-50 flex flex-wrap gap-3">
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="btn-secondary text-sm flex-shrink-0">
                                <iconify-icon icon="solar:eye-bold" class="inline-block mr-1"></iconify-icon>
                                View Details
                            </a>
                            
                            @if($order->payment_status === 'pending' && !$order->payment)
                                <a href="{{ route('customer.payments.upload', $order->id) }}" class="btn-primary text-sm flex-shrink-0">
                                    <iconify-icon icon="solar:upload-bold" class="inline-block mr-1"></iconify-icon>
                                    Upload Payment
                                </a>
                            @elseif($order->payment_status === 'rejected')
                                <a href="{{ route('customer.payments.upload', $order->id) }}" class="btn-primary text-sm flex-shrink-0 bg-red-600 hover:bg-red-700">
                                    <iconify-icon icon="solar:upload-bold" class="inline-block mr-1"></iconify-icon>
                                    Re-upload Payment
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
            <div class="text-center py-20 bg-white rounded-lg border border-gray-200">
                <iconify-icon icon="solar:box-bold" class="text-gray-300 mx-auto mb-6" style="font-size: 6rem;"></iconify-icon>
                <h2 class="font-serif text-3xl font-bold text-espresso mb-4">No orders yet</h2>
                <p class="text-gray-600 mb-8">Start shopping to see your orders here</p>
                <a href="{{ route('shop.index') }}" class="btn-primary inline-block">
                    <iconify-icon icon="solar:shop-bold" class="inline-block mr-2"></iconify-icon>
                    Browse Products
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
