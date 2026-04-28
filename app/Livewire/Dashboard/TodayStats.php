<?php

namespace App\Livewire\Dashboard;

use App\Models\Invoice;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TodayStats extends Component
{
    public int $salesCount = 0;
    public string $salesTotal = 'Rs. 0';

    public function mount(): void
    {
        $branchId = session('active_branch_id');
        $today = today();

        $invoices = Invoice::where('branch_id', $branchId)
            ->whereDate('created_at', $today)
            ->whereIn('status', ['issued', 'paid', 'partial'])
            ->selectRaw('count(*) as cnt, sum(total) as total')
            ->first();

        $this->salesCount = $invoices->cnt ?? 0;
        $this->salesTotal = 'Rs. ' . number_format($invoices->total ?? 0, 2);
    }

    public function render()
    {
        return view('livewire.dashboard.today-stats');
    }
}
