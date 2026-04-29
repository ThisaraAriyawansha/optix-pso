<x-layouts.app title="System Settings">
    <x-page-header title="System Settings" :breadcrumbs="[['label' => 'Settings', 'url' => '#'], ['label' => 'System', 'url' => '#']]"/>
    <div class="p-6 max-w-3xl">
        @if(session('success'))
            <x-alert type="success" :message="session('success')" class="mb-5"/>
        @endif
        <form method="POST" action="{{ route('settings.system.update') }}">
            @csrf
            <div class="space-y-5">
                <x-card class="p-6">
                    <h3 class="font-semibold font-heading text-[#1A202C] mb-5">Shop Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="form-label">Shop Name</label>
                            <input type="text" name="settings[shop_name]" value="{{ $settings['shop_name'] ?? '' }}" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Phone</label>
                            <input type="text" name="settings[shop_phone]" value="{{ $settings['shop_phone'] ?? '' }}" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" name="settings[shop_email]" value="{{ $settings['shop_email'] ?? '' }}" class="form-input">
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-label">Address</label>
                            <textarea name="settings[shop_address]" rows="2" class="form-textarea">{{ $settings['shop_address'] ?? '' }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-label">Invoice Footer / Terms</label>
                            <textarea name="settings[invoice_footer]" rows="3" class="form-textarea" placeholder="Thank you for your business!">{{ $settings['invoice_footer'] ?? '' }}</textarea>
                        </div>
                    </div>
                </x-card>

                <x-card class="p-6">
                    <h3 class="font-semibold font-heading text-[#1A202C] mb-5">Financial Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label">Currency Symbol</label>
                            <input type="text" name="settings[currency_symbol]" value="{{ $settings['currency_symbol'] ?? 'Rs.' }}" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Tax Rate (%)</label>
                            <input type="number" name="settings[tax_rate]" value="{{ $settings['tax_rate'] ?? '0' }}" min="0" max="100" step="0.01" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Default Payment Method</label>
                            <select name="settings[default_payment_method]" class="form-select">
                                @foreach(['cash','card','bank_transfer','online'] as $m)
                                    <option value="{{ $m }}" {{ ($settings['default_payment_method'] ?? 'cash') == $m ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$m)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </x-card>

                <x-card class="p-6">
                    <h3 class="font-semibold font-heading text-[#1A202C] mb-5">Loyalty Programme</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label">Points per Rs. Spent</label>
                            <input type="number" name="settings[loyalty_points_per_unit]" value="{{ $settings['loyalty_points_per_unit'] ?? '100' }}" min="1" class="form-input">
                            <p class="text-xs text-[#64748B] mt-1">1 point earned per Rs. X spent</p>
                        </div>
                        <div>
                            <label class="form-label">Point Value (Rs. per point)</label>
                            <input type="number" name="settings[loyalty_point_value]" value="{{ $settings['loyalty_point_value'] ?? '1' }}" min="0" step="0.01" class="form-input">
                        </div>
                        <div class="md:col-span-2 flex items-center gap-3">
                            <input type="checkbox" name="settings[loyalty_enabled]" id="loyalty_enabled" value="1" {{ ($settings['loyalty_enabled'] ?? '1') == '1' ? 'checked' : '' }} class="rounded border-[#CBD5E1] text-[#004080]">
                            <label for="loyalty_enabled" class="form-label mb-0">Enable Loyalty Programme</label>
                        </div>
                    </div>
                </x-card>

                <x-card class="p-6">
                    <h3 class="font-semibold font-heading text-[#1A202C] mb-5">Repair Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label">Default Repair SLA (days)</label>
                            <input type="number" name="settings[repair_sla_days]" value="{{ $settings['repair_sla_days'] ?? '3' }}" min="1" class="form-input">
                        </div>
                    </div>
                </x-card>
            </div>
            <div class="mt-5">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</x-layouts.app>
