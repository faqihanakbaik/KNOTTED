<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = auth()->user()->orders()
            ->with(['items.product', 'payment'])
            ->findOrFail($id);

        return view('customer.orders.show', compact('order'));
    }

    public function uploadPaymentProof(Request $request, $id)
    {
        $order = auth()->user()->orders()->findOrFail($id);

        if ($order->payment_method !== 'bank_transfer') {
            return back()->with('error', 'Payment proof is only for bank transfer orders.');
        }

        if ($order->payment_status === 'paid') {
            return back()->with('error', 'This order has already been paid.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $file = $request->file('payment_proof');
        $filename = 'payment_' . $order->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/payments'), $filename);

        $order->payment->update([
            'proof_image' => $filename,
            'status' => 'pending',
        ]);

        $order->update(['payment_status' => 'pending_verification']);

        return back()->with('success', 'Payment proof uploaded successfully! Please wait for admin verification.');
    }

    public function cancel($id)
    {
        $order = auth()->user()->orders()->findOrFail($id);

        if (!$order->isPending()) {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        // Restore stock
        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        return back()->with('success', 'Order cancelled successfully.');
    }
}
