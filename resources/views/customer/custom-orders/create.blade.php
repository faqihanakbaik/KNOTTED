@extends('layouts.app')

@section('title', 'Create Custom Order')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#F5F0E8] via-white to-[#FFFFFF] py-12">
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8" data-aos="fade-down">
        <a href="{{ route('customer.custom-orders.index') }}" class="inline-flex items-center text-[#947765] hover:text-[#DCCBBC] mb-4 font-medium transition-all duration-300 hover:gap-3 gap-2">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Back to My Requests
        </a>
        <h1 class="text-4xl font-serif font-bold text-[#947765] mb-3">Create Custom Order <iconify-icon icon="solar:star-bold" class="inline-block w-8 h-8"></iconify-icon></h1>
        <p class="text-[#947765]/80 text-lg">Tell us about your dream crochet piece and we'll make it happen</p>
    </div>

    @if(session('error'))
    <div class="bg-gradient-to-r from-red-50 to-red-100 border-2 border-red-300 text-red-800 px-6 py-4 rounded-2xl mb-6 shadow-lg" data-aos="fade-up">
        <div class="flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-3xl border-2 border-[#F5F0E8] p-8 md:p-10 shadow-xl" data-aos="fade-up">
        <form action="{{ route('customer.custom-orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Request Type -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-[#947765] mb-4 uppercase tracking-wide">
                    Request Type <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative flex items-start p-5 border-2 border-[#F5F0E8] rounded-2xl cursor-pointer hover:border-[#DCCBBC] hover:shadow-lg transition-all duration-300 has-[:checked]:border-[#DCCBBC] has-[:checked]:bg-gradient-to-br has-[:checked]:from-[#F5F0E8] has-[:checked]:to-white has-[:checked]:shadow-lg">
                        <input type="radio" name="request_type" value="modify_existing" class="mt-1 accent-[#DCCBBC]" required {{ old('request_type') === 'modify_existing' ? 'checked' : '' }}>
                        <div class="ml-4">
                            <div class="flex items-center gap-2 mb-2">
                                <i data-lucide="edit-3" class="w-5 h-5 text-[#DCCBBC]"></i>
                                <span class="font-bold text-[#947765]">Modify Existing Product</span>
                            </div>
                            <p class="text-sm text-[#947765]/70">Customize a product from our catalog with different colors, sizes, or details</p>
                        </div>
                    </label>

                    <label class="relative flex items-start p-5 border-2 border-[#F5F0E8] rounded-2xl cursor-pointer hover:border-[#DCCBBC] hover:shadow-lg transition-all duration-300 has-[:checked]:border-[#DCCBBC] has-[:checked]:bg-gradient-to-br has-[:checked]:from-[#F5F0E8] has-[:checked]:to-white has-[:checked]:shadow-lg">
                        <input type="radio" name="request_type" value="new_design" class="mt-1 accent-[#DCCBBC]" required {{ old('request_type') === 'new_design' ? 'checked' : '' }}>
                        <div class="ml-4">
                            <div class="flex items-center gap-2 mb-2">
                                <i data-lucide="sparkles" class="w-5 h-5 text-[#DCCBBC]"></i>
                                <span class="font-bold text-[#947765]">New Custom Design</span>
                            </div>
                            <p class="text-sm text-[#947765]/70">Request a completely new design that doesn't exist in our catalog</p>
                        </div>
                    </label>
                </div>
                @error('request_type')
                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <div class="space-y-6">
                <!-- Product Name -->
                <div>
                    <label for="product_name" class="block text-sm font-bold text-[#947765] mb-2 uppercase tracking-wide">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="product_name" id="product_name" value="{{ old('product_name') }}" required
                        placeholder="e.g., Mini Crochet Bunny, Oversized Cardigan"
                        class="w-full px-5 py-3 border-2 border-[#F5F0E8] rounded-xl focus:ring-2 focus:ring-[#DCCBBC] focus:border-[#DCCBBC] transition-all duration-300 @error('product_name') border-red-500 @enderror">
                    @error('product_name')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-bold text-[#947765] mb-2 uppercase tracking-wide">
                        Category (Optional)
                    </label>
                    <select name="category_id" id="category_id" class="w-full px-5 py-3 border-2 border-[#F5F0E8] rounded-xl focus:ring-2 focus:ring-[#DCCBBC] focus:border-[#DCCBBC] transition-all duration-300">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-bold text-[#947765] mb-2 uppercase tracking-wide">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="5" required
                        placeholder="Describe what you want in detail. Include style, pattern, special features, or any specific requirements..."
                        class="w-full px-5 py-3 border-2 border-[#F5F0E8] rounded-xl focus:ring-2 focus:ring-[#DCCBBC] focus:border-[#DCCBBC] transition-all duration-300 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    <p class="mt-2 text-sm text-[#947765]/70 flex items-center gap-1">
                        <i data-lucide="info" class="w-4 h-4"></i>
                        Be as detailed as possible to help us understand your vision
                    </p>
                    @error('description')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Color, Size, Quantity -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="color" class="block text-sm font-bold text-[#947765] mb-2 uppercase tracking-wide">
                            Color (Optional)
                        </label>
                        <input type="text" name="color" id="color" value="{{ old('color') }}"
                            placeholder="e.g., Pastel Pink, Navy Blue"
                            class="w-full px-5 py-3 border-2 border-[#F5F0E8] rounded-xl focus:ring-2 focus:ring-[#DCCBBC] focus:border-[#DCCBBC] transition-all duration-300">
                    </div>

                    <div>
                        <label for="size" class="block text-sm font-bold text-[#947765] mb-2 uppercase tracking-wide">
                            Size (Optional)
                        </label>
                        <input type="text" name="size" id="size" value="{{ old('size') }}"
                            placeholder="e.g., 30cm, Large, S/M/L"
                            class="w-full px-5 py-3 border-2 border-[#F5F0E8] rounded-xl focus:ring-2 focus:ring-[#DCCBBC] focus:border-[#DCCBBC] transition-all duration-300">
                    </div>

                    <div>
                        <label for="quantity" class="block text-sm font-bold text-[#947765] mb-2 uppercase tracking-wide">
                            Quantity <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" min="1" required
                            class="w-full px-5 py-3 border-2 border-[#F5F0E8] rounded-xl focus:ring-2 focus:ring-[#DCCBBC] focus:border-[#DCCBBC] transition-all duration-300 @error('quantity') border-red-500 @enderror">
                        @error('quantity')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

                <!-- Reference Images -->
                <div>
                    <label for="images" class="block text-sm font-bold text-[#947765] mb-2 uppercase tracking-wide">
                        Reference Images (Optional)
                    </label>
                    <div class="border-2 border-dashed border-[#DCCBBC] rounded-2xl p-8 hover:border-[#F5F0E8] hover:bg-[#F5F0E8]/30 transition-all duration-300">
                        <input type="file" name="images[]" id="images" multiple accept="image/jpeg,image/jpg,image/png,image/webp"
                            class="hidden" onchange="previewImages(event)">
                        <label for="images" class="flex flex-col items-center cursor-pointer">
                            <i data-lucide="upload" class="w-16 h-16 text-[#DCCBBC] mb-4"></i>
                            <p class="text-base font-bold text-[#947765] mb-2">Click to upload images</p>
                            <p class="text-sm text-[#947765]/70">PNG, JPG, WEBP up to 2MB each. Multiple files allowed.</p>
                        </label>
                    </div>
                    <div id="imagePreview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4"></div>
                    @error('images.*')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Expected Price -->
                <div>
                    <label for="customer_expected_price" class="block text-sm font-bold text-[#947765] mb-2 uppercase tracking-wide">
                        Your Expected Price (Optional)
                    </label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-[#947765] font-semibold">Rp</span>
                        <input type="number" name="customer_expected_price" id="customer_expected_price" value="{{ old('customer_expected_price') }}" min="0" step="1000"
                            placeholder="0"
                            class="w-full pl-16 pr-5 py-3 border-2 border-[#F5F0E8] rounded-xl focus:ring-2 focus:ring-[#DCCBBC] focus:border-[#DCCBBC] transition-all duration-300">
                    </div>
                    <p class="mt-2 text-sm text-[#947765]/70 flex items-center gap-1">
                        <i data-lucide="info" class="w-4 h-4"></i>
                        Leave empty if you want us to quote a price first
                    </p>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-bold text-[#947765] mb-2 uppercase tracking-wide">
                        Additional Notes (Optional)
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                        placeholder="Any additional information, deadline preferences, or special requests..."
                        class="w-full px-5 py-3 border-2 border-[#F5F0E8] rounded-xl focus:ring-2 focus:ring-[#DCCBBC] focus:border-[#DCCBBC] transition-all duration-300">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Terms Notice -->
            <div class="mt-8 p-6 bg-gradient-to-br from-[#F5F0E8] to-white rounded-2xl border-2 border-[#DCCBBC]">
                <div class="flex items-start space-x-3">
                    <i data-lucide="info" class="w-6 h-6 text-[#DCCBBC] flex-shrink-0 mt-1"></i>
                    <div class="text-sm text-[#947765]/90">
                        <p class="font-bold text-[#947765] mb-3 text-base">Before you submit:</p>
                        <ul class="list-disc list-inside space-y-2">
                            <li>We'll review your request and provide a price quote within 1-2 business days</li>
                            <li>You can negotiate the price until we both agree</li>
                            <li>Once agreed, you'll proceed to checkout and make payment</li>
                            <li>Custom orders take 3-14 days depending on complexity</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-4 mt-8 pt-6 border-t-2 border-[#F5F0E8]">
                <button type="submit" class="flex-1 bg-gradient-to-r from-[#DCCBBC] to-[#F5F0E8] hover:from-[#F5F0E8] hover:to-[#DCCBBC] text-white font-bold px-8 py-4 rounded-full transition-all duration-300 hover:shadow-xl hover:scale-105 flex items-center justify-center gap-2">
                    <i data-lucide="send" class="w-5 h-5"></i>
                    Submit Custom Request
                </button>
                <a href="{{ route('customer.custom-orders.index') }}" class="px-8 py-4 border-2 border-[#F5F0E8] rounded-full text-[#947765] hover:border-[#DCCBBC] hover:bg-[#F5F0E8]/50 transition-all duration-300 font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
</div>

<script>
    lucide.createIcons();

    function previewImages(event) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        
        const files = event.target.files;
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-32 object-cover rounded-xl border-2 border-[#F5F0E8]">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#947765] to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end p-3">
                        <p class="text-white text-xs font-medium truncate">${file.name}</p>
                    </div>
                `;
                preview.appendChild(div);
            }
            
            reader.readAsDataURL(file);
        }
        
        lucide.createIcons();
    }
</script>
@endsection
