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
            'pending_orders' => Order::whereIn('status', ['pending_payment', 'payment_verification'])->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
            'total_products' => Product::count(),
            'low_stock_products' => 0, // Will calculate from variations
            'total_customers' => User::where('role', 'customer')->count(),
            'pending_payments' => Payment::where('status', 'under_review')->count(),
        ];

        // Calculate low stock from product variations
        $lowStockCount = \DB::table('product_variations')
            ->where('stock_quantity', '<=', 5)
            ->where('stock_quantity', '>', 0)
            ->count();
        $stats['low_stock_products'] = $lowStockCount;

        $recentOrders = Order::with('user')->latest()->take(10)->get();
        
        // Get products with low stock variations
        $lowStockProducts = Product::with(['variations' => function($query) {
            $query->where('stock_quantity', '<=', 5)->where('stock_quantity', '>', 0);
        }])
        ->whereHas('variations', function($query) {
            $query->where('stock_quantity', '<=', 5)->where('stock_quantity', '>', 0);
        })
        ->take(10)
        ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts'));
    }
}
