<x-layouts.app title="Branches">
    <x-page-header title="Branches" :breadcrumbs="[['label' => 'Settings', 'url' => '#'], ['label' => 'Branches', 'url' => '#']]">
        <x-slot name="actions">
            <a href="{{ route('settings.branches.create') }}" class="btn btn-primary">+ New Branch</a>
        </x-slot>
    </x-page-header>
    <div class="p-6">
        <x-card class="overflow-hidden">
            @if($branches->isEmpty())
                <x-empty-state title="No branches yet">
                    <x-slot name="actions"><a href="{{ route('settings.branches.create') }}" class="btn btn-primary">Create Branch</a></x-slot>
                </x-empty-state>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Name</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Code</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Address</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Phone</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Status</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Actions</th>
                </tr></thead>
                <tbody>
                @foreach($branches as $branch)
                <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA]">
                    <td class="px-5 py-3 font-semibold text-[#1A202C]">{{ $branch->name }}</td>
                    <td class="px-5 py-3 font-mono text-xs text-[#64748B]">{{ $branch->code }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $branch->address ?? '—' }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $branch->phone ?? '—' }}</td>
                    <td class="px-5 py-3 text-center">
                        <x-badge :status="$branch->is_active ? 'active' : 'inactive'" :label="$branch->is_active ? 'Active' : 'Inactive'"/>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('settings.branches.edit', $branch) }}" class="btn btn-ghost btn-sm">Edit</a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </x-card>
    </div>
</x-layouts.app>
