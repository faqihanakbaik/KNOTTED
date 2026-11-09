@extends('layouts.app')

@section('title', 'Reset Password - KNOTTED')

@section('content')
<div class="min-h-screen bg-cream flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-lg shadow-xl border border-oat p-8">
            <div class="text-center mb-8">
                <h1 class="font-serif text-3xl font-bold text-espresso mb-2">Reset Password</h1>
                <p class="text-mocha">Enter your new password</p>
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

            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div>
                    <label for="email-display" class="form-label">Email Address</label>
                    <input id="email-display" type="email" value="{{ $email }}" disabled
                           class="form-input bg-gray-100 cursor-not-allowed">
                    <p class="text-xs text-sage mt-1">Email untuk reset password</p>
                </div>

                <div>
                    <label for="password" class="form-label">New Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required 
                               class="form-input pr-14" placeholder="Minimal 8 karakter" minlength="8">
                        <button type="button" onclick="togglePassword('password')" 
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-sage hover:text-espresso transition z-10 cursor-pointer border-l border-oat"
                                style="pointer-events: auto;">
                            <i data-lucide="eye" id="password-eye" class="w-5 h-5"></i>
                            <i data-lucide="eye-off" id="password-eye-off" class="w-5 h-5 hidden"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <div class="relative">
                        <input id="password_confirmation" type="password" name="password_confirmation" required 
                               class="form-input pr-14" placeholder="Konfirmasi password baru" minlength="8">
                        <button type="button" onclick="togglePassword('password_confirmation')" 
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-sage hover:text-espresso transition z-10 cursor-pointer border-l border-oat"
                                style="pointer-events: auto;">
                            <i data-lucide="eye" id="password_confirmation-eye" class="w-5 h-5"></i>
                            <i data-lucide="eye-off" id="password_confirmation-eye-off" class="w-5 h-5 hidden"></i>
                        </button>
                    </div>
                </div>

                <div class="bg-sage/10 border border-sage/30 rounded-lg p-4">
                    <p class="text-sm text-espresso font-semibold mb-2">
                        <i data-lucide="shield-check" class="w-4 h-4 inline-block"></i>
                        Syarat Password:
                    </p>
                    <ul class="text-sm text-mocha space-y-1 ml-6 list-disc">
                        <li>Minimal 8 karakter</li>
                        <li>Kombinasi huruf dan angka disarankan</li>
                        <li>Password dan konfirmasi harus sama</li>
                    </ul>
                </div>

                <button type="submit" class="w-full btn-primary text-lg py-3">
                    Reset Password
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-mocha hover:text-espresso transition">
                    <i data-lucide="arrow-left" class="w-3 h-3 inline-block"></i> Back to Login
                </a>
            </div>
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

    // Password match validation
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');

    function validatePasswordMatch() {
        if (confirmPassword.value === '') return;
        
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Password tidak cocok');
        } else {
            confirmPassword.setCustomValidity('');
        }
    }

    password.addEventListener('input', validatePasswordMatch);
    confirmPassword.addEventListener('input', validatePasswordMatch);

    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
@endpush
@endsection
