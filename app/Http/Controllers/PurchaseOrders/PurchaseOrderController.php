<?php

namespace App\Http\Controllers\PurchaseOrders;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Services\NumberGeneratorService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $pos = PurchaseOrder::with(['supplier', 'creator'])
            ->where('branch_id', session('active_branch_id'))
            ->latest()
            ->paginate(20);
        return view('purchase-orders.index', compact('pos'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('purchase-orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request, NumberGeneratorService $numbers)
    {
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty_ordered' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($data, $numbers) {
            $subtotal = collect($data['items'])->sum(fn($i) => $i['qty_ordered'] * $i['unit_cost']);

            $po = PurchaseOrder::create([
                'supplier_id' => $data['supplier_id'],
                'branch_id' => session('active_branch_id'),
                'created_by' => Auth::id(),
                'po_number' => $numbers->nextPoNumber(),
                'status' => 'ordered',
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'total' => $subtotal,
                'expected_date' => $data['expected_date'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                PurchaseOrderItem::create([
                    'po_id' => $po->id,
                    'product_id' => $item['product_id'],
                    'qty_ordered' => $item['qty_ordered'],
                    'qty_received' => 0,
                    'unit_cost' => $item['unit_cost'],
                    'line_total' => $item['qty_ordered'] * $item['unit_cost'],
                ]);
            }

            $this->po = $po;
        });

        return redirect()->route('purchase-orders.show', $this->po ?? PurchaseOrder::latest()->first())->with('success', 'PO created.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'creator', 'items.product']);
        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        return redirect()->route('purchase-orders.show', $purchaseOrder);
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        return redirect()->route('purchase-orders.show', $purchaseOrder);
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->update(['status' => 'cancelled']);
        return redirect()->route('purchase-orders.index')->with('success', 'PO cancelled.');
    }

    public function receive(Request $request, PurchaseOrder $purchaseOrder, StockService $stockService)
    {
        $branchId = session('active_branch_id');

        foreach ($purchaseOrder->items as $item) {
            $stockService->adjust($branchId, $item->product_id, $item->variant_id, $item->qty_ordered, 'purchase', $purchaseOrder->po_number);
            $item->update(['qty_received' => $item->qty_ordered]);
        }

        $purchaseOrder->update(['status' => 'received', 'received_date' => today()]);
        return back()->with('success', 'PO received and stock updated.');
    }
}
