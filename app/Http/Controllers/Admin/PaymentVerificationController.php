<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;

class PaymentVerificationController extends Controller
{
    public function index()
    {
        // Get payments that need review (under_review status)
        $pendingPayments = Payment::with(['order.user', 'order.items.product'])
            ->where('status', 'under_review')
            ->latest()
            ->paginate(20);

        // Get all payments for history
        $allPayments = Payment::with(['order.user'])
            ->whereIn('status', ['paid', 'rejected'])
            ->latest()
            ->paginate(20);

        return view('admin.payments.index', compact('pendingPayments', 'allPayments'));
    }

    public function show($id)
    {
        $payment = Payment::with('order.user')->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    public function verify(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        if (!in_array($payment->status, ['pending', 'under_review'])) {
            return back()->with('error', 'Payment has already been processed.');
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $payment->update([
            'status' => 'paid',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
            'admin_notes' => $validated['admin_notes'] ?? 'Payment verified by admin',
        ]);

        $payment->order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment verified successfully! Order status updated to Processing.');
    }

    public function reject(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        if (!in_array($payment->status, ['pending', 'under_review'])) {
            return back()->with('error', 'Payment has already been processed.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $payment->update([
            'status' => 'rejected',
            'admin_notes' => $validated['rejection_reason'],
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $payment->order->update([
            'payment_status' => 'rejected',
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment rejected. Customer can re-upload payment proof.');
    }
}
