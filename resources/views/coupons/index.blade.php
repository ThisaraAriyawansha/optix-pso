<x-layouts.app title="Coupons">
    <x-page-header title="Coupons" :breadcrumbs="[['label' => 'Coupons', 'url' => '#']]">
        <x-slot name="actions">
            <a href="{{ route('coupons.create') }}" class="btn btn-primary">+ New Coupon</a>
        </x-slot>
    </x-page-header>
    <div class="p-6">
        <form method="GET" class="flex gap-3 mb-5">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Coupon code..." class="form-input w-64">
            <select name="status" class="form-select w-36">
                <option value="">All</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request()->hasAny(['search','status']))<a href="{{ route('coupons.index') }}" class="btn btn-ghost">Clear</a>@endif
        </form>
        <x-card class="overflow-hidden">
            @if($coupons->isEmpty())
                <x-empty-state title="No coupons found">
                    <x-slot name="actions"><a href="{{ route('coupons.create') }}" class="btn btn-primary">Create Coupon</a></x-slot>
                </x-empty-state>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Code</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Type</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Value</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Min Order</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Used / Limit</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Expires</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Status</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Actions</th>
                </tr></thead>
                <tbody>
                @foreach($coupons as $coupon)
                <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA]">
                    <td class="px-5 py-3 font-mono text-sm font-bold text-[#1A202C]">{{ $coupon->code }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ ucwords(str_replace('_',' ',$coupon->discount_type)) }}</td>
                    <td class="px-5 py-3 text-right font-semibold">{{ $coupon->discount_type === 'percentage' ? $coupon->discount_value . '%' : 'Rs. ' . number_format($coupon->discount_value, 2) }}</td>
                    <td class="px-5 py-3 text-right text-[#64748B]">{{ $coupon->min_order_amount ? 'Rs. ' . number_format($coupon->min_order_amount, 2) : '—' }}</td>
                    <td class="px-5 py-3 text-center text-[#64748B]">{{ $coupon->usage_count }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $coupon->expires_at?->format('d M Y') ?? 'Never' }}</td>
                    <td class="px-5 py-3 text-center">
                        @php
                            $expired = $coupon->expires_at && $coupon->expires_at->isPast();
                            $limitHit = $coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit;
                        @endphp
                        @if($expired || $limitHit)
                            <x-badge status="danger" label="Expired"/>
                        @elseif($coupon->is_active)
                            <x-badge status="success" label="Active"/>
                        @else
                            <x-badge status="gray" label="Inactive"/>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('coupons.edit', $coupon) }}" class="btn btn-ghost btn-sm">Edit</a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-[#E2E8F0]">{{ $coupons->links() }}</div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
