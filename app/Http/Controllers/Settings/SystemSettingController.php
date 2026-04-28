<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    private array $keys = [
        'shop_name', 'shop_address', 'shop_phone', 'shop_email',
        'currency', 'tax_rate', 'invoice_prefix', 'quotation_prefix', 'repair_prefix',
        'loyalty_points_per_unit', 'low_stock_threshold', 'invoice_footer',
    ];

    public function index()
    {
        $settings = [];
        foreach ($this->keys as $key) {
            $settings[$key] = Setting::get($key, '');
        }
        return view('settings.system', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'shop_name' => 'required|string|max:150',
            'shop_address' => 'nullable|string',
            'shop_phone' => 'nullable|string|max:20',
            'shop_email' => 'nullable|email',
            'currency' => 'required|string|max:10',
            'tax_rate' => 'numeric|min:0|max:100',
            'invoice_prefix' => 'string|max:10',
            'quotation_prefix' => 'string|max:10',
            'repair_prefix' => 'string|max:10',
            'loyalty_points_per_unit' => 'integer|min:1',
            'low_stock_threshold' => 'integer|min:0',
            'invoice_footer' => 'nullable|string',
        ]);

        foreach ($data as $key => $value) {
            Setting::set($key, $value, 'general');
        }

        return back()->with('success', 'Settings saved successfully.');
    }
}
