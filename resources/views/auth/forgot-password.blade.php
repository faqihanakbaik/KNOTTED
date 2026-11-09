@extends('layouts.app')

@section('title', 'Forgot Password - KNOTTED')

@section('content')
<div class="min-h-screen bg-cream flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-lg shadow-xl border border-oat p-8">
            <div class="text-center mb-8">
                <h1 class="font-serif text-3xl font-bold text-espresso mb-2">Forgot Password?</h1>
                <p class="text-mocha">Enter your email to reset your password</p>
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

            @if(session('success'))
                <div class="bg-sage/20 border border-sage text-espresso px-4 py-3 rounded-lg mb-6">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                           class="form-input" placeholder="your@email.com" autofocus>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full btn-primary text-lg py-3">
                    Send Reset Link
                </button>
            </form>

            <div class="mt-6 text-center space-y-2">
                <p class="text-sm text-sage">
                    <i data-lucide="info" class="w-4 h-4 inline-block"></i>
                    Demo: Link reset akan ditampilkan langsung (tanpa email)
                </p>
                <a href="{{ route('login') }}" class="block text-sm text-mocha hover:text-espresso transition">
                    <i data-lucide="arrow-left" class="w-3 h-3 inline-block"></i> Back to Login
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
@endpush
@endsection
