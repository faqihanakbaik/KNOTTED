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
            <!-- Product Images Gallery -->
            <div>
                <!-- Main Image -->
                <div class="bg-white rounded-lg overflow-hidden shadow-sm border border-oat mb-4">
                    @if($product->image)
                        <img id="main-product-image" src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-auto" onerror="this.src='https://via.placeholder.com/800x800/F5F0E8/342C28?text={{ urlencode($product->name) }}'">
                    @else
                        <img id="main-product-image" src="https://via.placeholder.com/800x800/F5F0E8/342C28?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" class="w-full h-auto">
                    @endif
                </div>

                <!-- Thumbnail Gallery -->
                @if($product->productImages && $product->productImages->count() > 0)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($product->productImages as $image)
                            <div class="bg-white rounded-lg overflow-hidden shadow-sm border border-oat cursor-pointer hover:border-sage transition"
                                 onclick="changeMainImage('{{ $image->image_url }}')">
                                <img src="{{ $image->image_url }}" alt="Product image" class="w-full h-24 object-cover">
                            </div>
                        @endforeach
                    </div>
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
                    <div class="space-y-3 text-sm">
                        <!-- Product Type -->
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-sage mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-espresso">
                                @if($product->isMadeToOrder())
                                    <span class="font-semibold">Made to Order</span> ({{ $product->lead_time_days }} days lead time)
                                @else
                                    <span class="font-semibold">Ready Stock</span>
                                @endif
                            </span>
                        </div>

                        <!-- Stock Status -->
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-sage mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span class="text-espresso">
                                @if($product->isInStock())
                                    <span class="font-semibold text-green-600">In Stock</span> 
                                    @if(!$product->isMadeToOrder())
                                        ({{ $product->getTotalStock() }} available)
                                    @endif
                                @else
                                    <span class="font-semibold text-red-600">Out of Stock</span>
                                @endif
                            </span>
                        </div>

                        <!-- SKU -->
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-sage mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                            <span class="text-espresso">SKU: <span class="font-semibold">{{ $product->sku }}</span></span>
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
                                
                                <!-- Variation Selector -->
                                @if($product->variations && $product->variations->count() > 0)
                                    <div>
                                        <label class="form-label">Select Size/Variation <span class="text-red-500">*</span></label>
                                        <select name="variation_id" id="variation-selector" required class="form-input">
                                            <option value="">Choose a variation</option>
                                            @foreach($product->variations as $variation)
                                                <option value="{{ $variation->id }}" 
                                                        data-stock="{{ $variation->stock_quantity }}"
                                                        data-price="{{ $variation->price_adjustment }}"
                                                        {{ $variation->stock_quantity <= 0 ? 'disabled' : '' }}>
                                                    {{ $variation->size }} 
                                                    @if($variation->price_adjustment != 0)
                                                        (+Rp {{ number_format($variation->price_adjustment, 0, ',', '.') }})
                                                    @endif
                                                    - {{ $variation->stock_quantity > 0 ? $variation->stock_quantity . ' in stock' : 'Out of stock' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <!-- Quantity -->
                                <div>
                                    <label class="form-label">Quantity</label>
                                    <div class="flex items-center space-x-4">
                                        <input type="number" name="quantity" id="quantity-input" value="1" min="1" 
                                               max="{{ $product->getTotalStock() }}" 
                                               class="form-input w-24 text-center">
                                        <span class="text-sm text-sage">Max: <span id="max-quantity">{{ $product->getTotalStock() }}</span></span>
                                    </div>
                                </div>

                                <!-- Total Price Display -->
                                <div class="bg-sage/10 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-espresso font-medium">Total Price:</span>
                                        <span class="text-2xl font-bold text-mocha" id="total-price">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
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
                                    <img src="{{ $related->image }}" alt="{{ $related->name }}" onerror="this.src='https://via.placeholder.com/300x300/F5F0E8/342C28?text={{ urlencode($related->name) }}'">
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

@push('scripts')
<script>
    // Change main product image
    function changeMainImage(imageUrl) {
        document.getElementById('main-product-image').src = imageUrl;
    }

    // Variation selector logic
    const variationSelector = document.getElementById('variation-selector');
    const quantityInput = document.getElementById('quantity-input');
    const maxQuantitySpan = document.getElementById('max-quantity');
    const totalPriceSpan = document.getElementById('total-price');
    const basePrice = {{ $product->price }};

    if (variationSelector) {
        variationSelector.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const stock = parseInt(selectedOption.dataset.stock) || 0;
            const priceAdjustment = parseFloat(selectedOption.dataset.price) || 0;
            
            // Update max quantity
            maxQuantitySpan.textContent = stock;
            quantityInput.max = stock;
            
            // Reset quantity if exceeds new max
            if (parseInt(quantityInput.value) > stock) {
                quantityInput.value = stock > 0 ? 1 : 0;
            }
            
            // Update total price
            updateTotalPrice();
        });
    }

    if (quantityInput) {
        quantityInput.addEventListener('input', function() {
            updateTotalPrice();
        });
    }

    function updateTotalPrice() {
        let price = basePrice;
        const quantity = parseInt(quantityInput.value) || 1;
        
        // Add variation price adjustment if selected
        if (variationSelector) {
            const selectedOption = variationSelector.options[variationSelector.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const priceAdjustment = parseFloat(selectedOption.dataset.price) || 0;
                price += priceAdjustment;
            }
        }
        
        const totalPrice = price * quantity;
        totalPriceSpan.textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
    }

    // Initialize Lucide icons if available
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
@endpush

@endsection
