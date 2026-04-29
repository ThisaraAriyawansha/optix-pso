<x-layouts.app title="Stock Report">
    <x-page-header title="Stock Report" :breadcrumbs="[['label' => 'Reports', 'url' => '#'], ['label' => 'Stock', 'url' => '#']]"/>
    <div class="p-6">
        <form method="GET" class="flex gap-3 mb-5 flex-wrap items-end">
            <div>
                <label class="form-label">Category</label>
                <select name="category" class="form-select w-44">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Filter</label>
                <select name="filter" class="form-select w-36">
                    <option value="">All</option>
                    <option value="low" {{ request('filter') == 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out" {{ request('filter') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">Generate</button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <x-stat-card title="Total Products" :value="$summary['total_products'] ?? 0" icon="cube" color="blue"/>
            <x-stat-card title="Low Stock Items" :value="$summary['low_stock'] ?? 0" icon="warning" color="amber"/>
            <x-stat-card title="Out of Stock" :value="$summary['out_of_stock'] ?? 0" icon="x-circle" color="red"/>
            <x-stat-card title="Stock Value" :value="'Rs. ' . number_format($summary['stock_value'] ?? 0, 2)" icon="currency" color="green"/>
        </div>

        <x-card class="overflow-hidden">
            <div class="px-5 py-4 border-b border-[#E2E8F0]">
                <h3 class="font-semibold font-heading text-[#1A202C]">Product Stock Levels</h3>
            </div>
            @if($stocks->isEmpty())
                <div class="px-5 py-8 text-center text-sm text-[#64748B]">No data</div>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Product</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">SKU</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Category</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Cost Price</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Qty</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Stock Value</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Status</th>
                </tr></thead>
                <tbody>
                @foreach($stocks as $stock)
                @php
                    $qty = $stock->qty_on_hand;
                    $low = $stock->product->reorder_point && $qty <= $stock->product->reorder_point;
                @endphp
                <tr class="table-row-alt border-b border-[#F1F5F9]">
                    <td class="px-5 py-3 font-medium text-[#1A202C]">{{ $stock->product->name }}</td>
                    <td class="px-5 py-3 font-mono text-xs text-[#64748B]">{{ $stock->product->sku }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $stock->product->category?->name ?? '—' }}</td>
                    <td class="px-5 py-3 text-right text-[#64748B]">Rs. {{ number_format($stock->product->cost_price ?? 0, 2) }}</td>
                    <td class="px-5 py-3 text-center font-semibold">{{ $qty }}</td>
                    <td class="px-5 py-3 text-right font-semibold">Rs. {{ number_format($qty * ($stock->product->cost_price ?? 0), 2) }}</td>
                    <td class="px-5 py-3 text-center">
                        @if($qty <= 0)
                            <x-badge status="danger" label="Out of Stock"/>
                        @elseif($low)
                            <x-badge status="warning" label="Low Stock"/>
                        @else
                            <x-badge status="success" label="OK"/>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-[#E2E8F0]">{{ $stocks->links() }}</div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
