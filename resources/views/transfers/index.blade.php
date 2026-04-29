<x-layouts.app title="Branch Transfers">
    <x-page-header title="Branch Transfers" :breadcrumbs="[['label' => 'Transfers', 'url' => '#']]">
        <x-slot name="actions">
            <a href="{{ route('transfers.create') }}" class="btn btn-primary">+ New Transfer</a>
        </x-slot>
    </x-page-header>
    <div class="p-6">
        <form method="GET" class="flex gap-3 mb-5">
            <select name="status" class="form-select w-40">
                <option value="">All Status</option>
                @foreach(['pending','in_transit','received','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request('status'))<a href="{{ route('transfers.index') }}" class="btn btn-ghost">Clear</a>@endif
        </form>
        <x-card class="overflow-hidden">
            @if($transfers->isEmpty())
                <x-empty-state title="No transfers found">
                    <x-slot name="actions"><a href="{{ route('transfers.create') }}" class="btn btn-primary">Create Transfer</a></x-slot>
                </x-empty-state>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Transfer #</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">From</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">To</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Date</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Items</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Status</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Actions</th>
                </tr></thead>
                <tbody>
                @foreach($transfers as $transfer)
                <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA]">
                    <td class="px-5 py-3"><a href="{{ route('transfers.show', $transfer) }}" class="text-[#004080] font-medium font-mono text-xs hover:underline">{{ $transfer->transfer_number }}</a></td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $transfer->fromBranch->name }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $transfer->toBranch->name }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $transfer->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3 text-center text-[#64748B]">{{ $transfer->items_count }}</td>
                    <td class="px-5 py-3 text-center"><x-badge :status="$transfer->status" :label="ucwords(str_replace('_',' ',$transfer->status))"/></td>
                    <td class="px-5 py-3 text-center flex justify-center gap-1">
                        <a href="{{ route('transfers.show', $transfer) }}" class="btn btn-ghost btn-sm">View</a>
                        @if($transfer->status === 'in_transit')
                        <form method="POST" action="{{ route('transfers.receive', $transfer) }}">
                            @csrf
                            <button type="submit" class="btn btn-ghost btn-sm text-green-600">Receive</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-[#E2E8F0]">{{ $transfers->links() }}</div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
