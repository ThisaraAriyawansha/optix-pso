<x-layouts.app title="Installment Plans">
    <x-page-header title="Installment Plans" :breadcrumbs="[['label' => 'Installments', 'url' => '#']]"/>
    <div class="p-6">
        <form method="GET" class="flex gap-3 mb-5">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Customer or plan #..." class="form-input w-64">
            <select name="status" class="form-select w-36">
                <option value="">All Status</option>
                @foreach(['active','completed','defaulted','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request()->hasAny(['search','status']))<a href="{{ route('installments.index') }}" class="btn btn-ghost">Clear</a>@endif
        </form>
        <x-card class="overflow-hidden">
            @if($installments->isEmpty())
                <x-empty-state title="No installment plans found"/>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Customer</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Invoice</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Total</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Paid</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Balance</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Next Due</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Status</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Actions</th>
                </tr></thead>
                <tbody>
                @foreach($installments as $plan)
                <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA]">
                    <td class="px-5 py-3 font-medium text-[#1A202C]">{{ $plan->customer->name }}</td>
                    <td class="px-5 py-3 font-mono text-xs"><a href="{{ route('invoices.show', $plan->invoice) }}" class="text-[#004080] hover:underline">{{ $plan->invoice->invoice_number }}</a></td>
                    <td class="px-5 py-3 text-right">Rs. {{ number_format($plan->total_amount, 2) }}</td>
                    <td class="px-5 py-3 text-right text-green-600 font-semibold">Rs. {{ number_format($plan->amount_paid, 2) }}</td>
                    <td class="px-5 py-3 text-right text-red-500 font-semibold">Rs. {{ number_format($plan->balance, 2) }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $plan->next_due_date?->format('d M Y') ?? '—' }}</td>
                    <td class="px-5 py-3 text-center"><x-badge :status="$plan->status" :label="ucfirst($plan->status)"/></td>
                    <td class="px-5 py-3 text-center"><a href="{{ route('installments.show', $plan) }}" class="btn btn-ghost btn-sm">View</a></td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-[#E2E8F0]">{{ $installments->links() }}</div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
