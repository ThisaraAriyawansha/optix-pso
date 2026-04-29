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

        $stockItems = Stock::with(['product.category', 'variant'])
            ->where('branch_id', $branchId)
            ->get();

        $totalValue = $stockItems->sum(fn($s) => $s->qty_on_hand * ($s->product->cost_price ?? 0));
        $totalItems = $stockItems->count();
        $lowStockCount = $stockItems->filter->isLow()->count();

        return view('reports.stock', compact('stockItems', 'categories', 'totalValue', 'totalItems', 'lowStockCount'));
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
