<x-layouts.app title="New Purchase Order">
    <x-page-header title="New Purchase Order" :breadcrumbs="[['label' => 'Purchase Orders', 'url' => route('purchase-orders.index')], ['label' => 'New', 'url' => '#']]"/>
    <div class="p-6" x-data="poForm()">
        <form method="POST" action="{{ route('purchase-orders.store') }}" class="max-w-4xl">
            @csrf
            <div class="space-y-5">
                <x-card class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="form-label">Supplier *</label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">— Select Supplier —</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Order Date</label>
                            <input type="date" name="order_date" value="{{ old('order_date', today()->toDateString()) }}" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Expected Delivery</label>
                            <input type="date" name="expected_date" value="{{ old('expected_date') }}" class="form-input">
                        </div>
                        <div class="md:col-span-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" rows="2" class="form-textarea">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </x-card>

                <x-card class="overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E2E8F0]"><h3 class="font-semibold font-heading text-[#1A202C]">Items</h3></div>
                    <div class="p-5 space-y-3">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="grid grid-cols-12 gap-3 items-end">
                                <div class="col-span-5">
                                    <label class="form-label text-xs" x-show="index === 0">Product / Description</label>
                                    <input type="text" :name="`items[${index}][description]`" x-model="item.description" class="form-input" placeholder="Item description" required>
                                </div>
                                <div class="col-span-2">
                                    <label class="form-label text-xs" x-show="index === 0">Qty</label>
                                    <input type="number" :name="`items[${index}][qty]`" x-model.number="item.qty" min="1" class="form-input" @input="calc(index)">
                                </div>
                                <div class="col-span-2">
                                    <label class="form-label text-xs" x-show="index === 0">Unit Cost</label>
                                    <input type="number" :name="`items[${index}][unit_cost]`" x-model.number="item.unit_cost" min="0" step="0.01" class="form-input" @input="calc(index)">
                                </div>
                                <div class="col-span-2">
                                    <label class="form-label text-xs" x-show="index === 0">Total</label>
                                    <input type="number" :name="`items[${index}][line_total]`" :value="item.line_total" class="form-input bg-[#F9FAFB]" readonly>
                                </div>
                                <div class="col-span-1 flex justify-end">
                                    <button type="button" @click="remove(index)" class="text-red-400 hover:text-red-600 mt-1" x-show="items.length > 1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <button type="button" @click="add" class="text-sm text-[#004080] hover:underline font-medium">+ Add Item</button>
                    </div>
                    <div class="border-t border-[#E2E8F0] px-5 py-4 flex justify-end">
                        <div class="flex items-center gap-4 text-sm font-semibold text-[#004080]">
                            <span>Total:</span>
                            <span x-text="'Rs. ' + total().toFixed(2)"></span>
                            <input type="hidden" name="total" :value="total()">
                        </div>
                    </div>
                </x-card>
            </div>
            <div class="flex gap-3 mt-5">
                <button type="submit" class="btn btn-primary">Create Purchase Order</button>
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>

@push('scripts')
<script>
function poForm() {
    return {
        items: [{ description: '', qty: 1, unit_cost: 0, line_total: 0 }],
        add() { this.items.push({ description: '', qty: 1, unit_cost: 0, line_total: 0 }); },
        remove(i) { this.items.splice(i, 1); },
        calc(i) { this.items[i].line_total = (this.items[i].qty * this.items[i].unit_cost).toFixed(2); },
        total() { return this.items.reduce((s, i) => s + parseFloat(i.line_total || 0), 0); }
    }
}
</script>
@endpush
