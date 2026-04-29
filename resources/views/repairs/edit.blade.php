<x-layouts.app title="Edit Repair Job">
    <x-page-header :title="'Edit — ' . $repair->job_number" :breadcrumbs="[['label' => 'Repairs', 'url' => route('repairs.index')], ['label' => $repair->job_number, 'url' => route('repairs.show', $repair)], ['label' => 'Edit', 'url' => '#']]"/>
    <div class="p-6">
        <form method="POST" action="{{ route('repairs.update', $repair) }}" class="max-w-3xl">
            @csrf
            @method('PUT')
            <x-card class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Customer *</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">— Select Customer —</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ old('customer_id', $repair->customer_id) == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->phone }})</option>
                            @endforeach
                        </select>
                        @error('customer_id')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Technician</label>
                        <select name="technician_id" class="form-select">
                            <option value="">— Unassigned —</option>
                            @foreach($technicians as $t)
                                <option value="{{ $t->id }}" {{ old('technician_id', $repair->technician_id) == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Device Type *</label>
                        <input type="text" name="device_type" value="{{ old('device_type', $repair->device_type) }}" class="form-input" required>
                        @error('device_type')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Brand</label>
                        <input type="text" name="device_brand" value="{{ old('device_brand', $repair->device_brand) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Model</label>
                        <input type="text" name="device_model" value="{{ old('device_model', $repair->device_model) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Serial Number</label>
                        <input type="text" name="serial_number" value="{{ old('serial_number', $repair->serial_number) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select">
                            @foreach(['low','normal','high','urgent'] as $p)
                                <option value="{{ $p }}" {{ old('priority', $repair->priority) == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Estimated Cost (Rs.)</label>
                        <input type="number" name="estimated_cost" value="{{ old('estimated_cost', $repair->estimated_cost) }}" step="0.01" min="0" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Final Cost (Rs.)</label>
                        <input type="number" name="final_cost" value="{{ old('final_cost', $repair->final_cost) }}" step="0.01" min="0" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Promised Date</label>
                        <input type="date" name="promised_date" value="{{ old('promised_date', $repair->promised_date?->toDateString()) }}" class="form-input">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Reported Issue *</label>
                        <textarea name="reported_issue" rows="3" class="form-textarea" required>{{ old('reported_issue', $repair->reported_issue) }}</textarea>
                        @error('reported_issue')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Diagnosis</label>
                        <textarea name="diagnosis" rows="3" class="form-textarea" placeholder="Technician diagnosis...">{{ old('diagnosis', $repair->diagnosis) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="2" class="form-textarea">{{ old('notes', $repair->notes) }}</textarea>
                    </div>
                </div>
            </x-card>
            <div class="flex gap-3 mt-5">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('repairs.show', $repair) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>
