<x-layouts.app title="Stock Levels">
    <x-page-header title="Stock Levels" :breadcrumbs="[['label' => 'Stock', 'url' => '#']]">
        <x-slot name="actions">
            <a href="{{ route('stock.movements') }}" class="btn btn-secondary btn-sm">Movement History</a>
        </x-slot>
    </x-page-header>
    <div class="p-6">
        <form method="GET" class="flex gap-3 mb-5 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Product name or SKU..." class="form-input w-72">
            <select name="category" class="form-select w-44">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="low_stock" class="form-select w-36">
                <option value="">All Stock</option>
                <option value="1" {{ request('low_stock') ? 'selected' : '' }}>Low Stock Only</option>
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request()->hasAny(['search','category','low_stock']))<a href="{{ route('stock.index') }}" class="btn btn-ghost">Clear</a>@endif
        </form>
        <x-card class="overflow-hidden">
            @if($stocks->isEmpty())
                <x-empty-state title="No stock records found"/>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Product</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">SKU</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Category</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">On Hand</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Reserved</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Available</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Reorder At</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Status</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Adjust</th>
                </tr></thead>
                <tbody>
                @foreach($stocks as $stock)
                @php
                    $available = $stock->qty_on_hand - $stock->qty_reserved;
                    $low = $stock->product->reorder_point && $stock->qty_on_hand <= $stock->product->reorder_point;
                @endphp
                <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA] {{ $low ? 'bg-red-50' : '' }}">
                    <td class="px-5 py-3 font-medium text-[#1A202C]">
                        <a href="{{ route('products.show', $stock->product) }}" class="hover:text-[#004080] hover:underline">{{ $stock->product->name }}</a>
                    </td>
                    <td class="px-5 py-3 font-mono text-xs text-[#64748B]">{{ $stock->product->sku }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $stock->product->category?->name ?? '—' }}</td>
                    <td class="px-5 py-3 text-center font-semibold text-[#1A202C]">{{ $stock->qty_on_hand }}</td>
                    <td class="px-5 py-3 text-center text-[#64748B]">{{ $stock->qty_reserved }}</td>
                    <td class="px-5 py-3 text-center font-semibold {{ $available <= 0 ? 'text-red-600' : 'text-green-700' }}">{{ $available }}</td>
                    <td class="px-5 py-3 text-center text-[#64748B]">{{ $stock->product->reorder_point ?? '—' }}</td>
                    <td class="px-5 py-3 text-center">
                        @if($available <= 0)
                            <x-badge status="danger" label="Out of Stock"/>
                        @elseif($low)
                            <x-badge status="warning" label="Low Stock"/>
                        @else
                            <x-badge status="success" label="In Stock"/>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-center">
                        <button
                            x-data
                            @click="$dispatch('open-adjust', {{ json_encode(['id' => $stock->id, 'name' => $stock->product->name, 'qty' => $stock->qty_on_hand]) }})"
                            class="btn btn-ghost btn-sm">Adjust</button>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-[#E2E8F0]">{{ $stocks->links() }}</div>
            @endif
        </x-card>
    </div>

    {{-- Adjust Stock Modal --}}
    <div x-data="adjustModal()" x-on:open-adjust.window="open($event.detail)">
        <dialog x-ref="dialog" class="modal-box rounded-xl p-0 w-full max-w-sm backdrop:bg-black/40">
            <form method="POST" :action="`/stock/${stockId}/adjust`" class="p-6">
                @csrf
                <h3 class="font-semibold font-heading text-[#1A202C] mb-1">Adjust Stock</h3>
                <p class="text-sm text-[#64748B] mb-4" x-text="productName"></p>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Adjustment Type</label>
                        <select name="type" class="form-select">
                            <option value="addition">Addition (+)</option>
                            <option value="subtraction">Subtraction (−)</option>
                            <option value="set">Set Exact Quantity</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Quantity</label>
                        <input type="number" name="qty" min="0" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Reason</label>
                        <input type="text" name="reason" class="form-input" placeholder="e.g. stock count, damage...">
                    </div>
                </div>
                <div class="flex gap-3 mt-5">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <button type="button" @click="$refs.dialog.close()" class="btn btn-secondary">Cancel</button>
                </div>
            </form>
        </dialog>
    </div>
</x-layouts.app>

@push('scripts')
<script>
function adjustModal() {
    return {
        stockId: null,
        productName: '',
        open(detail) {
            this.stockId = detail.id;
            this.productName = detail.name;
            this.$refs.dialog.showModal();
        }
    }
}
</script>
@endpush
