@extends('layouts.app')

@section('title', 'Shop - KNOTTED')

@section('content')
<div class="bg-cream py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="font-serif text-4xl md:text-5xl font-bold text-espresso mb-4">Shop Collection</h1>
            <p class="text-mocha text-lg">Discover our handmade crochet creations</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-oat p-6 mb-8">
            <form method="GET" action="{{ route('shop.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="form-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search products..." class="form-input">
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="form-label">Category</label>
                    <select name="category" class="form-input">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label class="form-label">Sort By</label>
                    <select name="sort" class="form-input">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A-Z</option>
                    </select>
                </div>

                <!-- Submit -->
                <div class="flex items-end">
                    <button type="submit" class="w-full btn-primary">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($products as $product)
                    <div class="product-card">
                        <a href="{{ route('product.show', $product->slug) }}">
                            @if($product->image)
                                <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" onerror="this.src='https://via.placeholder.com/400x400/F5F0E8/342C28?text={{ urlencode($product->name) }}'">
                            @else
                                <img src="https://via.placeholder.com/400x400/F5F0E8/342C28?text={{ urlencode($product->name) }}" alt="{{ $product->name }}">
                            @endif
                            @if($product->is_featured)
                                <span class="absolute top-2 right-2 bg-sage text-white px-3 py-1 rounded-full text-xs font-semibold">
                                    Featured
                                </span>
                            @endif
                        </a>
                        <div class="p-5">
                            <p class="text-sm text-sage mb-2">{{ $product->category->name }}</p>
                            <h3 class="font-serif text-lg font-bold text-espresso mb-2 line-clamp-2">
                                <a href="{{ route('product.show', $product->slug) }}" class="hover:text-mocha transition">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="text-mocha text-xl font-semibold mb-3">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-sage mb-4">
                                Stock: {{ $product->stock }} {{ $product->stock > 1 ? 'items' : 'item' }}
                            </p>
                            @auth
                                @if(!auth()->user()->isAdmin())
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full btn-primary" @if(!$product->isInStock()) disabled @endif>
                                            @if($product->isInStock())
                                                Add to Cart
                                            @else
                                                Out of Stock
                                            @endif
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="block w-full text-center btn-primary">
                                    Login to Purchase
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <svg class="mx-auto h-16 w-16 text-mocha mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-2xl font-serif font-bold text-espresso mb-2">No products found</h3>
                <p class="text-mocha mb-6">Try adjusting your filters or search terms</p>
                <a href="{{ route('shop.index') }}" class="btn-secondary">Clear All Filters</a>
            </div>
        @endif
    </div>
</div>
@endsection
