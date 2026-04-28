<?php

namespace App\Http\Controllers\Repairs;

use App\Http\Controllers\Controller;
use App\Models\RepairJob;
use App\Models\RepairJobHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepairJobStatusController extends Controller
{
    public function update(Request $request, RepairJob $repair)
    {
        $data = $request->validate([
            'status' => 'required|in:received,diagnosing,waiting_parts,in_repair,ready,delivered,cancelled',
            'notes' => 'nullable|string',
        ]);

        $from = $repair->status;
        $repair->update(['status' => $data['status']]);

        RepairJobHistory::create([
            'repair_job_id' => $repair->id,
            'user_id' => Auth::id(),
            'from_status' => $from,
            'to_status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);

        return back()->with('success', 'Status updated to ' . ucfirst(str_replace('_', ' ', $data['status'])) . '.');
    }
}
