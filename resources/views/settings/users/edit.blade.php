<x-layouts.app title="Edit User">
    <x-page-header :title="'Edit — ' . $user->name" :breadcrumbs="[['label' => 'Settings', 'url' => '#'], ['label' => 'Users', 'url' => route('settings.users.index')], ['label' => 'Edit', 'url' => '#']]"/>
    <div class="p-6">
        <form method="POST" action="{{ route('settings.users.update', $user) }}" class="max-w-2xl">
            @csrf
            @method('PUT')
            <x-card class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                        @error('email')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Role *</label>
                        <select name="role" class="form-select" required>
                            @foreach(['admin','cashier','technician'] as $r)
                                <option value="{{ $r }}" {{ old('role', $user->role) == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Branch</label>
                        <select name="branch_id" class="form-select">
                            <option value="">— Select Branch —</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">New Password <span class="text-xs text-[#64748B] font-normal">(leave blank to keep)</span></label>
                        <input type="password" name="password" class="form-input">
                        @error('password')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-input">
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="rounded border-[#CBD5E1] text-[#004080]">
                        <label for="is_active" class="form-label mb-0">Active</label>
                    </div>
                </div>
            </x-card>
            <div class="flex gap-3 mt-5">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('settings.users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>
