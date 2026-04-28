@props(['title' => '', 'breadcrumbs' => []])

<div class="px-6 py-5 flex items-center justify-between border-b border-[#E2E8F0] bg-white">
    <div>
        <h1 class="page-title">{{ $title }}</h1>
        @if(count($breadcrumbs) > 0)
        <nav class="flex items-center gap-1.5 mt-1 text-xs text-[#64748B]">
            <a href="{{ route('dashboard') }}" class="hover:text-[#004080]">Home</a>
            @foreach($breadcrumbs as $crumb)
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                @if($loop->last)
                    <span class="text-[#1A202C] font-medium">{{ $crumb['label'] }}</span>
                @else
                    <a href="{{ $crumb['url'] }}" class="hover:text-[#004080]">{{ $crumb['label'] }}</a>
                @endif
            @endforeach
        </nav>
        @endif
    </div>
    @if(isset($actions))
    <div class="flex items-center gap-2">
        {{ $actions }}
    </div>
    @endif
</div>
