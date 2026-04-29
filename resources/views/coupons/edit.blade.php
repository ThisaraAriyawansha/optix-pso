<x-layouts.app title="Edit Coupon">
    <x-page-header :title="'Edit — ' . $coupon->code" :breadcrumbs="[['label' => 'Coupons', 'url' => route('coupons.index')], ['label' => 'Edit', 'url' => '#']]"/>
    <div class="p-6">
        <form method="POST" action="{{ route('coupons.update', $coupon) }}" class="max-w-2xl">
            @csrf
            @method('PUT')
            <x-card class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Coupon Code *</label>
                        <input type="text" name="code" value="{{ old('code', $coupon->code) }}" class="form-input uppercase" required>
                        @error('code')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Discount Type *</label>
                        <select name="discount_type" class="form-select">
                            <option value="percentage" {{ old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed_amount" {{ old('discount_type', $coupon->discount_type) == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount (Rs.)</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Discount Value *</label>
                        <input type="number" name="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}" min="0" step="0.01" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Max Discount Amount (Rs.)</label>
                        <input type="number" name="max_discount_amount" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}" min="0" step="0.01" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Minimum Order Amount (Rs.)</label>
                        <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" min="0" step="0.01" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Usage Limit</label>
                        <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Expires At</label>
                        <input type="date" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->toDateString()) }}" class="form-input">
                    </div>
                    <div class="flex items-center gap-3 mt-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }} class="rounded border-[#CBD5E1] text-[#004080]">
                        <label for="is_active" class="form-label mb-0">Active</label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="2" class="form-textarea">{{ old('description', $coupon->description) }}</textarea>
                    </div>
                </div>
            </x-card>
            <div class="flex gap-3 mt-5">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('coupons.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>
