@extends('layouts.admin')

@section('page-title', 'Payment Verification')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-espresso">Payment Verification</h2>
    <p class="text-gray-600 mt-1">Review and verify customer payment proofs</p>
</div>

<!-- Tabs -->
<div class="mb-6 border-b border-gray-200">
    <nav class="-mb-px flex space-x-8">
        <button onclick="showTab('pending')" id="tab-pending" class="tab-button border-b-2 border-mocha text-mocha py-4 px-1 font-medium">
            Pending Review
            @if($pendingPayments->count() > 0)
                <span class="ml-2 bg-red-100 text-red-600 py-1 px-2 rounded-full text-xs">{{ $pendingPayments->count() }}</span>
            @endif
        </button>
        <button onclick="showTab('history')" id="tab-history" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 font-medium">
            History
        </button>
    </nav>
</div>

<!-- Pending Payments Tab -->
<div id="content-pending" class="tab-content">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="table">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Payment Date</th>
                    <th>Submitted At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($pendingPayments as $payment)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $payment->order->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                {{ $payment->order->order_number }}
                            </a>
                        </td>
                        <td>
                            <div>
                                <p class="font-medium text-gray-900">{{ $payment->order->customer_name }}</p>
                                <p class="text-sm text-gray-500">{{ $payment->order->user->email }}</p>
                            </div>
                        </td>
                        <td class="font-semibold text-mocha">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td>
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                @if($payment->payment_method === 'bank_transfer')
                                    <iconify-icon icon="solar:card-bold"></iconify-icon>
                                    Bank Transfer
                                @else
                                    <iconify-icon icon="solar:qr-code-bold"></iconify-icon>
                                    QRIS
                                @endif
                            </span>
                        </td>
                        <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : '-' }}</td>
                        <td>{{ $payment->updated_at->format('d M Y, H:i') }}</td>
                        <td>
                            <span class="badge badge-pending">Under Review</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn-primary text-sm px-4 py-2">
                                Review Payment
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-12">
                            <iconify-icon icon="solar:check-circle-bold" class="text-green-500 text-6xl mx-auto mb-3"></iconify-icon>
                            <p class="text-gray-500 font-medium">No payments to verify</p>
                            <p class="text-sm text-gray-400 mt-1">All caught up!</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pendingPayments->hasPages())
        <div class="mt-6">
            {{ $pendingPayments->links() }}
        </div>
    @endif
</div>

<!-- History Tab -->
<div id="content-history" class="tab-content hidden">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="table">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Verified At</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($allPayments as $payment)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $payment->order->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                {{ $payment->order->order_number }}
                            </a>
                        </td>
                        <td>{{ $payment->order->customer_name }}</td>
                        <td class="font-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td>{{ $payment->payment_method === 'bank_transfer' ? 'Bank Transfer' : 'QRIS' }}</td>
                        <td>{{ $payment->verified_at ? $payment->verified_at->format('d M Y, H:i') : '-' }}</td>
                        <td>
                            @if($payment->status === 'paid')
                                <span class="badge badge-completed">Verified</span>
                            @elseif($payment->status === 'rejected')
                                <span class="badge badge-cancelled">Rejected</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            No payment history yet
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($allPayments->hasPages())
        <div class="mt-6">
            {{ $allPayments->links() }}
        </div>
    @endif
</div>

<script>
function showTab(tab) {
    // Hide all content
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    // Remove active state from all tabs
    document.querySelectorAll('.tab-button').forEach(el => {
        el.classList.remove('border-mocha', 'text-mocha');
        el.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected content
    document.getElementById('content-' + tab).classList.remove('hidden');
    // Add active state to selected tab
    const selectedTab = document.getElementById('tab-' + tab);
    selectedTab.classList.remove('border-transparent', 'text-gray-500');
    selectedTab.classList.add('border-mocha', 'text-mocha');
}
</script>
@endsection
