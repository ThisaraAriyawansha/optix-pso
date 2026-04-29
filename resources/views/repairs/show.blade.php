<x-layouts.app title="{{ $repair->job_number }}">
    <x-page-header :title="$repair->job_number" :breadcrumbs="[['label' => 'Repairs', 'url' => route('repairs.index')], ['label' => $repair->job_number, 'url' => '#']]">
        <x-slot name="actions">
            <a href="{{ route('repairs.edit', $repair) }}" class="btn btn-secondary btn-sm">Edit</a>
        </x-slot>
    </x-page-header>

    <div class="p-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="xl:col-span-2 space-y-5">
            <x-card class="p-5">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="font-semibold font-heading text-[#1A202C]">{{ $repair->customer->name }}</h3>
                        <p class="text-sm text-[#64748B]">{{ $repair->customer->phone }}</p>
                    </div>
                    <div class="text-right">
                        <x-badge :status="$repair->status_color" :label="$repair->status_label"/>
                        <p class="text-xs text-[#64748B] mt-1">Priority: {{ ucfirst($repair->priority) }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm border-t border-[#F1F5F9] pt-4">
                    <div><p class="text-[#64748B] text-xs mb-0.5">Device</p><p class="font-medium">{{ $repair->device_type }}</p></div>
                    <div><p class="text-[#64748B] text-xs mb-0.5">Brand</p><p class="font-medium">{{ $repair->device_brand ?? '—' }}</p></div>
                    <div><p class="text-[#64748B] text-xs mb-0.5">Model</p><p class="font-medium">{{ $repair->device_model ?? '—' }}</p></div>
                    <div><p class="text-[#64748B] text-xs mb-0.5">Serial #</p><p class="font-medium font-mono">{{ $repair->serial_number ?? '—' }}</p></div>
                    <div><p class="text-[#64748B] text-xs mb-0.5">Received</p><p class="font-medium">{{ $repair->received_date->format('d M Y') }}</p></div>
                    <div><p class="text-[#64748B] text-xs mb-0.5">Promised</p><p class="font-medium">{{ $repair->promised_date?->format('d M Y') ?? '—' }}</p></div>
                    <div><p class="text-[#64748B] text-xs mb-0.5">Technician</p><p class="font-medium">{{ $repair->technician?->name ?? '—' }}</p></div>
                    <div><p class="text-[#64748B] text-xs mb-0.5">Est. Cost</p><p class="font-medium">{{ $repair->estimated_cost ? 'Rs. ' . number_format($repair->estimated_cost, 2) : '—' }}</p></div>
                    <div><p class="text-[#64748B] text-xs mb-0.5">Final Cost</p><p class="font-semibold text-[#004080]">{{ $repair->final_cost ? 'Rs. ' . number_format($repair->final_cost, 2) : '—' }}</p></div>
                </div>
                <div class="mt-4 pt-4 border-t border-[#F1F5F9]">
                    <p class="text-xs font-semibold text-[#64748B] mb-1">Reported Issue</p>
                    <p class="text-sm text-[#1A202C]">{{ $repair->reported_issue }}</p>
                </div>
                @if($repair->diagnosis)
                <div class="mt-3">
                    <p class="text-xs font-semibold text-[#64748B] mb-1">Diagnosis</p>
                    <p class="text-sm text-[#1A202C]">{{ $repair->diagnosis }}</p>
                </div>
                @endif
            </x-card>

            {{-- Status Update --}}
            <x-card class="p-5">
                <h3 class="font-semibold font-heading text-[#1A202C] mb-4">Update Status</h3>
                <form method="POST" action="{{ route('repairs.status', $repair) }}" class="flex gap-3 flex-wrap">
                    @csrf
                    <select name="status" class="form-select w-44">
                        @foreach(['received','diagnosing','waiting_parts','in_repair','ready','delivered','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $repair->status == $s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="notes" class="form-input flex-1" placeholder="Status change note...">
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </x-card>

            {{-- Timeline --}}
            <x-card class="overflow-hidden">
                <div class="px-5 py-4 border-b border-[#E2E8F0]"><h3 class="font-semibold font-heading text-[#1A202C]">History</h3></div>
                <div class="divide-y divide-[#F1F5F9]">
                    @forelse($repair->history as $h)
                    <div class="flex gap-3 px-5 py-3">
                        <div class="w-2 h-2 rounded-full bg-[#004080] flex-shrink-0 mt-1.5"></div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                @if($h->from_status)
                                    <x-badge :status="$h->from_status" :label="ucwords(str_replace('_',' ',$h->from_status))"/>
                                    <span class="text-xs text-[#64748B]">→</span>
                                @endif
                                <x-badge :status="$h->to_status" :label="ucwords(str_replace('_',' ',$h->to_status))"/>
                            </div>
                            @if($h->notes)<p class="text-xs text-[#64748B] mt-1">{{ $h->notes }}</p>@endif
                            <p class="text-xs text-[#64748B] mt-0.5">{{ $h->user->name }} · {{ $h->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="px-5 py-4 text-sm text-[#64748B]">No history yet</div>
                    @endforelse
                </div>
            </x-card>
        </div>

        {{-- Parts --}}
        <div class="space-y-5">
            <x-card class="overflow-hidden">
                <div class="px-5 py-4 border-b border-[#E2E8F0]"><h3 class="font-semibold font-heading text-[#1A202C]">Parts Used</h3></div>
                @forelse($repair->parts as $part)
                <div class="flex items-center justify-between px-5 py-3 border-b border-[#F1F5F9]">
                    <div>
                        <p class="text-sm font-medium text-[#1A202C]">{{ $part->description }}</p>
                        <p class="text-xs text-[#64748B]">Qty: {{ $part->qty }}</p>
                    </div>
                    <span class="text-sm font-semibold">Rs. {{ number_format($part->line_total, 2) }}</span>
                </div>
                @empty
                <div class="px-5 py-4 text-sm text-[#64748B]">No parts recorded</div>
                @endforelse
            </x-card>
        </div>
    </div>
</x-layouts.app>
