<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockReportController extends Controller
{
    public function index(Request $request)
    {
        $branchId = session('active_branch_id');

        $categories = Category::orderBy('name')->get();

        $stocks = Stock::with(['product.category', 'variant'])
            ->where('branch_id', $branchId)
            ->when($request->category, fn($q) => $q->whereHas('product', fn($q2) => $q2->where('category_id', $request->category)))
            ->when($request->filter === 'low', fn($q) => $q->whereColumn('qty_on_hand', '<=', 'min_qty')->where('qty_on_hand', '>', 0))
            ->when($request->filter === 'out', fn($q) => $q->where('qty_on_hand', '<=', 0))
            ->get();

        $summary = [
            'total_products' => $stocks->count(),
            'low_stock'      => $stocks->filter->isLow()->count(),
            'out_of_stock'   => $stocks->filter(fn($s) => $s->qty_on_hand <= 0)->count(),
            'stock_value'    => $stocks->sum(fn($s) => $s->qty_on_hand * ($s->product->cost_price ?? 0)),
        ];

        return view('reports.stock', compact('stocks', 'categories', 'summary'));
    }

    public function export(Request $request)
    {
        $branchId = session('active_branch_id');
        $items = Stock::with(['product', 'variant'])->where('branch_id', $branchId)->get();

        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="stock_report.csv"'];
        $callback = function () use ($items) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Product', 'SKU', 'Qty', 'Min Qty', 'Status', 'Cost Value']);
            foreach ($items as $item) {
                fputcsv($file, [
                    $item->product->name,
                    $item->product->sku ?? '',
                    $item->qty_on_hand,
                    $item->min_qty,
                    $item->isLow() ? 'Low' : 'OK',
                    number_format($item->qty_on_hand * ($item->product->cost_price ?? 0), 2),
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}
