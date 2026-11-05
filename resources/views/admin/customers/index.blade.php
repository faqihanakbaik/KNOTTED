@extends('layouts.admin')

@section('page-title', 'Customers Management')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-espresso">All Customers</h2>
    <p class="text-gray-600 mt-1">Registered customer accounts</p>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Orders</th>
                <th>Registered</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($customers as $customer)
                <tr>
                    <td class="font-semibold">{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone ?? '-' }}</td>
                    <td>
                        <span class="badge badge-pending">{{ $customer->orders_count }} orders</span>
                    </td>
                    <td>{{ $customer->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-blue-600 hover:text-blue-800">
                            View Details
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">No customers found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $customers->links() }}
</div>
@endsection
