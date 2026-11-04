@extends('layouts.app')

@section('title', 'KNOTTED - Made by hand, tied with love')

@section('content')
<!-- Hero Section with Image -->
<section class="relative bg-gradient-to-br from-[#FFE5EC] via-white to-[#FFF0F5] py-16 md:py-20 flex items-center overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-10 right-10 w-32 h-32 bg-[#FFB6D9]/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 left-10 w-40 h-40 bg-[#FFC9E0]/10 rounded-full blur-3xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <!-- Left: Text Content -->
            <div class="space-y-6 lg:space-y-7" data-aos="fade-right">
                <div class="inline-block">
                    <p class="text-xs uppercase tracking-widest text-[#D84797] font-bold border-l-4 border-[#FFB6D9] pl-3 bg-[#FFE5EC]/50 py-2 pr-4 rounded-r-full">✨ HANDMADE CROCHET SHOP</p>
                </div>
                
                <h1 class="font-serif text-5xl lg:text-6xl xl:text-7xl font-bold text-[#D84797] leading-tight">
                    Made by hand,<br>
                    <span class="inline-flex items-center gap-3 bg-gradient-to-r from-[#FFB6D9] to-[#FFC9E0] bg-clip-text text-transparent">
                        tied with love.
                        <svg class="w-10 h-10 lg:w-12 lg:h-12 text-[#FFB6D9]" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M25 45C20 35 5 30 5 20C5 10 15 5 25 15C35 5 45 10 45 20C45 30 30 35 25 45Z" stroke="currentColor" stroke-width="2" fill="currentColor" opacity="0.3"/>
                        </svg>
                    </span>
                </h1>
                
                <p class="text-lg text-[#D84797]/80 leading-relaxed max-w-xl">
                    Discover unique handmade crochet creations that bring warmth, beauty, and charm to your life ✨
                </p>
                
                <div class="flex flex-wrap gap-4 pt-3">
                    <a href="{{ route('shop.index') }}" class="bg-gradient-to-r from-[#FFB6D9] to-[#FFC9E0] hover:from-[#FFC9E0] hover:to-[#FFB6D9] text-white font-semibold px-8 py-4 rounded-full inline-flex items-center gap-2 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                        Shop Collection
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                    <a href="{{ route('about') }}" class="bg-white border-2 border-[#FFB6D9] text-[#D84797] hover:bg-[#FFE5EC] font-semibold px-8 py-4 rounded-full transition-all duration-300 hover:scale-105 shadow-md">
                        Our Story
                    </a>
                </div>
                
                <!-- Carousel Dots -->
                <div class="flex gap-3 pt-3">
                    <button class="w-10 h-2.5 rounded-full bg-gradient-to-r from-[#FFB6D9] to-[#FFC9E0] shadow-sm"></button>
                    <button class="w-2.5 h-2.5 rounded-full bg-[#FFB6D9]/30 hover:bg-[#FFB6D9]/60 transition"></button>
                    <button class="w-2.5 h-2.5 rounded-full bg-[#FFB6D9]/30 hover:bg-[#FFB6D9]/60 transition"></button>
                </div>
            </div>
            
            <!-- Right: Product Image -->
            <div class="relative hidden lg:block" data-aos="fade-left">
                <div class="relative bg-gradient-to-br from-white to-[#FFE5EC] backdrop-blur-sm rounded-3xl p-8 border-2 border-[#FFB6D9]/30 shadow-2xl hover:shadow-[#FFB6D9]/20 transition-all duration-500 hover:scale-105">
                    <img src="https://images.unsplash.com/photo-1601570377889-7ea32e36a3b8?w=600&h=600&fit=crop" 
                         alt="Handmade Crochet Products" 
                         class="rounded-2xl w-full object-cover shadow-lg"
                         onerror="this.src='https://images.unsplash.com/photo-1601570377889-7ea32e36a3b8?w=600&h=600&fit=crop'">
                    <div class="absolute -bottom-5 -right-5 bg-gradient-to-br from-[#FFB6D9] to-[#FFC9E0] text-white px-6 py-3 rounded-2xl shadow-xl border-4 border-white">
                        <p class="text-xs font-medium mb-0.5">crafted by</p>
                        <p class="font-serif font-bold text-xl">KNOTTED</p>
                    </div>
                </div>
                <!-- Decorative elements -->
                <div class="absolute -top-6 -right-6 w-24 h-24">
                    <svg viewBox="0 0 100 100" fill="none" class="text-[#FFB6D9] animate-pulse">
                        <circle cx="50" cy="50" r="45" fill="currentColor" opacity="0.2"/>
                        <path d="M20,50 Q30,30 50,20 T80,50 T50,80 T20,50" fill="none" stroke="currentColor" stroke-width="3" opacity="0.4"/>
                    </svg>
                </div>
                <div class="absolute -bottom-8 -left-8 w-20 h-20">
                    <svg viewBox="0 0 100 100" fill="currentColor" class="text-[#FFC9E0] opacity-30">
                        <circle cx="50" cy="50" r="40"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Badges -->
