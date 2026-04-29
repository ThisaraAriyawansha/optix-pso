<x-layouts.app title="Invoices">
    <x-page-header title="Invoices" :breadcrumbs="[['label' => 'Invoices', 'url' => '#']]"/>
    <div class="p-6">
        <form method="GET" class="flex gap-3 mb-5 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Invoice # or customer..." class="form-input w-64">
            <select name="status" class="form-select w-40">
                <option value="">All Status</option>
                @foreach(['issued','paid','partial','cancelled','refunded'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <input type="date" name="from" value="{{ request('from') }}" class="form-input w-40">
            <input type="date" name="to" value="{{ request('to') }}" class="form-input w-40">
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request()->hasAny(['search','status','from','to']))<a href="{{ route('invoices.index') }}" class="btn btn-ghost">Clear</a>@endif
        </form>
        <x-card class="overflow-hidden">
            @if($invoices->isEmpty())
                <x-empty-state title="No invoices found" description="Complete a sale to generate invoices"/>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Invoice #</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Customer</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Date</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Cashier</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Total</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Due</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Status</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Actions</th>
                </tr></thead>
                <tbody>
                @foreach($invoices as $inv)
                <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA]">
                    <td class="px-5 py-3"><a href="{{ route('invoices.show', $inv) }}" class="text-[#004080] font-medium hover:underline font-mono text-xs">{{ $inv->invoice_number }}</a></td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $inv->customer_display_name }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $inv->created_at->format('d M Y, H:i') }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $inv->cashier->name }}</td>
                    <td class="px-5 py-3 text-right font-semibold text-[#1A202C]">Rs. {{ number_format($inv->total, 2) }}</td>
                    <td class="px-5 py-3 text-right {{ $inv->amount_due > 0 ? 'text-[#DC2626]' : 'text-[#64748B]' }}">Rs. {{ number_format($inv->amount_due, 2) }}</td>
                    <td class="px-5 py-3 text-center"><x-badge :status="$inv->status"/></td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('invoices.show', $inv) }}" class="btn btn-ghost btn-sm">View</a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-[#E2E8F0]">{{ $invoices->links() }}</div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
