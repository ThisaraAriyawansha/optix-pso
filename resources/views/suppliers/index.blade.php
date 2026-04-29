<x-layouts.app title="Suppliers">
    <x-page-header title="Suppliers" :breadcrumbs="[['label' => 'Suppliers', 'url' => '#']]">
        <x-slot name="actions">
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">+ New Supplier</a>
        </x-slot>
    </x-page-header>
    <div class="p-6">
        <form method="GET" class="flex gap-3 mb-5">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Supplier name or email..." class="form-input w-72">
            <button type="submit" class="btn btn-secondary">Search</button>
            @if(request('search'))<a href="{{ route('suppliers.index') }}" class="btn btn-ghost">Clear</a>@endif
        </form>
        <x-card class="overflow-hidden">
            @if($suppliers->isEmpty())
                <x-empty-state title="No suppliers yet">
                    <x-slot name="actions"><a href="{{ route('suppliers.create') }}" class="btn btn-primary">Create Supplier</a></x-slot>
                </x-empty-state>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Name</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Contact</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Email</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Phone</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Balance</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Actions</th>
                </tr></thead>
                <tbody>
                @foreach($suppliers as $supplier)
                <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA]">
                    <td class="px-5 py-3 font-medium text-[#1A202C]">{{ $supplier->name }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $supplier->contact_person ?? '—' }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $supplier->email ?? '—' }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $supplier->phone ?? '—' }}</td>
                    <td class="px-5 py-3 text-right font-semibold {{ ($supplier->balance ?? 0) > 0 ? 'text-red-500' : 'text-[#64748B]' }}">
                        Rs. {{ number_format($supplier->balance ?? 0, 2) }}
                    </td>
                    <td class="px-5 py-3 text-center flex justify-center gap-1">
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-ghost btn-sm">Edit</a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-[#E2E8F0]">{{ $suppliers->links() }}</div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
