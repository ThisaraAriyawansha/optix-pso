@props(['title' => '', 'value' => '0', 'icon' => 'chart-bar', 'color' => 'primary', 'trend' => null, 'trendUp' => true, 'subtitle' => ''])

@php
$bgColors = [
    'primary' => 'bg-[#E8F0FB] text-[#004080]',
    'success' => 'bg-green-50 text-green-600',
    'warning' => 'bg-amber-50 text-amber-600',
    'danger'  => 'bg-red-50 text-red-600',
    'info'    => 'bg-blue-50 text-blue-600',
];
$iconBg = $bgColors[$color] ?? $bgColors['primary'];

$iconPaths = [
    'chart-bar'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
    'currency'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    'wrench'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
    'exclamation'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
    'shopping-cart' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>',
];
@endphp

<div class="stat-card">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <p class="text-sm text-[#64748B] font-medium">{{ $title }}</p>
            <p class="text-2xl font-semibold font-heading text-[#1A202C] mt-1">{{ $value }}</p>
            @if($subtitle)
                <p class="text-xs text-[#64748B] mt-1">{{ $subtitle }}</p>
            @endif
            @if($trend !== null)
            <div class="flex items-center gap-1 mt-2">
                @if($trendUp)
                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    <span class="text-xs text-green-600 font-medium">{{ $trend }}</span>
                @else
                    <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    <span class="text-xs text-red-600 font-medium">{{ $trend }}</span>
                @endif
                <span class="text-xs text-[#64748B]">vs yesterday</span>
            </div>
            @endif
        </div>
        <div class="w-11 h-11 rounded-lg {{ $iconBg }} flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $iconPaths[$icon] ?? $iconPaths['chart-bar'] !!}
            </svg>
        </div>
    </div>
</div>
