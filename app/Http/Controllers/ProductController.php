<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::with(['category', 'variations', 'productImages'])
            ->where('slug', $slug)
            ->firstOrFail();
        
        $relatedProducts = Product::with(['category', 'variations'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inStock()
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }
}
