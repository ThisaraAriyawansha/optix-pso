<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseReportController extends Controller
{
    public function index(Request $request)
    {
        $branchId = session('active_branch_id');
        $from = $request->from ?? today()->startOfMonth()->toDateString();
        $to = $request->to ?? today()->toDateString();

        $expenses = Expense::with(['user', 'category'])
            ->where('branch_id', $branchId)
            ->whereBetween('expense_date', [$from, $to])
            ->latest('expense_date')
            ->get();

        $total = $expenses->sum('amount');
        $byCategory = $expenses->groupBy(fn($e) => $e->category?->name ?? 'Uncategorised')
            ->map(fn($g) => $g->sum('amount'));

        return view('reports.expenses', compact('expenses', 'total', 'byCategory', 'from', 'to'));
    }
}
