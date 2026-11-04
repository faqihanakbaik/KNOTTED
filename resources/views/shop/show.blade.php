@extends('layouts.app')

@section('title', $product->name . ' - KNOTTED')

@section('content')
<div class="bg-cream py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8 text-sm">
            <a href="{{ route('home') }}" class="text-mocha hover:text-espresso">Home</a>
            <span class="mx-2 text-sage">/</span>
            <a href="{{ route('shop.index') }}" class="text-mocha hover:text-espresso">Shop</a>
            <span class="mx-2 text-sage">/</span>
            <a href="{{ route('shop.index', ['category' => $product->category_id]) }}" class="text-mocha hover:text-espresso">{{ $product->category->name }}</a>
            <span class="mx-2 text-sage">/</span>
            <span class="text-espresso">{{ $product->name }}</span>
        </nav>

        <!-- Product Detail -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
            <!-- Product Image -->
            <div class="bg-white rounded-lg overflow-hidden shadow-sm border border-oat">
                @if($product->image)
                    <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-auto" onerror="this.src='https://via.placeholder.com/800x800/F5F0E8/342C28?text={{ urlencode($product->name) }}'">
                @else
                    <img src="https://via.placeholder.com/800x800/F5F0E8/342C28?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" class="w-full h-auto">
                @endif
            </div>

            <!-- Product Info -->
            <div class="bg-white rounded-lg p-8 shadow-sm border border-oat">
                <div class="mb-4">
                    <span class="inline-block bg-sage/20 text-sage px-4 py-1 rounded-full text-sm font-semibold">
                        {{ $product->category->name }}
                    </span>
                    @if($product->is_featured)
                        <span class="inline-block bg-mocha/20 text-mocha px-4 py-1 rounded-full text-sm font-semibold ml-2">
                            Featured
                        </span>
                    @endif
                </div>

                <h1 class="font-serif text-3xl md:text-4xl font-bold text-espresso mb-4">
                    {{ $product->name }}
                </h1>

                <div class="flex items-baseline mb-6">
                    <span class="text-4xl font-bold text-mocha">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                </div>

                <div class="mb-6 pb-6 border-b border-oat">
                    <div class="flex items-center space-x-4 text-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-sage mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span class="text-espresso">
                                @if($product->isInStock())
                                    <span class="font-semibold text-green-600">In Stock</span> ({{ $product->stock }} available)
                                @else
                                    <span class="font-semibold text-red-600">Out of Stock</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="prose prose-sm max-w-none mb-8">
                    <h3 class="font-serif text-xl font-bold text-espresso mb-3">Description</h3>
                    <p class="text-espresso leading-relaxed">{{ $product->description }}</p>
                </div>

                @auth
                    @if(!auth()->user()->isAdmin())
                        @if($product->isInStock())
                            <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div>
                                    <label class="form-label">Quantity</label>
                                    <div class="flex items-center space-x-4">
                                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                               class="form-input w-24 text-center">
                                        <span class="text-sm text-sage">Max: {{ $product->stock }}</span>
                                    </div>
                                </div>

                                <button type="submit" class="w-full btn-primary text-lg py-4">
                                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    Add to Cart
                                </button>
                            </form>
                        @else
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                                <p class="text-red-800 font-semibold">This product is currently out of stock</p>
                            </div>
                        @endif
                    @endif
                @else
                    <div class="bg-sage/10 border border-sage rounded-lg p-6 text-center">
                        <p class="text-espresso mb-4">Please login to purchase this product</p>
                        <a href="{{ route('login') }}" class="btn-primary inline-block">Login Now</a>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mt-16">
                <h2 class="font-serif text-3xl font-bold text-espresso mb-8">You May Also Like</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                        <div class="product-card">
                            <a href="{{ route('product.show', $related->slug) }}">
                                @if($related->image)
                                    <img src="{{ asset('images/products/' . $related->image) }}" alt="{{ $related->name }}" onerror="this.src='https://via.placeholder.com/300x300/F5F0E8/342C28?text={{ urlencode($related->name) }}'">
                                @else
                                    <img src="https://via.placeholder.com/300x300/F5F0E8/342C28?text={{ urlencode($related->name) }}" alt="{{ $related->name }}">
                                @endif
                            </a>
                            <div class="p-4">
                                <h3 class="font-serif text-lg font-bold text-espresso mb-2">{{ $related->name }}</h3>
                                <p class="text-mocha font-semibold">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
