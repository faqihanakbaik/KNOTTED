@extends('layouts.admin')

@section('page-title', 'Payment Verification')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.payments.index') }}" class="text-gray-600 hover:text-espresso">
        ← Back to Payments
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Payment Proof -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-xl font-bold text-espresso mb-4">Payment Proof</h3>
            
            @if($payment->proof_image)
                <div class="mb-6">
                    <img src="{{ $payment->proof_image }}" 
                         alt="Payment Proof" 
                         class="w-full rounded-lg border border-gray-300">
                </div>
            @else
                <div class="bg-gray-100 rounded-lg p-8 text-center mb-6">
                    <p class="text-gray-500">No payment proof uploaded</p>
                </div>
            @endif

            <!-- Payment Details -->
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Payment ID</p>
                    <p class="font-semibold">#{{ $payment->id }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Order Number</p>
                    <p class="font-semibold">
                        <a href="{{ route('admin.orders.show', $payment->order->id) }}" class="text-blue-600 hover:text-blue-800">
                            #{{ $payment->order->order_number }}
                        </a>
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Payment Method</p>
                    <p class="font-semibold">{{ ucfirst($payment->payment_method) }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Amount</p>
                    <p class="font-semibold text-terracotta">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Submitted</p>
                    <p class="font-semibold">{{ $payment->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Status</p>
                    <p class="font-semibold">
                        @if($payment->status == 'pending')
                            <span class="badge badge-pending">Pending</span>
                        @elseif($payment->status == 'verified')
                            <span class="badge badge-completed">Verified</span>
                        @else
                            <span class="badge badge-cancelled">Rejected</span>
                        @endif
                    </p>
                </div>
            </div>

            @if($payment->notes)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-gray-500 text-sm mb-1">Notes</p>
                    <p class="text-gray-700">{{ $payment->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Customer Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-espresso mb-4">Customer</h3>
            <div class="space-y-2 text-sm">
                <p class="font-semibold">{{ $payment->order->user->name }}</p>
                <p class="text-gray-600">{{ $payment->order->user->email }}</p>
                <p class="text-gray-600">{{ $payment->order->user->phone ?? 'No phone' }}</p>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-espresso mb-4">Order Summary</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Order Total</span>
                    <span class="font-semibold">Rp {{ number_format($payment->order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Items</span>
                    <span class="font-semibold">{{ $payment->order->items->count() }} items</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Order Status</span>
                    <span class="font-semibold">{{ ucfirst($payment->order->status) }}</span>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.orders.show', $payment->order->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    View Full Order →
                </a>
            </div>
        </div>

        <!-- Actions -->
        @if($payment->status == 'pending')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-espresso mb-4">Verification</h3>
            
            <!-- Verify Button -->
            <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="mb-3">
                @csrf
                @method('PUT')
                <button type="submit" class="w-full btn-primary bg-green-600 hover:bg-green-700" onclick="return confirm('Verify this payment?')">
                    Verify Payment
                </button>
            </form>

            <!-- Reject Form -->
            <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="3" 
                              placeholder="Explain why this payment is rejected..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500"></textarea>
                </div>
                
                <button type="submit" class="w-full btn-secondary bg-red-600 text-white hover:bg-red-700" onclick="return confirm('Reject this payment?')">
                    Reject Payment
                </button>
            </form>
        </div>
        @else
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm text-blue-800">
                This payment has been {{ $payment->status }}.
                @if($payment->verified_at)
                    Processed on {{ $payment->verified_at->format('d M Y, H:i') }}.
                @endif
            </p>
        </div>
        @endif
    </div>
</div>
@endsection
