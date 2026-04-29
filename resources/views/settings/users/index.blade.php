<x-layouts.app title="Users">
    <x-page-header title="Users" :breadcrumbs="[['label' => 'Settings', 'url' => '#'], ['label' => 'Users', 'url' => '#']]">
        <x-slot name="actions">
            <a href="{{ route('settings.users.create') }}" class="btn btn-primary">+ New User</a>
        </x-slot>
    </x-page-header>
    <div class="p-6">
        <form method="GET" class="flex gap-3 mb-5">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..." class="form-input w-64">
            <select name="role" class="form-select w-36">
                <option value="">All Roles</option>
                @foreach(['admin','cashier','technician'] as $r)
                    <option value="{{ $r }}" {{ request('role') == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request()->hasAny(['search','role']))<a href="{{ route('settings.users.index') }}" class="btn btn-ghost">Clear</a>@endif
        </form>
        <x-card class="overflow-hidden">
            @if($users->isEmpty())
                <x-empty-state title="No users found">
                    <x-slot name="actions"><a href="{{ route('settings.users.create') }}" class="btn btn-primary">Create User</a></x-slot>
                </x-empty-state>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Name</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Email</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Role</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Branch</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Last Login</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Status</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Actions</th>
                </tr></thead>
                <tbody>
                @foreach($users as $user)
                <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA]">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-[#004080] flex items-center justify-center text-white text-xs font-bold flex-shrink-0">{{ $user->initials }}</div>
                            <span class="font-medium text-[#1A202C]">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $user->email }}</td>
                    <td class="px-5 py-3">
                        <span class="badge {{ match($user->role) { 'admin' => 'badge-primary', 'cashier' => 'badge-info', default => 'badge-gray' } }}">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $user->branch?->name ?? '—' }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $user->last_login ? $user->last_login->diffForHumans() : 'Never' }}</td>
                    <td class="px-5 py-3 text-center">
                        <x-badge :status="$user->is_active ? 'active' : 'inactive'" :label="$user->is_active ? 'Active' : 'Inactive'"/>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('settings.users.edit', $user) }}" class="btn btn-ghost btn-sm">Edit</a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-[#E2E8F0]">{{ $users->links() }}</div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
