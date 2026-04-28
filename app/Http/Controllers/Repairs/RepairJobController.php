<?php

namespace App\Http\Controllers\Repairs;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\RepairJob;
use App\Models\RepairJobHistory;
use App\Models\User;
use App\Services\NumberGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepairJobController extends Controller
{
    public function index(Request $request)
    {
        $branchId = session('active_branch_id');

        if ($request->view === 'kanban') {
            $statuses = ['received', 'diagnosing', 'waiting_parts', 'in_repair', 'ready', 'delivered'];
            $jobs = RepairJob::with(['customer', 'technician'])
                ->where('branch_id', $branchId)
                ->whereNotIn('status', ['cancelled'])
                ->get()
                ->groupBy('status');
            return view('repairs.kanban', compact('jobs', 'statuses'));
        }

        $repairs = RepairJob::with(['customer', 'technician'])
            ->where('branch_id', $branchId)
            ->when($request->search, fn($q) => $q->where('job_number', 'like', "%{$request->search}%")
                ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', "%{$request->search}%")))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('repairs.index', compact('repairs'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $technicians = User::where('role', 'technician')->where('is_active', true)->orderBy('name')->get();
        return view('repairs.create', compact('customers', 'technicians'));
    }

    public function store(Request $request, NumberGeneratorService $numbers)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'technician_id' => 'nullable|exists:users,id',
            'device_type' => 'required|string|max:100',
            'device_brand' => 'nullable|string|max:100',
            'device_model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:200',
            'reported_issue' => 'required|string',
            'priority' => 'in:low,normal,high,urgent',
            'estimated_cost' => 'nullable|numeric|min:0',
            'received_date' => 'required|date',
            'promised_date' => 'nullable|date|after_or_equal:received_date',
            'notes' => 'nullable|string',
        ]);

        $data['branch_id'] = session('active_branch_id');
        $data['created_by'] = Auth::id();
        $data['job_number'] = $numbers->nextRepairNumber();
        $data['status'] = 'received';

        $job = RepairJob::create($data);

        RepairJobHistory::create([
            'repair_job_id' => $job->id,
            'user_id' => Auth::id(),
            'from_status' => null,
            'to_status' => 'received',
            'notes' => 'Job created',
        ]);

        return redirect()->route('repairs.show', $job)->with('success', 'Repair job created.');
    }

    public function show(RepairJob $repair)
    {
        $repair->load(['customer', 'technician', 'creator', 'history.user', 'parts.product', 'invoice']);
        return view('repairs.show', compact('repair'));
    }

    public function edit(RepairJob $repair)
    {
        $customers = Customer::orderBy('name')->get();
        $technicians = User::where('role', 'technician')->where('is_active', true)->orderBy('name')->get();
        return view('repairs.edit', compact('repair', 'customers', 'technicians'));
    }

    public function update(Request $request, RepairJob $repair)
    {
        $data = $request->validate([
            'technician_id' => 'nullable|exists:users,id',
            'device_brand' => 'nullable|string|max:100',
            'device_model' => 'nullable|string|max:100',
            'diagnosis' => 'nullable|string',
            'estimated_cost' => 'nullable|numeric|min:0',
            'final_cost' => 'nullable|numeric|min:0',
            'priority' => 'in:low,normal,high,urgent',
            'promised_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $repair->update($data);
        return redirect()->route('repairs.show', $repair)->with('success', 'Repair job updated.');
    }

    public function destroy(RepairJob $repair)
    {
        $repair->update(['status' => 'cancelled']);
        return redirect()->route('repairs.index')->with('success', 'Repair job cancelled.');
    }

    public function generateInvoice(RepairJob $repair, NumberGeneratorService $numbers)
    {
        // Redirect to POS with repair pre-filled
        return redirect()->route('repairs.show', $repair)->with('info', 'Use the invoice button on the repair page.');
    }
}
