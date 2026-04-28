<x-layouts.app title="Edit Product">
    <x-page-header title="Edit Product" :breadcrumbs="[['label' => 'Products', 'url' => route('products.index')], ['label' => $product->name, 'url' => route('products.show', $product)], ['label' => 'Edit', 'url' => '#']]"/>

    <div class="p-6">
        <form method="POST" action="{{ route('products.update', $product) }}" class="max-w-3xl">
            @csrf @method('PUT')
            <x-card class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="form-label">Product Name <span class="text-[#DC2626]">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">— Select Category —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select">
                            <option value="">— Select Supplier —</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}" {{ old('supplier_id', $product->supplier_id) == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Barcode</label>
                        <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Brand</label>
                        <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Model</label>
                        <input type="text" name="model" value="{{ old('model', $product->model) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Cost Price (Rs.) <span class="text-[#DC2626]">*</span></label>
                        <input type="number" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" step="0.01" min="0" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Selling Price (Rs.) <span class="text-[#DC2626]">*</span></label>
                        <input type="number" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" step="0.01" min="0" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Tax Rate (%)</label>
                        <input type="number" name="tax_rate" value="{{ old('tax_rate', $product->tax_rate) }}" step="0.01" min="0" max="100" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ old('is_active', $product->is_active) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('is_active', $product->is_active) ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-textarea">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </x-card>

            <div class="flex items-center gap-3 mt-5">
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>
