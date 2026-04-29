<x-layouts.app :title="$po->po_number">
    <x-page-header :title="$po->po_number" :breadcrumbs="[['label' => 'Purchase Orders', 'url' => route('purchase-orders.index')], ['label' => $po->po_number, 'url' => '#']]">
        <x-slot name="actions">
            @if($po->status === 'draft')
            <form method="POST" action="{{ route('purchase-orders.approve', $po) }}" class="inline">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm">Mark as Ordered</button>
            </form>
            @endif
            @if($po->status === 'ordered' || $po->status === 'partial')
            <form method="POST" action="{{ route('purchase-orders.receive', $po) }}" class="inline">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm text-green-100">Mark Received</button>
            </form>
            @endif
        </x-slot>
    </x-page-header>

    <div class="p-6 max-w-4xl">
        <x-card class="p-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold font-heading font-mono text-[#1A202C]">{{ $po->po_number }}</h2>
                    <p class="text-sm text-[#64748B]">Supplier: <span class="font-medium text-[#1A202C]">{{ $po->supplier->name }}</span></p>
                    <p class="text-sm text-[#64748B]">Order Date: {{ $po->order_date->format('d M Y') }}</p>
                    @if($po->expected_date)<p class="text-sm text-[#64748B]">Expected: {{ $po->expected_date->format('d M Y') }}</p>@endif
                </div>
                <x-badge :status="$po->status" :label="ucfirst($po->status)"/>
            </div>

            <table class="w-full text-sm mb-6">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-4 py-2 text-xs font-semibold text-[#64748B]">Description</th>
                    <th class="text-center px-4 py-2 text-xs font-semibold text-[#64748B]">Qty Ordered</th>
                    <th class="text-center px-4 py-2 text-xs font-semibold text-[#64748B]">Qty Received</th>
                    <th class="text-right px-4 py-2 text-xs font-semibold text-[#64748B]">Unit Cost</th>
                    <th class="text-right px-4 py-2 text-xs font-semibold text-[#64748B]">Total</th>
                </tr></thead>
                <tbody>
                @foreach($po->items as $item)
                <tr class="border-b border-[#F1F5F9]">
                    <td class="px-4 py-2.5 text-[#1A202C]">{{ $item->description }}</td>
                    <td class="px-4 py-2.5 text-center text-[#64748B]">{{ $item->qty }}</td>
                    <td class="px-4 py-2.5 text-center {{ $item->qty_received >= $item->qty ? 'text-green-600 font-semibold' : 'text-amber-600' }}">{{ $item->qty_received ?? 0 }}</td>
                    <td class="px-4 py-2.5 text-right text-[#64748B]">Rs. {{ number_format($item->unit_cost, 2) }}</td>
                    <td class="px-4 py-2.5 text-right font-medium">Rs. {{ number_format($item->line_total, 2) }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>

            <div class="flex justify-end">
                <div class="font-bold text-[#004080] text-lg">
                    Total: Rs. {{ number_format($po->total, 2) }}
                </div>
            </div>

            @if($po->notes)
            <div class="mt-6 pt-4 border-t border-[#F1F5F9]">
                <p class="text-xs font-semibold text-[#64748B] mb-1">Notes</p>
                <p class="text-sm">{{ $po->notes }}</p>
            </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
