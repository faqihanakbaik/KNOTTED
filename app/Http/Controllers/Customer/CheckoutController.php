<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = auth()->user()->cart()->with(['items.product.variations', 'items.variation'])->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $user = auth()->user();
        
        return view('customer.checkout', compact('cart', 'user'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:bank_transfer,qris',
        ]);

        $cart = auth()->user()->cart()->with(['items.product.variations', 'items.variation'])->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Check stock availability
        foreach ($cart->items as $item) {
            $availableStock = $item->variation 
                ? $item->variation->stock_quantity 
                : $item->product->getTotalStock();
                
            if ($item->quantity > $availableStock) {
                $itemName = $item->product->name;
                if ($item->variation) {
                    $itemName .= ' (Size: ' . $item->variation->size . ')';
                }
                return back()->with('error', "Not enough stock for {$itemName}");
            }
        }

        DB::beginTransaction();
        try {
            // Shipping cost (flat rate for now)
            $shippingCost = 15000;
            $totalAmount = $cart->getTotalAmount() + $shippingCost;

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'subtotal' => $cart->getTotalAmount(),
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'status' => 'pending_payment',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'recipient_name' => $validated['recipient_name'],
                'recipient_phone' => $validated['recipient_phone'],
                'shipping_address' => $validated['shipping_address'],
                'province' => $validated['province'],
                'city' => $validated['city'],
                'district' => $validated['district'],
                'postal_code' => $validated['postal_code'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create order items and reduce stock
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variation_id' => $item->variation_id,
                    'product_name' => $item->product->name,
                    'sku_snapshot' => $item->variation ? $item->variation->getFullSku() : $item->product->sku,
                    'size_snapshot' => $item->variation ? $item->variation->size : null,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->price * $item->quantity,
                ]);

                // Reduce stock from variation or product
                if ($item->variation_id) {
                    $item->variation->decrement('stock_quantity', $item->quantity);
                } else {
                    // For products without variations (deprecated flow)
                    $item->product->decrement('stock', $item->quantity);
                }
            }

            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
            ]);

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            // Redirect to payment upload page
            return redirect()->route('customer.payments.upload', $order->id)
                ->with('success', 'Order placed successfully! Please complete your payment.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process order. Please try again.');
        }
    }
}
