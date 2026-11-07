@extends('layouts.admin')

@section('page-title', 'Custom Order Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.custom-orders.index') }}" class="text-gray-600 hover:text-espresso">
        ← Back to Custom Orders
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Order Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-bold text-espresso">{{ $customOrder->product_name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Request ID: #{{ $customOrder->id }}</p>
                </div>
                <div>
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
                        <span class="badge badge-completed">Converted</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Category</p>
                    <p class="font-semibold">{{ $customOrder->category->name ?? 'Not specified' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Quantity</p>
                    <p class="font-semibold">{{ $customOrder->quantity }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Customer Budget</p>
                    <p class="font-semibold text-mocha">
                        @if($customOrder->budget)
                            Rp {{ number_format($customOrder->budget, 0, ',', '.') }}
                        @else
                            Not specified
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Requested</p>
                    <p class="font-semibold">{{ $customOrder->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-gray-500 text-sm mb-2">Description</p>
                <p class="text-gray-900">{{ $customOrder->description }}</p>
            </div>

            @if($customOrder->notes)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-gray-500 text-sm mb-2">Customer Notes</p>
                    <p class="text-gray-900">{{ $customOrder->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Reference Images -->
        @if($customOrder->images && $customOrder->images->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h4 class="font-semibold text-lg text-espresso mb-4">Reference Images</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($customOrder->images as $image)
                        <div class="border-2 border-gray-200 rounded-lg overflow-hidden">
                            <img src="{{ $image->image_path }}" 
                                 alt="Reference Image" 
                                 class="w-full h-48 object-cover cursor-pointer hover:opacity-90"
                                 onclick="openImageModal('{{ $image->image_path }}')">
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Negotiation History -->
        @if($customOrder->offers && $customOrder->offers->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h4 class="font-semibold text-lg text-espresso mb-4">Negotiation History</h4>
                <div class="space-y-4">
                    @foreach($customOrder->offers()->latest()->get() as $offer)
                        <div class="border-l-4 pl-4 {{ $offer->isFromAdmin() ? 'border-blue-500' : 'border-green-500' }}">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-sm">
                                        {{ $offer->isFromAdmin() ? 'Admin Quote' : 'Customer Counter-offer' }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $offer->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-lg text-mocha">Rp {{ number_format($offer->offered_price, 0, ',', '.') }}</p>
                                    @if($offer->status == 'pending')
                                        <span class="text-xs badge badge-pending">Pending</span>
                                    @elseif($offer->status == 'accepted')
                                        <span class="text-xs badge badge-completed">Accepted</span>
                                    @elseif($offer->status == 'rejected')
                                        <span class="text-xs badge badge-cancelled">Rejected</span>
                                    @else
                                        <span class="text-xs badge badge-pending">{{ ucfirst($offer->status) }}</span>
                                    @endif
                                </div>
                            </div>
                            @if($offer->notes)
                                <p class="text-sm text-gray-700 mt-2">{{ $offer->notes }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Actions -->
        @if($customOrder->status == 'pending' || $customOrder->status == 'quoted' || $customOrder->status == 'negotiating')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h4 class="font-semibold text-lg text-espresso mb-4">Send Price Quote</h4>
                <form action="{{ route('admin.custom-orders.quote', $customOrder) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label">Quoted Price (Rp) *</label>
                        <input type="number" 
                               name="quoted_price" 
                               min="0" 
                               step="1000"
                               value="{{ old('quoted_price', $customOrder->admin_quoted_price) }}"
                               class="form-input" 
                               required>
                    </div>
                    <div>
                        <label class="form-label">Estimated Production Days</label>
                        <input type="number" 
                               name="estimated_days" 
                               min="1"
                               value="{{ old('estimated_days', $customOrder->estimated_days) }}"
                               class="form-input" 
                               placeholder="e.g., 7">
                    </div>
                    <div>
                        <label class="form-label">Notes to Customer</label>
                        <textarea name="notes" 
                                  rows="3" 
                                  class="form-input" 
                                  placeholder="Additional information about materials, process, etc.">{{ old('notes') }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary w-full">
                        Send Quote to Customer
                    </button>
                </form>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <form action="{{ route('admin.custom-orders.reject', $customOrder) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to reject this custom order?')">
                        @csrf
                        @method('PATCH')
                        <label class="form-label">Rejection Reason (Optional)</label>
                        <textarea name="rejection_reason" 
                                  rows="2" 
                                  class="form-input mb-3" 
                                  placeholder="Explain why this request is rejected"></textarea>
                        <button type="submit" class="btn-secondary bg-red-600 hover:bg-red-700 text-white w-full">
                            Reject Custom Order
                        </button>
                    </form>
                </div>
            </div>
        @elseif($customOrder->status == 'accepted' && !$customOrder->order_id)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h4 class="font-semibold text-lg text-espresso mb-4">Convert to Order</h4>
                <p class="text-sm text-gray-600 mb-4">
                    This custom order has been accepted. Convert it to a regular order to proceed with production and payment.
                </p>
                <p class="text-sm font-semibold text-mocha mb-4">
                    Final Agreed Price: Rp {{ number_format($customOrder->final_agreed_price, 0, ',', '.') }}
                </p>
                <form action="{{ route('admin.custom-orders.convert', $customOrder) }}" 
                      method="POST"
                      onsubmit="return confirm('Convert this custom order to a regular order?')">
                    @csrf
                    <button type="submit" class="btn-primary w-full">
                        Convert to Order
                    </button>
                </form>
            </div>
        @elseif($customOrder->status == 'converted' && $customOrder->order_id)
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <div class="flex items-start gap-3">
                    <iconify-icon icon="solar:check-circle-bold" class="text-green-600 text-2xl flex-shrink-0"></iconify-icon>
                    <div>
                        <p class="font-semibold text-green-900">Order Created</p>
                        <p class="text-sm text-green-700 mt-1">This custom order has been converted to a regular order.</p>
                        <a href="{{ route('admin.orders.show', $customOrder->order) }}" 
                           class="inline-block mt-3 text-sm font-semibold text-green-700 hover:text-green-900">
                            View Order →
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Customer Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h4 class="font-semibold text-lg text-espresso mb-4">Customer</h4>
            <div class="space-y-2 text-sm">
                <p class="font-semibold">{{ $customOrder->user->name }}</p>
                <p class="text-gray-600">{{ $customOrder->user->email }}</p>
                <p class="text-gray-600">{{ $customOrder->user->phone ?? 'No phone' }}</p>
            </div>
            <a href="{{ route('admin.customers.show', $customOrder->user) }}" 
               class="block mt-4 text-sm text-blue-600 hover:text-blue-800 font-semibold">
                View Customer Profile →
            </a>
        </div>

        <!-- Status Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h4 class="font-semibold text-lg text-espresso mb-4">Status Info</h4>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-500">Current Status</p>
                    <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $customOrder->status)) }}</p>
                </div>
                @if($customOrder->admin_quoted_price)
                    <div>
                        <p class="text-gray-500">Quoted Price</p>
                        <p class="font-semibold text-mocha">Rp {{ number_format($customOrder->admin_quoted_price, 0, ',', '.') }}</p>
                    </div>
                @endif
                @if($customOrder->final_agreed_price)
                    <div>
                        <p class="text-gray-500">Agreed Price</p>
                        <p class="font-bold text-lg text-terracotta">Rp {{ number_format($customOrder->final_agreed_price, 0, ',', '.') }}</p>
                    </div>
                @endif
                @if($customOrder->estimated_days)
                    <div>
                        <p class="text-gray-500">Estimated Days</p>
                        <p class="font-semibold">{{ $customOrder->estimated_days }} days</p>
                    </div>
                @endif
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
        <img id="modalImage" src="" alt="Reference Image" class="max-w-full max-h-[90vh] object-contain rounded-lg">
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
