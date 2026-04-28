<x-layouts.app title="Edit Customer">
    <x-page-header title="Edit Customer" :breadcrumbs="[['label' => 'Customers', 'url' => route('customers.index')], ['label' => $customer->name, 'url' => route('customers.show', $customer)], ['label' => 'Edit', 'url' => '#']]"/>
    <div class="p-6">
        <form method="POST" action="{{ route('customers.update', $customer) }}" class="max-w-2xl">
            @csrf @method('PUT')
            <x-card class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="form-input">
                        @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="form-input">
                        @error('email')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div><label class="form-label">NIC / ID Number</label><input type="text" name="id_number" value="{{ old('id_number', $customer->id_number) }}" class="form-input"></div>
                    <div><label class="form-label">Credit Limit (Rs.)</label><input type="number" name="credit_limit" value="{{ old('credit_limit', $customer->credit_limit) }}" min="0" step="0.01" class="form-input"></div>
                    <div class="md:col-span-2"><label class="form-label">Address</label><textarea name="address" rows="2" class="form-textarea">{{ old('address', $customer->address) }}</textarea></div>
                    <div class="md:col-span-2"><label class="form-label">Notes</label><textarea name="notes" rows="2" class="form-textarea">{{ old('notes', $customer->notes) }}</textarea></div>
                </div>
            </x-card>
            <div class="flex gap-3 mt-5">
                <button type="submit" class="btn btn-primary">Update Customer</button>
                <a href="{{ route('customers.show', $customer) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>
