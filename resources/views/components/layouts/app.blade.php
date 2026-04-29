@props(['title' => 'Dashboard'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - OptiX POS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#F5F7FA] font-body" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

{{-- TOP NAVBAR --}}
<header class="fixed top-0 left-0 right-0 h-14 bg-white border-b border-[#E2E8F0] z-30 flex items-center px-4 gap-4">
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = !sidebarOpen" class="p-1.5 rounded-md hover:bg-[#F5F7FA] text-[#64748B]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <a href="{{ route('dashboard') }}" class="flex items-center font-body font-semibold text-xl text-[#1A202C]">
                <img src="{{ asset('assets/img/pageImg/563436753467.png') }}" alt="OptiX" class="h-16 object-contain">
        </a>
    </div>

    <div class="hidden md:flex items-center gap-2 ml-2">
        <svg class="w-4 h-4 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        <span class="text-sm text-[#64748B] font-medium">{{ auth()->user()->branch->name ?? 'No Branch' }}</span>
    </div>

    <div class="flex-1"></div>

    <div class="relative p-2 text-[#64748B] hover:text-[#004080] hover:bg-[#F5F7FA] rounded-md cursor-pointer transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
    </div>

    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-md hover:bg-[#F5F7FA] transition-colors">
            <div class="w-8 h-8 rounded-full bg-[#004080] text-white flex items-center justify-center text-sm font-semibold font-heading">
                {{ auth()->user()->initials }}
            </div>
            <span class="hidden md:block text-sm font-medium text-[#1A202C]">{{ auth()->user()->name }}</span>
            <svg class="w-4 h-4 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="open" @click.away="open = false" x-transition
             class="absolute right-0 mt-1 w-48 bg-white border border-[#E2E8F0] rounded-lg shadow-lg z-50 py-1">
            <div class="px-4 py-2 border-b border-[#E2E8F0]">
                <p class="text-sm font-semibold text-[#1A202C]">{{ auth()->user()->name }}</p>
                <p class="text-xs text-[#64748B] capitalize">{{ auth()->user()->role }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-[#DC2626] hover:bg-[#FEF2F2]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </div>
</header>

{{-- Mobile sidebar backdrop --}}
<div x-show="sidebarOpen" @click="sidebarOpen = false"
     class="lg:hidden fixed inset-0 bg-black/40 z-[15]"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;"></div>

{{-- SIDEBAR --}}
<aside :class="sidebarOpen ? 'sidebar-expanded' : 'sidebar-collapsed'"
       class="fixed left-0 top-14 bottom-10 bg-white border-r border-[#E2E8F0] z-20 overflow-y-auto overflow-x-hidden transition-all duration-200">
    @php
        $perms = \App\Models\RolePermission::forRole(auth()->user()->role);
        $isAdmin = auth()->user()->isAdmin();
    @endphp
    <nav class="py-3 space-y-0.5">
        <div class="px-4 mb-1 mt-2">
            <p x-show="sidebarOpen" class="text-[10px] font-semibold text-[#64748B] uppercase tracking-wider">Main</p>
        </div>
        <x-sidebar-item href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="chart-bar" label="Dashboard"/>
        @if($isAdmin || ($perms['pos'] ?? false))
        <x-sidebar-item href="{{ route('pos.index') }}" :active="request()->routeIs('pos.*')" icon="shopping-cart" label="New Sale"/>
        @endif
        @if($isAdmin || ($perms['quotations'] ?? false))
        <x-sidebar-item href="{{ route('quotations.index') }}" :active="request()->routeIs('quotations.*')" icon="document-text" label="Quotations"/>
        @endif
        @if($isAdmin || ($perms['invoices'] ?? false))
        <x-sidebar-item href="{{ route('invoices.index') }}" :active="request()->routeIs('invoices.*')" icon="receipt-tax" label="Invoices"/>
        @endif

        @if($isAdmin || ($perms['products'] ?? false) || ($perms['categories'] ?? false) || ($perms['suppliers'] ?? false) || ($perms['stock'] ?? false) || ($perms['purchase_orders'] ?? false) || ($perms['transfers'] ?? false))
        <div class="px-4 mb-1 mt-4">
            <p x-show="sidebarOpen" class="text-[10px] font-semibold text-[#64748B] uppercase tracking-wider">Inventory</p>
        </div>
        @if($isAdmin || ($perms['products'] ?? false))
        <x-sidebar-item href="{{ route('products.index') }}" :active="request()->routeIs('products.*')" icon="cube" label="Products"/>
        @endif
        @if($isAdmin || ($perms['categories'] ?? false))
        <x-sidebar-item href="{{ route('categories.index') }}" :active="request()->routeIs('categories.*')" icon="tag" label="Categories"/>
        @endif
        @if($isAdmin || ($perms['suppliers'] ?? false))
        <x-sidebar-item href="{{ route('suppliers.index') }}" :active="request()->routeIs('suppliers.*')" icon="building-storefront" label="Suppliers"/>
        @endif
        @if($isAdmin || ($perms['stock'] ?? false))
        <x-sidebar-item href="{{ route('stock.index') }}" :active="request()->routeIs('stock.*')" icon="archive-box" label="Stock"/>
        @endif
        @if($isAdmin || ($perms['purchase_orders'] ?? false))
        <x-sidebar-item href="{{ route('purchase-orders.index') }}" :active="request()->routeIs('purchase-orders.*')" icon="truck" label="Purchase Orders"/>
        @endif
        @if($isAdmin || ($perms['transfers'] ?? false))
        <x-sidebar-item href="{{ route('transfers.index') }}" :active="request()->routeIs('transfers.*')" icon="arrows-right-left" label="Transfers"/>
        @endif
        @endif

        @if($isAdmin || ($perms['customers'] ?? false) || ($perms['installments'] ?? false) || ($perms['loyalty'] ?? false) || ($perms['coupons'] ?? false))
        <div class="px-4 mb-1 mt-4">
            <p x-show="sidebarOpen" class="text-[10px] font-semibold text-[#64748B] uppercase tracking-wider">Customers</p>
        </div>
        @if($isAdmin || ($perms['customers'] ?? false))
        <x-sidebar-item href="{{ route('customers.index') }}" :active="request()->routeIs('customers.*')" icon="users" label="Customers"/>
        @endif
        @if($isAdmin || ($perms['installments'] ?? false))
        <x-sidebar-item href="{{ route('installments.index') }}" :active="request()->routeIs('installments.*')" icon="credit-card" label="Installments"/>
        @endif
        @if($isAdmin || ($perms['loyalty'] ?? false))
        <x-sidebar-item href="{{ route('loyalty.index') }}" :active="request()->routeIs('loyalty.*')" icon="star" label="Loyalty Points"/>
        @endif
        @if($isAdmin || ($perms['coupons'] ?? false))
        <x-sidebar-item href="{{ route('coupons.index') }}" :active="request()->routeIs('coupons.*')" icon="ticket" label="Coupons"/>
        @endif
        @endif

        @if($isAdmin || ($perms['repairs'] ?? false))
        <div class="px-4 mb-1 mt-4">
            <p x-show="sidebarOpen" class="text-[10px] font-semibold text-[#64748B] uppercase tracking-wider">Repairs</p>
        </div>
        <x-sidebar-item href="{{ route('repairs.index') }}" :active="request()->routeIs('repairs.*')" icon="wrench-screwdriver" label="Repair Jobs"/>
        @endif

        @if($isAdmin || ($perms['reports_sales'] ?? false) || ($perms['reports_stock'] ?? false) || ($perms['reports_expenses'] ?? false))
        <div class="px-4 mb-1 mt-4">
            <p x-show="sidebarOpen" class="text-[10px] font-semibold text-[#64748B] uppercase tracking-wider">Reports</p>
        </div>
        @if($isAdmin || ($perms['reports_sales'] ?? false))
        <x-sidebar-item href="{{ route('reports.sales') }}" :active="request()->routeIs('reports.sales')" icon="chart-bar-square" label="Sales Report"/>
        @endif
        @if($isAdmin || ($perms['reports_stock'] ?? false))
        <x-sidebar-item href="{{ route('reports.stock') }}" :active="request()->routeIs('reports.stock')" icon="chart-pie" label="Stock Report"/>
        @endif
        @if($isAdmin || ($perms['reports_expenses'] ?? false))
        <x-sidebar-item href="{{ route('reports.expenses') }}" :active="request()->routeIs('reports.expenses')" icon="banknotes" label="Expense Report"/>
        @endif
        @endif

        @if($isAdmin)
        <div class="px-4 mb-1 mt-4">
            <p x-show="sidebarOpen" class="text-[10px] font-semibold text-[#64748B] uppercase tracking-wider">Settings</p>
        </div>
        <x-sidebar-item href="{{ route('settings.branches.index') }}" :active="request()->routeIs('settings.branches.*')" icon="building-office" label="Branches"/>
        <x-sidebar-item href="{{ route('settings.users.index') }}" :active="request()->routeIs('settings.users.*')" icon="user-group" label="Users & Roles"/>
        <x-sidebar-item href="{{ route('settings.permissions.index') }}" :active="request()->routeIs('settings.permissions.*')" icon="shield-check" label="Role Permissions"/>
        <x-sidebar-item href="{{ route('settings.system') }}" :active="request()->routeIs('settings.system')" icon="cog-6-tooth" label="System Settings"/>
        @endif
    </nav>
</aside>

{{-- MAIN CONTENT --}}
<main :class="sidebarOpen ? 'main-sidebar-expanded' : 'main-sidebar-collapsed'" class="pt-14 pb-10 transition-all duration-200 min-h-screen">
    @if(session('success'))
        <div class="mx-6 mt-4">
            <x-alert type="success" :message="session('success')"/>
        </div>
    @endif
    @if(session('error'))
        <div class="mx-6 mt-4">
            <x-alert type="error" :message="session('error')"/>
        </div>
    @endif
    {{ $slot }}
</main>

{{-- BOTTOM APP BAR --}}
<footer class="fixed bottom-0 left-0 right-0 h-10 bg-[#004080] text-white flex items-center px-4 gap-2 z-30 text-xs">
    <div class="flex items-center gap-1.5 shrink-0">
        <a href="{{ route('pos.index') }}" class="quick-action-btn">+ New Sale</a>
        <a href="{{ route('repairs.create') }}" class="quick-action-btn hidden sm:inline-flex">+ Repair</a>
        <a href="{{ route('quotations.create') }}" class="quick-action-btn hidden sm:inline-flex">+ Quote</a>
    </div>
    <div class="flex-1 text-center text-white/70 min-w-0 truncate">
        @livewire('dashboard.today-stats')
    </div>
    <div class="hidden md:block text-white/60 text-[11px] shrink-0">Copyright © {{ date('Y') }} OptiX. All rights reserved.</div>
</footer>

@livewireScripts
@stack('scripts')
</body>
</html>
