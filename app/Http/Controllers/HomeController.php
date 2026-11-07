<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['category', 'variations'])
            ->featured()
            ->inStock()
            ->take(6)
            ->get();

        $bestSellers = Product::with(['category', 'variations'])
            ->inStock()
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(4)
            ->get();

        $categories = Category::withCount('products')->get();

        return view('home', compact('featuredProducts', 'bestSellers', 'categories'));
    }
}
