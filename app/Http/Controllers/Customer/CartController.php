<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = auth()->user()->cart()->with(['items.product.category', 'items.variation'])->first();
        
        if (!$cart) {
            $cart = Cart::create(['user_id' => auth()->id()]);
        }

        return view('customer.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'nullable|exists:product_variations,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::with('variations')->findOrFail($request->product_id);

        if (!$product->isInStock()) {
            return back()->with('error', 'Product is out of stock.');
        }

        // Check variation stock if variation is selected
        $variation = null;
        $availableStock = $product->getTotalStock();
        $price = $product->price;

        if ($request->variation_id) {
            $variation = $product->variations()->find($request->variation_id);
            
            if (!$variation) {
                return back()->with('error', 'Invalid product variation.');
            }

            if ($variation->stock_quantity <= 0) {
                return back()->with('error', 'Selected variation is out of stock.');
            }

            $availableStock = $variation->stock_quantity;
            $price = $product->price + $variation->price_adjustment;
        }

        if ($request->quantity > $availableStock) {
            return back()->with('error', 'Not enough stock available.');
        }

        $cart = auth()->user()->cart;
        if (!$cart) {
            $cart = Cart::create(['user_id' => auth()->id()]);
        }

        // Check for existing cart item with same product and variation
        $cartItem = $cart->items()
            ->where('product_id', $product->id)
            ->where('variation_id', $request->variation_id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity > $availableStock) {
                return back()->with('error', 'Not enough stock available.');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'variation_id' => $request->variation_id,
                'quantity' => $request->quantity,
                'price' => $price,
            ]);
        }

        return back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::with(['product.variations', 'variation'])->findOrFail($id);
        
        // Check if this cart item belongs to the authenticated user
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        // Check stock availability
        $availableStock = $cartItem->product->getTotalStock();
        if ($cartItem->variation_id) {
            $availableStock = $cartItem->variation->stock_quantity;
        }

        if ($request->quantity > $availableStock) {
            return back()->with('error', 'Not enough stock available.');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated successfully!');
    }

    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);
        
        // Check if this cart item belongs to the authenticated user
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        $cart = auth()->user()->cart;
        if ($cart) {
            $cart->items()->delete();
        }

        return back()->with('success', 'Cart cleared successfully!');
    }
}
