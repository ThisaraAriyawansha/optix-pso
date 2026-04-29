<x-layouts.app title="Repair Kanban">
    <x-page-header title="Repair Jobs — Kanban" :breadcrumbs="[['label' => 'Repairs', 'url' => route('repairs.index')], ['label' => 'Kanban', 'url' => '#']]">
        <x-slot name="actions">
            <a href="{{ route('repairs.index') }}" class="btn btn-secondary btn-sm">List View</a>
            <a href="{{ route('repairs.create') }}" class="btn btn-primary">+ New Job</a>
        </x-slot>
    </x-page-header>

    <div class="p-6 overflow-x-auto">
        <div class="flex gap-4 min-w-max">
            @php
            $statusColors = [
                'received' => 'bg-blue-100 text-blue-800',
                'diagnosing' => 'bg-amber-100 text-amber-800',
                'waiting_parts' => 'bg-orange-100 text-orange-800',
                'in_repair' => 'bg-purple-100 text-purple-800',
                'ready' => 'bg-green-100 text-green-800',
                'delivered' => 'bg-gray-100 text-gray-600',
            ];
            $statusLabels = [
                'received' => 'Received',
                'diagnosing' => 'Diagnosing',
                'waiting_parts' => 'Waiting Parts',
                'in_repair' => 'In Repair',
                'ready' => 'Ready',
                'delivered' => 'Delivered',
            ];
            @endphp

            @foreach($statuses as $status)
            @if($status !== 'cancelled')
            <div class="kanban-column w-64">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-semibold font-heading text-[#64748B] uppercase tracking-wider">{{ $statusLabels[$status] ?? $status }}</h3>
                    <span class="badge badge-gray text-xs">{{ $jobs->get($status, collect())->count() }}</span>
                </div>

                @foreach($jobs->get($status, collect()) as $job)
                <div class="kanban-card">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <a href="{{ route('repairs.show', $job) }}" class="text-xs font-semibold text-[#004080] hover:underline font-mono">{{ $job->job_number }}</a>
                        <x-badge :status="match($job->priority){'urgent'=>'danger','high'=>'warning','normal'=>'info',default=>'gray'}" :label="ucfirst($job->priority)"/>
                    </div>
                    <p class="text-xs font-medium text-[#1A202C] mb-0.5">{{ $job->customer->name }}</p>
                    <p class="text-xs text-[#64748B]">{{ $job->device_type }}</p>
                    @if($job->device_brand)<p class="text-xs text-[#64748B]">{{ $job->device_brand }}</p>@endif
                    <div class="mt-2 pt-2 border-t border-[#F1F5F9] flex items-center justify-between">
                        <span class="text-xs text-[#64748B]">{{ $job->received_date->diffForHumans() }}</span>
                        @if($job->technician)
                            <span class="text-xs text-[#64748B]">{{ $job->technician->name }}</span>
                        @endif
                    </div>
                </div>
                @endforeach

                @if($jobs->get($status, collect())->isEmpty())
                <div class="text-center py-6 text-xs text-[#CBD5E1]">No jobs</div>
                @endif
            </div>
            @endif
            @endforeach
        </div>
    </div>
</x-layouts.app>
