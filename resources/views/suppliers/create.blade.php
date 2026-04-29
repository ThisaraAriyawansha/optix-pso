<x-layouts.app title="New Supplier">
    <x-page-header title="New Supplier" :breadcrumbs="[['label' => 'Suppliers', 'url' => route('suppliers.index')], ['label' => 'New', 'url' => '#']]"/>
    <div class="p-6">
        <form method="POST" action="{{ route('suppliers.store') }}" class="max-w-2xl">
            @csrf
            <x-card class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="form-label">Company Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Tax / Registration Number</label>
                        <input type="text" name="tax_number" value="{{ old('tax_number') }}" class="form-input">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Address</label>
                        <textarea name="address" rows="2" class="form-textarea">{{ old('address') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="2" class="form-textarea">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </x-card>
            <div class="flex gap-3 mt-5">
                <button type="submit" class="btn btn-primary">Create Supplier</button>
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>
