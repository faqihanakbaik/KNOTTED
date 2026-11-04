@extends('layouts.app')

@section('title', 'My Addresses')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-serif text-espresso mb-2">My Addresses</h1>
            <p class="text-mocha">Manage your shipping addresses</p>
        </div>
        <a href="{{ route('customer.addresses.create') }}" class="inline-flex items-center px-4 py-2 bg-espresso text-cream rounded-lg hover:bg-mocha transition">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Add Address
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if($addresses->count() > 0)
    <div class="space-y-4">
        @foreach($addresses as $address)
        <div class="bg-white rounded-lg border border-oat p-6 @if($address->is_default) ring-2 ring-mocha @endif">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="font-medium text-espresso">{{ $address->recipient_name }}</h3>
                        @if($address->label)
                        <span class="px-2 py-0.5 bg-cream text-mocha text-xs rounded-full">{{ $address->label }}</span>
                        @endif
                        @if($address->is_default)
                        <span class="px-2 py-0.5 bg-mocha text-white text-xs rounded-full">Default</span>
                        @endif
                    </div>
                    <p class="text-sm text-mocha mb-1">{{ $address->phone }}</p>
                    <p class="text-sm text-mocha">{{ $address->full_address }}</p>
                </div>

                <div class="flex items-center gap-2 ml-4">
                    @if(!$address->is_default)
                    <form action="{{ route('customer.addresses.set-default', $address) }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 text-mocha hover:text-espresso transition" title="Set as default">
                            <i data-lucide="star" class="w-5 h-5"></i>
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('customer.addresses.edit', $address) }}" class="p-2 text-mocha hover:text-espresso transition" title="Edit">
                        <i data-lucide="edit" class="w-5 h-5"></i>
                    </a>

                    <form action="{{ route('customer.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this address?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-red-600 hover:text-red-800 transition" title="Delete">
                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-lg border border-oat p-12 text-center">
        <i data-lucide="map-pin" class="w-16 h-16 text-oat mx-auto mb-4"></i>
        <h3 class="text-lg font-medium text-espresso mb-2">No addresses yet</h3>
        <p class="text-mocha mb-6">Add your first shipping address to make checkout easier</p>
        <a href="{{ route('customer.addresses.create') }}" class="inline-flex items-center px-6 py-3 bg-espresso text-cream rounded-lg hover:bg-mocha transition">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Add Address
        </a>
    </div>
    @endif
</div>

<script>
    lucide.createIcons();
</script>
@endsection
