@extends('layouts.admin')

@section('page-title', 'Order Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-espresso">
        ← Back to Orders
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Order Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-xl font-bold text-espresso mb-4">Order #{{ $order->order_number }}</h3>
            
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Order Date</p>
                    <p class="font-semibold">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Order Status</p>
                    <p class="font-semibold">
                        @if($order->status == 'pending')
                            <span class="badge badge-pending">Pending</span>
                        @elseif($order->status == 'processing')
                            <span class="badge badge-processing">Processing</span>
                        @elseif($order->status == 'shipped')
                            <span class="badge badge-shipped">Shipped</span>
                        @elseif($order->status == 'completed')
                            <span class="badge badge-completed">Completed</span>
                        @else
                            <span class="badge badge-cancelled">Cancelled</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Payment Status</p>
                    <p class="font-semibold">
                        @if($order->payment_status == 'pending')
                            <span class="badge badge-pending">Pending</span>
                        @elseif($order->payment_status == 'verified')
                            <span class="badge badge-completed">Verified</span>
                        @else
                            <span class="badge badge-cancelled">Rejected</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Payment Method</p>
                    <p class="font-semibold">{{ ucfirst($order->payment_method) }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-espresso mb-4">Order Items</h3>
            
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex gap-4 pb-4 border-b border-gray-200 last:border-0">
                        <img src="{{ $item->product->image }}" 
                             alt="{{ $item->product_name }}" 
                             class="w-20 h-20 object-cover rounded"
                             onerror="this.src='https://via.placeholder.com/100x100/F5F0E8/342C28?text=Product'">
                        <div class="flex-1">
                            <h4 class="font-semibold">{{ $item->product_name }}</h4>
                            <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                            <p class="text-sm font-semibold text-espresso mt-1">
                                Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-espresso">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Summary -->
            <div class="mt-6 pt-6 border-t border-gray-200 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Shipping</span>
                    <span class="font-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                    <span>Total</span>
                    <span class="text-terracotta">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Customer Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-espresso mb-4">Customer</h3>
            <div class="space-y-2 text-sm">
                <p class="font-semibold">{{ $order->user->name }}</p>
                <p class="text-gray-600">{{ $order->user->email }}</p>
                <p class="text-gray-600">{{ $order->user->phone ?? 'No phone' }}</p>
            </div>
        </div>

        <!-- Shipping Address -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-espresso mb-4">Shipping Address</h3>
            <div class="text-sm text-gray-700 space-y-1">
                <p>{{ $order->shipping_address }}</p>
                <p>{{ $order->shipping_city }}, {{ $order->shipping_province }}</p>
                <p>{{ $order->shipping_postal_code }}</p>
            </div>
        </div>

        <!-- Update Status -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-espresso mb-4">Update Status</h3>
            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                        <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full btn-primary">
                        Update Status
                    </button>
                </div>
            </form>
        </div>

        <!-- Payment Info -->
        @if($order->payment)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-espresso mb-4">Payment Proof</h3>
            @if($order->payment->proof_image)
                <img src="{{ $order->payment->proof_image }}" 
                     alt="Payment Proof" 
                     class="w-full rounded border border-gray-300 mb-3">
                <a href="{{ route('admin.payments.show', $order->payment->id) }}" class="text-sm text-blue-600 hover:text-blue-800">
                    View Payment Details →
                </a>
            @else
                <p class="text-sm text-gray-500">No payment proof uploaded</p>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
