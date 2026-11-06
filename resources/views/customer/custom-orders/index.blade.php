@extends('layouts.app')

@section('title', 'My Custom Requests')

@section('content')
<div class="min-h-screen bg-white py-12">
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4" data-aos="fade-down">
        <div>
            <h1 class="text-4xl font-serif font-bold text-[#947765] mb-3">My Custom Requests <iconify-icon icon="solar:star-bold" class="inline-block w-8 h-8"></iconify-icon></h1>
            <p class="text-[#947765]/80 text-lg">Track your custom order requests and negotiations</p>
        </div>
        <a href="{{ route('customer.custom-orders.create') }}" class="inline-flex items-center px-6 py-3 bg-[#DCCBBC] hover:bg-[#947765] text-[#342C28] hover:text-white rounded-full font-bold transition-all duration-300 hover:shadow-xl hover:scale-105 gap-2">
            <i data-lucide="plus" class="w-5 h-5"></i>
            New Request
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-2 border-green-300 text-green-800 px-6 py-4 rounded-2xl mb-8 shadow-lg" data-aos="fade-up">
        <div class="flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if($customOrders->count() > 0)
    <div class="space-y-6">
        @foreach($customOrders as $customOrder)
        <div class="bg-white rounded-3xl border-2 border-[#F5F0E8] overflow-hidden hover:border-[#DCCBBC] hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row items-start justify-between mb-6 gap-4">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <h3 class="text-xl font-serif font-bold text-[#947765]">{{ $customOrder->product_name }}</h3>
                            <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide
                                @if($customOrder->status === 'pending') bg-gray-200 text-gray-800
                                @elseif($customOrder->status === 'quoted') bg-blue-200 text-blue-800
                                @elseif($customOrder->status === 'negotiating') bg-yellow-200 text-yellow-800
                                @elseif($customOrder->status === 'accepted') bg-green-200 text-green-800
                                @elseif($customOrder->status === 'rejected') bg-red-200 text-red-800
                                @elseif($customOrder->status === 'cancelled') bg-gray-200 text-gray-600
                                @elseif($customOrder->status === 'converted') bg-purple-200 text-purple-800
                                @endif">
                                {{ ucfirst($customOrder->status) }}
                            </span>
                        </div>

                        <div class="flex flex-wrap items-center gap-5 text-sm text-[#947765]/70 mb-4">
                            <span class="flex items-center gap-2 font-medium">
                                <i data-lucide="calendar" class="w-4 h-4 text-[#DCCBBC]"></i>
                                {{ $customOrder->created_at->format('M d, Y') }}
                            </span>
                            @if($customOrder->category)
                            <span class="flex items-center gap-2 font-medium">
                                <i data-lucide="tag" class="w-4 h-4 text-[#DCCBBC]"></i>
                                {{ $customOrder->category->name }}
                            </span>
                            @endif
                            <span class="flex items-center gap-2 font-medium">
                                <i data-lucide="hash" class="w-4 h-4 text-[#DCCBBC]"></i>
                                Qty: {{ $customOrder->quantity }}
                            </span>
                        </div>

                        <p class="text-sm text-[#947765]/80 line-clamp-2 leading-relaxed">{{ $customOrder->description }}</p>
                    </div>

                    @if($customOrder->images->count() > 0)
                    <div class="ml-0 md:ml-6">
                        <img src="{{ asset('storage/' . $customOrder->images->first()->image_path) }}" 
                             alt="Reference" 
                             class="w-full md:w-28 h-28 object-cover rounded-2xl border-2 border-[#F5F0E8] shadow-md">
                    </div>
                    @endif
                </div>

                <!-- Price Information -->
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between py-4 border-t-2 border-[#F5F0E8] gap-4">
                    <div class="flex flex-wrap items-center gap-6 text-sm">
                        @if($customOrder->customer_expected_price)
                        <div>
                            <span class="text-[#947765]/70 block mb-1 text-xs">Your offer:</span>
                            <span class="font-bold text-[#947765] text-base">Rp {{ number_format($customOrder->customer_expected_price, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        @if($customOrder->admin_quoted_price)
                        <div>
                            <span class="text-[#947765]/70 block mb-1 text-xs">Admin quote:</span>
                            <span class="font-bold text-[#947765] text-base">Rp {{ number_format($customOrder->admin_quoted_price, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        @if($customOrder->final_agreed_price)
                        <div>
                            <span class="text-green-600 block mb-1 text-xs font-semibold">✓ Agreed:</span>
                            <span class="font-bold text-green-700 text-base">Rp {{ number_format($customOrder->final_agreed_price, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>

                    <a href="{{ route('customer.custom-orders.show', $customOrder) }}" class="inline-flex items-center px-6 py-3 border-2 border-[#F5F0E8] rounded-full text-[#947765] hover:border-[#DCCBBC] hover:bg-[#F5F0E8] transition-all duration-300 font-bold text-sm gap-2 hover:scale-105">
                        View Details
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-3xl border-2 border-[#F5F0E8] p-16 text-center shadow-lg" data-aos="zoom-in">
        <div class="w-24 h-24 bg-[#F5F0E8] rounded-full flex items-center justify-center mx-auto mb-6">
            <i data-lucide="wand-2" class="w-12 h-12 text-[#DCCBBC]"></i>
        </div>
        <h3 class="text-2xl font-serif font-bold text-[#947765] mb-3">No custom requests yet</h3>
        <p class="text-[#947765]/70 mb-8 text-lg max-w-md mx-auto">Have a special design in mind? Create your first custom order request</p>
        <a href="{{ route('customer.custom-orders.create') }}" class="inline-flex items-center px-8 py-4 bg-[#DCCBBC] hover:bg-[#947765] text-[#342C28] hover:text-white rounded-full font-bold transition-all duration-300 hover:shadow-xl hover:scale-105 gap-2">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Create Custom Request
        </a>
    </div>
    @endif
</div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
