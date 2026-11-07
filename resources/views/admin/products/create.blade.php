@extends('layouts.admin')

@section('page-title', 'Add New Product')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-espresso">
        ← Back to Products
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-2xl font-bold text-espresso mb-6">Add New Product</h2>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Product Name -->
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta focus:border-transparent"
                       required>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                <select id="category_id" 
                        name="category_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta focus:border-transparent"
                        required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (Rp) *</label>
                <input type="number" 
                       id="price" 
                       name="price" 
                       value="{{ old('price') }}"
                       min="0"
                       step="1000"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta focus:border-transparent"
                       required>
                @error('price')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Product Variations (Size & Stock) -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Product Variations (Size & Stock) *
                </label>
                <div id="variations-container" class="space-y-3">
                    <!-- Variation template will be added here by JavaScript -->
                </div>
                <button type="button" 
                        onclick="addVariation()" 
                        class="mt-3 text-sm text-terracotta hover:text-espresso font-semibold">
                    + Add Variation
                </button>
                <p class="text-gray-500 text-xs mt-1">Add sizes and stock quantities (e.g., Small, Medium, Large or One Size)</p>
            </div>

            <!-- Product Image -->
            <div class="md:col-span-2">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Product Image *</label>
                
                <!-- Preview Image -->
                <div id="imagePreview" class="mb-3 hidden">
                    <img id="previewImg" src="" alt="Preview" class="w-48 h-48 object-cover rounded border border-gray-300">
                </div>

                <input type="file" 
                       id="image" 
                       name="image" 
                       accept="image/jpeg,image/png,image/jpg"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta focus:border-transparent"
                       required
                       onchange="previewImage(event)">
                <p class="text-gray-500 text-xs mt-1">Required. Max 2MB. Formats: JPG, PNG</p>
                @error('image')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea id="description" 
                          name="description" 
                          rows="5"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta focus:border-transparent"
                          required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Checkboxes -->
            <div class="md:col-span-2 space-y-3">
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_featured" 
                           name="is_featured" 
                           value="1"
                           {{ old('is_featured') ? 'checked' : '' }}
                           class="w-4 h-4 text-terracotta border-gray-300 rounded focus:ring-terracotta">
                    <label for="is_featured" class="ml-2 text-sm text-gray-700">
                        Featured Product (Show on homepage)
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-terracotta border-gray-300 rounded focus:ring-terracotta">
                    <label for="is_active" class="ml-2 text-sm text-gray-700">
                        Active (Available for purchase)
                    </label>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn-primary">
                Create Product
            </button>
        </div>
    </form>
</div>

<script>
    let variationIndex = 0;

    // Add first variation on page load
    document.addEventListener('DOMContentLoaded', function() {
        addVariation();
    });

    function addVariation() {
        const container = document.getElementById('variations-container');
        const variationHtml = `
            <div class="variation-row flex gap-3 items-start" data-index="${variationIndex}">
                <div class="flex-1">
                    <input type="text" 
                           name="variations[${variationIndex}][size]" 
                           placeholder="Size (e.g., Small, Medium, One Size)"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta focus:border-transparent"
                           required>
                </div>
                <div class="w-32">
                    <input type="number" 
                           name="variations[${variationIndex}][stock_quantity]" 
                           placeholder="Stock"
                           min="0"
                           value="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta focus:border-transparent"
                           required>
                </div>
                <button type="button" 
                        onclick="removeVariation(this)" 
                        class="px-3 py-2 text-red-600 hover:text-red-800 font-semibold">
                    Remove
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', variationHtml);
        variationIndex++;
    }

    function removeVariation(button) {
        const variationsContainer = document.getElementById('variations-container');
        const variationRows = variationsContainer.querySelectorAll('.variation-row');
        
        // Keep at least one variation
        if (variationRows.length > 1) {
            button.closest('.variation-row').remove();
        } else {
            alert('At least one variation is required');
        }
    }

    function previewImage(event) {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
        }
    }
</script>
@endsection
