<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\Request;

class StoreSettingController extends Controller
{
    public function index()
    {
        $settings = [
            'bank_name' => StoreSetting::get('bank_name', 'BCA'),
            'bank_account_number' => StoreSetting::get('bank_account_number', ''),
            'bank_account_name' => StoreSetting::get('bank_account_name', 'KNOTTED'),
            'store_email' => StoreSetting::get('store_email', 'hello@knotted.com'),
            'store_phone' => StoreSetting::get('store_phone', ''),
            'store_address' => StoreSetting::get('store_address', ''),
            'store_description' => StoreSetting::get('store_description', ''),
            'instagram_url' => StoreSetting::get('instagram_url', ''),
            'tiktok_url' => StoreSetting::get('tiktok_url', ''),
            'whatsapp_number' => StoreSetting::get('whatsapp_number', ''),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name' => 'required|string|max:255',
            'store_email' => 'required|email|max:255',
            'store_phone' => 'required|string|max:20',
            'store_address' => 'nullable|string|max:500',
            'store_description' => 'nullable|string|max:1000',
            'instagram_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
        ]);

        foreach ($validated as $key => $value) {
            StoreSetting::set($key, $value, 'text');
        }

        return back()->with('success', 'Store settings updated successfully!');
    }
}
