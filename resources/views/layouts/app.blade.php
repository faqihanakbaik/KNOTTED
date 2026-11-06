<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KNOTTED - Made by hand, tied with love')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://code.iconify.design/iconify-icon/2.0.0/iconify-icon.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="font-sans antialiased bg-cream text-espresso">
    
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-cream/95 backdrop-blur-sm border-b border-oat">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center">
                    <span class="font-serif text-xl font-bold text-espresso tracking-tight uppercase">KNOTTED</span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    <div class="relative group">
                        <button class="nav-link flex items-center gap-1">
                            Shop
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                        </button>
                        <!-- Shop Dropdown -->
                        <div class="absolute top-full left-0 mt-1 w-48 bg-white rounded-lg shadow-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <a href="{{ route('shop.index') }}" class="block px-4 py-2 text-sm text-espresso hover:bg-cream">All Products</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <a href="{{ route('shop.index', ['category' => 1]) }}" class="block px-4 py-2 text-sm text-espresso hover:bg-cream">Amigurumi</a>
                            <a href="{{ route('shop.index', ['category' => 2]) }}" class="block px-4 py-2 text-sm text-espresso hover:bg-cream">Flowers</a>
                            <a href="{{ route('shop.index', ['category' => 3]) }}" class="block px-4 py-2 text-sm text-espresso hover:bg-cream">Keychains</a>
                            <a href="{{ route('shop.index', ['category' => 4]) }}" class="block px-4 py-2 text-sm text-espresso hover:bg-cream">Bags</a>
                        </div>
                    </div>
                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
                    <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
                    <a href="{{ route('faq') }}" class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}">FAQ</a>
                </div>

                <!-- Right Navigation -->
                <div class="flex items-center space-x-3">
                    <!-- Search Icon -->
                    <button class="text-espresso hover:text-mocha transition">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </button>

                    @auth
                        <!-- Cart (Only for customers) -->
                        @if(!auth()->user()->isAdmin())
                            <a href="{{ route('cart.index') }}" class="relative text-espresso hover:text-mocha transition">
                                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                                @if(auth()->user()->cart && auth()->user()->cart->items->count() > 0)
                                    <span class="absolute -top-2 -right-2 bg-espresso text-cream text-xs rounded-full h-4 w-4 flex items-center justify-center font-semibold text-[10px]">
                                        {{ auth()->user()->cart->getTotalItems() }}
                                    </span>
                                @endif
                            </a>
                        @endif
                        
                        <!-- User Dropdown -->
                        <div class="relative group">
                            <button class="text-espresso hover:text-mocha transition">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </button>
                            <div class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-espresso">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-mocha">{{ auth()->user()->email }}</p>
                                </div>
                                
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-espresso hover:bg-cream">
                                        <i data-lucide="shield" class="w-4 h-4"></i>
                                        <span>Admin Dashboard</span>
                                    </a>
                                @else
                                    <a href="{{ route('customer.dashboard') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-espresso hover:bg-cream">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                        <span>Dashboard</span>
                                    </a>
                                    <a href="{{ route('customer.orders.index') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-espresso hover:bg-cream">
                                        <i data-lucide="package" class="w-4 h-4"></i>
                                        <span>My Orders</span>
                                    </a>
                                    <a href="{{ route('customer.custom-orders.index') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-espresso hover:bg-cream">
                                        <i data-lucide="wand-2" class="w-4 h-4"></i>
                                        <span>Custom Requests</span>
                                    </a>
                                    <a href="{{ route('customer.addresses.index') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-espresso hover:bg-cream">
                                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                                        <span>Addresses</span>
                                    </a>
                                    <a href="{{ route('customer.profile') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-espresso hover:bg-cream">
                                        <i data-lucide="user-circle" class="w-4 h-4"></i>
                                        <span>Profile</span>
                                    </a>
                                @endif
                                
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center space-x-2 text-left px-4 py-2 text-sm text-espresso hover:bg-cream">
                                        <i data-lucide="log-out" class="w-4 h-4"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-espresso hover:text-mocha transition font-medium">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary text-sm px-4 py-1.5">Register</a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn" class="md:hidden text-espresso">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-oat">
            <div class="px-4 py-3 space-y-2">
                <a href="{{ route('home') }}" class="block text-espresso hover:text-mocha transition text-sm">Home</a>
                <a href="{{ route('shop.index') }}" class="block text-espresso hover:text-mocha transition text-sm">Shop</a>
                <a href="{{ route('about') }}" class="block text-espresso hover:text-mocha transition text-sm">About</a>
                <a href="{{ route('contact') }}" class="block text-espresso hover:text-mocha transition text-sm">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-sage/20 border border-sage text-espresso px-4 py-3 rounded-lg" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-espresso text-cream mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <h3 class="font-serif text-xl font-bold mb-4">KNOTTED</h3>
                    <p class="text-oat text-sm leading-relaxed">
                        Made by hand, tied with love. Each piece tells a story of craftsmanship and care.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold mb-4">Shop</h4>
                    <ul class="space-y-2 text-oat text-sm">
                        <li><a href="{{ route('shop.index') }}" class="hover:text-cream transition">All Products</a></li>
                        <li><a href="{{ route('shop.index') }}?category=1" class="hover:text-cream transition">Amigurumi</a></li>
                        <li><a href="{{ route('shop.index') }}?category=2" class="hover:text-cream transition">Flowers</a></li>
                        <li><a href="{{ route('shop.index') }}?category=3" class="hover:text-cream transition">Keychains</a></li>
                        <li><a href="{{ route('shop.index') }}?category=4" class="hover:text-cream transition">Bags</a></li>
                    </ul>
                </div>

                <!-- Customer Service -->
                <div>
                    <h4 class="font-semibold mb-4">Customer Service</h4>
                    <ul class="space-y-2 text-oat text-sm">
                        <li><a href="{{ route('about') }}" class="hover:text-cream transition">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-cream transition">Contact</a></li>
                        @auth
                            <li><a href="{{ route('customer.orders.index') }}" class="hover:text-cream transition">My Orders</a></li>
                        @endauth
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-semibold mb-4">Get in Touch</h4>
                    <ul class="space-y-2 text-oat text-sm">
                        <li><a href="mailto:faqihmuqoddam3007@gmail.com" class="hover:text-cream transition">Email: faqihmuqoddam3007@gmail.com</a></li>
                        <li><a href="https://wa.me/6285892992913" target="_blank" class="hover:text-cream transition">WhatsApp: +62 858-9299-2913</a></li>
                        <li><a href="https://instagram.com/faqiiihhhhhh" target="_blank" class="hover:text-cream transition">Instagram: @faqiiihhhhhh</a></li>
                        <li><a href="https://github.com/faqihanakbaik" target="_blank" class="hover:text-cream transition">GitHub: faqihanakbaik</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-mocha mt-8 pt-8 text-center text-oat text-sm">
                <p>&copy; {{ date('Y') }} KNOTTED. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            once: true,
            duration: 800,
            easing: 'ease-out-cubic',
            offset: 100
        });
        
        // Initialize Lucide Icons
        lucide.createIcons();
        
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Auto-hide flash messages
        setTimeout(function() {
            document.querySelectorAll('[role="alert"]').forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);

        // Handle 419 CSRF errors on logout forms
        document.querySelectorAll('form[action*="logout"]').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value,
                        'Accept': 'application/json',
                    },
                    body: new FormData(form)
                })
                .then(response => {
                    if (response.status === 419) {
                        // CSRF expired, use GET fallback
                        window.location.href = '/logout';
                    } else {
                        // Success, redirect to home
                        window.location.href = '/';
                    }
                })
                .catch(error => {
                    // Any error, just use GET fallback
                    window.location.href = '/logout';
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
