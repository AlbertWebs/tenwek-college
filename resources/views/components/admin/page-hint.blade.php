@props([
    'text',
])

<div class="relative inline-flex shrink-0 align-middle" x-data="{ open: false }">
    <button
        type="button"
        class="rounded-full p-1 text-thc-text/45 transition hover:bg-thc-navy/[0.06] hover:text-thc-royal focus:outline-none focus-visible:ring-2 focus-visible:ring-thc-royal/40"
        @click="open = !open"
        :aria-expanded="open.toString()"
        aria-label="{{ __('What this page manages') }}"
    >
        <svg class="h-4 w-4 sm:h-[1.125rem] sm:w-[1.125rem]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
        </svg>
    </button>
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.outside="open = false"
        @keydown.escape.window="open = false"
        class="absolute left-0 top-full z-[60] mt-2 w-[min(calc(100vw-2rem),22rem)] origin-top-left rounded-xl border border-thc-navy/12 bg-white p-3 text-left text-xs leading-relaxed text-thc-text/90 shadow-lg ring-1 ring-black/5 sm:left-auto sm:right-0 sm:origin-top-right"
        role="tooltip"
        id="admin-page-hint-popover"
    >
        {{ $text }}
    </div>
</div>
