<x-layouts.app title="Loyalty Points">
    <x-page-header title="Loyalty Programme" :breadcrumbs="[['label' => 'Loyalty', 'url' => '#']]"/>
    <div class="p-6">
        <form method="GET" class="flex gap-3 mb-5">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Customer name or phone..." class="form-input w-72">
            <button type="submit" class="btn btn-secondary">Search</button>
            @if(request('search'))<a href="{{ route('loyalty.index') }}" class="btn btn-ghost">Clear</a>@endif
        </form>
        <x-card class="overflow-hidden">
            @if($customers->isEmpty())
                <x-empty-state title="No loyalty members found"/>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Customer</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Phone</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Points Balance</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Lifetime Earned</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Redeemed</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Actions</th>
                </tr></thead>
                <tbody>
                @foreach($customers as $customer)
                <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA]">
                    <td class="px-5 py-3 font-medium text-[#1A202C]"><a href="{{ route('customers.show', $customer) }}" class="hover:text-[#004080] hover:underline">{{ $customer->name }}</a></td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $customer->phone ?? '—' }}</td>
                    <td class="px-5 py-3 text-right">
                        <span class="font-bold text-[#004080] text-base">{{ number_format($customer->loyalty_points) }}</span>
                        <span class="text-xs text-[#64748B] ml-1">pts</span>
                    </td>
                    <td class="px-5 py-3 text-right text-[#64748B]">{{ number_format($customer->loyalty_transactions_sum_points_earned ?? 0) }}</td>
                    <td class="px-5 py-3 text-right text-[#64748B]">{{ number_format($customer->loyalty_transactions_sum_points_redeemed ?? 0) }}</td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-ghost btn-sm">History</a>
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
