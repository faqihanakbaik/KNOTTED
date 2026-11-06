@extends('layouts.admin')

@section('page-title', 'Review Payment')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center text-mocha hover:text-espresso mb-4 transition">
        <iconify-icon icon="solar:arrow-left-bold" class="mr-2"></iconify-icon>
        Back to Payment List
    </a>
    <h2 class="text-2xl font-bold text-espresso">Payment Verification</h2>
    <p class="text-gray-600 mt-1">Order #{{ $payment->order->order_number }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Payment Proof & Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Payment Proof Image -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="font-semibold text-lg text-espresso mb-4">Payment Proof</h3>
            
            @if($payment->payment_proof)
                <div class="border-2 border-gray-200 rounded-lg overflow-hidden">
                    <img src="{{ $payment->payment_proof }}" 
                         alt="Payment Proof" 
                         class="w-full h-auto cursor-pointer hover:opacity-90 transition"
                         onclick="openImageModal(this.src)">
                </div>
                <p class="text-xs text-gray-500 mt-2">Click image to view fullscreen</p>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <iconify-icon icon="solar:gallery-bold" class="text-gray-300 text-6xl mx-auto mb-3"></iconify-icon>
                    <p class="text-gray-500">No payment proof uploaded yet</p>
                </div>
            @endif
        </div>

        <!-- Payment Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="font-semibold text-lg text-espresso mb-4">Payment Information</h3>
            
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 mb-1">Payment Method</p>
                    <p class="font-semibold text-gray-900">
                        @if($payment->payment_method === 'bank_transfer')
                            <iconify-icon icon="solar:card-bold" class="inline-block"></iconify-icon> Bank Transfer
                        @else
                            <iconify-icon icon="solar:qr-code-bold" class="inline-block"></iconify-icon> QRIS
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Payment Date</p>
                    <p class="font-semibold text-gray-900">
                        {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Amount</p>
                    <p class="font-semibold text-mocha text-lg">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Status</p>
                    <p>
                        @if($payment->status === 'under_review')
                            <span class="badge badge-pending">Under Review</span>
                        @elseif($payment->status === 'paid')
                            <span class="badge badge-completed">Verified</span>
                        @elseif($payment->status === 'rejected')
                            <span class="badge badge-cancelled">Rejected</span>
                        @endif
                    </p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-500 mb-1">Customer Notes</p>
                    <p class="text-gray-900">{{ $payment->notes ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Verification Actions -->
        @if($payment->status === 'under_review')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-lg text-espresso mb-4">Verification Action</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Approve Form -->
                    <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="form-label">Admin Notes (Optional)</label>
                            <textarea name="admin_notes" rows="3" class="form-input" placeholder="Internal notes about this verification"></textarea>
                        </div>
                        <button type="submit" class="w-full btn-primary bg-green-600 hover:bg-green-700">
                            <iconify-icon icon="solar:check-circle-bold" class="inline-block mr-2"></iconify-icon>
                            Approve Payment
                        </button>
                    </form>

                    <!-- Reject Form -->
                    <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="form-label">Rejection Reason *</label>
                            <textarea name="rejection_reason" rows="3" required class="form-input" placeholder="Explain why this payment is rejected (customer will see this)"></textarea>
                        </div>
                        <button type="submit" class="w-full btn-secondary bg-red-600 hover:bg-red-700 text-white">
                            <iconify-icon icon="solar:close-circle-bold" class="inline-block mr-2"></iconify-icon>
                            Reject Payment
                        </button>
                    </form>
                </div>
            </div>
        @elseif($payment->status === 'paid')
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <iconify-icon icon="solar:check-circle-bold" class="text-green-600 text-2xl flex-shrink-0"></iconify-icon>
                    <div>
                        <p class="font-semibold text-green-900">Payment Verified</p>
                        <p class="text-sm text-green-700 mt-1">Verified at: {{ $payment->verified_at->format('d M Y, H:i') }}</p>
                        @if($payment->admin_notes)
                            <p class="text-sm text-green-700 mt-1">Notes: {{ $payment->admin_notes }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @elseif($payment->status === 'rejected')
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <iconify-icon icon="solar:close-circle-bold" class="text-red-600 text-2xl flex-shrink-0"></iconify-icon>
                    <div>
                        <p class="font-semibold text-red-900">Payment Rejected</p>
                        <p class="text-sm text-red-700 mt-1">Rejected at: {{ $payment->verified_at->format('d M Y, H:i') }}</p>
                        @if($payment->admin_notes)
                            <p class="text-sm text-red-700 mt-1">Reason: {{ $payment->admin_notes }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Order Summary Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-6 space-y-6">
            <div>
                <h3 class="font-semibold text-lg text-espresso mb-4">Order Details</h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Order Number</p>
                        <p class="font-semibold">{{ $payment->order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Customer</p>
                        <p class="font-semibold">{{ $payment->order->customer_name }}</p>
                        <p class="text-xs text-gray-500">{{ $payment->order->customer_email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Phone</p>
                        <p class="font-semibold">{{ $payment->order->customer_phone }}</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <h4 class="font-semibold text-espresso mb-3">Order Items</h4>
                <div class="space-y-2">
                    @foreach($payment->order->items as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-700">{{ $item->product_name }} <span class="text-gray-500">x{{ $item->quantity }}</span></span>
                            <span class="font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-semibold">Rp {{ number_format($payment->order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Shipping</span>
                    <span class="font-semibold">Rp {{ number_format($payment->order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-gray-200">
                    <span class="font-bold text-espresso">Total</span>
                    <span class="font-bold text-lg text-mocha">Rp {{ number_format($payment->order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <a href="{{ route('admin.orders.show', $payment->order->id) }}" class="btn-secondary w-full text-center">
                    View Full Order
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
            <iconify-icon icon="solar:close-circle-bold" style="font-size: 2rem;"></iconify-icon>
        </button>
        <img id="modalImage" src="" alt="Payment Proof" class="max-w-full max-h-[90vh] object-contain rounded-lg">
    </div>
</div>

<script>
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModal').classList.add('flex');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('imageModal').classList.remove('flex');
}
</script>
@endsection
