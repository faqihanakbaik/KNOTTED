@extends('layouts.admin')

@section('page-title', 'Payment Verification')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-espresso">Pending Payments</h2>
    <p class="text-gray-600 mt-1">Review and verify customer payment proofs</p>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="table">
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Order</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($pendingPayments as $payment)
                <tr>
                    <td class="font-semibold">#{{ $payment->id }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $payment->order->id) }}" class="text-blue-600 hover:text-blue-800">
                            #{{ $payment->order->order_number }}
                        </a>
                    </td>
                    <td>{{ $payment->order->user->name }}</td>
                    <td class="font-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($payment->payment_method) }}</td>
                    <td>{{ $payment->created_at->format('d M Y, H:i') }}</td>
                    <td>
                        @if($payment->status == 'pending')
                            <span class="badge badge-pending">Pending Verification</span>
                        @elseif($payment->status == 'verified')
                            <span class="badge badge-completed">Verified</span>
                        @else
                            <span class="badge badge-cancelled">Rejected</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.payments.show', $payment->id) }}" class="text-blue-600 hover:text-blue-800">
                            Review
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-8 text-gray-500">
                        No pending payments to verify
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $pendingPayments->links() }}
</div>
@endsection
