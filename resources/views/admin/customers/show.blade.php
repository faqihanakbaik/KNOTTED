@extends('layouts.admin')

@section('page-title', 'Customer Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.customers.index') }}" class="text-gray-600 hover:text-espresso">
        ← Back to Customers
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Customer Orders -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-xl font-bold text-espresso mb-4">Order History</h3>
            
            @if($customer->orders->count() > 0)
                <div class="space-y-4">
                    @foreach($customer->orders as $order)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-terracotta transition">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="font-semibold text-blue-600 hover:text-blue-800">
                                        Order #{{ $order->order_number }}
                                    </a>
                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-espresso">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                    @if($order->status == 'completed')
                                        <span class="badge badge-completed">Completed</span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="badge badge-cancelled">Cancelled</span>
                                    @else
                                        <span class="badge badge-pending">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="text-sm text-gray-600">
                                <p>{{ $order->items->count() }} item(s)</p>
                                <p>Payment: {{ ucfirst($order->payment_status) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    No orders yet
                </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Customer Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-espresso mb-4">Customer Information</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-500">Full Name</p>
                    <p class="font-semibold">{{ $customer->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Email</p>
                    <p class="font-semibold">{{ $customer->email }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Phone</p>
                    <p class="font-semibold">{{ $customer->phone ?? 'Not provided' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Member Since</p>
                    <p class="font-semibold">{{ $customer->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-espresso mb-4">Statistics</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Total Orders</span>
                    <span class="font-bold">{{ $customer->orders->count() }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Completed Orders</span>
                    <span class="font-bold text-green-600">{{ $customer->orders->where('status', 'completed')->count() }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Cancelled Orders</span>
                    <span class="font-bold text-red-600">{{ $customer->orders->where('status', 'cancelled')->count() }}</span>
                </div>
                <div class="flex justify-between text-sm pt-3 border-t border-gray-200">
                    <span class="text-gray-600">Total Spent</span>
                    <span class="font-bold text-terracotta">
                        Rp {{ number_format($customer->orders->where('status', 'completed')->sum('total_amount'), 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
