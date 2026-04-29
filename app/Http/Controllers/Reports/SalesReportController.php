<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $branchId = session('active_branch_id');
        $from = $request->from ?? today()->startOfMonth()->toDateString();
        $to = $request->to ?? today()->toDateString();

        $invoices = Invoice::with(['customer', 'payments'])
            ->where('branch_id', $branchId)
            ->whereIn('status', ['issued', 'paid', 'partial'])
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->latest()
            ->get();

        $totalRevenue = $invoices->sum('total');
        $totalInvoices = $invoices->count();

        $paymentBreakdown = $invoices->flatMap->payments->groupBy('method')->map(fn($g) => $g->sum('amount'));

        $group = $request->group ?? 'day';

        $rows = match ($group) {
            'month' => $invoices->groupBy(fn($inv) => $inv->created_at->format('Y-m'))
                ->map(fn($g, $k) => [
                    'period'  => \Carbon\Carbon::parse($k . '-01')->format('M Y'),
                    'count'   => $g->count(),
                    'revenue' => $g->sum('total'),
                    'avg'     => $g->count() > 0 ? $g->sum('total') / $g->count() : 0,
                ])->values()->toArray(),
            'week' => $invoices->groupBy(fn($inv) => $inv->created_at->startOfWeek()->toDateString())
                ->map(fn($g, $k) => [
                    'period'  => 'Week of ' . \Carbon\Carbon::parse($k)->format('d M Y'),
                    'count'   => $g->count(),
                    'revenue' => $g->sum('total'),
                    'avg'     => $g->count() > 0 ? $g->sum('total') / $g->count() : 0,
                ])->values()->toArray(),
            default => $invoices->groupBy(fn($inv) => $inv->created_at->toDateString())
                ->map(fn($g, $k) => [
                    'period'  => \Carbon\Carbon::parse($k)->format('d M Y'),
                    'count'   => $g->count(),
                    'revenue' => $g->sum('total'),
                    'avg'     => $g->count() > 0 ? $g->sum('total') / $g->count() : 0,
                ])->values()->toArray(),
        };

        $refunds = Invoice::where('branch_id', $branchId)
            ->where('status', 'refunded')
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->sum('total');

        $summary = [
            'revenue' => $totalRevenue,
            'count'   => $totalInvoices,
            'avg'     => $totalInvoices > 0 ? $totalRevenue / $totalInvoices : 0,
            'refunds' => $refunds,
        ];

        return view('reports.sales', compact('summary', 'paymentBreakdown', 'rows', 'from', 'to'));
    }

    public function export(Request $request)
    {
        // CSV export
        $branchId = session('active_branch_id');
        $from = $request->from ?? today()->startOfMonth()->toDateString();
        $to = $request->to ?? today()->toDateString();

        $invoices = Invoice::with(['customer'])
            ->where('branch_id', $branchId)
            ->whereIn('status', ['issued', 'paid', 'partial'])
            ->whereBetween('created_at', [$from, $to])
            ->get();

        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="sales_report.csv"'];

        $callback = function () use ($invoices) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Invoice#', 'Date', 'Customer', 'Total', 'Status']);
            foreach ($invoices as $inv) {
                fputcsv($file, [$inv->invoice_number, $inv->created_at->toDateString(), $inv->customer_display_name, $inv->total, $inv->status]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
