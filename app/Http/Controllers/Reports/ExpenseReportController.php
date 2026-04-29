<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseReportController extends Controller
{
    public function index(Request $request)
    {
        $branchId = session('active_branch_id');
        $from = $request->from ?? today()->startOfMonth()->toDateString();
        $to = $request->to ?? today()->toDateString();

        $expenseCategories = ExpenseCategory::orderBy('name')->pluck('name');

        $allExpenses = Expense::with(['user', 'category'])
            ->where('branch_id', $branchId)
            ->whereBetween('expense_date', [$from, $to])
            ->when($request->category, fn($q) => $q->whereHas('category', fn($q2) => $q2->where('name', $request->category)))
            ->latest('expense_date')
            ->get();

        $summary = [
            'total' => $allExpenses->sum('amount'),
            'count' => $allExpenses->count(),
            'avg'   => $allExpenses->count() > 0 ? $allExpenses->sum('amount') / $allExpenses->count() : 0,
        ];

        $byCategory = $allExpenses->groupBy(fn($e) => $e->category?->name ?? 'Uncategorised')
            ->map(fn($g) => $g->sum('amount'));

        $expenses = Expense::with(['user', 'category'])
            ->where('branch_id', $branchId)
            ->whereBetween('expense_date', [$from, $to])
            ->when($request->category, fn($q) => $q->whereHas('category', fn($q2) => $q2->where('name', $request->category)))
            ->latest('expense_date')
            ->paginate(25)
            ->withQueryString();

        return view('reports.expenses', compact('expenses', 'expenseCategories', 'summary', 'byCategory', 'from', 'to'));
    }
}
