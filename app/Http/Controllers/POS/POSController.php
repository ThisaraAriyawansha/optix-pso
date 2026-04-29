<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\Product;
use App\Services\NumberGeneratorService;
use App\Services\StockService;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        $products = Product::with(['stock' => fn($q) => $q->where('branch_id', session('active_branch_id'))])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $customers = Customer::orderBy('name')->get();

        return view('pos.index', compact('categories', 'products', 'customers'));
    }

    public function completeSale(
        Request $request,
        NumberGeneratorService $numbers,
        StockService $stockService,
        LoyaltyService $loyaltyService
    ) {
        $data = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:150',
            'discount_type' => 'in:fixed,percent',
            'discount_value' => 'numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_pct' => 'numeric|min:0|max:100',
            'payments' => 'required|array|min:1',
            'payments.*.method' => 'required|in:cash,card,mobile_pay,bank_transfer,loyalty_points,credit',
            'payments.*.amount' => 'required|numeric|min:0',
        ]);

        $branchId = session('active_branch_id');

        DB::transaction(function () use ($data, $branchId, $numbers, $stockService, $loyaltyService) {
            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $lineTotal = $item['qty'] * $item['unit_price'] * (1 - ($item['discount_pct'] ?? 0) / 100);
                $subtotal += $lineTotal;
            }

            $discountAmount = ($data['discount_type'] ?? 'fixed') === 'percent'
                ? $subtotal * (($data['discount_value'] ?? 0) / 100)
                : ($data['discount_value'] ?? 0);

            $total = max(0, $subtotal - $discountAmount);
            $amountPaid = collect($data['payments'])->sum('amount');
            $status = $amountPaid >= $total ? 'paid' : 'partial';

            $customer = isset($data['customer_id']) ? \App\Models\Customer::find($data['customer_id']) : null;

            $invoice = Invoice::create([
                'customer_id' => $data['customer_id'] ?? null,
                'branch_id' => $branchId,
                'cashier_id' => Auth::id(),
                'invoice_number' => $numbers->nextInvoiceNumber(),
                'type' => 'sale',
                'status' => $status,
                'customer_name' => $customer?->name ?? ($data['customer_name'] ?? 'Walk-in'),
                'subtotal' => $subtotal,
                'discount_type' => $data['discount_type'] ?? 'fixed',
                'discount_value' => $data['discount_value'] ?? 0,
                'discount_amount' => $discountAmount,
                'tax_amount' => 0,
                'total' => $total,
                'amount_paid' => $amountPaid,
                'amount_due' => max(0, $total - $amountPaid),
            ]);

            foreach ($data['items'] as $i => $item) {
                $product = Product::find($item['product_id']);
                $lineTotal = $item['qty'] * $item['unit_price'] * (1 - ($item['discount_pct'] ?? 0) / 100);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'description' => $product->name . (($item['variant_id'] ?? null) ? ' - variant' : ''),
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'cost_price' => $product->cost_price,
                    'discount_pct' => $item['discount_pct'] ?? 0,
                    'line_total' => $lineTotal,
                    'sort_order' => $i,
                ]);

                $stockService->adjust($branchId, $item['product_id'], $item['variant_id'] ?? null, -$item['qty'], 'sale', $invoice->invoice_number);
            }

            foreach ($data['payments'] as $pmt) {
                if ($pmt['amount'] > 0) {
                    Payment::create([
                        'invoice_id' => $invoice->id,
                        'cashier_id' => Auth::id(),
                        'method' => $pmt['method'],
                        'amount' => $pmt['amount'],
                    ]);
                }
            }

            if ($customer) {
                $loyaltyService->earn($customer, $total, $invoice->id);
            }

            $this->invoiceId = $invoice->id;
        });

        return response()->json(['success' => true, 'invoice_id' => $this->invoiceId ?? null]);
    }
}
