<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function upload($orderId)
    {
        $order = Order::where('user_id', auth()->id())
            ->with(['items.product', 'payment'])
            ->findOrFail($orderId);

        // Only allow upload if payment status is pending or rejected
        if (!in_array($order->payment_status, ['pending', 'rejected'])) {
            return redirect()->route('customer.orders.show', $order->id)
                ->with('error', 'Payment proof can only be uploaded for pending or rejected payments.');
        }

        return view('customer.payments.upload', compact('order'));
    }

    public function store(Request $request, $orderId)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($orderId);

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
            'payment_date' => 'required|date',
            'payment_notes' => 'nullable|string|max:500',
        ]);

        try {
            // Upload to Supabase Storage or local storage
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                
                // Check if Supabase is configured
                if (env('SUPABASE_URL') && env('SUPABASE_KEY')) {
                    // Upload to Supabase
                    $fileName = 'payments/' . time() . '_' . $order->order_number . '.' . $file->getClientOriginalExtension();
                    
                    $supabaseUrl = env('SUPABASE_URL');
                    $supabaseKey = env('SUPABASE_KEY');
                    $bucketName = env('SUPABASE_BUCKET', 'knotted-storage');
                    
                    $fileContent = file_get_contents($file->getRealPath());
                    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "$supabaseUrl/storage/v1/object/$bucketName/$fileName");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContent);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $supabaseKey,
                        'Content-Type: ' . $file->getMimeType(),
                    ]);
                    
                    $response = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    
                    if ($httpCode === 200) {
                        $paymentProofUrl = "$supabaseUrl/storage/v1/object/public/$bucketName/$fileName";
                    } else {
                        // Fallback to local storage
                        $path = $file->store('payment_proofs', 'public');
                        $paymentProofUrl = Storage::url($path);
                    }
                } else {
                    // Use local storage
                    $path = $file->store('payment_proofs', 'public');
                    $paymentProofUrl = Storage::url($path);
                }

                // Update payment record
                $payment = Payment::where('order_id', $order->id)->first();
                $payment->update([
                    'payment_proof' => $paymentProofUrl,
                    'payment_date' => $request->payment_date,
                    'notes' => $request->payment_notes,
                    'status' => 'under_review',
                ]);

                // Update order payment status
                $order->update([
                    'payment_status' => 'under_review',
                ]);

                return redirect()->route('customer.orders.show', $order->id)
                    ->with('success', 'Payment proof uploaded successfully! Please wait for admin verification.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to upload payment proof. Please try again. Error: ' . $e->getMessage());
        }

        return back()->with('error', 'No file uploaded.');
    }
}
