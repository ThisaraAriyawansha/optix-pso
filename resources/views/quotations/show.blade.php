<x-layouts.app :title="$quotation->quote_number">
    <x-page-header :title="$quotation->quote_number" :breadcrumbs="[['label' => 'Quotations', 'url' => route('quotations.index')], ['label' => $quotation->quote_number, 'url' => '#']]">
        <x-slot name="actions">
            @if(in_array($quotation->status, ['draft','issued']))
            <a href="{{ route('quotations.edit', $quotation) }}" class="btn btn-secondary btn-sm">Edit</a>
            <form method="POST" action="{{ route('quotations.convert', $quotation) }}" class="inline">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Convert this quotation to an invoice?')">Convert to Invoice</button>
            </form>
            @endif
        </x-slot>
    </x-page-header>

    <div class="p-6 max-w-4xl">
        <x-card class="p-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold font-heading text-[#1A202C] font-mono">{{ $quotation->quote_number }}</h2>
                    <p class="text-sm text-[#64748B] mt-1">Issued: {{ $quotation->issue_date->format('d M Y') }}</p>
                    @if($quotation->expiry_date)
                    <p class="text-sm text-[#64748B]">Expires: {{ $quotation->expiry_date->format('d M Y') }}</p>
                    @endif
                </div>
                <x-badge :status="$quotation->status" :label="ucfirst($quotation->status)"/>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6 pb-6 border-b border-[#F1F5F9]">
                <div>
                    <p class="text-xs font-semibold text-[#64748B] mb-1">Customer</p>
                    <p class="font-medium text-[#1A202C]">{{ $quotation->customer?->name ?? $quotation->customer_name ?? '—' }}</p>
                    @if($quotation->customer?->phone)
                    <p class="text-sm text-[#64748B]">{{ $quotation->customer->phone }}</p>
                    @endif
                </div>
                <div>
                    <p class="text-xs font-semibold text-[#64748B] mb-1">Prepared By</p>
                    <p class="font-medium text-[#1A202C]">{{ $quotation->user?->name ?? '—' }}</p>
                </div>
            </div>

            <table class="w-full text-sm mb-6">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-4 py-2 text-xs font-semibold text-[#64748B]">Description</th>
                    <th class="text-center px-4 py-2 text-xs font-semibold text-[#64748B]">Qty</th>
                    <th class="text-right px-4 py-2 text-xs font-semibold text-[#64748B]">Unit Price</th>
                    <th class="text-right px-4 py-2 text-xs font-semibold text-[#64748B]">Total</th>
                </tr></thead>
                <tbody>
                @foreach($quotation->items as $item)
                <tr class="border-b border-[#F1F5F9]">
                    <td class="px-4 py-2.5 text-[#1A202C]">{{ $item->description }}</td>
                    <td class="px-4 py-2.5 text-center text-[#64748B]">{{ $item->qty }}</td>
                    <td class="px-4 py-2.5 text-right text-[#64748B]">Rs. {{ number_format($item->unit_price, 2) }}</td>
                    <td class="px-4 py-2.5 text-right font-medium">Rs. {{ number_format($item->line_total, 2) }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>

            <div class="flex justify-end">
                <div class="w-64 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-[#64748B]">Subtotal</span>
                        <span>Rs. {{ number_format($quotation->subtotal, 2) }}</span>
                    </div>
                    @if($quotation->discount_amount)
                    <div class="flex justify-between">
                        <span class="text-[#64748B]">Discount</span>
                        <span class="text-red-500">- Rs. {{ number_format($quotation->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between font-bold text-[#004080] text-base border-t border-[#E2E8F0] pt-2">
                        <span>Total</span>
                        <span>Rs. {{ number_format($quotation->total, 2) }}</span>
                    </div>
                </div>
            </div>

            @if($quotation->notes)
            <div class="mt-6 pt-6 border-t border-[#F1F5F9]">
                <p class="text-xs font-semibold text-[#64748B] mb-1">Notes</p>
                <p class="text-sm text-[#1A202C]">{{ $quotation->notes }}</p>
            </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
