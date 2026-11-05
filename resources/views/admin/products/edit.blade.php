@extends('layouts.admin')

@section('page-title', 'Edit Product')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-espresso">
        ← Back to Products
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-2xl font-bold text-espresso mb-6">Edit Product</h2>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Product Name -->
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $product->name) }}"
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
                        <option value="{{ $category->id }}" 
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                       value="{{ old('price', $product->price) }}"
                       min="0"
                       step="1000"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta focus:border-transparent"
                       required>
                @error('price')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Stock -->
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                <input type="number" 
                       id="stock" 
                       name="stock" 
                       value="{{ old('stock', $product->stock) }}"
                       min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta focus:border-transparent"
                       required>
                @error('stock')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Image -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Product Image</label>
                <div class="flex items-start gap-6">
                    <!-- Current Image Display -->
                    <div>
                        <p class="text-xs text-gray-500 mb-2">Current image:</p>
                        @if($product->image)
                            <img id="currentImage" 
                                 src="{{ $product->image }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-48 h-48 object-cover rounded border border-gray-300"
                                 onerror="this.src='https://via.placeholder.com/200x200/F5F0E8/342C28?text=No+Image'">
                        @else
                            <div id="currentImage" class="w-48 h-48 bg-gray-100 rounded border border-gray-300 flex items-center justify-center">
                                <p class="text-gray-500 text-sm">No image</p>
                            </div>
                        @endif
                    </div>

                    <!-- New Image Preview (hidden initially) -->
                    <div id="newImagePreview" class="hidden">
                        <p class="text-xs text-gray-500 mb-2">New image preview:</p>
                        <img id="previewImg" 
                             src="" 
                             alt="New Preview" 
                             class="w-48 h-48 object-cover rounded border-2 border-terracotta">
                    </div>
                </div>
            </div>

            <!-- Upload New Image -->
            <div class="md:col-span-2">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                    Change Product Image
                    <span class="text-gray-500 font-normal">(Optional)</span>
                </label>
                <input type="file" 
                       id="image" 
                       name="image" 
                       accept="image/jpeg,image/png,image/jpg"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta focus:border-transparent"
                       onchange="previewNewImage(event)">
                <p class="text-gray-500 text-xs mt-1">
                    Leave empty to keep current image. Upload new image to replace. Max 2MB. Formats: JPG, PNG
                </p>
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
                          required>{{ old('description', $product->description) }}</textarea>
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
                           {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
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
                           {{ old('is_active', $product->is_active) ? 'checked' : '' }}
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
                Update Product
            </button>
        </div>
    </form>
</div>

<script>
    function previewNewImage(event) {
        const preview = document.getElementById('newImagePreview');
        const previewImg = document.getElementById('previewImg');
        const file = event.target.files[0];
        
        if (file) {
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must not exceed 2MB');
                event.target.value = '';
                preview.classList.add('hidden');
                return;
            }
            
            // Validate file type
            if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                alert('Only JPG, JPEG, and PNG files are allowed');
                event.target.value = '';
                preview.classList.add('hidden');
                return;
            }
            
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
