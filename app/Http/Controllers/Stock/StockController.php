<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $branchId = session('active_branch_id');

        $categories = Category::orderBy('name')->get();

        $stocks = Stock::with(['product.category', 'variant'])
            ->where('branch_id', $branchId)
            ->when($request->search, fn($q) => $q->whereHas('product', fn($q2) => $q2->where('name', 'like', "%{$request->search}%")
                ->orWhere('sku', 'like', "%{$request->search}%")))
            ->when($request->category, fn($q) => $q->whereHas('product', fn($q2) => $q2->where('category_id', $request->category)))
            ->when($request->low_stock, fn($q) => $q->whereColumn('qty_on_hand', '<=', 'min_qty'))
            ->orderByRaw('qty_on_hand <= min_qty DESC')
            ->paginate(25)
            ->withQueryString();

        return view('stock.index', compact('stocks', 'categories'));
    }

    public function adjust(Request $request, StockService $stockService)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'qty_change' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        $stockService->adjust(
            session('active_branch_id'),
            $data['product_id'],
            $data['variant_id'] ?? null,
            $data['qty_change'],
            'adjustment',
            '',
            $data['notes'] ?? ''
        );

        return back()->with('success', 'Stock adjusted successfully.');
    }
}
