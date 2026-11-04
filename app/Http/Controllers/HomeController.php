<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('category')
            ->featured()
            ->available()
            ->take(6)
            ->get();

        $bestSellers = Product::with('category')
            ->available()
            ->take(4)
            ->get();

        $categories = Category::withCount('products')->get();

        return view('home', compact('featuredProducts', 'bestSellers', 'categories'));
    }
}
