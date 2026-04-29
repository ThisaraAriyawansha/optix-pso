<x-layouts.app title="Stock Movements">
    <x-page-header title="Stock Movements" :breadcrumbs="[['label' => 'Stock', 'url' => route('stock.index')], ['label' => 'Movements', 'url' => '#']]"/>
    <div class="p-6">
        <form method="GET" class="flex gap-3 mb-5 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Product or reference..." class="form-input w-64">
            <select name="type" class="form-select w-44">
                <option value="">All Types</option>
                @foreach(['sale','purchase','adjustment','transfer_in','transfer_out','return'] as $t)
                    <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$t)) }}</option>
                @endforeach
            </select>
            <input type="date" name="from" value="{{ request('from') }}" class="form-input w-36">
            <input type="date" name="to" value="{{ request('to') }}" class="form-input w-36">
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request()->hasAny(['search','type','from','to']))<a href="{{ route('stock.movements') }}" class="btn btn-ghost">Clear</a>@endif
        </form>
        <x-card class="overflow-hidden">
            @if($movements->isEmpty())
                <x-empty-state title="No movements found"/>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Date</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Product</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Type</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Qty</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Before</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">After</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Reference</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">User</th>
                </tr></thead>
                <tbody>
                @foreach($movements as $m)
                <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA]">
                    <td class="px-5 py-3 text-[#64748B]">{{ $m->created_at->format('d M Y H:i') }}</td>
                    <td class="px-5 py-3 font-medium text-[#1A202C]">{{ $m->product->name }}</td>
                    <td class="px-5 py-3">
                        @php
                            $typeMap = ['sale'=>'badge-danger','purchase'=>'badge-success','adjustment'=>'badge-info','transfer_in'=>'badge-success','transfer_out'=>'badge-warning','return'=>'badge-info'];
                        @endphp
                        <span class="badge {{ $typeMap[$m->type] ?? 'badge-gray' }} text-xs">{{ ucwords(str_replace('_',' ',$m->type)) }}</span>
                    </td>
                    <td class="px-5 py-3 text-center font-semibold {{ $m->qty > 0 ? 'text-green-600' : 'text-red-500' }}">
                        {{ $m->qty > 0 ? '+' : '' }}{{ $m->qty }}
                    </td>
                    <td class="px-5 py-3 text-center text-[#64748B]">{{ $m->qty_before }}</td>
                    <td class="px-5 py-3 text-center text-[#64748B]">{{ $m->qty_after }}</td>
                    <td class="px-5 py-3 text-[#64748B] font-mono text-xs">{{ $m->reference ?? '—' }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $m->user?->name ?? '—' }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-[#E2E8F0]">{{ $movements->links() }}</div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
