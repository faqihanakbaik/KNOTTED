@extends('layouts.app')

@section('title', 'Custom Order Detail')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('customer.custom-orders.index') }}" class="inline-flex items-center text-mocha hover:text-espresso mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Back to My Requests
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-serif text-espresso mb-2">{{ $customOrder->product_name }}</h1>
                <p class="text-mocha">Submitted on {{ $customOrder->created_at->format('M d, Y') }}</p>
            </div>
            <span class="px-4 py-2 rounded-full text-sm font-medium
                @if($customOrder->status === 'pending') bg-gray-100 text-gray-800
                @elseif($customOrder->status === 'quoted') bg-blue-100 text-blue-800
                @elseif($customOrder->status === 'negotiating') bg-yellow-100 text-yellow-800
                @elseif($customOrder->status === 'accepted') bg-green-100 text-green-800
                @elseif($customOrder->status === 'rejected') bg-red-100 text-red-800
                @elseif($customOrder->status === 'cancelled') bg-gray-100 text-gray-600
                @elseif($customOrder->status === 'converted') bg-purple-100 text-purple-800
                @endif">
                {{ ucfirst($customOrder->status) }}
            </span>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Request Details -->
            <div class="bg-white rounded-lg border border-oat p-6">
                <h2 class="text-xl font-serif text-espresso mb-4">Request Details</h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-mocha mb-1">Request Type</h3>
                        <p class="text-espresso">
                            @if($customOrder->request_type === 'modify_existing')
                                <i data-lucide="edit-3" class="w-4 h-4 inline mr-1"></i>
                                Modify Existing Product
                            @else
                                <i data-lucide="sparkles" class="w-4 h-4 inline mr-1"></i>
                                New Custom Design
                            @endif
                        </p>
                    </div>

                    @if($customOrder->category)
                    <div>
                        <h3 class="text-sm font-medium text-mocha mb-1">Category</h3>
                        <p class="text-espresso">{{ $customOrder->category->name }}</p>
                    </div>
                    @endif

                    <div>
                        <h3 class="text-sm font-medium text-mocha mb-1">Description</h3>
                        <p class="text-espresso whitespace-pre-line">{{ $customOrder->description }}</p>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        @if($customOrder->color)
                        <div>
                            <h3 class="text-sm font-medium text-mocha mb-1">Color</h3>
                            <p class="text-espresso">{{ $customOrder->color }}</p>
                        </div>
                        @endif

                        @if($customOrder->size)
                        <div>
                            <h3 class="text-sm font-medium text-mocha mb-1">Size</h3>
                            <p class="text-espresso">{{ $customOrder->size }}</p>
                        </div>
                        @endif

                        <div>
                            <h3 class="text-sm font-medium text-mocha mb-1">Quantity</h3>
                            <p class="text-espresso">{{ $customOrder->quantity }}</p>
                        </div>
                    </div>

                    @if($customOrder->notes)
                    <div>
                        <h3 class="text-sm font-medium text-mocha mb-1">Additional Notes</h3>
                        <p class="text-espresso whitespace-pre-line">{{ $customOrder->notes }}</p>
                    </div>
                    @endif

                    @if($customOrder->estimated_days)
                    <div>
                        <h3 class="text-sm font-medium text-mocha mb-1">Estimated Production Time</h3>
                        <p class="text-espresso">{{ $customOrder->estimated_days }} days</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Reference Images -->
            @if($customOrder->images->count() > 0)
            <div class="bg-white rounded-lg border border-oat p-6">
                <h2 class="text-xl font-serif text-espresso mb-4">Reference Images</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($customOrder->images as $image)
                    <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank" class="group">
                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                             alt="Reference" 
                             class="w-full h-40 object-cover rounded-lg border border-oat group-hover:border-mocha transition">
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Negotiation History -->
            @if($customOrder->offers->count() > 0)
            <div class="bg-white rounded-lg border border-oat p-6">
                <h2 class="text-xl font-serif text-espresso mb-4 flex items-center">
                    <i data-lucide="message-square" class="w-5 h-5 mr-2"></i>
                    Negotiation History
                </h2>
                
                <div class="space-y-4">
                    @foreach($customOrder->offers as $offer)
                    <div class="relative pl-8 pb-6 last:pb-0">
                        <!-- Timeline line -->
                        @if(!$loop->last)
                        <div class="absolute left-3 top-6 bottom-0 w-0.5 bg-oat"></div>
                        @endif
                        
                        <!-- Timeline dot -->
                        <div class="absolute left-0 top-1 w-6 h-6 rounded-full border-2 flex items-center justify-center
                            @if($offer->status === 'accepted') bg-green-100 border-green-500
                            @elseif($offer->status === 'rejected') bg-red-100 border-red-500
                            @elseif($offer->status === 'pending') bg-blue-100 border-blue-500
                            @else bg-gray-100 border-gray-300
                            @endif">
                            @if($offer->status === 'accepted')
                                <i data-lucide="check" class="w-3 h-3 text-green-600"></i>
                            @elseif($offer->status === 'rejected')
                                <i data-lucide="x" class="w-3 h-3 text-red-600"></i>
                            @endif
                        </div>

                        <!-- Offer content -->
                        <div class="bg-cream rounded-lg p-4 border border-oat">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-espresso">
                                        @if($offer->isFromAdmin())
                                            Admin Offer
                                        @else
                                            Your Offer
                                        @endif
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                        @if($offer->status === 'accepted') bg-green-100 text-green-800
                                        @elseif($offer->status === 'rejected') bg-red-100 text-red-800
                                        @elseif($offer->status === 'pending') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-600
                                        @endif">
                                        {{ ucfirst($offer->status) }}
                                    </span>
                                </div>
                                <span class="text-sm text-mocha">{{ $offer->created_at->diffForHumans() }}</span>
                            </div>

                            <p class="text-2xl font-serif text-espresso mb-2">
                                Rp {{ number_format($offer->offered_price, 0, ',', '.') }}
                            </p>

                            @if($offer->notes)
                            <p class="text-sm text-mocha">{{ $offer->notes }}</p>
                            @endif

                            <!-- Action buttons for pending admin offers -->
                            @if($offer->isFromAdmin() && $offer->isPending() && $customOrder->canNegotiate())
                            <div class="flex items-center gap-3 mt-4 pt-4 border-t border-oat">
                                <form action="{{ route('customer.custom-orders.accept-offer', [$customOrder, $offer]) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm font-medium">
                                        <i data-lucide="check" class="w-4 h-4 inline mr-1"></i>
                                        Accept Offer
                                    </button>
                                </form>

                                <form action="{{ route('customer.custom-orders.reject-offer', [$customOrder, $offer]) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                                        <i data-lucide="x" class="w-4 h-4 inline mr-1"></i>
                                        Reject
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Price Summary -->
            <div class="bg-white rounded-lg border border-oat p-6">
                <h2 class="text-lg font-serif text-espresso mb-4">Price Summary</h2>
                
                <div class="space-y-3">
                    @if($customOrder->customer_expected_price)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-mocha">Your Expected Price</span>
                        <span class="font-medium text-espresso">Rp {{ number_format($customOrder->customer_expected_price, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    @if($customOrder->admin_quoted_price)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-mocha">Admin Quoted Price</span>
                        <span class="font-medium text-espresso">Rp {{ number_format($customOrder->admin_quoted_price, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    @if($customOrder->final_agreed_price)
                    <div class="flex items-center justify-between pt-3 border-t border-oat">
                        <span class="font-medium text-espresso">Agreed Price</span>
                        <span class="text-xl font-serif text-green-700">Rp {{ number_format($customOrder->final_agreed_price, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    @if(!$customOrder->customer_expected_price && !$customOrder->admin_quoted_price && !$customOrder->final_agreed_price)
                    <div class="text-center py-4">
                        <i data-lucide="clock" class="w-12 h-12 text-oat mx-auto mb-2"></i>
                        <p class="text-sm text-mocha">Waiting for price quote from admin</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Make Counter Offer -->
            @if($customOrder->canNegotiate() && $customOrder->admin_quoted_price)
            <div class="bg-white rounded-lg border border-oat p-6">
                <h2 class="text-lg font-serif text-espresso mb-4">Make Counter Offer</h2>
                
                <form action="{{ route('customer.custom-orders.make-offer', $customOrder) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="offered_price" class="block text-sm font-medium text-espresso mb-2">
                            Your Price Offer <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-mocha">Rp</span>
                            <input type="number" name="offered_price" id="offered_price" required min="0" step="1000"
                                class="w-full pl-12 pr-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-espresso mb-2">
                            Notes (Optional)
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                            placeholder="Explain your counter offer..."
                            class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-espresso text-cream px-4 py-2 rounded-lg hover:bg-mocha transition font-medium">
                        Send Counter Offer
                    </button>
                </form>
            </div>
            @endif

            <!-- Status Actions -->
            @if($customOrder->status === 'accepted' && !$customOrder->order_id)
            <div class="bg-green-50 rounded-lg border border-green-200 p-6">
                <div class="flex items-start space-x-3 mb-4">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600 flex-shrink-0"></i>
                    <div>
                        <h3 class="font-medium text-green-900 mb-1">Price Agreed!</h3>
                        <p class="text-sm text-green-700">You can now proceed to checkout when admin creates the order.</p>
                    </div>
                </div>
            </div>
            @endif

            @if($customOrder->status === 'converted' && $customOrder->order_id)
            <div class="bg-purple-50 rounded-lg border border-purple-200 p-6">
                <div class="flex items-start space-x-3 mb-4">
                    <i data-lucide="package" class="w-6 h-6 text-purple-600 flex-shrink-0"></i>
                    <div>
                        <h3 class="font-medium text-purple-900 mb-1">Order Created!</h3>
                        <p class="text-sm text-purple-700 mb-3">Your custom order has been converted to a regular order.</p>
                        <a href="{{ route('customer.orders.show', $customOrder->order_id) }}" class="inline-flex items-center text-sm font-medium text-purple-700 hover:text-purple-900">
                            View Order
                            <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if($customOrder->status === 'pending')
            <div class="bg-white rounded-lg border border-oat p-6">
                <form action="{{ route('customer.custom-orders.cancel', $customOrder) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this custom order request?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full px-4 py-2 border border-red-500 text-red-600 rounded-lg hover:bg-red-50 transition font-medium">
                        Cancel Request
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
