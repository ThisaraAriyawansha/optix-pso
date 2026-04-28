<x-layouts.app title="{{ $customer->name }}">
    <x-page-header :title="$customer->name" :breadcrumbs="[['label' => 'Customers', 'url' => route('customers.index')], ['label' => $customer->name, 'url' => '#']]">
        <x-slot name="actions"><a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">Edit</a></x-slot>
    </x-page-header>
    <div class="p-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="space-y-5">
            <x-card class="p-5">
                <h3 class="font-semibold font-heading mb-4">Contact Info</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-[#64748B]">Phone</span><span class="font-medium">{{ $customer->phone ?? '—' }}</span></div>
                    <div class="flex justify-between"><span class="text-[#64748B]">Email</span><span class="font-medium">{{ $customer->email ?? '—' }}</span></div>
                    <div class="flex justify-between"><span class="text-[#64748B]">NIC</span><span class="font-medium">{{ $customer->id_number ?? '—' }}</span></div>
                    <div class="flex justify-between"><span class="text-[#64748B]">Address</span><span class="font-medium text-right max-w-[60%]">{{ $customer->address ?? '—' }}</span></div>
                    <div class="flex justify-between"><span class="text-[#64748B]">Loyalty Points</span><span class="font-semibold text-[#004080]">{{ number_format($customer->loyalty_points) }}</span></div>
                    <div class="flex justify-between"><span class="text-[#64748B]">Credit Balance</span><span class="font-medium">Rs. {{ number_format($customer->credit_balance, 2) }}</span></div>
                </div>
            </x-card>
        </div>
        <div class="xl:col-span-2 space-y-5">
            <x-card class="overflow-hidden">
                <div class="px-5 py-4 border-b border-[#E2E8F0]"><h3 class="font-semibold font-heading">Recent Invoices</h3></div>
                @forelse($customer->invoices as $inv)
                <div class="flex items-center justify-between px-5 py-3 border-b border-[#F1F5F9]">
                    <a href="{{ route('invoices.show', $inv) }}" class="text-sm font-medium text-[#004080] hover:underline">{{ $inv->invoice_number }}</a>
                    <span class="text-sm text-[#64748B]">{{ $inv->created_at->format('d M Y') }}</span>
                    <span class="text-sm font-semibold">Rs. {{ number_format($inv->total, 2) }}</span>
                    <x-badge :status="$inv->status"/>
                </div>
                @empty
                <x-empty-state title="No invoices yet"/>
                @endforelse
            </x-card>
        </div>
    </div>
</x-layouts.app>
