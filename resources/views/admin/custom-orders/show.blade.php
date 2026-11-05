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
        <!-- Request Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-xl font-bold text-espresso mb-4">Request Details</h3>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-500">Product Name</p>
                    <p class="font-semibold">{{ $customOrder->product_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Category</p>
                    <p class="font-semibold">{{ $customOrder->category->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Quantity</p>
                    <p class="font-semibold">{{ $customOrder->quantity }} pcs</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Customer Budget</p>
                    <p class="font-semibold">Rp {{ number_format($customOrder->customer_budget, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-sm text-gray-500 mb-2">Description</p>
                <p class="text-gray-700">{{ $customOrder->description }}</p>
            </div>

            @if($customOrder->images->count() > 0)
            <div>
                <p class="text-sm text-gray-500 mb-2">Reference Images</p>
                <div class="grid grid-cols-3 gap-3">
                    @foreach($customOrder->images as $image)
                        <img src="{{ $image->image_path }}" 
                             alt="Reference" 
                             class="w-full h-32 object-cover rounded border border-gray-300">
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Negotiation History -->
        @if($customOrder->offers->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-espresso mb-4">Negotiation History</h3>
            
            <div class="space-y-4">
                @foreach($customOrder->offers()->latest()->get() as $offer)
                    <div class="border-l-4 {{ $offer->isFromAdmin() ? 'border-blue-500 bg-blue-50' : 'border-green-500 bg-green-50' }} pl-4 py-3 rounded-r">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-semibold">
                                    {{ $offer->isFromAdmin() ? 'Admin' : $offer->offeredBy->name }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $offer->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-lg">Rp {{ number_format($offer->offered_price, 0, ',', '.') }}</p>
                                @if($offer->status == 'pending')
                                    <span class="badge badge-pending">Pending</span>
                                @elseif($offer->status == 'accepted')
                                    <span class="badge badge-completed">Accepted</span>
                                @elseif($offer->status == 'rejected')
                                    <span class="badge badge-cancelled">Rejected</span>
                                @else
                                    <span class="badge badge-pending">{{ ucfirst($offer->status) }}</span>
                                @endif
                            </div>
                        </div>
                        @if($offer->notes)
                            <p class="text-sm text-gray-700">{{ $offer->notes }}</p>
                        @endif

                        @if($offer->isFromCustomer() && $offer->isPending())
                            <div class="flex gap-2 mt-3">
                                <form action="{{ route('admin.custom-orders.accept-offer', [$customOrder, $offer]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-sm px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                        Accept
                                    </button>
                                </form>
                                <form action="{{ route('admin.custom-orders.reject-offer', [$customOrder, $offer]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-sm px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Customer Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-espresso mb-4">Customer</h3>
            <div class="space-y-2 text-sm">
                <p class="font-semibold">{{ $customOrder->user->name }}</p>
                <p class="text-gray-600">{{ $customOrder->user->email }}</p>
                <p class="text-gray-600">{{ $customOrder->user->phone ?? 'No phone' }}</p>
            </div>
        </div>

        <!-- Current Status -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-espresso mb-4">Status</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Current Status</p>
                    <p class="font-semibold">{{ ucfirst($customOrder->status) }}</p>
                </div>
                @if($customOrder->admin_quoted_price)
                <div>
                    <p class="text-sm text-gray-500">Quoted Price</p>
                    <p class="font-semibold text-terracotta">Rp {{ number_format($customOrder->admin_quoted_price, 0, ',', '.') }}</p>
                </div>
                @endif
                @if($customOrder->final_agreed_price)
                <div>
                    <p class="text-sm text-gray-500">Final Agreed Price</p>
                    <p class="font-semibold text-green-600">Rp {{ number_format($customOrder->final_agreed_price, 0, ',', '.') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        @if($customOrder->status == 'pending')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-espresso mb-4">Quote Price</h3>
            <form action="{{ route('admin.custom-orders.quote-price', $customOrder) }}" method="POST">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label for="quoted_price" class="block text-sm font-medium text-gray-700 mb-2">Price (Rp) *</label>
                        <input type="number" 
                               name="quoted_price" 
                               id="quoted_price" 
                               min="0"
                               step="1000"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta"
                               required>
                    </div>

                    <div>
                        <label for="estimated_days" class="block text-sm font-medium text-gray-700 mb-2">Est. Days</label>
                        <input type="number" 
                               name="estimated_days" 
                               id="estimated_days" 
                               min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta">
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta"></textarea>
                    </div>

                    <button type="submit" class="w-full btn-primary">
                        Send Quote
                    </button>
                </div>
            </form>

            <form action="{{ route('admin.custom-orders.reject', $customOrder) }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="w-full btn-secondary bg-red-600 text-white hover:bg-red-700" onclick="return confirm('Reject this request?')">
                    Reject Request
                </button>
            </form>
        </div>
        @elseif($customOrder->status == 'accepted' && !$customOrder->order_id)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-espresso mb-4">Convert to Order</h3>
            <p class="text-sm text-gray-600 mb-4">
                This custom order has been accepted. Convert it to a regular order to proceed with production and payment.
            </p>
            <form action="{{ route('admin.custom-orders.convert-to-order', $customOrder) }}" method="POST">
                @csrf
                <button type="submit" class="w-full btn-primary" onclick="return confirm('Convert to order?')">
                    Convert to Order
                </button>
            </form>
        </div>
        @elseif($customOrder->order_id)
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-sm text-green-800 mb-2">Converted to Order</p>
            <a href="{{ route('admin.orders.show', $customOrder->order_id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                View Order →
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
