@extends('layouts.app')

@section('title', 'My Custom Requests')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FFE5EC] via-white to-[#FFF0F5] py-12">
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4" data-aos="fade-down">
        <div>
            <h1 class="text-4xl font-serif font-bold text-[#D84797] mb-3">My Custom Requests ✨</h1>
            <p class="text-[#D84797]/80 text-lg">Track your custom order requests and negotiations</p>
        </div>
        <a href="{{ route('customer.custom-orders.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#FFB6D9] to-[#FFC9E0] hover:from-[#FFC9E0] hover:to-[#FFB6D9] text-white rounded-full font-bold transition-all duration-300 hover:shadow-xl hover:scale-105 gap-2">
            <i data-lucide="plus" class="w-5 h-5"></i>
            New Request
        </a>
    </div>

    @if(session('success'))
    <div class="bg-gradient-to-r from-green-50 to-green-100 border-2 border-green-300 text-green-800 px-6 py-4 rounded-2xl mb-8 shadow-lg" data-aos="fade-up">
        <div class="flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if($customOrders->count() > 0)
    <div class="space-y-6">
        @foreach($customOrders as $customOrder)
        <div class="bg-white rounded-3xl border-2 border-[#FFE5EC] overflow-hidden hover:border-[#FFB6D9] hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row items-start justify-between mb-6 gap-4">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <h3 class="text-xl font-serif font-bold text-[#D84797]">{{ $customOrder->product_name }}</h3>
                            <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide
                                @if($customOrder->status === 'pending') bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800
                                @elseif($customOrder->status === 'quoted') bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800
                                @elseif($customOrder->status === 'negotiating') bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800
                                @elseif($customOrder->status === 'accepted') bg-gradient-to-r from-green-100 to-green-200 text-green-800
                                @elseif($customOrder->status === 'rejected') bg-gradient-to-r from-red-100 to-red-200 text-red-800
                                @elseif($customOrder->status === 'cancelled') bg-gradient-to-r from-gray-100 to-gray-200 text-gray-600
                                @elseif($customOrder->status === 'converted') bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800
                                @endif">
                                {{ ucfirst($customOrder->status) }}
                            </span>
                        </div>

                        <div class="flex flex-wrap items-center gap-5 text-sm text-[#D84797]/70 mb-4">
                            <span class="flex items-center gap-2 font-medium">
                                <i data-lucide="calendar" class="w-4 h-4 text-[#FFB6D9]"></i>
                                {{ $customOrder->created_at->format('M d, Y') }}
                            </span>
                            @if($customOrder->category)
                            <span class="flex items-center gap-2 font-medium">
                                <i data-lucide="tag" class="w-4 h-4 text-[#FFB6D9]"></i>
                                {{ $customOrder->category->name }}
                            </span>
                            @endif
                            <span class="flex items-center gap-2 font-medium">
                                <i data-lucide="hash" class="w-4 h-4 text-[#FFB6D9]"></i>
                                Qty: {{ $customOrder->quantity }}
                            </span>
                        </div>

                        <p class="text-sm text-[#D84797]/80 line-clamp-2 leading-relaxed">{{ $customOrder->description }}</p>
                    </div>

                    @if($customOrder->images->count() > 0)
                    <div class="ml-0 md:ml-6">
                        <img src="{{ asset('storage/' . $customOrder->images->first()->image_path) }}" 
                             alt="Reference" 
                             class="w-full md:w-28 h-28 object-cover rounded-2xl border-2 border-[#FFE5EC] shadow-md">
                    </div>
                    @endif
                </div>

                <!-- Price Information -->
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between py-4 border-t-2 border-[#FFE5EC] gap-4">
                    <div class="flex flex-wrap items-center gap-6 text-sm">
                        @if($customOrder->customer_expected_price)
                        <div>
                            <span class="text-[#D84797]/70 block mb-1 text-xs">Your offer:</span>
                            <span class="font-bold text-[#D84797] text-base">Rp {{ number_format($customOrder->customer_expected_price, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        @if($customOrder->admin_quoted_price)
                        <div>
                            <span class="text-[#D84797]/70 block mb-1 text-xs">Admin quote:</span>
                            <span class="font-bold text-[#D84797] text-base">Rp {{ number_format($customOrder->admin_quoted_price, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        @if($customOrder->final_agreed_price)
                        <div>
                            <span class="text-green-600 block mb-1 text-xs font-semibold">✓ Agreed:</span>
                            <span class="font-bold text-green-700 text-base">Rp {{ number_format($customOrder->final_agreed_price, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>

                    <a href="{{ route('customer.custom-orders.show', $customOrder) }}" class="inline-flex items-center px-6 py-3 border-2 border-[#FFE5EC] rounded-full text-[#D84797] hover:border-[#FFB6D9] hover:bg-gradient-to-r hover:from-[#FFE5EC] hover:to-white transition-all duration-300 font-bold text-sm gap-2 hover:scale-105">
                        View Details
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-3xl border-2 border-[#FFE5EC] p-16 text-center shadow-lg" data-aos="zoom-in">
        <div class="w-24 h-24 bg-gradient-to-br from-[#FFE5EC] to-[#FFF0F5] rounded-full flex items-center justify-center mx-auto mb-6">
            <i data-lucide="wand-2" class="w-12 h-12 text-[#FFB6D9]"></i>
        </div>
        <h3 class="text-2xl font-serif font-bold text-[#D84797] mb-3">No custom requests yet</h3>
        <p class="text-[#D84797]/70 mb-8 text-lg max-w-md mx-auto">Have a special design in mind? Create your first custom order request</p>
        <a href="{{ route('customer.custom-orders.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-[#FFB6D9] to-[#FFC9E0] hover:from-[#FFC9E0] hover:to-[#FFB6D9] text-white rounded-full font-bold transition-all duration-300 hover:shadow-xl hover:scale-105 gap-2">
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
