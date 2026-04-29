<x-layouts.app title="New Repair Job">
    <x-page-header title="New Repair Job" :breadcrumbs="[['label' => 'Repairs', 'url' => route('repairs.index')], ['label' => 'New Job', 'url' => '#']]"/>
    <div class="p-6">
        <form method="POST" action="{{ route('repairs.store') }}" class="max-w-3xl">
            @csrf
            <x-card class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Customer *</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">— Select Customer —</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->phone }})</option>
                            @endforeach
                        </select>
                        @error('customer_id')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Technician</label>
                        <select name="technician_id" class="form-select">
                            <option value="">— Unassigned —</option>
                            @foreach($technicians as $t)
                                <option value="{{ $t->id }}" {{ old('technician_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Device Type *</label>
                        <input type="text" name="device_type" value="{{ old('device_type') }}" class="form-input" placeholder="Laptop, Desktop, Printer..." required>
                        @error('device_type')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Brand</label>
                        <input type="text" name="device_brand" value="{{ old('device_brand') }}" class="form-input" placeholder="Dell, HP, Apple...">
                    </div>
                    <div>
                        <label class="form-label">Model</label>
                        <input type="text" name="device_model" value="{{ old('device_model') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Serial Number</label>
                        <input type="text" name="serial_number" value="{{ old('serial_number') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select">
                            @foreach(['low','normal','high','urgent'] as $p)
                                <option value="{{ $p }}" {{ old('priority', 'normal') == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Estimated Cost (Rs.)</label>
                        <input type="number" name="estimated_cost" value="{{ old('estimated_cost') }}" step="0.01" min="0" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Received Date *</label>
                        <input type="date" name="received_date" value="{{ old('received_date', today()->toDateString()) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Promised Date</label>
                        <input type="date" name="promised_date" value="{{ old('promised_date') }}" class="form-input">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Reported Issue *</label>
                        <textarea name="reported_issue" rows="3" class="form-textarea" required placeholder="Describe the issue reported by the customer...">{{ old('reported_issue') }}</textarea>
                        @error('reported_issue')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="2" class="form-textarea" placeholder="Internal notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </x-card>
            <div class="flex gap-3 mt-5">
                <button type="submit" class="btn btn-primary">Create Repair Job</button>
                <a href="{{ route('repairs.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>
