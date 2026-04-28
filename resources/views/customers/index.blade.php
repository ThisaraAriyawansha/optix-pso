<x-layouts.app title="Customers">
    <x-page-header title="Customers" :breadcrumbs="[['label' => 'Customers', 'url' => '#']]">
        <x-slot name="actions">
            <a href="{{ route('customers.create') }}" class="btn btn-primary">+ Add Customer</a>
        </x-slot>
    </x-page-header>
    <div class="p-6">
        <form method="GET" class="flex gap-3 mb-5">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, phone, email..." class="form-input w-80">
            <button type="submit" class="btn btn-secondary">Search</button>
            @if(request('search'))<a href="{{ route('customers.index') }}" class="btn btn-ghost">Clear</a>@endif
        </form>
        <x-card class="overflow-hidden">
            @if($customers->isEmpty())
                <x-empty-state title="No customers found">
                    <x-slot name="actions"><a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a></x-slot>
                </x-empty-state>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Name</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Phone</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Email</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Loyalty Pts</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Invoices</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Actions</th>
                </tr></thead>
                <tbody>
                @foreach($customers as $c)
                <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA]">
                    <td class="px-5 py-3"><a href="{{ route('customers.show', $c) }}" class="font-medium text-[#004080] hover:underline">{{ $c->name }}</a></td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $c->phone ?? '—' }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $c->email ?? '—' }}</td>
                    <td class="px-5 py-3 text-right font-medium text-[#004080]">{{ number_format($c->loyalty_points) }}</td>
                    <td class="px-5 py-3 text-right text-[#64748B]">{{ $c->invoices_count }}</td>
                    <td class="px-5 py-3 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('customers.edit', $c) }}" class="btn btn-ghost btn-sm">Edit</a>
                            <a href="{{ route('customers.show', $c) }}" class="btn btn-ghost btn-sm">View</a>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-[#E2E8F0]">{{ $customers->links() }}</div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
