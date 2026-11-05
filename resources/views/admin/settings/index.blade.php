@extends('layouts.admin')

@section('page-title', 'Store Settings')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-espresso">Store Settings</h2>
    <p class="text-gray-600 mt-1">Manage your store information and payment details</p>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-3xl">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Bank Information -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-espresso mb-4 pb-2 border-b border-gray-200">Bank Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">Bank Name *</label>
                    <input type="text" 
                           name="bank_name" 
                           id="bank_name" 
                           value="{{ old('bank_name', $settings['bank_name']) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta"
                           required>
                    @error('bank_name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bank_account_number" class="block text-sm font-medium text-gray-700 mb-2">Account Number *</label>
                    <input type="text" 
                           name="bank_account_number" 
                           id="bank_account_number" 
                           value="{{ old('bank_account_number', $settings['bank_account_number']) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta"
                           required>
                    @error('bank_account_number')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="bank_account_name" class="block text-sm font-medium text-gray-700 mb-2">Account Name *</label>
                    <input type="text" 
                           name="bank_account_name" 
                           id="bank_account_name" 
                           value="{{ old('bank_account_name', $settings['bank_account_name']) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta"
                           required>
                    @error('bank_account_name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Store Contact -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-espresso mb-4 pb-2 border-b border-gray-200">Store Contact</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="store_email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" 
                           name="store_email" 
                           id="store_email" 
                           value="{{ old('store_email', $settings['store_email']) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta"
                           required>
                    @error('store_email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="store_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                    <input type="text" 
                           name="store_phone" 
                           id="store_phone" 
                           value="{{ old('store_phone', $settings['store_phone']) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta"
                           required>
                    @error('store_phone')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="store_address" class="block text-sm font-medium text-gray-700 mb-2">Store Address</label>
                    <textarea name="store_address" 
                              id="store_address" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta">{{ old('store_address', $settings['store_address']) }}</textarea>
                    @error('store_address')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="store_description" class="block text-sm font-medium text-gray-700 mb-2">Store Description</label>
                    <textarea name="store_description" 
                              id="store_description" 
                              rows="4"
                              placeholder="About your store..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta">{{ old('store_description', $settings['store_description']) }}</textarea>
                    @error('store_description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-espresso mb-4 pb-2 border-b border-gray-200">Social Media</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="instagram_url" class="block text-sm font-medium text-gray-700 mb-2">Instagram URL</label>
                    <input type="url" 
                           name="instagram_url" 
                           id="instagram_url" 
                           value="{{ old('instagram_url', $settings['instagram_url']) }}"
                           placeholder="https://instagram.com/knotted"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta">
                    @error('instagram_url')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tiktok_url" class="block text-sm font-medium text-gray-700 mb-2">TikTok URL</label>
                    <input type="url" 
                           name="tiktok_url" 
                           id="tiktok_url" 
                           value="{{ old('tiktok_url', $settings['tiktok_url']) }}"
                           placeholder="https://tiktok.com/@knotted"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta">
                    @error('tiktok_url')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Number</label>
                    <input type="text" 
                           name="whatsapp_number" 
                           id="whatsapp_number" 
                           value="{{ old('whatsapp_number', $settings['whatsapp_number']) }}"
                           placeholder="+62812345678"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-terracotta">
                    <p class="text-gray-500 text-xs mt-1">Include country code, e.g. +62812345678</p>
                    @error('whatsapp_number')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end pt-6 border-t border-gray-200">
            <button type="submit" class="btn-primary">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
