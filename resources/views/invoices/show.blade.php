<x-layouts.app title="{{ $invoice->invoice_number }}">
    <x-page-header :title="$invoice->invoice_number" :breadcrumbs="[['label' => 'Invoices', 'url' => route('invoices.index')], ['label' => $invoice->invoice_number, 'url' => '#']]">
        <x-slot name="actions">
            <div class="flex gap-2">
                @if(in_array($invoice->status, ['issued','partial']))
                    <button onclick="document.getElementById('paymentModal').showModal()" class="btn btn-primary btn-sm">Record Payment</button>
                @endif
                @if(in_array($invoice->status, ['issued','paid','partial']))
                    <form method="POST" action="{{ route('invoices.refund', $invoice) }}" onsubmit="return confirm('Refund this invoice?')">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Refund</button>
                    </form>
                @endif
            </div>
        </x-slot>
    </x-page-header>

    <div class="p-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Invoice Detail --}}
        <div class="xl:col-span-2 space-y-5">
            {{-- Header Info --}}
            <x-card class="p-5">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div><p class="text-[#64748B] text-xs mb-1">Invoice #</p><p class="font-semibold font-mono">{{ $invoice->invoice_number }}</p></div>
                    <div><p class="text-[#64748B] text-xs mb-1">Date</p><p class="font-medium">{{ $invoice->created_at->format('d M Y') }}</p></div>
                    <div><p class="text-[#64748B] text-xs mb-1">Cashier</p><p class="font-medium">{{ $invoice->cashier->name }}</p></div>
                    <div><p class="text-[#64748B] text-xs mb-1">Status</p><x-badge :status="$invoice->status"/></div>
                    <div><p class="text-[#64748B] text-xs mb-1">Customer</p><p class="font-medium">{{ $invoice->customer_display_name }}</p></div>
                    <div><p class="text-[#64748B] text-xs mb-1">Branch</p><p class="font-medium">{{ $invoice->branch->name }}</p></div>
                    <div><p class="text-[#64748B] text-xs mb-1">Type</p><p class="font-medium capitalize">{{ $invoice->type }}</p></div>
                </div>
            </x-card>

            {{-- Items --}}
            <x-card class="overflow-hidden">
                <div class="px-5 py-3 border-b border-[#E2E8F0] bg-[#F9FAFB]">
                    <h3 class="font-semibold font-heading text-sm text-[#1A202C]">Line Items</h3>
                </div>
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-[#E2E8F0]">
                        <th class="text-left px-5 py-2.5 text-xs font-semibold text-[#64748B]">#</th>
                        <th class="text-left px-5 py-2.5 text-xs font-semibold text-[#64748B]">Description</th>
                        <th class="text-right px-5 py-2.5 text-xs font-semibold text-[#64748B]">Qty</th>
                        <th class="text-right px-5 py-2.5 text-xs font-semibold text-[#64748B]">Unit Price</th>
                        <th class="text-right px-5 py-2.5 text-xs font-semibold text-[#64748B]">Disc %</th>
                        <th class="text-right px-5 py-2.5 text-xs font-semibold text-[#64748B]">Total</th>
                    </tr></thead>
                    <tbody>
                    @foreach($invoice->items as $i => $item)
                    <tr class="table-row-alt border-b border-[#F1F5F9]">
                        <td class="px-5 py-2.5 text-[#64748B]">{{ $i + 1 }}</td>
                        <td class="px-5 py-2.5 font-medium text-[#1A202C]">{{ $item->description }}</td>
                        <td class="px-5 py-2.5 text-right text-[#64748B]">{{ $item->qty }}</td>
                        <td class="px-5 py-2.5 text-right text-[#64748B]">Rs. {{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-5 py-2.5 text-right text-[#64748B]">{{ $item->discount_pct > 0 ? $item->discount_pct . '%' : '—' }}</td>
                        <td class="px-5 py-2.5 text-right font-semibold text-[#1A202C]">Rs. {{ number_format($item->line_total, 2) }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="px-5 py-4 border-t border-[#E2E8F0] space-y-2 text-sm">
                    <div class="flex justify-between text-[#64748B]"><span>Subtotal</span><span>Rs. {{ number_format($invoice->subtotal, 2) }}</span></div>
                    @if($invoice->discount_amount > 0)
                    <div class="flex justify-between text-[#64748B]"><span>Discount</span><span>− Rs. {{ number_format($invoice->discount_amount, 2) }}</span></div>
                    @endif
                    @if($invoice->tax_amount > 0)
                    <div class="flex justify-between text-[#64748B]"><span>Tax</span><span>Rs. {{ number_format($invoice->tax_amount, 2) }}</span></div>
                    @endif
                    <div class="flex justify-between font-bold text-base text-[#1A202C] border-t border-[#E2E8F0] pt-2">
                        <span>Total</span><span class="text-[#004080]">Rs. {{ number_format($invoice->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-[#64748B]"><span>Paid</span><span class="text-[#16A34A]">Rs. {{ number_format($invoice->amount_paid, 2) }}</span></div>
                    <div class="flex justify-between font-semibold {{ $invoice->amount_due > 0 ? 'text-[#DC2626]' : 'text-[#64748B]' }}">
                        <span>Balance Due</span><span>Rs. {{ number_format($invoice->amount_due, 2) }}</span>
                    </div>
                </div>
            </x-card>
        </div>

        {{-- Payments Sidebar --}}
        <div class="space-y-5">
            <x-card class="overflow-hidden">
                <div class="px-5 py-4 border-b border-[#E2E8F0]"><h3 class="font-semibold font-heading text-[#1A202C]">Payments</h3></div>
                @forelse($invoice->payments as $pmt)
                <div class="flex items-center justify-between px-5 py-3 border-b border-[#F1F5F9]">
                    <div>
                        <p class="text-sm font-medium capitalize">{{ str_replace('_', ' ', $pmt->method) }}</p>
                        <p class="text-xs text-[#64748B]">{{ $pmt->paid_at->format('d M Y, H:i') }}</p>
                    </div>
                    <span class="text-sm font-semibold text-[#16A34A]">Rs. {{ number_format($pmt->amount, 2) }}</span>
                </div>
                @empty
                <div class="px-5 py-4 text-sm text-[#64748B]">No payments recorded</div>
                @endforelse
            </x-card>
        </div>
    </div>

    {{-- Payment Modal --}}
    <dialog id="paymentModal" class="modal-box w-full max-w-md p-0 rounded-lg shadow-xl backdrop:bg-black/50">
        <div class="flex items-center justify-between px-5 py-4 border-b border-[#E2E8F0]">
            <h3 class="font-semibold font-heading text-[#1A202C]">Record Payment</h3>
            <button onclick="document.getElementById('paymentModal').close()" class="text-[#64748B] hover:text-[#1A202C]">✕</button>
        </div>
        <form method="POST" action="{{ route('invoices.payment', $invoice) }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="form-label">Payment Method</label>
                <select name="method" class="form-select" required>
                    @foreach(['cash' => 'Cash','card' => 'Card','mobile_pay' => 'Mobile Pay','bank_transfer' => 'Bank Transfer','loyalty_points' => 'Loyalty Points','credit' => 'Credit'] as $v => $l)
                        <option value="{{ $v }}">{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Amount (Rs.)</label>
                <input type="number" name="amount" value="{{ $invoice->amount_due }}" step="0.01" min="0.01" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Reference (optional)</label>
                <input type="text" name="reference" class="form-input" placeholder="Card auth, mobile ref...">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn btn-primary flex-1 justify-center">Record Payment</button>
                <button type="button" onclick="document.getElementById('paymentModal').close()" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </dialog>
</x-layouts.app>
