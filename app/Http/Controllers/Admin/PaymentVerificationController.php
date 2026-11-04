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
        $pendingPayments = Payment::with('order.user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.payments.index', compact('pendingPayments'));
    }

    public function show($id)
    {
        $payment = Payment::with('order.user')->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    public function verify(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Payment has already been processed.');
        }

        $payment->update([
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        $payment->order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);

        return back()->with('success', 'Payment verified successfully!');
    }

    public function reject(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Payment has already been processed.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $payment->update([
            'status' => 'failed',
            'notes' => $validated['notes'] ?? 'Payment rejected by admin',
            'verified_by' => auth()->id(),
        ]);

        $payment->order->update([
            'payment_status' => 'unpaid',
        ]);

        return back()->with('success', 'Payment rejected.');
    }
}
