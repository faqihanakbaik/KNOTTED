<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // Cache homepage data for 5 minutes
        $featuredProducts = cache()->remember('home_featured_products', 300, function() {
            return Product::with(['category', 'variations'])
                ->featured()
                ->inStock()
                ->take(6)
                ->get();
        });

        $bestSellers = cache()->remember('home_best_sellers', 300, function() {
            return Product::with(['category', 'variations'])
                ->inStock()
                ->withCount('orderItems')
                ->orderBy('order_items_count', 'desc')
                ->take(4)
                ->get();
        });

        $categories = cache()->remember('home_categories', 600, function() {
            return Category::withCount('products')->get();
        });

        return view('home', compact('featuredProducts', 'bestSellers', 'categories'));
    }
}
