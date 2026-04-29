<x-layouts.app title="Quotations">
    <x-page-header title="Quotations" :breadcrumbs="[['label' => 'Quotations', 'url' => '#']]">
        <x-slot name="actions">
            <a href="{{ route('quotations.create') }}" class="btn btn-primary">+ New Quotation</a>
        </x-slot>
    </x-page-header>
    <div class="p-6">
        <form method="GET" class="flex gap-3 mb-5">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Quote # or customer..." class="form-input w-72">
            <select name="status" class="form-select w-40">
                <option value="">All Status</option>
                @foreach(['draft','issued','accepted','declined','expired','converted'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request()->hasAny(['search','status']))<a href="{{ route('quotations.index') }}" class="btn btn-ghost">Clear</a>@endif
        </form>
        <x-card class="overflow-hidden">
            @if($quotations->isEmpty())
                <x-empty-state title="No quotations found">
                    <x-slot name="actions"><a href="{{ route('quotations.create') }}" class="btn btn-primary">Create Quotation</a></x-slot>
                </x-empty-state>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Quote #</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Customer</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Date</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Expires</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Total</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Status</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Actions</th>
                </tr></thead>
                <tbody>
                @foreach($quotations as $q)
                <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA]">
                    <td class="px-5 py-3"><a href="{{ route('quotations.show', $q) }}" class="text-[#004080] font-medium font-mono text-xs hover:underline">{{ $q->quote_number }}</a></td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $q->customer?->name ?? $q->customer_name }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $q->issue_date->format('d M Y') }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $q->expiry_date?->format('d M Y') ?? '—' }}</td>
                    <td class="px-5 py-3 text-right font-semibold">Rs. {{ number_format($q->total, 2) }}</td>
                    <td class="px-5 py-3 text-center"><x-badge :status="$q->status" :label="ucfirst($q->status)"/></td>
                    <td class="px-5 py-3 text-center flex items-center justify-center gap-1">
                        <a href="{{ route('quotations.show', $q) }}" class="btn btn-ghost btn-sm">View</a>
                        @if($q->status === 'draft' || $q->status === 'issued')
                        <a href="{{ route('quotations.edit', $q) }}" class="btn btn-ghost btn-sm">Edit</a>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-[#E2E8F0]">{{ $quotations->links() }}</div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
