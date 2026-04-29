<x-layouts.app title="Expenses">
    <x-page-header title="Expenses" :breadcrumbs="[['label' => 'Expenses', 'url' => '#']]">
        <x-slot name="actions">
            <a href="{{ route('expenses.create') }}" class="btn btn-primary">+ Record Expense</a>
        </x-slot>
    </x-page-header>
    <div class="p-6">
        <form method="GET" class="flex gap-3 mb-5 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Description..." class="form-input w-64">
            <select name="category" class="form-select w-44">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                @endforeach
            </select>
            <input type="date" name="from" value="{{ request('from') }}" class="form-input w-36">
            <input type="date" name="to" value="{{ request('to') }}" class="form-input w-36">
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request()->hasAny(['search','category','from','to']))<a href="{{ route('expenses.index') }}" class="btn btn-ghost">Clear</a>@endif
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
            <x-stat-card title="This Month" :value="'Rs. ' . number_format($monthTotal ?? 0, 2)" icon="currency" color="red"/>
            <x-stat-card title="Today" :value="'Rs. ' . number_format($todayTotal ?? 0, 2)" icon="calendar" color="amber"/>
            <x-stat-card title="Total (filtered)" :value="'Rs. ' . number_format($filteredTotal ?? 0, 2)" icon="chart" color="blue"/>
        </div>

        <x-card class="overflow-hidden">
            @if($expenses->isEmpty())
                <x-empty-state title="No expenses recorded">
                    <x-slot name="actions"><a href="{{ route('expenses.create') }}" class="btn btn-primary">Record Expense</a></x-slot>
                </x-empty-state>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Date</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Description</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Category</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Payment</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Amount</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Recorded By</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Actions</th>
                </tr></thead>
                <tbody>
                @foreach($expenses as $exp)
                <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA]">
                    <td class="px-5 py-3 text-[#64748B]">{{ $exp->expense_date->format('d M Y') }}</td>
                    <td class="px-5 py-3 font-medium text-[#1A202C]">{{ $exp->description }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ ucfirst($exp->category) }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ ucwords(str_replace('_',' ',$exp->payment_method ?? 'cash')) }}</td>
                    <td class="px-5 py-3 text-right font-bold text-red-600">Rs. {{ number_format($exp->amount, 2) }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $exp->user?->name ?? '—' }}</td>
                    <td class="px-5 py-3 text-center">
                        <form method="POST" action="{{ route('expenses.destroy', $exp) }}" onsubmit="return confirm('Delete this expense?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-ghost btn-sm text-red-500">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-[#E2E8F0]">{{ $expenses->links() }}</div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