<section class="py-8 bg-white border-y border-oat/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <!-- Premium Quality -->
            <div class="flex flex-col md:flex-row items-center gap-2 text-center md:text-left">
                <div class="flex-shrink-0">
                    <i data-lucide="gem" class="w-8 h-8 text-mocha"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-espresso text-xs">Premium Quality</h3>
                    <p class="text-[10px] text-mocha mt-0.5">Handmade</p>
                </div>
            </div>
            
            <!-- Fast & Safe Delivery -->
            <div class="flex flex-col md:flex-row items-center gap-2 text-center md:text-left">
                <div class="flex-shrink-0">
                    <i data-lucide="truck" class="w-8 h-8 text-mocha"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-espresso text-xs">Fast & Safe</h3>
                    <p class="text-[10px] text-mocha mt-0.5">Delivery</p>
                </div>
            </div>
            
            <!-- Secure Payment -->
            <div class="flex flex-col md:flex-row items-center gap-2 text-center md:text-left">
                <div class="flex-shrink-0">
                    <i data-lucide="shield-check" class="w-8 h-8 text-mocha"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-espresso text-xs">Secure Payment</h3>
                    <p class="text-[10px] text-mocha mt-0.5">Protected</p>
                </div>
            </div>
            
            <!-- Friendly Customer Service -->
            <div class="flex flex-col md:flex-row items-center gap-2 text-center md:text-left">
                <div class="flex-shrink-0">
                    <i data-lucide="headphones" class="w-8 h-8 text-mocha"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-espresso text-xs">Friendly Customer</h3>
                    <p class="text-[10px] text-mocha mt-0.5">Service</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-16 bg-gradient-to-br from-[#FFE5EC] via-white to-[#FFF0F5]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="flex items-end justify-between mb-10" data-aos="fade-up">
            <div>
                <p class="text-xs uppercase tracking-wider text-[#D84797] font-semibold mb-2 border-l-4 border-[#FFB6D9] pl-3">HANDPICKED FOR YOU</p>
                <h2 class="font-serif text-4xl md:text-5xl font-bold text-[#D84797] mb-2">Best Sellers ✨</h2>
                <p class="text-[#D84797]/70 text-base mt-1">Crafted with love, cherished by many</p>
            </div>
            <a href="{{ route('shop.index') }}" class="hidden md:inline-flex items-center gap-2 text-white bg-gradient-to-r from-[#FFB6D9] to-[#FFC9E0] hover:from-[#FFC9E0] hover:to-[#FFB6D9] px-6 py-3 rounded-full font-semibold text-sm shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
                View All Products
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        @php
            // Merge featured products and best sellers, remove duplicates, take 8
            $allProducts = collect($featuredProducts ?? [])->merge(collect($bestSellers ?? []))->unique('id')->take(8);
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($allProducts as $product)
                <div class="group product-card bg-white rounded-3xl overflow-hidden border-2 border-[#FFE5EC] hover:border-[#FFB6D9] hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <!-- Image Container -->
                    <div class="relative overflow-hidden bg-gradient-to-br from-[#FFF0F5] to-[#FFE5EC]">
                        <a href="{{ route('product.show', $product->slug) }}">
                            @if($product->image)
                                <img src="{{ asset('images/products/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-72 object-cover group-hover:scale-110 transition-transform duration-700"
                                     onerror="this.src='https://images.unsplash.com/photo-1601570377889-7ea32e36a3b8?w=400&h=400&fit=crop'">
                            @else
                                <img src="https://images.unsplash.com/photo-1601570377889-7ea32e36a3b8?w=400&h=400&fit=crop" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-72 object-cover group-hover:scale-110 transition-transform duration-700">
                            @endif
                        </a>
                        
                        <!-- Wishlist Button -->
                        <button class="absolute top-4 right-4 w-11 h-11 bg-white/95 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-gradient-to-br hover:from-[#FFB6D9] hover:to-[#FFC9E0] transition-all shadow-lg group-hover:scale-110 hover:shadow-xl">
                            <i data-lucide="heart" class="w-5 h-5 text-[#D84797] hover:text-white transition-colors"></i>
                        </button>
                        
                        <!-- NEW badge for first 2 products -->
                        @if($loop->index < 2)
                            <div class="absolute top-4 left-4 bg-gradient-to-r from-[#FFB6D9] to-[#FFC9E0] text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg">
                                ✨ NEW
                            </div>
                        @endif
                    </div>
                    
                    <!-- Product Info -->
                    <div class="p-6">
                        <h3 class="font-serif text-lg font-bold text-[#D84797] mb-3 group-hover:text-[#FFB6D9] transition line-clamp-2 min-h-[3.5rem]">
                            <a href="{{ route('product.show', $product->slug) }}">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[#D84797] text-2xl font-bold">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        </div>
                        
                        <!-- Rating & Stock -->
                        <div class="flex items-center justify-between text-xs mb-5">
                            <div class="flex items-center gap-1">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < 4)
                                        <i data-lucide="star" class="w-4 h-4 fill-[#FFB6D9] text-[#FFB6D9]"></i>
                                    @else
                                        <i data-lucide="star" class="w-4 h-4 text-gray-300"></i>
                                    @endif
                                @endfor
                                <span class="text-[#D84797]/70 ml-1 font-medium">({{ rand(50, 999) }})</span>
                            </div>
                            @if($product->isInStock())
                                <span class="bg-gradient-to-r from-green-100 to-green-50 text-green-700 px-3 py-1 rounded-full font-semibold text-xs">In Stock</span>
                            @else
                                <span class="bg-gradient-to-r from-red-100 to-red-50 text-red-700 px-3 py-1 rounded-full font-semibold text-xs">Out of Stock</span>
                            @endif
                        </div>
                        
                        <!-- Add to Cart Button -->
                        <button class="w-full bg-gradient-to-r from-[#FFB6D9] to-[#FFC9E0] hover:from-[#FFC9E0] hover:to-[#FFB6D9] text-white font-semibold py-3 rounded-full transition-all duration-300 hover:shadow-lg hover:scale-105 flex items-center justify-center gap-2">
                            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                            Add to Cart
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-12 text-mocha">
                    <p>No featured products available at the moment.</p>
                </div>
            @endforelse
        </div>

        <!-- Mobile View All Button -->
        <div class="text-center mt-12 md:hidden" data-aos="fade-up">
            <a href="{{ route('shop.index') }}" class="bg-gradient-to-r from-[#FFB6D9] to-[#FFC9E0] hover:from-[#FFC9E0] hover:to-[#FFB6D9] text-white font-semibold px-10 py-4 rounded-full inline-flex items-center gap-2 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                View All Products
                <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
        </div>
    </div>
</section>

<!-- Shop by Category -->
<section class="relative py-20 bg-gradient-to-br from-[#E8DDD0] to-[#F5EFE7] overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left: Text & Button -->
            <div>
                <h2 class="font-serif text-4xl md:text-5xl font-bold text-espresso mb-4">
                    Shop by Category
                </h2>
                <p class="text-lg text-mocha mb-8">
                    Find your favorite handmade items ♡
                </p>
                <a href="{{ route('shop.index') }}" class="btn-primary px-8 py-4 inline-flex items-center gap-2">
                    Explore Categories
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            
            <!-- Right: Polaroid Photos -->
            <div class="relative h-[400px] hidden lg:block">
                <!-- Decorative SVG Doodles -->
                <svg class="absolute top-10 left-10 w-16 h-16 text-mocha/20" viewBox="0 0 100 100" fill="none">
                    <path d="M10,50 Q30,10 50,50 T90,50" stroke="currentColor" stroke-width="2"/>
                    <circle cx="20" cy="30" r="3" fill="currentColor"/>
                </svg>
                
                <svg class="absolute bottom-10 right-10 w-20 h-20 text-mocha/20" viewBox="0 0 100 100" fill="none">
                    <circle cx="50" cy="50" r="40" stroke="currentColor" stroke-width="2"/>
                    <path d="M30,50 L70,50" stroke="currentColor" stroke-width="2"/>
                </svg>
                
                <!-- Polaroid 1 -->
                <div class="absolute top-4 right-20 w-40 bg-white p-3 shadow-xl transform rotate-12 hover:rotate-6 transition-transform duration-300 border border-gray-100">
                    <img src="https://images.unsplash.com/photo-1601570377889-7ea32e36a3b8?w=200&h=200&fit=crop" 
                         alt="Crochet Item" 
                         class="w-full h-32 object-cover">
                    <p class="text-center text-xs mt-2 font-handwriting text-espresso">Amigurumi</p>
                </div>
                
                <!-- Polaroid 2 -->
                <div class="absolute top-32 left-10 w-40 bg-white p-3 shadow-xl transform -rotate-6 hover:rotate-0 transition-transform duration-300 border border-gray-100">
                    <img src="https://images.unsplash.com/photo-1601570377889-7ea32e36a3b8?w=200&h=200&fit=crop" 
                         alt="Crochet Item" 
                         class="w-full h-32 object-cover">
                    <p class="text-center text-xs mt-2 font-handwriting text-espresso">Flowers</p>
                </div>
                
                <!-- Polaroid 3 -->
                <div class="absolute bottom-24 right-8 w-40 bg-white p-3 shadow-xl transform rotate-6 hover:rotate-3 transition-transform duration-300 border border-gray-100">
                    <img src="https://images.unsplash.com/photo-1601570377889-7ea32e36a3b8?w=200&h=200&fit=crop" 
                         alt="Crochet Item" 
                         class="w-full h-32 object-cover">
                    <p class="text-center text-xs mt-2 font-handwriting text-espresso">Bags</p>
                </div>
                
                <!-- Polaroid 4 -->
                <div class="absolute bottom-4 left-32 w-40 bg-white p-3 shadow-xl transform -rotate-3 hover:rotate-0 transition-transform duration-300 border border-gray-100">
                    <img src="https://images.unsplash.com/photo-1601570377889-7ea32e36a3b8?w=200&h=200&fit=crop" 
                         alt="Crochet Item" 
                         class="w-full h-32 object-cover">
                    <p class="text-center text-xs mt-2 font-handwriting text-espresso">Accessories</p>
                </div>
            </div>
        </div>
        
        <!-- Mobile: Simple Category Grid -->
        <div class="grid grid-cols-2 gap-4 mt-12 lg:hidden">
            @foreach($categories as $category)
                <a href="{{ route('shop.index', ['category' => $category->id]) }}" 
                   class="bg-white rounded-xl p-6 text-center hover:shadow-lg transition border border-oat/50">
                    <span class="text-4xl block mb-2">
                        @if(str_contains(strtolower($category->name), 'amigurumi'))
                            🧸
                        @elseif(str_contains(strtolower($category->name), 'flower'))
                            🌷
                        @elseif(str_contains(strtolower($category->name), 'keychain'))
                            🔑
                        @elseif(str_contains(strtolower($category->name), 'bag'))
                            👜
                        @else
                            🎀
                        @endif
                    </span>
                    <h3 class="font-serif font-bold text-espresso text-sm">{{ $category->name }}</h3>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="section-title">Why Choose KNOTTED</h2>
            <p class="text-mocha text-lg">Made with love, crafted to last</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <!-- Feature 1 -->
            <div class="feature-item">
                <span class="feature-emoji" aria-hidden="true">🧶</span>
                <h3 class="font-serif text-2xl font-bold text-espresso mb-3">
                    Handmade Quality
                </h3>
                <p class="text-mocha leading-relaxed">
                    Every piece is crafted with care, using premium materials and traditional crochet techniques passed down through generations.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="feature-item">
                <span class="feature-emoji" aria-hidden="true">💝</span>
                <h3 class="font-serif text-2xl font-bold text-espresso mb-3">
                    Gift Ready
                </h3>
                <p class="text-mocha leading-relaxed">
                    Each order comes beautifully packaged with care, perfect for gifting to someone special or treating yourself.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="feature-item">
                <span class="feature-emoji" aria-hidden="true">✨</span>
                <h3 class="font-serif text-2xl font-bold text-espresso mb-3">
                    Unique Designs
                </h3>
                <p class="text-mocha leading-relaxed">
                    Original patterns and designs you won't find anywhere else, each piece tells its own story of creativity and passion.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Brand Story -->
<section class="py-20 bg-gradient-to-br from-sage/20 to-oat/20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="emoji-decor" aria-hidden="true">🏠</span>
        <h2 class="section-title">Our Story</h2>
        <p class="text-lg text-espresso leading-relaxed max-w-3xl mx-auto">
            At KNOTTED, every stitch tells a story. We believe in the beauty of handmade craftsmanship 
            and the warmth it brings to your life. Each piece is carefully crocheted with premium materials 
            and endless love, creating timeless treasures that you'll cherish forever.
        </p>
        <a href="{{ route('about') }}" class="btn-primary text-lg px-8 py-4 mt-8 inline-block">
            Learn More About Us
        </a>
    </div>
</section>

<!-- Testimonials -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="section-title">What Our Customers Say</h2>
            <p class="text-mocha text-lg">Love from our community</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-cream rounded-lg p-8 border border-oat">
                <div class="text-mocha text-4xl mb-4">"</div>
                <p class="text-espresso mb-6 leading-relaxed">
                    The quality is amazing! My bunny amigurumi is so adorable and well-made. 
                    Perfect gift for my daughter.
                </p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-sage rounded-full flex items-center justify-center text-white font-bold">
                        S
                    </div>
                    <div class="ml-3">
                        <p class="font-semibold text-espresso">Sarah M.</p>
                        <p class="text-sm text-sage">Jakarta</p>
                    </div>
                </div>
            </div>

            <div class="bg-cream rounded-lg p-8 border border-oat">
                <div class="text-mocha text-4xl mb-4">"</div>
                <p class="text-espresso mb-6 leading-relaxed">
                    Absolutely love my crochet bag! It's stylish, sturdy, and gets compliments everywhere I go.
                </p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-mocha rounded-full flex items-center justify-center text-white font-bold">
                        A
                    </div>
                    <div class="ml-3">
                        <p class="font-semibold text-espresso">Anita R.</p>
                        <p class="text-sm text-sage">Bandung</p>
                    </div>
                </div>
            </div>

            <div class="bg-cream rounded-lg p-8 border border-oat">
                <div class="text-mocha text-4xl mb-4">"</div>
                <p class="text-espresso mb-6 leading-relaxed">
                    Fast shipping and beautiful packaging. The flowers are so realistic and will last forever!
                </p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-espresso rounded-full flex items-center justify-center text-white font-bold">
                        D
                    </div>
                    <div class="ml-3">
                        <p class="font-semibold text-espresso">Devi L.</p>
                        <p class="text-sm text-sage">Surabaya</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
