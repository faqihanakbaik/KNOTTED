@extends('layouts.app')

@section('title', 'Register - KNOTTED')

@section('content')
<div class="min-h-screen bg-cream flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-lg shadow-xl border border-oat p-8">
            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <h1 class="font-serif text-3xl font-bold text-espresso mb-2">Create Account</h1>
                <p class="text-mocha">Join the KNOTTED community</p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                    <ul class="text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="form-label">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus 
                           class="form-input" placeholder="Your full name">
                </div>

                <div>
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                           class="form-input" placeholder="your@email.com">
                </div>

                <div>
                    <label for="phone" class="form-label">Phone Number (Optional)</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" 
                           class="form-input" placeholder="+62 812-3456-7890">
                </div>

                <div>
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" required 
                           class="form-input" placeholder="Minimum 8 characters">
                </div>

                <div>
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required 
                           class="form-input" placeholder="Re-enter your password">
                </div>

                <button type="submit" class="w-full btn-primary text-lg py-3">
                    Create Account
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-espresso">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-semibold text-mocha hover:text-espresso transition">
                        Login here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
