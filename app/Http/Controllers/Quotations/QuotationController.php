<?php

namespace App\Http\Controllers\Quotations;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Services\NumberGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $branchId = session('active_branch_id');
        $quotations = Quotation::with(['customer', 'creator'])
            ->where('branch_id', $branchId)
            ->when($request->search, fn($q) => $q->where('quote_number', 'like', "%{$request->search}%")
                ->orWhere('customer_name', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('quotations.index', compact('quotations'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('quotations.create', compact('customers', 'products'));
    }

    public function store(Request $request, NumberGeneratorService $numbers)
    {
        $data = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:150',
            'customer_phone' => 'nullable|string|max:20',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string',
            'discount_type' => 'in:fixed,percent',
            'discount_value' => 'numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string|max:300',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_pct' => 'numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($data, $numbers) {
            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $lineTotal = $item['qty'] * $item['unit_price'] * (1 - ($item['discount_pct'] ?? 0) / 100);
                $subtotal += $lineTotal;
            }

            $discountAmount = $data['discount_type'] === 'percent'
                ? $subtotal * ($data['discount_value'] / 100)
                : ($data['discount_value'] ?? 0);

            $total = $subtotal - $discountAmount;

            $quotation = Quotation::create([
                'customer_id' => $data['customer_id'] ?? null,
                'branch_id' => session('active_branch_id'),
                'created_by' => Auth::id(),
                'quote_number' => $numbers->nextQuotationNumber(),
                'status' => 'draft',
                'valid_until' => $data['valid_until'] ?? null,
                'customer_name' => $data['customer_name'] ?? null,
                'customer_phone' => $data['customer_phone'] ?? null,
                'subtotal' => $subtotal,
                'discount_type' => $data['discount_type'] ?? 'fixed',
                'discount_value' => $data['discount_value'] ?? 0,
                'discount_amount' => $discountAmount,
                'tax_amount' => 0,
                'total' => $total,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $i => $item) {
                $lineTotal = $item['qty'] * $item['unit_price'] * (1 - ($item['discount_pct'] ?? 0) / 100);
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $item['product_id'] ?? null,
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'discount_pct' => $item['discount_pct'] ?? 0,
                    'line_total' => $lineTotal,
                    'sort_order' => $i,
                ]);
            }

            $this->quotation = $quotation;
        });

        return redirect()->route('quotations.show', $this->quotation ?? Quotation::latest()->first())
            ->with('success', 'Quotation created.');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['items.product', 'customer', 'creator', 'branch']);
        return view('quotations.show', compact('quotation'));
    }

    public function edit(Quotation $quotation)
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $quotation->load('items.product');
        return view('quotations.edit', compact('quotation', 'customers', 'products'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        $data = $request->validate([
            'status' => 'in:draft,sent,accepted,rejected',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $quotation->update($data);
        return back()->with('success', 'Quotation updated.');
    }

    public function convertToInvoice(Quotation $quotation, NumberGeneratorService $numbers)
    {
        if ($quotation->status === 'converted') {
            return back()->with('error', 'This quotation has already been converted.');
        }

        DB::transaction(function () use ($quotation, $numbers) {
            $invoice = Invoice::create([
                'quotation_id' => $quotation->id,
                'customer_id' => $quotation->customer_id,
                'branch_id' => $quotation->branch_id,
                'cashier_id' => Auth::id(),
                'invoice_number' => $numbers->nextInvoiceNumber(),
                'type' => 'sale',
                'status' => 'issued',
                'customer_name' => $quotation->customer_display_name,
                'subtotal' => $quotation->subtotal,
                'discount_type' => $quotation->discount_type,
                'discount_value' => $quotation->discount_value,
                'discount_amount' => $quotation->discount_amount,
                'tax_amount' => $quotation->tax_amount,
                'total' => $quotation->total,
                'amount_paid' => 0,
                'amount_due' => $quotation->total,
                'notes' => $quotation->notes,
            ]);

            foreach ($quotation->items as $item) {
                $invoice->items()->create([
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'description' => $item->description,
                    'qty' => $item->qty,
                    'unit_price' => $item->unit_price,
                    'cost_price' => $item->product?->cost_price ?? 0,
                    'discount_pct' => $item->discount_pct,
                    'line_total' => $item->line_total,
                    'sort_order' => $item->sort_order,
                ]);
            }

            $quotation->update(['status' => 'converted']);
        });

        return redirect()->route('invoices.index')->with('success', 'Quotation converted to invoice.');
    }

    public function destroy(Quotation $quotation)
    {
        $quotation->delete();
        return redirect()->route('quotations.index')->with('success', 'Quotation deleted.');
    }
}
