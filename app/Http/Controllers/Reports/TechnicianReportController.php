<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\RepairJob;
use App\Models\User;
use Illuminate\Http\Request;

class TechnicianReportController extends Controller
{
    public function index(Request $request)
    {
        $branchId = session('active_branch_id');
        $from = $request->from ?? today()->startOfMonth()->toDateString();
        $to = $request->to ?? today()->toDateString();

        $technicians = User::where('role', 'technician')
            ->where('branch_id', $branchId)
            ->withCount(['repairJobs as jobs_count' => fn($q) => $q->whereBetween('received_date', [$from, $to])])
            ->get();

        return view('reports.technician', compact('technicians', 'from', 'to'));
    }
}
