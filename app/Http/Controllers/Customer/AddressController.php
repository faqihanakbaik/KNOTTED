<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses()->latest()->get();
        return view('customer.addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('customer.addresses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'label' => 'nullable|string|max:50',
            'is_default' => 'boolean',
        ]);

        $validated['user_id'] = Auth::id();

        // If this is set as default, unset other defaults
        if ($request->is_default) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        Address::create($validated);

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Address added successfully!');
    }

    public function edit(Address $address)
    {
        // Ensure user owns this address
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        return view('customer.addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        // Ensure user owns this address
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'label' => 'nullable|string|max:50',
            'is_default' => 'boolean',
        ]);

        // If this is set as default, unset other defaults
        if ($request->is_default) {
            Auth::user()->addresses()->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Address updated successfully!');
    }

    public function destroy(Address $address)
    {
        // Ensure user owns this address
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->delete();

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Address deleted successfully!');
    }

    public function setDefault(Address $address)
    {
        // Ensure user owns this address
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        // Unset all defaults first
        Auth::user()->addresses()->update(['is_default' => false]);

        // Set this as default
        $address->update(['is_default' => true]);

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Default address updated!');
    }
}
