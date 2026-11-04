@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Orders -->
    <div class="stat-card">
        <h3>Total Orders</h3>
        <p>{{ $stats['total_orders'] }}</p>
    </div>

    <!-- Pending Orders -->
    <div class="stat-card">
        <h3>Pending Orders</h3>
        <p class="text-yellow-600">{{ $stats['pending_orders'] }}</p>
    </div>

    <!-- Total Revenue -->
    <div class="stat-card">
        <h3>Total Revenue</h3>
        <p class="text-green-600">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
    </div>

    <!-- Total Customers -->
    <div class="stat-card">
        <h3>Total Customers</h3>
        <p>{{ $stats['total_customers'] }}</p>
    </div>

    <!-- Total Products -->
    <div class="stat-card">
        <h3>Total Products</h3>
        <p>{{ $stats['total_products'] }}</p>
    </div>

    <!-- Low Stock -->
    <div class="stat-card">
        <h3>Low Stock Products</h3>
        <p class="text-red-600">{{ $stats['low_stock_products'] }}</p>
    </div>

    <!-- Pending Payments -->
    <div class="stat-card">
        <h3>Pending Payments</h3>
        <p class="text-orange-600">{{ $stats['pending_payments'] }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-serif text-xl font-bold text-espresso mb-4">Recent Orders</h3>
        <div class="space-y-3">
            @forelse($recentOrders as $order)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-semibold text-espresso">{{ $order->order_number }}</p>
                        <p class="text-sm text-gray-500">{{ $order->customer_name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-mocha">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No recent orders</p>
            @endforelse
        </div>
        <a href="{{ route('admin.orders.index') }}" class="block text-center mt-4 text-mocha hover:text-espresso font-semibold">
            View All Orders →
        </a>
    </div>

    <!-- Low Stock Products -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-serif text-xl font-bold text-espresso mb-4">Low Stock Alert</h3>
        <div class="space-y-3">
            @forelse($lowStockProducts as $product)
                <div class="flex justify-between items-center p-3 bg-red-50 rounded">
                    <div>
                        <p class="font-semibold text-espresso">{{ $product->name }}</p>
                        <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-red-600">{{ $product->stock }} left</p>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="text-xs text-mocha hover:text-espresso">Edit</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">All products have sufficient stock</p>
            @endforelse
        </div>
        <a href="{{ route('admin.products.index') }}" class="block text-center mt-4 text-mocha hover:text-espresso font-semibold">
            Manage Products →
        </a>
    </div>
</div>
@endsection
