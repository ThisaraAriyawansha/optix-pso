<x-layouts.app title="Technician Report">
    <x-page-header title="Technician Report" :breadcrumbs="[['label' => 'Reports', 'url' => '#'], ['label' => 'Technician', 'url' => '#']]"/>
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
                <label class="form-label">Technician</label>
                <select name="technician" class="form-select w-44">
                    <option value="">All Technicians</option>
                    @foreach($technicians as $t)
                        <option value="{{ $t->id }}" {{ request('technician') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">Generate</button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <x-stat-card title="Total Jobs" :value="$summary['total_jobs'] ?? 0" icon="wrench" color="blue"/>
            <x-stat-card title="Completed" :value="$summary['completed'] ?? 0" icon="check" color="green"/>
            <x-stat-card title="In Progress" :value="$summary['in_progress'] ?? 0" icon="clock" color="amber"/>
            <x-stat-card title="Revenue Generated" :value="'Rs. ' . number_format($summary['revenue'] ?? 0, 2)" icon="currency" color="indigo"/>
        </div>

        <x-card class="overflow-hidden">
            <div class="px-5 py-4 border-b border-[#E2E8F0]">
                <h3 class="font-semibold font-heading text-[#1A202C]">Performance by Technician</h3>
            </div>
            @if(empty($rows))
                <div class="px-5 py-8 text-center text-sm text-[#64748B]">No data for selected range</div>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Technician</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Assigned</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Completed</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">In Progress</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Avg. Days</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Revenue</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Completion %</th>
                </tr></thead>
                <tbody>
                @foreach($rows as $row)
                <tr class="table-row-alt border-b border-[#F1F5F9]">
                    <td class="px-5 py-3 font-medium text-[#1A202C]">{{ $row['name'] }}</td>
                    <td class="px-5 py-3 text-center text-[#64748B]">{{ $row['total'] }}</td>
                    <td class="px-5 py-3 text-center text-green-600 font-semibold">{{ $row['completed'] }}</td>
                    <td class="px-5 py-3 text-center text-amber-600">{{ $row['in_progress'] }}</td>
                    <td class="px-5 py-3 text-center text-[#64748B]">{{ number_format($row['avg_days'] ?? 0, 1) }}</td>
                    <td class="px-5 py-3 text-right font-semibold">Rs. {{ number_format($row['revenue'] ?? 0, 2) }}</td>
                    <td class="px-5 py-3 text-center">
                        @php $pct = $row['total'] ? round($row['completed'] / $row['total'] * 100) : 0; @endphp
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-[#F1F5F9] rounded-full h-1.5">
                                <div class="bg-[#004080] h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-xs text-[#64748B] w-8">{{ $pct }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </x-card>
    </div>
</x-layouts.app>
