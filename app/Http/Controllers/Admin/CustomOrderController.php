<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use App\Models\CustomOffer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomOrder::with(['user', 'category', 'images']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $customOrders = $query->latest()->paginate(20);

        $statusCounts = [
            'all' => CustomOrder::count(),
            'pending' => CustomOrder::where('status', 'pending')->count(),
            'negotiating' => CustomOrder::whereIn('status', ['quoted', 'negotiating'])->count(),
            'accepted' => CustomOrder::where('status', 'accepted')->count(),
        ];

        return view('admin.custom-orders.index', compact('customOrders', 'statusCounts'));
    }

    public function show(CustomOrder $customOrder)
    {
        $customOrder->load(['user', 'category', 'images', 'offers.offeredBy', 'order']);

        return view('admin.custom-orders.show', compact('customOrder'));
    }

    public function quotePrice(Request $request, CustomOrder $customOrder)
    {
        $validated = $request->validate([
            'quoted_price' => 'required|numeric|min:0',
            'estimated_days' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Mark previous pending offers as countered
            $customOrder->offers()
                ->where('status', 'pending')
                ->update(['status' => 'countered']);

            // Create admin offer
            CustomOffer::create([
                'custom_order_id' => $customOrder->id,
                'offered_by' => Auth::id(),
                'offered_by_role' => 'admin',
                'offered_price' => $validated['quoted_price'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
            ]);

            // Update custom order
            $customOrder->update([
                'admin_quoted_price' => $validated['quoted_price'],
                'estimated_days' => $validated['estimated_days'] ?? null,
                'status' => 'quoted',
            ]);

            DB::commit();

            return back()->with('success', 'Price quote sent to customer!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to send quote. Please try again.');
        }
    }

    public function acceptOffer(CustomOrder $customOrder, CustomOffer $offer)
    {
        // Ensure offer belongs to this custom order
        if ($offer->custom_order_id !== $customOrder->id) {
            abort(403);
        }

        // Ensure offer is from customer and is pending
        if (!$offer->isFromCustomer() || !$offer->isPending()) {
            return back()->with('error', 'Cannot accept this offer.');
        }

        DB::beginTransaction();
        try {
            // Accept the offer
            $offer->update(['status' => 'accepted']);

            // Update custom order
            $customOrder->update([
                'status' => 'accepted',
                'final_agreed_price' => $offer->offered_price,
            ]);

            // Mark other offers as withdrawn
            $customOrder->offers()
                ->where('id', '!=', $offer->id)
                ->where('status', 'pending')
                ->update(['status' => 'withdrawn']);

            DB::commit();

            return back()->with('success', 'Customer offer accepted!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to accept offer. Please try again.');
        }
    }

    public function rejectOffer(CustomOrder $customOrder, CustomOffer $offer)
    {
        // Ensure offer belongs to this custom order
        if ($offer->custom_order_id !== $customOrder->id) {
            abort(403);
        }

        // Ensure offer is pending
        if (!$offer->isPending()) {
            return back()->with('error', 'Cannot reject this offer.');
        }

        $offer->update(['status' => 'rejected']);

        return back()->with('success', 'Offer rejected.');
    }

    public function reject(CustomOrder $customOrder, Request $request)
    {
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $customOrder->update([
            'status' => 'rejected',
            'notes' => ($customOrder->notes ? $customOrder->notes . "\n\n" : '') . 
                       'Rejected: ' . ($validated['rejection_reason'] ?? 'No reason provided'),
        ]);

        return back()->with('success', 'Custom order rejected.');
    }

    public function convertToOrder(CustomOrder $customOrder)
    {
        // Ensure custom order is accepted
        if (!$customOrder->isAccepted()) {
            return back()->with('error', 'Can only convert accepted custom orders.');
        }

        // Check if already converted
        if ($customOrder->order_id) {
            return back()->with('error', 'This custom order has already been converted to an order.');
        }

        DB::beginTransaction();
        try {
            // Create order
            $order = Order::create([
                'user_id' => $customOrder->user_id,
                'order_number' => 'CO-' . strtoupper(uniqid()),
                'total_amount' => $customOrder->final_agreed_price,
                'status' => 'pending',
                'shipping_address' => 'Custom Order - Address to be confirmed',
                'payment_method' => null, // Customer will choose during checkout
            ]);

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => null, // Custom order doesn't have product_id
                'product_name' => $customOrder->product_name . ' (Custom)',
                'quantity' => $customOrder->quantity,
                'price' => $customOrder->final_agreed_price / $customOrder->quantity,
                'subtotal' => $customOrder->final_agreed_price,
            ]);

            // Link custom order to order
            $customOrder->update([
                'order_id' => $order->id,
                'status' => 'converted',
            ]);

            DB::commit();

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Custom order converted to regular order successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to convert custom order. Please try again.');
        }
    }
}
