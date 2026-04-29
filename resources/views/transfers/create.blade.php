<x-layouts.app title="New Transfer">
    <x-page-header title="New Branch Transfer" :breadcrumbs="[['label' => 'Transfers', 'url' => route('transfers.index')], ['label' => 'New', 'url' => '#']]"/>
    <div class="p-6" x-data="transferForm()">
        <form method="POST" action="{{ route('transfers.store') }}" class="max-w-4xl">
            @csrf
            <div class="space-y-5">
                <x-card class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label">From Branch *</label>
                            <select name="from_branch_id" class="form-select" required>
                                <option value="">— Select Branch —</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('from_branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @error('from_branch_id')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">To Branch *</label>
                            <select name="to_branch_id" class="form-select" required>
                                <option value="">— Select Branch —</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('to_branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @error('to_branch_id')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" rows="2" class="form-textarea">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </x-card>

                <x-card class="overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E2E8F0]"><h3 class="font-semibold font-heading text-[#1A202C]">Items to Transfer</h3></div>
                    <div class="p-5 space-y-3">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="grid grid-cols-12 gap-3 items-end">
                                <div class="col-span-7">
                                    <label class="form-label text-xs" x-show="index === 0">Product</label>
                                    <select :name="`items[${index}][product_id]`" x-model="item.product_id" class="form-select" required>
                                        <option value="">— Select Product —</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->sku }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-3">
                                    <label class="form-label text-xs" x-show="index === 0">Quantity</label>
                                    <input type="number" :name="`items[${index}][qty]`" x-model.number="item.qty" min="1" class="form-input" required>
                                </div>
                                <div class="col-span-2 flex justify-end">
                                    <button type="button" @click="remove(index)" class="text-red-400 hover:text-red-600 mt-1" x-show="items.length > 1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <button type="button" @click="add" class="text-sm text-[#004080] hover:underline font-medium">+ Add Item</button>
                    </div>
                </x-card>
            </div>
            <div class="flex gap-3 mt-5">
                <button type="submit" class="btn btn-primary">Initiate Transfer</button>
                <a href="{{ route('transfers.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>

@push('scripts')
<script>
function transferForm() {
    return {
        items: [{ product_id: '', qty: 1 }],
        add() { this.items.push({ product_id: '', qty: 1 }); },
        remove(i) { this.items.splice(i, 1); }
    }
}
</script>
@endpush
