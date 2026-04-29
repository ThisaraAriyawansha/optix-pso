<x-layouts.app title="Purchase Orders">
    <x-page-header title="Purchase Orders" :breadcrumbs="[['label' => 'Purchase Orders', 'url' => '#']]">
        <x-slot name="actions">
            <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">+ New PO</a>
        </x-slot>
    </x-page-header>
    <div class="p-6">
        <form method="GET" class="flex gap-3 mb-5 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="PO # or supplier..." class="form-input w-64">
            <select name="status" class="form-select w-40">
                <option value="">All Status</option>
                @foreach(['draft','ordered','partial','received','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request()->hasAny(['search','status']))<a href="{{ route('purchase-orders.index') }}" class="btn btn-ghost">Clear</a>@endif
        </form>
        <x-card class="overflow-hidden">
            @if($orders->isEmpty())
                <x-empty-state title="No purchase orders found">
                    <x-slot name="actions"><a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">Create PO</a></x-slot>
                </x-empty-state>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">PO #</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Supplier</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Order Date</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Expected</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Total</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Status</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Actions</th>
                </tr></thead>
                <tbody>
                @foreach($orders as $po)
                <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA]">
                    <td class="px-5 py-3"><a href="{{ route('purchase-orders.show', $po) }}" class="text-[#004080] font-medium font-mono text-xs hover:underline">{{ $po->po_number }}</a></td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $po->supplier->name }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $po->order_date->format('d M Y') }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $po->expected_date?->format('d M Y') ?? '—' }}</td>
                    <td class="px-5 py-3 text-right font-semibold">Rs. {{ number_format($po->total, 2) }}</td>
                    <td class="px-5 py-3 text-center"><x-badge :status="$po->status" :label="ucfirst($po->status)"/></td>
                    <td class="px-5 py-3 text-center flex justify-center gap-1">
                        <a href="{{ route('purchase-orders.show', $po) }}" class="btn btn-ghost btn-sm">View</a>
                        @if($po->status === 'ordered')
                        <form method="POST" action="{{ route('purchase-orders.receive', $po) }}">
                            @csrf
                            <button type="submit" class="btn btn-ghost btn-sm text-green-600">Mark Received</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-[#E2E8F0]">{{ $orders->links() }}</div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
