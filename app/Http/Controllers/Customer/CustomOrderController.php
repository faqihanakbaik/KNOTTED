<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CustomOrder;
use App\Models\CustomOffer;
use App\Models\CustomOrderImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomOrderController extends Controller
{
    public function index()
    {
        $customOrders = Auth::user()->customOrders()
            ->with(['category', 'images', 'offers'])
            ->latest()
            ->get();

        return view('customer.custom-orders.index', compact('customOrders'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('customer.custom-orders.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_type' => 'required|in:modify_existing,new_design',
            'product_name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'required|string|max:2000',
            'color' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
            'customer_expected_price' => 'nullable|numeric|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $validated['user_id'] = Auth::id();
            $validated['status'] = 'pending';

            $customOrder = CustomOrder::create($validated);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('custom-orders', 'public');
                    
                    CustomOrderImage::create([
                        'custom_order_id' => $customOrder->id,
                        'image_path' => $path,
                        'storage_path' => $path,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('customer.custom-orders.show', $customOrder)
                ->with('success', 'Custom order request submitted successfully! We will review it soon.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to submit custom order. Please try again.');
        }
    }

    public function show(CustomOrder $customOrder)
    {
        // Ensure user owns this custom order
        if ($customOrder->user_id !== Auth::id()) {
            abort(403);
        }

        $customOrder->load(['category', 'images', 'offers.offeredBy']);

        return view('customer.custom-orders.show', compact('customOrder'));
    }

    public function makeOffer(Request $request, CustomOrder $customOrder)
    {
        // Ensure user owns this custom order
        if ($customOrder->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if negotiation is still possible
        if (!$customOrder->canNegotiate()) {
            return back()->with('error', 'Cannot make offer on this custom order.');
        }

        $validated = $request->validate([
            'offered_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Mark previous pending offers as countered
            $customOrder->offers()
                ->where('status', 'pending')
                ->update(['status' => 'countered']);

            // Create new offer
            CustomOffer::create([
                'custom_order_id' => $customOrder->id,
                'offered_by' => Auth::id(),
                'offered_by_role' => 'customer',
                'offered_price' => $validated['offered_price'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
            ]);

            // Update custom order status
            $customOrder->update([
                'status' => 'negotiating',
            ]);

            DB::commit();

            return back()->with('success', 'Your offer has been sent to the admin!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit offer. Please try again.');
        }
    }

    public function acceptOffer(CustomOrder $customOrder, CustomOffer $offer)
    {
        // Ensure user owns this custom order
        if ($customOrder->user_id !== Auth::id()) {
            abort(403);
        }

        // Ensure offer belongs to this custom order
        if ($offer->custom_order_id !== $customOrder->id) {
            abort(403);
        }

        // Ensure offer is from admin and is pending
        if (!$offer->isFromAdmin() || !$offer->isPending()) {
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

            return back()->with('success', 'Offer accepted! You can now proceed to checkout.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to accept offer. Please try again.');
        }
    }

    public function rejectOffer(CustomOrder $customOrder, CustomOffer $offer)
    {
        // Ensure user owns this custom order
        if ($customOrder->user_id !== Auth::id()) {
            abort(403);
        }

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

    public function cancel(CustomOrder $customOrder)
    {
        // Ensure user owns this custom order
        if ($customOrder->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow cancellation if not yet converted
        if ($customOrder->order_id) {
            return back()->with('error', 'Cannot cancel custom order that has been converted to an order.');
        }

        $customOrder->update(['status' => 'cancelled']);

        return redirect()->route('customer.custom-orders.index')
            ->with('success', 'Custom order cancelled.');
    }
}
