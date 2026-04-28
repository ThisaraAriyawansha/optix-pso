<x-layouts.app title="Products">
    <x-page-header title="Products" :breadcrumbs="[['label' => 'Products', 'url' => route('products.index')]]">
        <x-slot name="actions">
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Product
            </a>
        </x-slot>
    </x-page-header>

    <div class="p-6">
        {{-- Filters --}}
        <form method="GET" class="flex gap-3 mb-5 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, SKU, barcode..." class="form-input w-72">
            <select name="category" class="form-select w-48">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request()->hasAny(['search', 'category']))
                <a href="{{ route('products.index') }}" class="btn btn-ghost">Clear</a>
            @endif
        </form>

        <x-card class="overflow-hidden">
            @if($products->isEmpty())
                <x-empty-state title="No products found" description="Add your first product to get started">
                    <x-slot name="actions">
                        <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
                    </x-slot>
                </x-empty-state>
            @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Product</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">SKU</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Category</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Cost</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Price</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Status</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA]">
                        <td class="px-5 py-3">
                            <a href="{{ route('products.show', $product) }}" class="font-medium text-[#1A202C] hover:text-[#004080]">{{ $product->name }}</a>
                            @if($product->brand)
                                <p class="text-xs text-[#64748B]">{{ $product->brand }} {{ $product->model }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-[#64748B] font-mono text-xs">{{ $product->sku ?? '—' }}</td>
                        <td class="px-5 py-3 text-[#64748B]">{{ $product->category?->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-right text-[#64748B]">Rs. {{ number_format($product->cost_price, 2) }}</td>
                        <td class="px-5 py-3 text-right font-medium text-[#1A202C]">Rs. {{ number_format($product->selling_price, 2) }}</td>
                        <td class="px-5 py-3 text-center">
                            <x-badge :status="$product->is_active ? 'success' : 'gray'" :label="$product->is_active ? 'Active' : 'Inactive'"/>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-ghost btn-sm">Edit</a>
                                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-sm text-[#DC2626]">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-[#E2E8F0]">
                {{ $products->links() }}
            </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
