@extends('layouts.app')

@section('title', 'Upload Payment Proof - KNOTTED')

@section('content')
<div class="bg-cream py-12 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('customer.orders.show', $order->id) }}" class="text-mocha hover:text-espresso text-sm font-medium">
                <i data-lucide="arrow-left" class="inline-block w-4 h-4"></i> Back to Order Details
            </a>
        </div>

        <h1 class="font-serif text-4xl font-bold text-espresso mb-2">Upload Payment Proof</h1>
        <p class="text-mocha mb-8">Order #{{ $order->order_number }}</p>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Upload Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg p-6 shadow-sm border border-oat">
                    <h2 class="font-serif text-2xl font-bold text-espresso mb-6">Payment Information</h2>

                    <form action="{{ route('customer.payments.store', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Payment Method Info -->
                        <div class="bg-sage/10 rounded-lg p-4">
                            <p class="text-sm font-semibold text-espresso mb-2">Selected Payment Method:</p>
                            <div class="flex items-center gap-2">
                                @if($order->payment_method === 'bank_transfer')
                                    <iconify-icon icon="solar:card-bold" class="text-mocha" style="font-size: 1.5rem;"></iconify-icon>
                                    <span class="font-bold text-mocha">Bank Transfer</span>
                                @else
                                    <iconify-icon icon="solar:qr-code-bold" class="text-mocha" style="font-size: 1.5rem;"></iconify-icon>
                                    <span class="font-bold text-mocha">QRIS</span>
                                @endif
                            </div>
                        </div>

                        @if($order->payment_method === 'bank_transfer')
                            <!-- Bank Account Information -->
                            <div class="bg-white border-2 border-mocha rounded-lg p-5">
                                <h3 class="font-semibold text-espresso mb-4 flex items-center gap-2">
                                    <iconify-icon icon="solar:card-bold" class="text-mocha" style="font-size: 1.25rem;"></iconify-icon>
                                    Transfer ke Rekening Berikut:
                                </h3>
                                
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center p-3 bg-cream rounded">
                                        <span class="text-sm text-espresso">Bank</span>
                                        <span class="font-bold text-mocha">BCA</span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 bg-cream rounded">
                                        <span class="text-sm text-espresso">Nomor Rekening</span>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-mocha font-mono" id="account-number">1234567890</span>
                                            <button type="button" onclick="copyAccountNumber()" class="text-sage hover:text-mocha">
                                                <i data-lucide="copy" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center p-3 bg-cream rounded">
                                        <span class="text-sm text-espresso">Atas Nama</span>
                                        <span class="font-bold text-mocha">KNOTTED Store</span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 bg-mocha/10 rounded border-2 border-mocha">
                                        <span class="text-sm font-semibold text-espresso">Total Transfer</span>
                                        <span class="font-bold text-mocha text-lg">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                                    <p class="text-xs text-yellow-800">
                                        <i data-lucide="info" class="w-4 h-4 inline-block"></i>
                                        Harap transfer <strong>sesuai nominal total</strong> untuk mempercepat verifikasi.
                                    </p>
                                </div>
                            </div>
                        @else
                            <!-- QRIS Information -->
                            <div class="bg-white border-2 border-mocha rounded-lg p-5">
                                <h3 class="font-semibold text-espresso mb-4 text-center">Scan QRIS Code</h3>
                                
                                <div class="flex justify-center mb-4">
                                    <div class="bg-white p-4 rounded-lg border-2 border-oat">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=KNOTTED_ORDER_{{ $order->order_number }}_{{ $order->total_amount }}" 
                                             alt="QRIS Code" 
                                             class="w-64 h-64">
                                    </div>
                                </div>

                                <div class="text-center mb-4">
                                    <p class="text-sm text-sage mb-2">Total Pembayaran</p>
                                    <p class="font-bold text-mocha text-2xl">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                </div>

                                <div class="p-3 bg-blue-50 border border-blue-200 rounded">
                                    <p class="text-xs text-blue-800 text-center">
                                        <i data-lucide="smartphone" class="w-4 h-4 inline-block"></i>
                                        Scan menggunakan aplikasi e-wallet atau mobile banking Anda
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- Payment Date -->
                        <div>
                            <label class="form-label">Tanggal Transfer / Pembayaran <span class="text-red-500">*</span></label>
                            <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" 
                                   max="{{ date('Y-m-d') }}" required class="form-input">
                            @error('payment_date')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-sage mt-1">Pilih tanggal saat Anda melakukan pembayaran</p>
                        </div>

                        <!-- Payment Proof Upload -->
                        <div>
                            <label class="form-label">Bukti Pembayaran <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <label for="payment_proof" class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-oat rounded-lg cursor-pointer hover:border-mocha transition bg-cream/30 hover:bg-cream/50">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center" id="upload-placeholder">
                                        <i data-lucide="upload-cloud" class="w-12 h-12 mb-3 text-mocha"></i>
                                        <p class="mb-2 text-sm text-espresso"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-sage">PNG, JPG, JPEG (MAX. 5MB)</p>
                                    </div>
                                    <img id="image-preview" class="hidden w-full h-full object-contain p-4" alt="Payment proof preview">
                                    <input id="payment_proof" name="payment_proof" type="file" class="hidden" accept="image/jpeg,image/png,image/jpg" required onchange="previewImage(event)">
                                </label>
                            </div>
                            @error('payment_proof')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-sage mt-1">Upload screenshot atau foto bukti transfer/pembayaran yang jelas</p>
                        </div>

                        <!-- Payment Notes -->
                        <div>
                            <label class="form-label">Catatan (Opsional)</label>
                            <textarea name="payment_notes" rows="3" class="form-input" placeholder="Contoh: Transfer dari rekening atas nama...">{{ old('payment_notes') }}</textarea>
                            @error('payment_notes')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-4">
                            <button type="submit" class="flex-1 btn-primary py-4">
                                <i data-lucide="upload" class="inline-block w-5 h-5 mr-2"></i>
                                Upload Payment Proof
                            </button>
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="flex-1 text-center btn-secondary py-4">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg p-6 shadow-sm border border-oat sticky top-24">
                    <h3 class="font-serif text-xl font-bold text-espresso mb-4">Order Summary</h3>
                    
                    <div class="space-y-3 mb-6 pb-6 border-b border-oat">
                        <div class="flex justify-between text-sm">
                            <span class="text-espresso">Order Number</span>
                            <span class="font-semibold text-mocha">{{ $order->order_number }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-espresso">Order Date</span>
                            <span class="text-sage">{{ $order->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-espresso">Status</span>
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->payment_status === 'rejected') bg-red-100 text-red-800
                                @else bg-blue-100 text-blue-800
                                @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-espresso">Subtotal</span>
                            <span class="text-sm font-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-espresso">Shipping</span>
                            <span class="text-sm font-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between pt-4 border-t border-oat">
                        <span class="font-bold text-espresso">Total</span>
                        <span class="font-bold text-mocha text-lg">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>

                    @if($order->payment_status === 'rejected' && $order->payment && $order->payment->notes)
                        <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded">
                            <p class="text-xs font-semibold text-red-800 mb-1">Rejection Reason:</p>
                            <p class="text-xs text-red-700">{{ $order->payment->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('upload-placeholder').classList.add('hidden');
                const preview = document.getElementById('image-preview');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    function copyAccountNumber() {
        const accountNumber = document.getElementById('account-number').textContent;
        navigator.clipboard.writeText(accountNumber).then(() => {
            alert('Account number copied to clipboard!');
        });
    }

    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
@endpush
@endsection
