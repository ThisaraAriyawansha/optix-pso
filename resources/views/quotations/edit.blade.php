<x-layouts.app :title="'Edit ' . $quotation->quote_number">
    <x-page-header :title="'Edit — ' . $quotation->quote_number" :breadcrumbs="[['label' => 'Quotations', 'url' => route('quotations.index')], ['label' => $quotation->quote_number, 'url' => route('quotations.show', $quotation)], ['label' => 'Edit', 'url' => '#']]"/>
    <div class="p-6" x-data="quotationForm({{ json_encode($quotation->items->map(fn($i) => ['description' => $i->description, 'qty' => $i->qty, 'unit_price' => $i->unit_price, 'line_total' => $i->line_total])) }}, {{ $quotation->discount_amount ?? 0 }})">
        <form method="POST" action="{{ route('quotations.update', $quotation) }}" class="max-w-4xl">
            @csrf
            @method('PUT')
            <div class="space-y-5">
                <x-card class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="form-label">Customer</label>
                            <select name="customer_id" class="form-select">
                                <option value="">— Walk-in / Manual —</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ old('customer_id', $quotation->customer_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Customer Name (if walk-in)</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name', $quotation->customer_name) }}" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Expiry Date</label>
                            <input type="date" name="expiry_date" value="{{ old('expiry_date', $quotation->expiry_date?->toDateString()) }}" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                @foreach(['draft','issued','accepted','declined','expired'] as $s)
                                    <option value="{{ $s }}" {{ old('status', $quotation->status) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" rows="2" class="form-textarea">{{ old('notes', $quotation->notes) }}</textarea>
                        </div>
                    </div>
                </x-card>

                <x-card class="overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E2E8F0]"><h3 class="font-semibold font-heading text-[#1A202C]">Line Items</h3></div>
                    <div class="p-5 space-y-3">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="grid grid-cols-12 gap-3 items-end">
                                <div class="col-span-5">
                                    <label class="form-label text-xs" x-show="index === 0">Description</label>
                                    <input type="text" :name="`items[${index}][description]`" x-model="item.description" class="form-input" required>
                                </div>
                                <div class="col-span-2">
                                    <label class="form-label text-xs" x-show="index === 0">Qty</label>
                                    <input type="number" :name="`items[${index}][qty]`" x-model.number="item.qty" min="1" class="form-input" @input="calcItem(index)">
                                </div>
                                <div class="col-span-2">
                                    <label class="form-label text-xs" x-show="index === 0">Unit Price</label>
                                    <input type="number" :name="`items[${index}][unit_price]`" x-model.number="item.unit_price" min="0" step="0.01" class="form-input" @input="calcItem(index)">
                                </div>
                                <div class="col-span-2">
                                    <label class="form-label text-xs" x-show="index === 0">Total</label>
                                    <input type="number" :name="`items[${index}][line_total]`" :value="item.line_total" class="form-input bg-[#F9FAFB]" readonly>
                                </div>
                                <div class="col-span-1 flex justify-end">
                                    <button type="button" @click="removeItem(index)" class="text-red-400 hover:text-red-600 mt-1" x-show="items.length > 1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <button type="button" @click="addItem" class="text-sm text-[#004080] hover:underline font-medium">+ Add Line</button>
                    </div>
                    <div class="border-t border-[#E2E8F0] px-5 py-4 flex justify-end">
                        <div class="w-56 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-[#64748B]">Subtotal</span>
                                <span class="font-medium" x-text="'Rs. ' + subtotal().toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[#64748B]">Discount</span>
                                <input type="number" name="discount_amount" x-model.number="discount" min="0" step="0.01" class="form-input w-24 text-right text-xs py-1">
                            </div>
                            <div class="flex justify-between font-semibold text-[#004080] text-base border-t border-[#E2E8F0] pt-2">
                                <span>Total</span>
                                <span x-text="'Rs. ' + total().toFixed(2)"></span>
                            </div>
                            <input type="hidden" name="total" :value="total()">
                        </div>
                    </div>
                </x-card>
            </div>
            <div class="flex gap-3 mt-5">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('quotations.show', $quotation) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>

@push('scripts')
<script>
function quotationForm(initialItems, initialDiscount) {
    return {
        items: initialItems.length ? initialItems : [{ description: '', qty: 1, unit_price: 0, line_total: 0 }],
        discount: initialDiscount,
        addItem() {
            this.items.push({ description: '', qty: 1, unit_price: 0, line_total: 0 });
        },
        removeItem(i) {
            this.items.splice(i, 1);
        },
        calcItem(i) {
            this.items[i].line_total = (this.items[i].qty * this.items[i].unit_price).toFixed(2);
        },
        subtotal() {
            return this.items.reduce((s, i) => s + parseFloat(i.line_total || 0), 0);
        },
        total() {
            return Math.max(0, this.subtotal() - parseFloat(this.discount || 0));
        }
    }
}
</script>
@endpush
