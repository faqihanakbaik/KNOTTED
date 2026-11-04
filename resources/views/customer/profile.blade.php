@extends('layouts.app')

@section('title', 'My Profile - KNOTTED')

@section('content')
<div class="bg-cream py-12 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-4xl font-bold text-espresso mb-8">My Profile</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Menu -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-oat p-4">
                    <a href="{{ route('customer.profile') }}" class="block px-4 py-3 rounded bg-mocha text-cream font-semibold mb-2">
                        Profile Information
                    </a>
                    <a href="{{ route('customer.orders.index') }}" class="block px-4 py-3 rounded hover:bg-cream text-espresso">
                        My Orders
                    </a>
                    <a href="{{ route('cart.index') }}" class="block px-4 py-3 rounded hover:bg-cream text-espresso">
                        Shopping Cart
                    </a>
                </div>
            </div>

            <!-- Profile Forms -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Update Profile -->
                <div class="bg-white rounded-lg p-6 shadow-sm border border-oat">
                    <h2 class="font-serif text-2xl font-bold text-espresso mb-6">Profile Information</h2>
                    
                    <form action="{{ route('customer.profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-4">
                            <div>
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-input">
                                @error('name')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input">
                                @error('email')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input">
                                @error('phone')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Address</label>
                                <textarea name="address" rows="3" class="form-input">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" value="{{ old('city', $user->city) }}" class="form-input">
                                    @error('city')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="form-label">Postal Code</label>
                                    <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="form-input">
                                    @error('postal_code')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="mt-6 btn-primary">Update Profile</button>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="bg-white rounded-lg p-6 shadow-sm border border-oat">
                    <h2 class="font-serif text-2xl font-bold text-espresso mb-6">Change Password</h2>
                    
                    <form action="{{ route('customer.profile.password') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-4">
                            <div>
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password" required class="form-input">
                                @error('current_password')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" required class="form-input">
                                @error('password')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" required class="form-input">
                            </div>
                        </div>

                        <button type="submit" class="mt-6 btn-primary">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
