<?php

namespace App\Http\Controllers\Loyalty;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->where('loyalty_points', '>', 0)
            ->orderByDesc('loyalty_points')
            ->paginate(20)
            ->withQueryString();
        return view('loyalty.index', compact('customers'));
    }

    public function adjust(Request $request, Customer $customer, LoyaltyService $loyaltyService)
    {
        $data = $request->validate([
            'points' => 'required|integer|not_in:0',
            'notes' => 'required|string|max:255',
        ]);
        $loyaltyService->adjust($customer, $data['points'], $data['notes']);
        return back()->with('success', 'Loyalty points adjusted.');
    }
}
