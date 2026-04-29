<x-layouts.app title="New Branch">
    <x-page-header title="New Branch" :breadcrumbs="[['label' => 'Settings', 'url' => '#'], ['label' => 'Branches', 'url' => route('settings.branches.index')], ['label' => 'New', 'url' => '#']]"/>
    <div class="p-6">
        <form method="POST" action="{{ route('settings.branches.store') }}" class="max-w-2xl">
            @csrf
            <x-card class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Branch Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Branch Code *</label>
                        <input type="text" name="code" value="{{ old('code') }}" class="form-input" placeholder="e.g. BR001" required>
                        @error('code')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-input">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Address</label>
                        <textarea name="address" rows="2" class="form-textarea">{{ old('address') }}</textarea>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="rounded border-[#CBD5E1] text-[#004080]">
                        <label for="is_active" class="form-label mb-0">Active</label>
                    </div>
                </div>
            </x-card>
            <div class="flex gap-3 mt-5">
                <button type="submit" class="btn btn-primary">Create Branch</button>
                <a href="{{ route('settings.branches.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>
