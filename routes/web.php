<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\Admin\CategoryManagementController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\CustomerManagementController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\CustomOrderController;
use App\Http\Controllers\Admin\CustomOrderController as AdminCustomOrderController;
use App\Http\Controllers\Admin\StoreSettingController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/faq', function () {
    return view('faq');
})->name('faq');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
// GET fallback for expired CSRF tokens
Route::get('/logout', [LoginController::class, 'logout'])->name('logout.get')->middleware('auth');

// Customer Routes (Requires Authentication)
Route::middleware(['auth', 'customer'])->group(function () {
    // Customer Dashboard
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');

    // Addresses
    Route::prefix('customer/addresses')->name('customer.addresses.')->group(function () {
        Route::get('/', [AddressController::class, 'index'])->name('index');
        Route::get('/create', [AddressController::class, 'create'])->name('create');
        Route::post('/', [AddressController::class, 'store'])->name('store');
        Route::get('/{address}/edit', [AddressController::class, 'edit'])->name('edit');
        Route::patch('/{address}', [AddressController::class, 'update'])->name('update');
        Route::delete('/{address}', [AddressController::class, 'destroy'])->name('destroy');
        Route::post('/{address}/set-default', [AddressController::class, 'setDefault'])->name('set-default');
    });

    // Custom Orders
    Route::prefix('customer/custom-orders')->name('customer.custom-orders.')->group(function () {
        Route::get('/', [CustomOrderController::class, 'index'])->name('index');
        Route::get('/create', [CustomOrderController::class, 'create'])->name('create');
        Route::post('/', [CustomOrderController::class, 'store'])->name('store');
        Route::get('/{customOrder}', [CustomOrderController::class, 'show'])->name('show');
        Route::post('/{customOrder}/offer', [CustomOrderController::class, 'makeOffer'])->name('make-offer');
        Route::post('/{customOrder}/offers/{offer}/accept', [CustomOrderController::class, 'acceptOffer'])->name('accept-offer');
        Route::post('/{customOrder}/offers/{offer}/reject', [CustomOrderController::class, 'rejectOffer'])->name('reject-offer');
        Route::patch('/{customOrder}/cancel', [CustomOrderController::class, 'cancel'])->name('cancel');
    });

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // Orders
    Route::get('/customer/orders', [CustomerOrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/customer/orders/{id}', [CustomerOrderController::class, 'show'])->name('customer.orders.show');
    Route::post('/customer/orders/{id}/payment-proof', [CustomerOrderController::class, 'uploadPaymentProof'])->name('customer.orders.payment-proof');
    Route::patch('/customer/orders/{id}/cancel', [CustomerOrderController::class, 'cancel'])->name('customer.orders.cancel');

    // Payment Upload
    Route::get('/customer/payments/{order}/upload', [\App\Http\Controllers\Customer\PaymentController::class, 'upload'])->name('customer.payments.upload');
    Route::post('/customer/payments/{order}/upload', [\App\Http\Controllers\Customer\PaymentController::class, 'store'])->name('customer.payments.store');

    // Profile
    Route::get('/customer/profile', [ProfileController::class, 'index'])->name('customer.profile');
    Route::patch('/customer/profile', [ProfileController::class, 'update'])->name('customer.profile.update');
    Route::patch('/customer/profile/password', [ProfileController::class, 'updatePassword'])->name('customer.profile.password');
});

// Admin Routes (Requires Admin Role + Tab Security)
Route::middleware(['auth', 'admin', 'admin.tab.security'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products Management
    Route::resource('products', ProductManagementController::class)->except(['show']);

    // Categories Management
    Route::resource('categories', CategoryManagementController::class)->except(['show']);

    // Orders Management
    Route::get('/orders', [OrderManagementController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderManagementController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.update-status');
    Route::delete('/orders/{id}', [OrderManagementController::class, 'destroy'])->name('orders.destroy');

    // Payment Verification
    Route::get('/payments', [PaymentVerificationController::class, 'index'])->name('payments.index');
    Route::get('/payments/{id}', [PaymentVerificationController::class, 'show'])->name('payments.show');
    Route::post('/payments/{id}/verify', [PaymentVerificationController::class, 'verify'])->name('payments.verify');
    Route::post('/payments/{id}/reject', [PaymentVerificationController::class, 'reject'])->name('payments.reject');

    // Customers Management
    Route::get('/customers', [CustomerManagementController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [CustomerManagementController::class, 'show'])->name('customers.show');

    // Custom Orders Management
    Route::prefix('custom-orders')->name('custom-orders.')->group(function () {
        Route::get('/', [AdminCustomOrderController::class, 'index'])->name('index');
        Route::get('/{customOrder}', [AdminCustomOrderController::class, 'show'])->name('show');
        Route::post('/{customOrder}/quote', [AdminCustomOrderController::class, 'quotePrice'])->name('quote');
        Route::post('/{customOrder}/offers/{offer}/accept', [AdminCustomOrderController::class, 'acceptOffer'])->name('accept-offer');
        Route::post('/{customOrder}/offers/{offer}/reject', [AdminCustomOrderController::class, 'rejectOffer'])->name('reject-offer');
        Route::patch('/{customOrder}/reject', [AdminCustomOrderController::class, 'reject'])->name('reject');
        Route::post('/{customOrder}/convert', [AdminCustomOrderController::class, 'convertToOrder'])->name('convert');
    });

    // Store Settings
    Route::get('/settings', [StoreSettingController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [StoreSettingController::class, 'update'])->name('settings.update');
});
