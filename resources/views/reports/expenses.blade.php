<x-layouts.app title="Expenses Report">
    <x-page-header title="Expenses Report" :breadcrumbs="[['label' => 'Reports', 'url' => '#'], ['label' => 'Expenses', 'url' => '#']]"/>
    <div class="p-6">
        <form method="GET" class="flex gap-3 mb-5 flex-wrap items-end">
            <div>
                <label class="form-label">From</label>
                <input type="date" name="from" value="{{ request('from', now()->startOfMonth()->toDateString()) }}" class="form-input w-36">
            </div>
            <div>
                <label class="form-label">To</label>
                <input type="date" name="to" value="{{ request('to', now()->toDateString()) }}" class="form-input w-36">
            </div>
            <div>
                <label class="form-label">Category</label>
                <select name="category" class="form-select w-44">
                    <option value="">All Categories</option>
                    @foreach($expenseCategories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">Generate</button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <x-stat-card title="Total Expenses" :value="'Rs. ' . number_format($summary['total'] ?? 0, 2)" icon="currency" color="red"/>
            <x-stat-card title="Transactions" :value="$summary['count'] ?? 0" icon="receipt" color="blue"/>
            <x-stat-card title="Average Expense" :value="'Rs. ' . number_format($summary['avg'] ?? 0, 2)" icon="chart" color="amber"/>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
            <div class="xl:col-span-2">
                <x-card class="overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E2E8F0]"><h3 class="font-semibold font-heading text-[#1A202C]">Expense List</h3></div>
                    @if($expenses->isEmpty())
                        <div class="px-5 py-8 text-center text-sm text-[#64748B]">No expenses in this period</div>
                    @else
                    <table class="w-full text-sm">
                        <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                            <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Date</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Description</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Category</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Amount</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Recorded By</th>
                        </tr></thead>
                        <tbody>
                        @foreach($expenses as $exp)
                        <tr class="table-row-alt border-b border-[#F1F5F9]">
                            <td class="px-5 py-3 text-[#64748B]">{{ $exp->expense_date->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-medium text-[#1A202C]">{{ $exp->description }}</td>
                            <td class="px-5 py-3 text-[#64748B]">{{ ucfirst($exp->category) }}</td>
                            <td class="px-5 py-3 text-right font-semibold text-red-600">Rs. {{ number_format($exp->amount, 2) }}</td>
                            <td class="px-5 py-3 text-[#64748B]">{{ $exp->user?->name ?? '—' }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="px-5 py-3 border-t border-[#E2E8F0]">{{ $expenses->links() }}</div>
                    @endif
                </x-card>
            </div>
            <div>
                <x-card class="p-5">
                    <h3 class="font-semibold font-heading text-[#1A202C] mb-4">By Category</h3>
                    <canvas id="expenseChart" height="280"></canvas>
                    <div class="mt-4 space-y-2">
                        @foreach($byCategory as $cat => $amount)
                        <div class="flex justify-between text-sm">
                            <span class="text-[#64748B]">{{ ucfirst($cat) }}</span>
                            <span class="font-semibold">Rs. {{ number_format($amount, 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const byCatData = @json($byCategory ?? []);
new Chart(document.getElementById('expenseChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(byCatData),
        datasets: [{ data: Object.values(byCatData), backgroundColor: ['#ef4444','#f97316','#f59e0b','#84cc16','#06b6d4','#8b5cf6'] }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});
</script>
@endpush
