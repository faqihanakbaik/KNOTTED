@extends('layouts.app')

@section('title', 'Forgot Password - KNOTTED')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-oat/30 to-cream flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-lg shadow-xl border border-oat p-8">
            <div class="text-center mb-8">
                <h1 class="font-serif text-3xl font-bold text-espresso mb-2">Forgot Password?</h1>
                <p class="text-mocha">Enter your email to reset your password</p>
            </div>

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" name="email" required 
                           class="form-input" placeholder="your@email.com">
                </div>

                <button type="submit" class="w-full btn-primary text-lg py-3">
                    Send Reset Link
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-mocha hover:text-espresso transition">
                    ← Back to Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
