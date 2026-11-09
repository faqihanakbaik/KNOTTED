@extends('layouts.app')

@section('title', 'Login - KNOTTED')

@section('content')
<div class="min-h-screen bg-cream flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
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
                    <div class="relative">
                        <input id="password" type="password" name="password" required 
                               class="form-input pr-14" placeholder="Enter your password">
                        <button type="button" onclick="togglePassword('password')" 
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-sage hover:text-espresso transition z-10 cursor-pointer border-l border-oat"
                                style="pointer-events: auto;">
                            <i data-lucide="eye" id="password-eye" class="w-5 h-5"></i>
                            <i data-lucide="eye-off" id="password-eye-off" class="w-5 h-5 hidden"></i>
                        </button>
                    </div>
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

@push('scripts')
<script>
    function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const eyeIcon = document.getElementById(fieldId + '-eye');
        const eyeOffIcon = document.getElementById(fieldId + '-eye-off');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeOffIcon.classList.remove('hidden');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeOffIcon.classList.add('hidden');
        }
    }

    // Auto-refresh CSRF token every 10 minutes to prevent 419 error
    setInterval(function() {
        fetch('{{ route('home') }}', {
            method: 'HEAD',
            credentials: 'same-origin'
        }).then(() => {
            console.log('Session refreshed');
        }).catch(err => {
            console.warn('Session refresh failed:', err);
        });
    }, 600000); // 10 minutes

    // Refresh CSRF token on form focus
    const loginForm = document.querySelector('form[action="{{ route('login') }}"]');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    
    let tokenRefreshed = false;
    
    function refreshCSRFToken() {
        if (tokenRefreshed) return;
        
        fetch('{{ route('home') }}')
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newToken = doc.querySelector('meta[name="csrf-token"]');
                
                if (newToken) {
                    const currentTokenMeta = document.querySelector('meta[name="csrf-token"]');
                    const currentTokenInput = loginForm.querySelector('input[name="_token"]');
                    
                    if (currentTokenMeta) {
                        currentTokenMeta.setAttribute('content', newToken.getAttribute('content'));
                    }
                    if (currentTokenInput) {
                        currentTokenInput.value = newToken.getAttribute('content');
                    }
                    
                    tokenRefreshed = true;
                    console.log('CSRF token refreshed');
                }
            })
            .catch(err => {
                console.warn('Token refresh failed:', err);
            });
    }
    
    // Refresh token when user starts interacting with form
    emailInput.addEventListener('focus', refreshCSRFToken, { once: true });
    passwordInput.addEventListener('focus', refreshCSRFToken, { once: true });

    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
@endpush
@endsection
