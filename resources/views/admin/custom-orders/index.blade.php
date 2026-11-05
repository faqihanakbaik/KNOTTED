@extends('layouts.admin')

@section('page-title', 'Custom Orders Management')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-espresso">Custom Order Requests</h2>
    <p class="text-gray-600 mt-1">Manage customer custom product requests</p>
</div>

<!-- Status Tabs -->
<div class="flex gap-4 mb-6">
    <a href="{{ route('admin.custom-orders.index') }}" 
       class="px-4 py-2 rounded-lg {{ !request('status') ? 'bg-terracotta text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-terracotta' }}">
        All ({{ $statusCounts['all'] }})
    </a>
    <a href="{{ route('admin.custom-orders.index', ['status' => 'pending']) }}" 
       class="px-4 py-2 rounded-lg {{ request('status') == 'pending' ? 'bg-terracotta text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-terracotta' }}">
        Pending ({{ $statusCounts['pending'] }})
    </a>
    <a href="{{ route('admin.custom-orders.index', ['status' => 'quoted']) }}" 
       class="px-4 py-2 rounded-lg {{ request('status') == 'quoted' ? 'bg-terracotta text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-terracotta' }}">
        Negotiating ({{ $statusCounts['negotiating'] }})
    </a>
    <a href="{{ route('admin.custom-orders.index', ['status' => 'accepted']) }}" 
       class="px-4 py-2 rounded-lg {{ request('status') == 'accepted' ? 'bg-terracotta text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-terracotta' }}">
        Accepted ({{ $statusCounts['accepted'] }})
    </a>
</div>

<!-- Orders List -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Quoted Price</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($customOrders as $customOrder)
                <tr>
                    <td class="font-semibold">#{{ $customOrder->id }}</td>
                    <td>{{ $customOrder->user->name }}</td>
                    <td>{{ Str::limit($customOrder->product_name, 30) }}</td>
                    <td>{{ $customOrder->quantity }}</td>
                    <td>
                        @if($customOrder->admin_quoted_price)
                            <span class="font-semibold">Rp {{ number_format($customOrder->admin_quoted_price, 0, ',', '.') }}</span>
                        @else
                            <span class="text-gray-400">Not quoted</span>
                        @endif
                    </td>
                    <td>
                        @if($customOrder->status == 'pending')
                            <span class="badge badge-pending">Pending</span>
                        @elseif($customOrder->status == 'quoted')
                            <span class="badge badge-processing">Quoted</span>
                        @elseif($customOrder->status == 'negotiating')
                            <span class="badge badge-processing">Negotiating</span>
                        @elseif($customOrder->status == 'accepted')
                            <span class="badge badge-completed">Accepted</span>
                        @elseif($customOrder->status == 'rejected')
                            <span class="badge badge-cancelled">Rejected</span>
                        @else
                            <span class="badge badge-shipped">{{ ucfirst($customOrder->status) }}</span>
                        @endif
                    </td>
                    <td>{{ $customOrder->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.custom-orders.show', $customOrder) }}" class="text-blue-600 hover:text-blue-800">
                            Review
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-8 text-gray-500">No custom orders found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $customOrders->links() }}
</div>
@endsection
