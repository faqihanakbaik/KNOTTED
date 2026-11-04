@extends('layouts.app')

@section('title', 'Add New Address')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('customer.addresses.index') }}" class="inline-flex items-center text-mocha hover:text-espresso mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Back to Addresses
        </a>
        <h1 class="text-3xl font-serif text-espresso mb-2">Add New Address</h1>
        <p class="text-mocha">Fill in your shipping address details</p>
    </div>

    <div class="bg-white rounded-lg border border-oat p-8">
        <form action="{{ route('customer.addresses.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <!-- Recipient Name -->
                <div>
                    <label for="recipient_name" class="block text-sm font-medium text-espresso mb-2">
                        Recipient Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="recipient_name" id="recipient_name" value="{{ old('recipient_name') }}" required
                        class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent @error('recipient_name') border-red-500 @enderror">
                    @error('recipient_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-espresso mb-2">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                        placeholder="08xxxxxxxxxx"
                        class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent @error('phone') border-red-500 @enderror">
                    @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address Line -->
                <div>
                    <label for="address_line" class="block text-sm font-medium text-espresso mb-2">
                        Street Address <span class="text-red-500">*</span>
                    </label>
                    <textarea name="address_line" id="address_line" rows="3" required
                        placeholder="Street name, building number, unit/floor"
                        class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent @error('address_line') border-red-500 @enderror">{{ old('address_line') }}</textarea>
                    @error('address_line')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- City & Province -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="city" class="block text-sm font-medium text-espresso mb-2">
                            City <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="city" id="city" value="{{ old('city') }}" required
                            class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent @error('city') border-red-500 @enderror">
                        @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="province" class="block text-sm font-medium text-espresso mb-2">
                            Province <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="province" id="province" value="{{ old('province') }}" required
                            class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent @error('province') border-red-500 @enderror">
                        @error('province')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Postal Code & Label -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-espresso mb-2">
                            Postal Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" required
                            placeholder="12345"
                            class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent @error('postal_code') border-red-500 @enderror">
                        @error('postal_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="label" class="block text-sm font-medium text-espresso mb-2">
                            Label (Optional)
                        </label>
                        <input type="text" name="label" id="label" value="{{ old('label') }}"
                            placeholder="Home, Office, etc."
                            class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent">
                    </div>
                </div>

                <!-- Set as Default -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}
                        class="w-4 h-4 text-espresso border-oat rounded focus:ring-mocha">
                    <label for="is_default" class="ml-2 text-sm text-espresso">
                        Set as default address
                    </label>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-4 mt-8 pt-6 border-t border-oat">
                <button type="submit" class="flex-1 bg-espresso text-cream px-6 py-3 rounded-lg hover:bg-mocha transition">
                    Save Address
                </button>
                <a href="{{ route('customer.addresses.index') }}" class="px-6 py-3 border border-oat rounded-lg text-mocha hover:border-mocha transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
