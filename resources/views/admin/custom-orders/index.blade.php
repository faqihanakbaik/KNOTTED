@extends('layouts.admin')

@section('page-title', 'Custom Orders Management')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-espresso">Custom Orders</h2>
    <p class="text-gray-600 mt-1">Manage customer custom order requests</p>
</div>

<!-- Status Filters -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.custom-orders.index') }}" 
           class="px-4 py-2 rounded-lg {{ !request('status') ? 'bg-mocha text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            All <span class="ml-1 text-xs">({{ $statusCounts['all'] }})</span>
        </a>
        <a href="{{ route('admin.custom-orders.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-lg {{ request('status') == 'pending' ? 'bg-mocha text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Pending <span class="ml-1 text-xs">({{ $statusCounts['pending'] }})</span>
        </a>
        <a href="{{ route('admin.custom-orders.index', ['status' => 'quoted']) }}" 
           class="px-4 py-2 rounded-lg {{ request('status') == 'quoted' ? 'bg-mocha text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Quoted <span class="ml-1 text-xs">({{ $statusCounts['negotiating'] }})</span>
        </a>
        <a href="{{ route('admin.custom-orders.index', ['status' => 'accepted']) }}" 
           class="px-4 py-2 rounded-lg {{ request('status') == 'accepted' ? 'bg-mocha text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Accepted <span class="ml-1 text-xs">({{ $statusCounts['accepted'] }})</span>
        </a>
    </div>
</div>

<!-- Custom Orders Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Customer</th>
                <th>Quantity</th>
                <th>Budget</th>
                <th>Status</th>
                <th>Requested</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($customOrders as $customOrder)
                <tr>
                    <td class="font-semibold">#{{ $customOrder->id }}</td>
                    <td>
                        <p class="font-medium">{{ $customOrder->product_name }}</p>
                        @if($customOrder->category)
                            <p class="text-xs text-gray-500">{{ $customOrder->category->name }}</p>
                        @endif
                    </td>
                    <td>
                        <p class="font-medium">{{ $customOrder->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $customOrder->user->email }}</p>
                    </td>
                    <td>{{ $customOrder->quantity }}</td>
                    <td class="font-semibold text-mocha">
                        @if($customOrder->budget)
                            Rp {{ number_format($customOrder->budget, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($customOrder->status == 'pending')
                            <span class="badge badge-pending">Pending Review</span>
                        @elseif($customOrder->status == 'quoted')
                            <span class="badge badge-processing">Quoted</span>
                        @elseif($customOrder->status == 'negotiating')
                            <span class="badge badge-processing">Negotiating</span>
                        @elseif($customOrder->status == 'accepted')
                            <span class="badge badge-completed">Accepted</span>
                        @elseif($customOrder->status == 'rejected')
                            <span class="badge badge-cancelled">Rejected</span>
                        @elseif($customOrder->status == 'converted')
                            <span class="badge badge-completed">Converted to Order</span>
                        @else
                            <span class="badge badge-pending">{{ ucfirst($customOrder->status) }}</span>
                        @endif
                    </td>
                    <td>{{ $customOrder->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.custom-orders.show', $customOrder) }}" 
                           class="text-blue-600 hover:text-blue-800 font-semibold">
                            View Details
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
