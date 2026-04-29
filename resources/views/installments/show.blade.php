<x-layouts.app title="Installment Plan">
    <x-page-header title="Installment Plan" :breadcrumbs="[['label' => 'Installments', 'url' => route('installments.index')], ['label' => $plan->customer->name, 'url' => '#']]">
        <x-slot name="actions">
            @if($plan->status === 'active')
            <button onclick="document.getElementById('payModal').showModal()" class="btn btn-primary btn-sm">Record Payment</button>
            @endif
        </x-slot>
    </x-page-header>

    <div class="p-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-5">
            <x-card class="p-5">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    <div><p class="text-xs text-[#64748B] mb-0.5">Customer</p><p class="font-semibold">{{ $plan->customer->name }}</p></div>
                    <div><p class="text-xs text-[#64748B] mb-0.5">Invoice</p><a href="{{ route('invoices.show', $plan->invoice) }}" class="font-mono text-xs text-[#004080] hover:underline">{{ $plan->invoice->invoice_number }}</a></div>
                    <div><p class="text-xs text-[#64748B] mb-0.5">Status</p><x-badge :status="$plan->status" :label="ucfirst($plan->status)"/></div>
                    <div><p class="text-xs text-[#64748B] mb-0.5">Total Amount</p><p class="font-semibold">Rs. {{ number_format($plan->total_amount, 2) }}</p></div>
                    <div><p class="text-xs text-[#64748B] mb-0.5">Down Payment</p><p class="font-semibold text-green-600">Rs. {{ number_format($plan->down_payment, 2) }}</p></div>
                    <div><p class="text-xs text-[#64748B] mb-0.5">Balance</p><p class="font-semibold text-red-500">Rs. {{ number_format($plan->balance, 2) }}</p></div>
                    <div><p class="text-xs text-[#64748B] mb-0.5">Installments</p><p class="font-semibold">{{ $plan->installments_count }}</p></div>
                    <div><p class="text-xs text-[#64748B] mb-0.5">Frequency</p><p class="font-semibold">{{ ucfirst($plan->frequency) }}</p></div>
                    <div><p class="text-xs text-[#64748B] mb-0.5">Next Due</p><p class="font-semibold">{{ $plan->next_due_date?->format('d M Y') ?? '—' }}</p></div>
                </div>
            </x-card>

            <x-card class="overflow-hidden">
                <div class="px-5 py-4 border-b border-[#E2E8F0]"><h3 class="font-semibold font-heading text-[#1A202C]">Payment Schedule</h3></div>
                <div class="divide-y divide-[#F1F5F9]">
                    @foreach($plan->schedules as $schedule)
                    <div class="flex items-center justify-between px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold {{ $schedule->paid_at ? 'bg-green-100 text-green-700' : 'bg-[#F1F5F9] text-[#64748B]' }}">{{ $loop->iteration }}</div>
                            <div>
                                <p class="text-sm font-medium text-[#1A202C]">{{ $schedule->due_date->format('d M Y') }}</p>
                                @if($schedule->paid_at)<p class="text-xs text-green-600">Paid {{ $schedule->paid_at->format('d M Y') }}</p>@endif
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-sm">Rs. {{ number_format($schedule->amount, 2) }}</p>
                            @if(!$schedule->paid_at && $schedule->due_date->isPast())
                                <span class="text-xs text-red-500">Overdue</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </x-card>
        </div>

        <div>
            <x-card class="p-5">
                <h3 class="font-semibold font-heading text-[#1A202C] mb-4">Progress</h3>
                @php $pct = $plan->total_amount ? round($plan->amount_paid / $plan->total_amount * 100) : 0; @endphp
                <div class="mb-3">
                    <div class="flex justify-between text-xs text-[#64748B] mb-1">
                        <span>Paid</span><span>{{ $pct }}%</span>
                    </div>
                    <div class="w-full bg-[#F1F5F9] rounded-full h-2">
                        <div class="bg-[#004080] h-2 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                <div class="space-y-2 text-sm mt-4">
                    <div class="flex justify-between"><span class="text-[#64748B]">Amount Paid</span><span class="text-green-600 font-semibold">Rs. {{ number_format($plan->amount_paid, 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-[#64748B]">Remaining</span><span class="text-red-500 font-semibold">Rs. {{ number_format($plan->balance, 2) }}</span></div>
                </div>
            </x-card>
        </div>
    </div>

    @if($plan->status === 'active')
    <dialog id="payModal" class="modal-box rounded-xl p-0 w-full max-w-sm backdrop:bg-black/40">
        <form method="POST" action="{{ route('installments.pay', $plan) }}" class="p-6">
            @csrf
            <h3 class="font-semibold font-heading text-[#1A202C] mb-4">Record Payment</h3>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Amount (Rs.)</label>
                    <input type="number" name="amount" min="0.01" step="0.01" value="{{ $plan->balance }}" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Payment Date</label>
                    <input type="date" name="payment_date" value="{{ today()->toDateString() }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Payment Method</label>
                    <select name="method" class="form-select">
                        @foreach(['cash','card','bank_transfer','online'] as $m)
                            <option value="{{ $m }}">{{ ucwords(str_replace('_',' ',$m)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex gap-3 mt-5">
                <button type="submit" class="btn btn-primary">Record</button>
                <button type="button" onclick="document.getElementById('payModal').close()" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </dialog>
    @endif
</x-layouts.app>
