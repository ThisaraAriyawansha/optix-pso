<x-layouts.app title="Role Permissions">
    <x-page-header title="Role Permissions"
        :breadcrumbs="[['label' => 'Settings', 'url' => '#'], ['label' => 'Role Permissions', 'url' => '#']]"/>

    <div class="p-6 max-w-5xl">
        @if(session('success'))
            <x-alert type="success" :message="session('success')" class="mb-5"/>
        @endif

        <form method="POST" action="{{ route('settings.permissions.update') }}">
            @csrf

            <div class="space-y-6">
                {{-- Legend --}}
                <x-card class="p-5">
                    <p class="text-sm text-[#64748B]">
                        Toggle which sidebar features each role can access. <strong class="text-[#1A202C]">Admin</strong> always has full access to everything.
                        Changes take effect immediately.
                    </p>
                </x-card>

                {{-- Permission Grid --}}
                <x-card class="overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-[#F8FAFC] border-b border-[#E2E8F0]">
                                <th class="text-left px-5 py-3 font-semibold text-[#1A202C] w-1/2">Feature</th>
                                @foreach($roles as $role)
                                    <th class="text-center px-5 py-3 font-semibold text-[#1A202C] capitalize">
                                        {{ ucfirst($role) }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E2E8F0]">

                            {{-- Sections --}}
                            @php
                                $sections = [
                                    'Sales' => ['pos', 'quotations', 'invoices'],
                                    'Inventory' => ['products', 'categories', 'suppliers', 'stock', 'purchase_orders', 'transfers'],
                                    'Customers' => ['customers', 'installments', 'loyalty', 'coupons'],
                                    'Repairs' => ['repairs'],
                                    'Reports' => ['reports_sales', 'reports_stock', 'reports_expenses'],
                                ];
                            @endphp

                            @foreach($sections as $section => $keys)
                                <tr class="bg-[#F8FAFC]">
                                    <td colspan="{{ count($roles) + 1 }}" class="px-5 py-2">
                                        <span class="text-[10px] font-semibold text-[#64748B] uppercase tracking-wider">{{ $section }}</span>
                                    </td>
                                </tr>
                                @foreach($keys as $key)
                                    <tr class="hover:bg-[#F8FAFC] transition-colors">
                                        <td class="px-5 py-3 text-[#1A202C] font-medium">
                                            {{ $features[$key] }}
                                        </td>
                                        @foreach($roles as $role)
                                            <td class="text-center px-5 py-3">
                                                <label class="relative inline-flex items-center justify-center cursor-pointer">
                                                    <input type="hidden" name="{{ $role }}[{{ $key }}]" value="0">
                                                    <input type="checkbox"
                                                           name="{{ $role }}[{{ $key }}]"
                                                           value="1"
                                                           {{ $permissions[$role][$key] ? 'checked' : '' }}
                                                           class="sr-only peer">
                                                    <div class="w-10 h-5 bg-[#E2E8F0] peer-focus:outline-none rounded-full peer
                                                                peer-checked:after:translate-x-5 peer-checked:after:border-white
                                                                after:content-[''] after:absolute after:top-0.5 after:left-0.5
                                                                after:bg-white after:border after:border-gray-300 after:rounded-full
                                                                after:h-4 after:w-4 after:transition-all
                                                                peer-checked:bg-[#004080]">
                                                    </div>
                                                </label>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </x-card>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary px-8">Save Permissions</button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
