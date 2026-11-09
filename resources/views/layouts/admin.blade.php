<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - KNOTTED')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://code.iconify.design/iconify-icon/2.0.0/iconify-icon.min.js"></script>
    
    <!-- Tab Authentication -->
    <meta name="auth-required" content="true">
    @if(session('tab_token'))
    <meta name="tab-token" content="{{ session('tab_token') }}">
    @endif
    <script src="{{ asset('js/tab-auth.js') }}" defer></script>
    
    <style>
        /* Remove default margins */
        html, body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-espresso text-cream flex-shrink-0">
            <div class="h-full flex flex-col">
                <!-- Logo -->
                <div class="px-6 py-6 border-b border-mocha">
                    <h1 class="font-serif text-2xl font-bold">KNOTTED</h1>
                    <p class="text-xs text-oat mt-1">Admin Panel</p>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.products.index') }}" class="admin-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i data-lucide="package" class="w-5 h-5"></i>
                        <span>Products</span>
                    </a>

                    <a href="{{ route('admin.categories.index') }}" class="admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i data-lucide="tag" class="w-5 h-5"></i>
                        <span>Categories</span>
                    </a>

                    <a href="{{ route('admin.orders.index') }}" class="admin-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                        <span>Orders</span>
                    </a>

                    <a href="{{ route('admin.custom-orders.index') }}" class="admin-nav-link {{ request()->routeIs('admin.custom-orders.*') ? 'active' : '' }}">
                        <i data-lucide="wand-2" class="w-5 h-5"></i>
                        <span>Custom Orders</span>
                    </a>

                    <a href="{{ route('admin.payments.index') }}" class="admin-nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                        <span>Payments</span>
                    </a>

                    <a href="{{ route('admin.customers.index') }}" class="admin-nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span>Customers</span>
                    </a>

                    <div class="pt-4 mt-4 border-t border-mocha">
                        <a href="{{ route('admin.settings.index') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <i data-lucide="settings" class="w-5 h-5"></i>
                            <span>Store Settings</span>
                        </a>
                    </div>
                </nav>

                <!-- User Info -->
                <div class="px-4 py-4 border-t border-mocha">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-oat truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div class="mt-3 flex gap-2">
                        <a href="{{ route('home') }}" class="flex-1 text-center text-xs px-3 py-2 bg-mocha hover:bg-mocha/80 rounded transition">
                            View Site
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full text-xs px-3 py-2 bg-transparent hover:bg-mocha/50 border border-mocha rounded transition">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-serif font-bold text-espresso">@yield('page-title', 'Dashboard')</h2>
                    <div class="text-sm text-gray-500">
                        {{ now()->format('l, F j, Y') }}
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <div class="px-6 pt-4">
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4" role="alert">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto px-6 py-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();
        
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
    </script>
</body>
</html>
