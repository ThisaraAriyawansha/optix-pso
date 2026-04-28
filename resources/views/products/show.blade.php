<x-layouts.app title="{{ $product->name }}">
    <x-page-header :title="$product->name" :breadcrumbs="[['label' => 'Products', 'url' => route('products.index')], ['label' => $product->name, 'url' => '#']]">
        <x-slot name="actions">
            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">Edit Product</a>
        </x-slot>
    </x-page-header>

    <div class="p-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-5">
            <x-card class="p-5">
                <h3 class="font-semibold font-heading text-[#1A202C] mb-4">Product Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-[#64748B]">Category:</span> <span class="font-medium ml-2">{{ $product->category?->name ?? '—' }}</span></div>
                    <div><span class="text-[#64748B]">Brand:</span> <span class="font-medium ml-2">{{ $product->brand ?? '—' }}</span></div>
                    <div><span class="text-[#64748B]">Model:</span> <span class="font-medium ml-2">{{ $product->model ?? '—' }}</span></div>
                    <div><span class="text-[#64748B]">SKU:</span> <span class="font-medium font-mono ml-2">{{ $product->sku ?? '—' }}</span></div>
                    <div><span class="text-[#64748B]">Barcode:</span> <span class="font-medium font-mono ml-2">{{ $product->barcode ?? '—' }}</span></div>
                    <div><span class="text-[#64748B]">Tax Rate:</span> <span class="font-medium ml-2">{{ $product->tax_rate }}%</span></div>
                    <div><span class="text-[#64748B]">Cost Price:</span> <span class="font-medium ml-2">Rs. {{ number_format($product->cost_price, 2) }}</span></div>
                    <div><span class="text-[#64748B]">Selling Price:</span> <span class="font-semibold text-[#004080] ml-2">Rs. {{ number_format($product->selling_price, 2) }}</span></div>
                    <div><span class="text-[#64748B]">Status:</span> <x-badge :status="$product->is_active ? 'success' : 'gray'" :label="$product->is_active ? 'Active' : 'Inactive'" class="ml-2"/></div>
                    <div><span class="text-[#64748B]">Track Serials:</span> <span class="font-medium ml-2">{{ $product->track_serial ? 'Yes' : 'No' }}</span></div>
                </div>
                @if($product->description)
                    <div class="mt-4 pt-4 border-t border-[#E2E8F0]">
                        <p class="text-sm text-[#64748B] font-medium mb-1">Description</p>
                        <p class="text-sm text-[#1A202C]">{{ $product->description }}</p>
                    </div>
                @endif
            </x-card>

            @if($product->has_variants && $product->variants->isNotEmpty())
            <x-card class="overflow-hidden">
                <div class="px-5 py-4 border-b border-[#E2E8F0]">
                    <h3 class="font-semibold font-heading text-[#1A202C]">Variants</h3>
                </div>
                <table class="w-full text-sm">
                    <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                        <th class="text-left px-5 py-2.5 text-xs font-semibold text-[#64748B] font-heading">Name</th>
                        <th class="text-left px-5 py-2.5 text-xs font-semibold text-[#64748B] font-heading">SKU</th>
                        <th class="text-right px-5 py-2.5 text-xs font-semibold text-[#64748B] font-heading">Price</th>
                    </tr></thead>
                    <tbody>
                    @foreach($product->variants as $v)
                    <tr class="table-row-alt border-b border-[#F1F5F9]">
                        <td class="px-5 py-2.5 font-medium text-[#1A202C]">{{ $v->name }}</td>
                        <td class="px-5 py-2.5 font-mono text-xs text-[#64748B]">{{ $v->sku ?? '—' }}</td>
                        <td class="px-5 py-2.5 text-right font-medium">Rs. {{ number_format($v->selling_price, 2) }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </x-card>
            @endif
        </div>

        <div class="space-y-5">
            <x-card class="p-5">
                <h3 class="font-semibold font-heading text-[#1A202C] mb-4">Stock by Branch</h3>
                @forelse($product->stock as $s)
                <div class="flex items-center justify-between py-2 border-b border-[#F1F5F9] last:border-0">
                    <span class="text-sm text-[#64748B]">{{ $s->branch->name }}</span>
                    <div class="text-right">
                        <span class="text-sm font-semibold {{ $s->isLow() ? 'text-[#DC2626]' : 'text-[#16A34A]' }}">{{ $s->qty }}</span>
                        <span class="text-xs text-[#64748B]"> / {{ $s->min_qty }}</span>
                    </div>
                </div>
                @empty
                    <p class="text-sm text-[#64748B] text-center py-4">No stock records yet</p>
                @endforelse
            </x-card>
        </div>
    </div>
</x-layouts.app>
