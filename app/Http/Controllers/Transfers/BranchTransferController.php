<?php

namespace App\Http\Controllers\Transfers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchTransfer;
use App\Models\BranchTransferItem;
use App\Models\Product;
use App\Services\NumberGeneratorService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BranchTransferController extends Controller
{
    public function index()
    {
        $transfers = BranchTransfer::with(['fromBranch', 'toBranch', 'requestedBy'])
            ->latest()
            ->paginate(20);
        return view('transfers.index', compact('transfers'));
    }

    public function create()
    {
        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('transfers.create', compact('branches', 'products'));
    }

    public function store(Request $request, NumberGeneratorService $numbers)
    {
        $data = $request->validate([
            'from_branch_id' => 'required|exists:branches,id',
            'to_branch_id' => 'required|exists:branches,id|different:from_branch_id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty_requested' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($data, $numbers) {
            $transfer = BranchTransfer::create([
                'from_branch_id' => $data['from_branch_id'],
                'to_branch_id' => $data['to_branch_id'],
                'requested_by' => Auth::id(),
                'transfer_number' => $numbers->nextTransferNumber(),
                'status' => 'pending',
                'requested_date' => today(),
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                BranchTransferItem::create([
                    'transfer_id' => $transfer->id,
                    'product_id' => $item['product_id'],
                    'qty_requested' => $item['qty_requested'],
                    'qty_received' => 0,
                ]);
            }

            $this->transfer = $transfer;
        });

        return redirect()->route('transfers.index')->with('success', 'Transfer request created.');
    }

    public function show(BranchTransfer $transfer)
    {
        $transfer->load(['fromBranch', 'toBranch', 'requestedBy', 'approvedBy', 'items.product']);
        return view('transfers.show', compact('transfer'));
    }

    public function edit(BranchTransfer $transfer)
    {
        return redirect()->route('transfers.show', $transfer);
    }

    public function update(Request $request, BranchTransfer $transfer)
    {
        return redirect()->route('transfers.show', $transfer);
    }

    public function destroy(BranchTransfer $transfer)
    {
        $transfer->update(['status' => 'cancelled']);
        return redirect()->route('transfers.index')->with('success', 'Transfer cancelled.');
    }

    public function approve(BranchTransfer $transfer)
    {
        $transfer->update(['status' => 'approved', 'approved_by' => Auth::id()]);
        return back()->with('success', 'Transfer approved.');
    }

    public function markReceived(BranchTransfer $transfer, StockService $stockService)
    {
        if ($transfer->status !== 'approved') {
            return back()->with('error', 'Transfer must be approved first.');
        }

        foreach ($transfer->items as $item) {
            $stockService->adjust($transfer->from_branch_id, $item->product_id, null, -$item->qty_requested, 'transfer_out', $transfer->transfer_number);
            $stockService->adjust($transfer->to_branch_id, $item->product_id, null, $item->qty_requested, 'transfer_in', $transfer->transfer_number);
            $item->update(['qty_received' => $item->qty_requested]);
        }

        $transfer->update(['status' => 'received', 'received_date' => today()]);
        return back()->with('success', 'Transfer marked as received and stock updated.');
    }
}
