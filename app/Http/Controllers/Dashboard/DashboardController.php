<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\RepairJob;
use App\Models\Stock;
use App\Services\StockService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(StockService $stockService)
    {
        $branchId = session('active_branch_id');
        $today = today();

        $todaySales = Invoice::where('branch_id', $branchId)
            ->whereDate('created_at', $today)
            ->whereIn('status', ['issued', 'paid', 'partial'])
            ->count();

        $todayRevenue = Invoice::where('branch_id', $branchId)
            ->whereDate('created_at', $today)
            ->whereIn('status', ['issued', 'paid', 'partial'])
            ->sum('total');

        $activeRepairs = RepairJob::where('branch_id', $branchId)
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->count();

        $lowStockCount = $stockService->getLowStockItems($branchId)->count();

        $recentInvoices = Invoice::with(['customer', 'cashier'])
            ->where('branch_id', $branchId)
            ->latest()
            ->limit(10)
            ->get();

        $lowStockItems = $stockService->getLowStockItems($branchId)->take(8);

        $activeRepairJobs = RepairJob::with(['customer', 'technician'])
            ->where('branch_id', $branchId)
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->latest()
            ->limit(5)
            ->get();

        // Chart data – last 7 days sales
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $chartLabels[] = $date->format('D d');
            $chartData[] = Invoice::where('branch_id', $branchId)
                ->whereDate('created_at', $date)
                ->whereIn('status', ['issued', 'paid', 'partial'])
                ->sum('total');
        }

        return view('dashboard.index', compact(
            'todaySales', 'todayRevenue', 'activeRepairs', 'lowStockCount',
            'recentInvoices', 'lowStockItems', 'activeRepairJobs',
            'chartLabels', 'chartData'
        ));
    }
}
