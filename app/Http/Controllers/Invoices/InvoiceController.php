<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $branchId = session('active_branch_id');

        $invoices = Invoice::with(['customer', 'cashier'])
            ->where('branch_id', $branchId)
            ->when($request->search, fn($q) => $q->where('invoice_number', 'like', "%{$request->search}%")
                ->orWhere('customer_name', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['items.product', 'items.variant', 'customer', 'cashier', 'payments', 'branch']);
        return view('invoices.show', compact('invoice'));
    }

    public function recordPayment(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'method' => 'required|in:cash,card,mobile_pay,bank_transfer,loyalty_points,credit',
            'amount' => 'required|numeric|min:0.01',
            'reference' => 'nullable|string|max:200',
            'notes' => 'nullable|string',
        ]);

        Payment::create([
            'invoice_id' => $invoice->id,
            'cashier_id' => Auth::id(),
            'method' => $data['method'],
            'amount' => $data['amount'],
            'reference' => $data['reference'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        $totalPaid = $invoice->payments()->sum('amount');
        $invoice->update([
            'amount_paid' => $totalPaid,
            'amount_due' => max(0, $invoice->total - $totalPaid),
            'status' => $totalPaid >= $invoice->total ? 'paid' : 'partial',
        ]);

        return back()->with('success', 'Payment recorded successfully.');
    }

    public function refund(Invoice $invoice, StockService $stockService)
    {
        if (!in_array($invoice->status, ['issued', 'paid', 'partial'])) {
            return back()->with('error', 'This invoice cannot be refunded.');
        }

        $branchId = $invoice->branch_id;

        foreach ($invoice->items as $item) {
            if ($item->product_id) {
                $stockService->adjust($branchId, $item->product_id, $item->variant_id, $item->qty, 'return', $invoice->invoice_number, 'Refund');
            }
        }

        $invoice->update(['status' => 'refunded']);

        return back()->with('success', 'Invoice refunded and stock restored.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->update(['status' => 'cancelled']);
        return redirect()->route('invoices.index')->with('success', 'Invoice cancelled.');
    }
}
