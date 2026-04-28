<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $branchId = session('active_branch_id');

        $movements = StockMovement::with(['product', 'variant', 'user'])
            ->where('branch_id', $branchId)
            ->when($request->product, fn($q) => $q->where('product_id', $request->product))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view('stock.movements', compact('movements'));
    }
}
