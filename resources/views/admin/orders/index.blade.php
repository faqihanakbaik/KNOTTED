@extends('layouts.admin')

@section('page-title', 'Orders Management')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-espresso">All Orders</h2>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex gap-4">
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
            <select name="status" id="status" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">All Statuses</option>
                <option value="pending_payment" {{ request('status') == 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                <option value="verifying_payment" {{ request('status') == 'verifying_payment' ? 'selected' : '' }}>Verifying Payment</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Shipping</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="payment_failed" {{ request('status') == 'payment_failed' ? 'selected' : '' }}>Payment Failed</option>
            </select>
        </div>

        <div>
            <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
            <select name="payment_status" id="payment_status" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">All Payment Status</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="verified" {{ request('payment_status') == 'verified' ? 'selected' : '' }}>Verified</option>
                <option value="rejected" {{ request('payment_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" class="btn-primary">Filter</button>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($orders as $order)
                <tr>
                    <td class="font-semibold">#{{ $order->order_number }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td class="font-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td>
                        @if($order->status == 'pending_payment')
                            <span class="badge badge-pending">Pending Payment</span>
                        @elseif($order->status == 'verifying_payment')
                            <span class="badge badge-pending">Verifying</span>
                        @elseif($order->status == 'processing')
                            <span class="badge badge-processing">Processing</span>
                        @elseif($order->status == 'shipping')
                            <span class="badge badge-shipped">Shipping</span>
                        @elseif($order->status == 'completed')
                            <span class="badge badge-completed">Completed</span>
                        @elseif($order->status == 'cancelled')
                            <span class="badge badge-cancelled">Cancelled</span>
                        @else
                            <span class="badge badge-cancelled">Payment Failed</span>
                        @endif
                    </td>
                    <td>
                        @if($order->payment_status == 'pending')
                            <span class="badge badge-pending">Pending</span>
                        @elseif($order->payment_status == 'verified')
                            <span class="badge badge-completed">Verified</span>
                        @else
                            <span class="badge badge-cancelled">Rejected</span>
                        @endif
                    </td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-800">
                            View Details
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-8 text-gray-500">No orders found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $orders->links() }}
</div>
@endsection
