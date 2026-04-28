<x-layouts.app title="Add Product">
    <x-page-header title="Add Product" :breadcrumbs="[['label' => 'Products', 'url' => route('products.index')], ['label' => 'New Product', 'url' => '#']]"/>

    <div class="p-6">
        <form method="POST" action="{{ route('products.store') }}" class="max-w-3xl">
            @csrf
            <x-card class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="form-label">Product Name <span class="text-[#DC2626]">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input @error('name') border-[#DC2626] @enderror" required>
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">— Select Category —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select">
                            <option value="">— Select Supplier —</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku') }}" class="form-input @error('sku') border-[#DC2626] @enderror">
                        @error('sku')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Barcode</label>
                        <input type="text" name="barcode" value="{{ old('barcode') }}" class="form-input @error('barcode') border-[#DC2626] @enderror">
                        @error('barcode')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Brand</label>
                        <input type="text" name="brand" value="{{ old('brand') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Model</label>
                        <input type="text" name="model" value="{{ old('model') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Cost Price (Rs.) <span class="text-[#DC2626]">*</span></label>
                        <input type="number" name="cost_price" value="{{ old('cost_price', '0.00') }}" step="0.01" min="0" class="form-input @error('cost_price') border-[#DC2626] @enderror" required>
                        @error('cost_price')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Selling Price (Rs.) <span class="text-[#DC2626]">*</span></label>
                        <input type="number" name="selling_price" value="{{ old('selling_price', '0.00') }}" step="0.01" min="0" class="form-input @error('selling_price') border-[#DC2626] @enderror" required>
                        @error('selling_price')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Tax Rate (%)</label>
                        <input type="number" name="tax_rate" value="{{ old('tax_rate', '0') }}" step="0.01" min="0" max="100" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-textarea">{{ old('description') }}</textarea>
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="track_serial" value="1" {{ old('track_serial') ? 'checked' : '' }} class="w-4 h-4 text-[#004080] rounded border-[#E2E8F0]">
                            <span class="text-sm font-medium text-[#1A202C]">Track Serial Numbers</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="has_variants" value="1" {{ old('has_variants') ? 'checked' : '' }} class="w-4 h-4 text-[#004080] rounded border-[#E2E8F0]">
                            <span class="text-sm font-medium text-[#1A202C]">Has Variants</span>
                        </label>
                    </div>
                </div>
            </x-card>

            <div class="flex items-center gap-3 mt-5">
                <button type="submit" class="btn btn-primary">Save Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>
