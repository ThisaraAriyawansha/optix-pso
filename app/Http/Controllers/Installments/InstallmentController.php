<?php

namespace App\Http\Controllers\Installments;

use App\Http\Controllers\Controller;
use App\Models\Installment;
use App\Models\InstallmentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstallmentController extends Controller
{
    public function index(Request $request)
    {
        $installments = Installment::with(['customer', 'invoice'])
            ->when($request->search, fn($q) => $q->whereHas('customer', fn($q2) => $q2->where('name', 'like', "%{$request->search}%")))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20)
            ->withQueryString();
        return view('installments.index', compact('installments'));
    }

    public function show(Installment $installment)
    {
        $installment->load(['customer', 'invoice', 'payments.cashier']);
        return view('installments.show', compact('installment'));
    }

    public function recordPayment(Request $request, Installment $installment)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:cash,card,mobile_pay,bank_transfer',
            'paid_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        InstallmentPayment::create([
            'installment_id' => $installment->id,
            'cashier_id' => Auth::id(),
            'amount' => $data['amount'],
            'method' => $data['method'],
            'paid_date' => $data['paid_date'],
            'notes' => $data['notes'] ?? null,
        ]);

        $totalPaid = $installment->payments()->sum('amount');
        $balance = max(0, $installment->total_amount - $totalPaid);

        $installment->update([
            'amount_paid' => $totalPaid,
            'balance' => $balance,
            'status' => $balance <= 0 ? 'completed' : 'active',
        ]);

        return back()->with('success', 'Payment recorded.');
    }
}
