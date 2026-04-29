<x-layouts.app :title="$transfer->transfer_number">
    <x-page-header :title="$transfer->transfer_number" :breadcrumbs="[['label' => 'Transfers', 'url' => route('transfers.index')], ['label' => $transfer->transfer_number, 'url' => '#']]">
        <x-slot name="actions">
            @if($transfer->status === 'in_transit')
            <form method="POST" action="{{ route('transfers.receive', $transfer) }}" class="inline">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm">Mark as Received</button>
            </form>
            @endif
        </x-slot>
    </x-page-header>

    <div class="p-6 max-w-3xl">
        <x-card class="p-6">
            <div class="flex items-start justify-between mb-6">
                <div class="space-y-1">
                    <h2 class="text-lg font-bold font-mono text-[#1A202C]">{{ $transfer->transfer_number }}</h2>
                    <p class="text-sm text-[#64748B]">From: <span class="font-semibold text-[#1A202C]">{{ $transfer->fromBranch->name }}</span></p>
                    <p class="text-sm text-[#64748B]">To: <span class="font-semibold text-[#1A202C]">{{ $transfer->toBranch->name }}</span></p>
                    <p class="text-sm text-[#64748B]">Initiated by: {{ $transfer->user?->name ?? '—' }} · {{ $transfer->created_at->format('d M Y H:i') }}</p>
                </div>
                <x-badge :status="$transfer->status" :label="ucwords(str_replace('_', ' ', $transfer->status))"/>
            </div>

            <table class="w-full text-sm mb-6">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-4 py-2 text-xs font-semibold text-[#64748B]">Product</th>
                    <th class="text-left px-4 py-2 text-xs font-semibold text-[#64748B]">SKU</th>
                    <th class="text-center px-4 py-2 text-xs font-semibold text-[#64748B]">Qty</th>
                </tr></thead>
                <tbody>
                @foreach($transfer->items as $item)
                <tr class="border-b border-[#F1F5F9]">
                    <td class="px-4 py-2.5 font-medium text-[#1A202C]">{{ $item->product->name }}</td>
                    <td class="px-4 py-2.5 font-mono text-xs text-[#64748B]">{{ $item->product->sku }}</td>
                    <td class="px-4 py-2.5 text-center font-semibold">{{ $item->qty }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>

            @if($transfer->notes)
            <div class="pt-4 border-t border-[#F1F5F9]">
                <p class="text-xs font-semibold text-[#64748B] mb-1">Notes</p>
                <p class="text-sm text-[#1A202C]">{{ $transfer->notes }}</p>
            </div>
            @endif

            @if($transfer->received_at)
            <div class="mt-4 p-3 bg-green-50 rounded-lg text-sm text-green-700">
                Received on {{ $transfer->received_at->format('d M Y H:i') }}
                @if($transfer->receivedBy) by {{ $transfer->receivedBy->name }}@endif
            </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
