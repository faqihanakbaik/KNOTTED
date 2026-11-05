@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<style>
    /* Soft Pink Palette */
    .bg-pink-soft { background: linear-gradient(135deg, #DCCBBC 0%, #F5F0E8 100%); }
    .bg-pink-light { background-color: #F5F0E8; }
    .bg-rose-gold { background: linear-gradient(135deg, #E8B4B8 0%, #F4C2C2 100%); }
    .text-pink-dark { color: #947765; }
    .border-pink-soft { border-color: #DCCBBC; }
    .hover-lift { transition: all 0.3s ease; }
    .hover-lift:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(255, 182, 217, 0.3); }
    
    /* Glassmorphism */
    .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3); }
    
    /* Animated gradient */
    @keyframes gradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
    .gradient-animate { background-size: 200% 200%; animation: gradient 15s ease infinite; }
</style>

<!-- Dreamy Hero Section -->
<div class="relative overflow-hidden" style="background: linear-gradient(135deg, #F5F0E8 0%, #FFF8FA 50%, #FFD4E5 100%);">
    <!-- Floating elements -->
    <div class="absolute top-20 right-20 w-72 h-72 rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, #DCCBBC 0%, transparent 70%);"></div>
    <div class="absolute -bottom-20 -left-20 w-96 h-96 rounded-full opacity-20 blur-3xl" style="background: radial-gradient(circle, #F5F0E8 0%, transparent 70%);"></div>
    <div class="absolute top-1/2 left-1/2 w-64 h-64 rounded-full opacity-10 blur-2xl" style="background: radial-gradient(circle, #E8B4B8 0%, transparent 70%);"></div>
    
    <div class="max-w-7xl mx-auto px-6 py-20 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div data-aos="fade-right">
                <!-- Welcome Badge -->
                <div class="inline-flex items-center gap-3 glass px-5 py-3 rounded-full mb-6 shadow-lg">
                    <div class="w-2.5 h-2.5 rounded-full animate-pulse" style="background: linear-gradient(135deg, #DCCBBC, #F5F0E8);"></div>
                    <span class="text-sm font-medium" style="color: #947765;">Welcome Back</span>
                </div>
                
                <h1 class="text-6xl font-serif mb-4 leading-tight" style="color: #2D3748;">
                    Hello,<br/>
                    <span class="text-pink-dark" style="background: linear-gradient(135deg, #947765, #DCCBBC); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ explode(' ', Auth::user()->name)[0] }}</span> 🌸
                </h1>
                <p class="text-lg mb-8" style="color: #6B7280;">Your creative space for handmade treasures. Let's make something beautiful today.</p>
                
                <div class="flex gap-4">
                    <a href="{{ route('shop.index') }}" class="px-8 py-4 rounded-2xl font-semibold shadow-xl hover-lift text-white" style="background: linear-gradient(135deg, #947765, #DCCBBC);">
                        Explore Shop
                    </a>
                    <a href="{{ route('customer.custom-orders.create') }}" class="px-8 py-4 glass rounded-2xl font-semibold shadow-lg hover-lift" style="color: #947765;">
                        Custom Order
                    </a>
                </div>
            </div>

            <!-- Right: Floating Cards -->
            <div class="hidden lg:block relative" data-aos="fade-left">
                <!-- Main stat card -->
                <div class="absolute top-0 right-0 bg-white rounded-3xl p-8 shadow-2xl transform rotate-6 hover:rotate-3 transition-transform w-72 border-2" style="border-color: #F5F0E8;">
                    <div class="text-7xl font-serif mb-2" style="color: #947765;">{{ Auth::user()->orders()->count() }}</div>
                    <div class="text-gray-600 font-medium text-lg">Total Orders</div>
                    <div class="mt-4 flex items-center gap-2">
                        <div class="px-3 py-1 rounded-full text-sm font-semibold" style="background: #F5F0E8; color: #947765;">+12% 📈</div>
                    </div>
                </div>

                <!-- Custom requests badge -->
                <div class="absolute bottom-24 left-0 rounded-3xl p-6 shadow-2xl transform -rotate-6 hover:rotate-0 transition-transform" style="background: linear-gradient(135deg, #DCCBBC, #F5F0E8);">
                    <div class="text-4xl font-bold text-white mb-1">{{ Auth::user()->customOrders()->count() }}</div>
                    <div class="text-white/90 font-medium">Custom Requests</div>
                </div>

                <!-- Member since -->
                <div class="absolute bottom-0 right-24 glass px-6 py-4 rounded-full shadow-xl">
                    <span class="font-medium" style="color: #6B7280;">Since {{ Auth::user()->created_at->format('Y') }} <iconify-icon icon="solar:star-bold" class="inline-block w-4 h-4"></iconify-icon></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-6 py-16">
    
    <!-- Bento Grid Stats -->
    <div class="grid grid-cols-12 gap-6 mb-16">
        <!-- Large featured stat -->
        <div class="col-span-12 md:col-span-6 lg:col-span-4 bg-white rounded-3xl p-8 shadow-xl hover-lift border-2" style="border-color: #F5F0E8;" data-aos="fade-up" data-aos-delay="100">
            <div class="relative">
                <div class="absolute -right-4 -top-4 text-8xl opacity-10">🛍️</div>
                <div class="relative">
                    <div class="text-sm font-bold mb-3 uppercase tracking-wider" style="color: #947765;">Shopping</div>
                    <div class="text-6xl font-serif mb-2" style="color: #2D3748;">{{ Auth::user()->cart ? Auth::user()->cart->items->sum('quantity') : 0 }}</div>
                    <div class="text-gray-600 mb-4">Items in cart</div>
                    <a href="{{ route('cart.index') }}" class="inline-flex items-center font-semibold hover:gap-3 transition-all" style="color: #947765;">
                        View Cart
                        <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Two stacked mini cards -->
        <div class="col-span-6 md:col-span-3 lg:col-span-2 flex flex-col gap-4" data-aos="fade-up" data-aos-delay="200">
            <div class="rounded-2xl p-6 flex-1 hover-lift shadow-lg text-white" style="background: linear-gradient(135deg, #947765, #DCCBBC);">
                <div class="text-4xl font-serif mb-1">{{ Auth::user()->orders()->count() }}</div>
                <div class="text-white/90 text-sm font-medium">Orders</div>
            </div>
            <div class="bg-white rounded-2xl p-6 flex-1 hover-lift shadow-lg border-2" style="border-color: #F5F0E8;">
                <div class="text-4xl font-serif mb-1" style="color: #947765;">{{ Auth::user()->addresses()->count() }}</div>
                <div class="text-gray-600 text-sm font-medium">Addresses</div>
            </div>
        </div>

        <!-- Wide stat -->
        <div class="col-span-6 md:col-span-3 lg:col-span-3 bg-white rounded-2xl p-6 hover-lift shadow-lg border-2" style="border-color: #F5F0E8;" data-aos="fade-up" data-aos-delay="300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-4 rounded-2xl" style="background: linear-gradient(135deg, #F5F0E8, #FFF8FA);">
                    <i data-lucide="wand-2" class="w-8 h-8" style="color: #947765;"></i>
                </div>
                <div class="text-right">
                    <div class="text-4xl font-serif" style="color: #947765;">{{ Auth::user()->customOrders()->count() }}</div>
                </div>
            </div>
            <div class="text-gray-600 font-medium">Custom Requests</div>
        </div>

        <!-- CTA card -->
        <div class="col-span-12 md:col-span-6 lg:col-span-3 rounded-2xl p-8 shadow-lg hover-lift cursor-pointer border-2 border-dashed" style="background: linear-gradient(135deg, #FFF8FA, #F5F0E8); border-color: #DCCBBC;" onclick="window.location='{{ route('customer.custom-orders.create') }}'" data-aos="fade-up" data-aos-delay="400">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg" style="background: linear-gradient(135deg, #947765, #DCCBBC);">
                    <i data-lucide="plus" class="w-8 h-8 text-white"></i>
                </div>
                <div class="font-bold text-lg mb-1" style="color: #947765;">New Request</div>
                <div class="text-sm text-gray-600">Start custom order</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="mb-16">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-5xl font-serif mb-3" style="color: #2D3748;">Quick Actions</h2>
            <div class="w-32 h-1.5 rounded-full mx-auto" style="background: linear-gradient(90deg, #DCCBBC, #F5F0E8);"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Card 1: Gradient overlay -->
            <a href="{{ route('shop.index') }}" class="group relative h-80 rounded-3xl overflow-hidden shadow-2xl hover-lift" data-aos="zoom-in" data-aos-delay="100">
                <div class="absolute inset-0 gradient-animate" style="background: linear-gradient(135deg, #DCCBBC, #F5F0E8, #E8B4B8);"></div>
                <div class="absolute inset-0 flex flex-col justify-end p-8 text-white">
                    <div class="mb-6 transform group-hover:scale-110 transition-transform">
                        <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center">
                            <i data-lucide="shopping-bag" class="w-10 h-10"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-serif mb-2">Browse Shop</h3>
                    <p class="text-white/90">Discover handmade treasures</p>
                </div>
            </a>

            <!-- Card 2: Glass morphism -->
            <a href="{{ route('customer.orders.index') }}" class="group relative h-80 rounded-3xl overflow-hidden shadow-2xl hover-lift" data-aos="zoom-in" data-aos-delay="200">
                <div class="absolute inset-0" style="background: linear-gradient(135deg, #FFF8FA, #F5F0E8);"></div>
                <div class="absolute inset-0 flex flex-col justify-between p-8">
                    <div class="self-end">
                        <div class="w-20 h-20 rounded-3xl flex items-center justify-center shadow-xl" style="background: linear-gradient(135deg, #DCCBBC, #F5F0E8);">
                            <i data-lucide="package" class="w-10 h-10 text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xl font-serif mb-2" style="color: #2D3748;">My Orders</h3>
                        <p class="text-gray-600">Track your purchases</p>
                    </div>
                </div>
            </a>

            <!-- Card 3: Split design -->
            <a href="{{ route('customer.addresses.index') }}" class="group relative h-80 rounded-3xl overflow-hidden shadow-2xl hover-lift" data-aos="zoom-in" data-aos-delay="300">
                <div class="absolute inset-0 bg-white"></div>
                <div class="absolute bottom-0 left-0 right-0 h-1/2 origin-bottom group-hover:h-full transition-all duration-500" style="background: linear-gradient(135deg, #947765, #DCCBBC);"></div>
                <div class="relative h-full flex flex-col justify-between p-8">
                    <div class="w-20 h-20 glass rounded-3xl flex items-center justify-center group-hover:bg-white transition-colors">
                        <i data-lucide="map-pin" class="w-10 h-10 group-hover:text-pink-500" style="color: #947765;"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-serif mb-2 group-hover:text-white transition-colors" style="color: #2D3748;">Addresses</h3>
                        <p class="text-gray-600 group-hover:text-white/90 transition-colors">Manage shipping</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    @php
        $recentOrders = Auth::user()->orders()->latest()->take(4)->get();
    @endphp

    @if($recentOrders->count() > 0)
    <div class="glass rounded-3xl p-10 shadow-2xl" data-aos="fade-up">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-4xl font-serif mb-3" style="color: #2D3748;">Recent Activity</h2>
                <div class="w-24 h-1.5 rounded-full" style="background: linear-gradient(90deg, #DCCBBC, #F5F0E8);"></div>
            </div>
            <a href="{{ route('customer.orders.index') }}" class="px-8 py-4 rounded-full font-semibold shadow-xl hover-lift text-white" style="background: linear-gradient(135deg, #947765, #DCCBBC);">
                View All
                <i data-lucide="arrow-right" class="w-5 h-5 inline ml-2"></i>
            </a>
        </div>

        <div class="grid gap-5">
            @foreach($recentOrders as $order)
            <div class="bg-white rounded-2xl p-6 border-l-4 hover:shadow-xl transition-all group" style="border-color: #DCCBBC;" data-aos="fade-right" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="flex items-start justify-between gap-6">
                    <div class="flex items-start gap-5 flex-1">
                        <div class="p-5 rounded-2xl group-hover:scale-110 transition-transform shadow-lg" style="background: linear-gradient(135deg, #F5F0E8, #FFF8FA);">
                            <i data-lucide="package" class="w-7 h-7" style="color: #947765;"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-bold text-xl" style="color: #2D3748;">Order #{{ $order->order_number }}</h3>
                                <span class="px-4 py-1.5 rounded-full text-xs font-bold text-white
                                    @if($order->status === 'completed') bg-gradient-to-r from-green-400 to-green-500
                                    @elseif($order->status === 'processing') bg-gradient-to-r from-blue-400 to-blue-500
                                    @elseif($order->status === 'shipped') bg-gradient-to-r from-purple-400 to-purple-500
                                    @else bg-gradient-to-r from-gray-400 to-gray-500
                                    @endif">
                                    {{ strtoupper($order->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-1">{{ $order->created_at->diffForHumans() }}</p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->format('l, F j, Y') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-serif mb-2" style="color: #947765;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        <a href="{{ route('customer.orders.show', $order->id) }}" class="text-sm font-semibold inline-flex items-center gap-1 hover:gap-2 transition-all" style="color: #947765;">
                            Details
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 50
    });
    lucide.createIcons();
</script>
@endsection
