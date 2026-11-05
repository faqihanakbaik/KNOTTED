@extends('layouts.app')

@section('title', 'Login - KNOTTED')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-oat/30 to-cream flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-lg shadow-xl border border-oat p-8">
            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <h1 class="font-serif text-3xl font-bold text-espresso mb-2">Welcome Back</h1>
                @if(request()->has('redirect'))
                    <p class="text-mocha">Please login to continue</p>
                @else
                    <p class="text-mocha">Login to your KNOTTED account</p>
                @endif
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

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                           class="form-input" placeholder="your@email.com">
                </div>

                <div>
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" required 
                           class="form-input" placeholder="Enter your password">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-mocha text-mocha focus:ring-mocha">
                        <span class="ml-2 text-sm text-espresso">Remember me</span>
                    </label>

                    <a href="{{ route('password.request') }}" class="text-sm text-mocha hover:text-espresso transition">
                        Forgot password?
                    </a>
                </div>

                <button type="submit" class="w-full btn-primary text-lg py-3">
                    Login
                </button>
            </form>

            <!-- Register Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-espresso">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-semibold text-mocha hover:text-espresso transition">
                        Create one now
                    </a>
                </p>
            </div>

            <!-- Continue Shopping Link -->
            <div class="mt-4 text-center">
                <a href="{{ route('home') }}" class="text-sm text-sage hover:text-mocha transition inline-flex items-center gap-1">
                    <i data-lucide="arrow-left" class="w-3 h-3"></i>
                    Continue browsing
                </a>
            </div>

            <!-- Demo Credentials
            <div class="mt-6 pt-6 border-t border-oat">
                <p class="text-xs text-sage text-center mb-2">Demo Accounts:</p>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="bg-cream p-2 rounded">
                        <p class="font-semibold text-espresso">Admin</p>
                        <p class="text-sage">admin@knotted.com</p>
                        <p class="text-sage">password</p>
                    </div>
                    <div class="bg-cream p-2 rounded">
                        <p class="font-semibold text-espresso">Customer</p>
                        <p class="text-sage">customer@example.com</p>
                        <p class="text-sage">password</p>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>
@endsection
