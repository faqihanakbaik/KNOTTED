<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
            'total_products' => Product::count(),
            'low_stock_products' => Product::where('stock', '<=', 5)->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(10)->get();
        $lowStockProducts = Product::where('stock', '<=', 5)->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts'));
    }
}
