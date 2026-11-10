@extends('layouts.app')

@section('title', 'Payment Instructions - KNOTTED')

@section('content')
<div class="min-h-screen bg-cream py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Success Message -->
        <div class="bg-green-50 border-l-4 border-green-500 p-6 mb-8 rounded-lg" data-aos="fade-down">
            <div class="flex items-center">
                <i data-lucide="check-circle" class="w-8 h-8 text-green-500 mr-4"></i>
                <div>
                    <h2 class="text-2xl font-bold text-green-800 mb-2">✅ Order Placed Successfully!</h2>
                    <p class="text-green-700">Order Number: <span class="font-bold">{{ $order->order_number }}</span></p>
                    <p class="text-green-700">Please complete payment within <span class="font-bold">24 hours</span></p>
                </div>
            </div>
        </div>

        <!-- Payment Instructions -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8" data-aos="fade-up">
            <div class="bg-gradient-to-r from-[#947765] to-[#DCCBBC] px-6 py-4">
                <h3 class="text-2xl font-bold text-white">💳 Payment Instructions</h3>
            </div>

            <div class="p-6">
                <!-- Order Summary -->
                <div class="border-b border-oat pb-6 mb-6">
                    <h4 class="text-lg font-bold text-espresso mb-4">Order Summary</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-mocha">Subtotal</span>
                            <span class="font-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-mocha">Shipping</span>
                            <span class="font-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xl font-bold border-t border-oat pt-2 mt-2">
                            <span>Total Payment</span>
                            <span class="text-[#947765]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                @if($order->payment_method === 'bank_transfer')
                    <!-- Bank Transfer -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-bold flex items-center">
                            <i data-lucide="building" class="w-5 h-5 mr-2"></i>
                            Bank Transfer
                        </h4>
                        
                        <!-- BCA -->
                        <div class="bg-[#F5F0E8] p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-white font-bold">BCA</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-mocha">Bank Central Asia</p>
                                    <p class="font-bold text-lg">1234 5678 9000</p>
                                    <p class="text-sm">a.n. <strong>KNOTTED Store</strong></p>
                                </div>
                                <button onclick="copyText('1234567890')" class="btn-secondary btn-sm">
                                    <i data-lucide="copy" class="w-4 h-4"></i> Copy
                                </button>
                            </div>
                        </div>

                        <!-- Mandiri -->
                        <div class="bg-[#F5F0E8] p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-white font-bold text-xs">MANDIRI</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-mocha">Bank Mandiri</p>
                                    <p class="font-bold text-lg">0987 6543 2100</p>
                                    <p class="text-sm">a.n. <strong>KNOTTED Store</strong></p>
                                </div>
                                <button onclick="copyText('09876543211')" class="btn-secondary btn-sm">
                                    <i data-lucide="copy" class="w-4 h-4"></i> Copy
                                </button>
                            </div>
                        </div>

                        <!-- Important Notes -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                            <h5 class="font-bold text-yellow-800 mb-2">⚠️ Important:</h5>
                            <ul class="text-sm text-yellow-800 space-y-1">
                                <li>✓ Transfer EXACT amount: <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></li>
                                <li>✓ Include order number: <strong>{{ $order->order_number }}</strong></li>
                                <li>✓ Save transfer receipt</li>
                            </ul>
                        </div>
                    </div>

                @else
                    <!-- QRIS -->
                    <div class="text-center space-y-4">
                        <h4 class="text-lg font-bold">📱 Scan QRIS Code</h4>
                        
                        <div class="bg-white p-6 rounded-lg inline-block shadow-lg">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=KNOTTED_{{ $order->order_number }}_Rp{{ $order->total_amount }}" 
                                 alt="QRIS" 
                                 class="w-64 h-64 mx-auto">
                        </div>
                        
                        <p class="text-2xl font-bold text-[#947765]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        
                        <div class="flex justify-center gap-3 flex-wrap">
                            <span class="px-4 py-2 bg-[#F5F0E8] rounded-full text-sm font-semibold">GoPay</span>
                            <span class="px-4 py-2 bg-[#F5F0E8] rounded-full text-sm font-semibold">OVO</span>
                            <span class="px-4 py-2 bg-[#F5F0E8] rounded-full text-sm font-semibold">Dana</span>
                            <span class="px-4 py-2 bg-[#F5F0E8] rounded-full text-sm font-semibold">ShopeePay</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Upload Payment Proof -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="200">
            <div class="bg-[#947765] px-6 py-4">
                <h3 class="text-xl font-bold text-white">📤 Upload Payment Proof</h3>
            </div>

            <form action="{{ route('customer.payments.store', $order->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <div>
                    <label class="form-label">Payment Date</label>
                    <input type="datetime-local" name="payment_date" value="{{ now()->format('Y-m-d\TH:i') }}" class="form-input" required>
                </div>

                <div>
                    <label class="form-label">Upload Receipt/Screenshot</label>
                    <input type="file" name="payment_proof" accept="image/*" class="form-input" required>
                    <p class="text-sm text-mocha mt-1">Max 5MB (JPG, PNG)</p>
                </div>

                <div>
                    <label class="form-label">Notes (Optional)</label>
                    <textarea name="payment_notes" rows="3" class="form-input" placeholder="Additional notes..."></textarea>
                </div>

                <button type="submit" class="btn-primary w-full">
                    <i data-lucide="upload" class="w-5 h-5 mr-2"></i>
                    Submit Payment Proof
                </button>
            </form>
        </div>

        <!-- Contact Support -->
        <div class="mt-8 text-center">
            <p class="text-mocha mb-4">Need help with payment?</p>
            <a href="https://wa.me/6281234567890?text=Hi%20KNOTTED%2C%20I%20need%20help%20with%20order%20{{ $order->order_number }}" 
               target="_blank"
               class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-full transition">
                <i data-lucide="message-circle" class="w-5 h-5"></i>
                Contact Us via WhatsApp
            </a>
        </div>

    </div>
</div>

@push('scripts')
<script>
function copyText(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Account number copied!');
    });
}

// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
@endpush
@endsection
