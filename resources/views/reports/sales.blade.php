<x-layouts.app title="Sales Report">
    <x-page-header title="Sales Report" :breadcrumbs="[['label' => 'Reports', 'url' => '#'], ['label' => 'Sales', 'url' => '#']]"/>
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
                <label class="form-label">Group By</label>
                <select name="group" class="form-select w-32">
                    <option value="day" {{ request('group','day') == 'day' ? 'selected' : '' }}>Day</option>
                    <option value="week" {{ request('group') == 'week' ? 'selected' : '' }}>Week</option>
                    <option value="month" {{ request('group') == 'month' ? 'selected' : '' }}>Month</option>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">Generate</button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <x-stat-card title="Total Revenue" :value="'Rs. ' . number_format($summary['revenue'] ?? 0, 2)" icon="currency" color="blue"/>
            <x-stat-card title="Total Sales" :value="$summary['count'] ?? 0" icon="receipt" color="green"/>
            <x-stat-card title="Average Sale" :value="'Rs. ' . number_format($summary['avg'] ?? 0, 2)" icon="chart" color="indigo"/>
            <x-stat-card title="Refunds" :value="'Rs. ' . number_format($summary['refunds'] ?? 0, 2)" icon="arrow" color="red"/>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
            <div class="xl:col-span-2">
                <x-card class="p-5">
                    <h3 class="font-semibold font-heading text-[#1A202C] mb-4">Revenue Over Time</h3>
                    <canvas id="salesChart" height="250"></canvas>
                </x-card>
            </div>
            <div>
                <x-card class="p-5">
                    <h3 class="font-semibold font-heading text-[#1A202C] mb-4">By Payment Method</h3>
                    <canvas id="paymentChart" height="250"></canvas>
                </x-card>
            </div>
        </div>

        <x-card class="overflow-hidden">
            <div class="px-5 py-4 border-b border-[#E2E8F0] flex items-center justify-between">
                <h3 class="font-semibold font-heading text-[#1A202C]">Breakdown</h3>
                <span class="text-xs text-[#64748B]">{{ count($rows) }} periods</span>
            </div>
            @if(empty($rows))
                <div class="px-5 py-8 text-center text-sm text-[#64748B]">No data for selected range</div>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Period</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Invoices</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Revenue</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Avg. Sale</th>
                </tr></thead>
                <tbody>
                @foreach($rows as $row)
                <tr class="table-row-alt border-b border-[#F1F5F9]">
                    <td class="px-5 py-3 font-medium text-[#1A202C]">{{ $row['period'] }}</td>
                    <td class="px-5 py-3 text-center text-[#64748B]">{{ $row['count'] }}</td>
                    <td class="px-5 py-3 text-right font-semibold">Rs. {{ number_format($row['revenue'], 2) }}</td>
                    <td class="px-5 py-3 text-right text-[#64748B]">Rs. {{ number_format($row['avg'], 2) }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </x-card>
    </div>
</x-layouts.app>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const chartLabels = @json(array_column($rows, 'period'));
const chartRevenue = @json(array_column($rows, 'revenue'));
const paymentData = @json($paymentBreakdown ?? []);

new Chart(document.getElementById('salesChart'), {
    type: 'bar',
    data: {
        labels: chartLabels,
        datasets: [{ label: 'Revenue (Rs.)', data: chartRevenue, backgroundColor: '#004080cc', borderRadius: 6 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

new Chart(document.getElementById('paymentChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(paymentData),
        datasets: [{ data: Object.values(paymentData), backgroundColor: ['#004080','#3b82f6','#10b981','#f59e0b','#ef4444'] }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>
@endpush
