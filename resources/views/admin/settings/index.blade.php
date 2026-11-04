@extends('layouts.admin')

@section('title', 'Store Settings')

@section('content')
<div class="p-6 max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-serif text-espresso mb-1">Store Settings</h1>
        <p class="text-mocha">Manage your store information and payment details</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PATCH')

        <!-- Bank Information -->
        <div class="bg-white rounded-lg border border-oat p-6 mb-6">
            <h2 class="text-lg font-serif text-espresso mb-4 flex items-center">
                <i data-lucide="credit-card" class="w-5 h-5 mr-2"></i>
                Bank Information
            </h2>
            <p class="text-sm text-mocha mb-6">This information will be shown to customers during checkout</p>

            <div class="space-y-4">
                <div>
                    <label for="bank_name" class="block text-sm font-medium text-espresso mb-2">
                        Bank Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $settings['bank_name']) }}" required
                        placeholder="e.g., BCA, BNI, Mandiri"
                        class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent @error('bank_name') border-red-500 @enderror">
                    @error('bank_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bank_account_number" class="block text-sm font-medium text-espresso mb-2">
                        Account Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="bank_account_number" id="bank_account_number" value="{{ old('bank_account_number', $settings['bank_account_number']) }}" required
                        placeholder="1234567890"
                        class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent @error('bank_account_number') border-red-500 @enderror">
                    @error('bank_account_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bank_account_name" class="block text-sm font-medium text-espresso mb-2">
                        Account Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="bank_account_name" id="bank_account_name" value="{{ old('bank_account_name', $settings['bank_account_name']) }}" required
                        placeholder="KNOTTED"
                        class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent @error('bank_account_name') border-red-500 @enderror">
                    @error('bank_account_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Store Contact -->
        <div class="bg-white rounded-lg border border-oat p-6 mb-6">
            <h2 class="text-lg font-serif text-espresso mb-4 flex items-center">
                <i data-lucide="phone" class="w-5 h-5 mr-2"></i>
                Store Contact Information
            </h2>

            <div class="space-y-4">
                <div>
                    <label for="store_email" class="block text-sm font-medium text-espresso mb-2">
                        Store Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="store_email" id="store_email" value="{{ old('store_email', $settings['store_email']) }}" required
                        placeholder="hello@knotted.com"
                        class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent @error('store_email') border-red-500 @enderror">
                    @error('store_email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="store_phone" class="block text-sm font-medium text-espresso mb-2">
                        Store Phone <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" name="store_phone" id="store_phone" value="{{ old('store_phone', $settings['store_phone']) }}" required
                        placeholder="08123456789"
                        class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent @error('store_phone') border-red-500 @enderror">
                    @error('store_phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="whatsapp_number" class="block text-sm font-medium text-espresso mb-2">
                        WhatsApp Number
                    </label>
                    <input type="tel" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number']) }}"
                        placeholder="628123456789 (with country code, no +)"
                        class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent">
                    <p class="mt-1 text-xs text-mocha">Format: 628123456789 (country code without +)</p>
                </div>

                <div>
                    <label for="store_address" class="block text-sm font-medium text-espresso mb-2">
                        Store Address
                    </label>
                    <textarea name="store_address" id="store_address" rows="3"
                        placeholder="Full store address..."
                        class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent">{{ old('store_address', $settings['store_address']) }}</textarea>
                </div>

                <div>
                    <label for="store_description" class="block text-sm font-medium text-espresso mb-2">
                        Store Description
                    </label>
                    <textarea name="store_description" id="store_description" rows="4"
                        placeholder="Brief description about your store..."
                        class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent">{{ old('store_description', $settings['store_description']) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div class="bg-white rounded-lg border border-oat p-6 mb-6">
            <h2 class="text-lg font-serif text-espresso mb-4 flex items-center">
                <i data-lucide="share-2" class="w-5 h-5 mr-2"></i>
                Social Media
            </h2>

            <div class="space-y-4">
                <div>
                    <label for="instagram_url" class="block text-sm font-medium text-espresso mb-2">
                        Instagram URL
                    </label>
                    <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $settings['instagram_url']) }}"
                        placeholder="https://instagram.com/knotted"
                        class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent">
                </div>

                <div>
                    <label for="tiktok_url" class="block text-sm font-medium text-espresso mb-2">
                        TikTok URL
                    </label>
                    <input type="url" name="tiktok_url" id="tiktok_url" value="{{ old('tiktok_url', $settings['tiktok_url']) }}"
                        placeholder="https://tiktok.com/@knotted"
                        class="w-full px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-4">
            <button type="submit" class="flex-1 bg-espresso text-cream px-6 py-3 rounded-lg hover:bg-mocha transition font-medium">
                <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>
                Save Settings
            </button>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
