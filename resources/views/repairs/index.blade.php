<x-layouts.app title="Repair Jobs">
    <x-page-header title="Repair Jobs" :breadcrumbs="[['label' => 'Repairs', 'url' => '#']]">
        <x-slot name="actions">
            <a href="{{ route('repairs.index', ['view' => 'kanban']) }}" class="btn btn-secondary btn-sm">Kanban</a>
            <a href="{{ route('repairs.create') }}" class="btn btn-primary">+ New Job</a>
        </x-slot>
    </x-page-header>
    <div class="p-6">
        <form method="GET" class="flex gap-3 mb-5">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Job # or customer..." class="form-input w-72">
            <select name="status" class="form-select w-44">
                <option value="">All Status</option>
                @foreach(['received','diagnosing','waiting_parts','in_repair','ready','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request()->hasAny(['search','status']))<a href="{{ route('repairs.index') }}" class="btn btn-ghost">Clear</a>@endif
        </form>
        <x-card class="overflow-hidden">
            @if($repairs->isEmpty())
                <x-empty-state title="No repair jobs found">
                    <x-slot name="actions"><a href="{{ route('repairs.create') }}" class="btn btn-primary">Create Repair Job</a></x-slot>
                </x-empty-state>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Job #</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Customer</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Device</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Technician</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Received</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Priority</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Status</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Actions</th>
                </tr></thead>
                <tbody>
                @foreach($repairs as $job)
                <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA]">
                    <td class="px-5 py-3"><a href="{{ route('repairs.show', $job) }}" class="text-[#004080] font-medium font-mono text-xs hover:underline">{{ $job->job_number }}</a></td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $job->customer->name }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $job->device_type }}{{ $job->device_brand ? ' · ' . $job->device_brand : '' }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $job->technician?->name ?? '—' }}</td>
                    <td class="px-5 py-3 text-[#64748B]">{{ $job->received_date->format('d M Y') }}</td>
                    <td class="px-5 py-3 text-center">
                        <x-badge :status="match($job->priority){'urgent'=>'danger','high'=>'warning','normal'=>'info',default=>'gray'}" :label="ucfirst($job->priority)"/>
                    </td>
                    <td class="px-5 py-3 text-center"><x-badge :status="$job->status_color" :label="$job->status_label"/></td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('repairs.show', $job) }}" class="btn btn-ghost btn-sm">View</a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-3 border-t border-[#E2E8F0]">{{ $repairs->links() }}</div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
