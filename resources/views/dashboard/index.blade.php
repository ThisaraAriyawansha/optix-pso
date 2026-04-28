<x-layouts.app title="Dashboard">
    <x-page-header title="Dashboard" :breadcrumbs="[]"/>

    <div class="p-6 space-y-6">
        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <x-stat-card title="Today's Sales" :value="$todaySales . ' orders'" icon="shopping-cart" color="primary"/>
            <x-stat-card title="Today's Revenue" :value="'Rs. ' . number_format($todayRevenue, 2)" icon="currency" color="success"/>
            <x-stat-card title="Active Repairs" :value="$activeRepairs" icon="wrench" color="warning"/>
            <x-stat-card title="Low Stock Items" :value="$lowStockCount" icon="exclamation" :color="$lowStockCount > 0 ? 'danger' : 'success'"/>
        </div>

        {{-- Chart + Low Stock --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2">
                <x-card class="p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold font-heading text-[#1A202C]">Sales — Last 7 Days</h2>
                        <span class="text-xs text-[#64748B]">Revenue (Rs.)</span>
                    </div>
                    <canvas id="salesChart" height="110"></canvas>
                </x-card>
            </div>

            <x-card class="overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-[#E2E8F0]">
                    <h2 class="font-semibold font-heading text-[#1A202C]">Low Stock Alerts</h2>
                    <a href="{{ route('stock.index') }}" class="text-xs text-[#004080] hover:underline">View all</a>
                </div>
                @forelse($lowStockItems as $item)
                <div class="flex items-center justify-between px-5 py-3 border-b border-[#F1F5F9]">
                    <div>
                        <p class="text-sm font-medium text-[#1A202C] truncate max-w-[160px]">{{ $item->product->name }}</p>
                        @if($item->variant)
                            <p class="text-xs text-[#64748B]">{{ $item->variant->name }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-[#DC2626]">{{ $item->qty }} left</p>
                        <p class="text-xs text-[#64748B]">Min: {{ $item->min_qty }}</p>
                    </div>
                </div>
                @empty
                    <x-empty-state title="Stock is healthy" description="No low stock items"/>
                @endforelse
            </x-card>
        </div>

        {{-- Recent Invoices + Active Repairs --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <x-card class="overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-[#E2E8F0]">
                    <h2 class="font-semibold font-heading text-[#1A202C]">Recent Invoices</h2>
                    <a href="{{ route('invoices.index') }}" class="text-xs text-[#004080] hover:underline">View all</a>
                </div>
                @if($recentInvoices->isEmpty())
                    <x-empty-state title="No invoices yet"/>
                @else
                <table class="w-full text-sm">
                    <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                        <th class="text-left px-5 py-2.5 text-xs font-semibold text-[#64748B] font-heading">Invoice</th>
                        <th class="text-left px-5 py-2.5 text-xs font-semibold text-[#64748B] font-heading">Customer</th>
                        <th class="text-right px-5 py-2.5 text-xs font-semibold text-[#64748B] font-heading">Total</th>
                        <th class="text-center px-5 py-2.5 text-xs font-semibold text-[#64748B] font-heading">Status</th>
                    </tr></thead>
                    <tbody>
                    @foreach($recentInvoices as $inv)
                    <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA]">
                        <td class="px-5 py-2.5">
                            <a href="{{ route('invoices.show', $inv) }}" class="text-[#004080] hover:underline font-medium text-xs">{{ $inv->invoice_number }}</a>
                        </td>
                        <td class="px-5 py-2.5 text-[#64748B] text-xs">{{ $inv->customer_display_name }}</td>
                        <td class="px-5 py-2.5 text-right font-medium text-[#1A202C] text-xs">Rs. {{ number_format($inv->total, 2) }}</td>
                        <td class="px-5 py-2.5 text-center"><x-badge :status="$inv->status"/></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                @endif
            </x-card>

            <x-card class="overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-[#E2E8F0]">
                    <h2 class="font-semibold font-heading text-[#1A202C]">Active Repairs</h2>
                    <a href="{{ route('repairs.index') }}" class="text-xs text-[#004080] hover:underline">View all</a>
                </div>
                @forelse($activeRepairJobs as $job)
                <div class="px-5 py-3 border-b border-[#F1F5F9]">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('repairs.show', $job) }}" class="text-sm font-medium text-[#004080] hover:underline">{{ $job->job_number }}</a>
                                <x-badge :status="$job->status"/>
                            </div>
                            <p class="text-xs text-[#64748B] mt-0.5 truncate">{{ $job->customer->name }} · {{ $job->device_type }}</p>
                        </div>
                        <span class="text-xs text-[#64748B] flex-shrink-0">{{ $job->received_date->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                    <x-empty-state title="No active repairs"/>
                @endforelse
            </x-card>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    new Chart(document.getElementById('salesChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Sales (Rs.)',
                data: @json($chartData),
                backgroundColor: 'rgba(0,64,128,0.12)',
                borderColor: '#004080',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => 'Rs. ' + ctx.parsed.y.toLocaleString() } } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#F1F5F9' }, ticks: { color: '#64748B', font: { size: 11 } } },
                x: { grid: { display: false }, ticks: { color: '#64748B', font: { size: 11 } } }
            }
        }
    });
    </script>
    @endpush
</x-layouts.app>
