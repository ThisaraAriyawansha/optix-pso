@props(['name' => 'modal', 'title' => '', 'maxWidth' => '560px'])

<div x-data x-show="$store.modals.{{ $name }}" @keydown.escape.window="$store.modals.{{ $name }} = false"
     class="modal-overlay" style="display: none;" x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="modal-box" style="max-width: {{ $maxWidth }}" @click.stop
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        @if($title)
        <div class="flex items-center justify-between px-5 py-4 border-b border-[#E2E8F0]">
            <h3 class="font-semibold font-heading text-[#1A202C]">{{ $title }}</h3>
            <button @click="$store.modals.{{ $name }} = false" class="p-1 text-[#64748B] hover:text-[#1A202C] rounded hover:bg-[#F5F7FA]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif
        <div class="p-5">{{ $slot }}</div>
    </div>
</div>
