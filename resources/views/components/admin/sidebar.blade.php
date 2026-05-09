@props(['adminNavGroups' => []])

@php
    $groups = $adminNavGroups;
@endphp

<aside
    id="admin-sidebar"
    class="admin-sidebar fixed inset-y-0 left-0 z-30 flex max-h-dvh min-h-0 flex-col border-r border-white/[0.08] bg-[var(--admin-sidebar-bg)] text-white/90 shadow-xl transition-[width,transform] duration-200 motion-reduce:transition-none lg:static lg:h-dvh lg:max-h-dvh lg:min-h-0 lg:shrink-0 lg:shadow-none"
    :class="[
        sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
        sidebarCollapsed ? 'lg:w-[4.25rem]' : 'lg:w-64',
        'w-64',
    ]"
    aria-label="Admin navigation"
>
    <div class="flex h-14 shrink-0 items-center gap-2 border-b border-white/[0.08] px-3">
        <a
            href="{{ route('admin.dashboard') }}"
            class="flex min-w-0 flex-1 items-center gap-2 rounded-lg px-1 py-1 text-sm font-semibold tracking-tight text-white transition hover:bg-white/[0.06] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-thc-royal"
            @click="closeMobileSidebar()"
        >
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-thc-royal/25 text-xs font-bold text-white">THC</span>
            <span class="truncate" x-show="!sidebarCollapsed" x-transition.opacity>{{ config('tenwek.name') }}</span>
        </a>
        <button
            type="button"
            class="hidden rounded-lg p-2 text-white/70 transition hover:bg-white/[0.06] hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-thc-royal lg:inline-flex"
            @click="toggleSidebarCollapse()"
            :aria-expanded="(!sidebarCollapsed).toString()"
            aria-controls="admin-sidebar"
            title="Collapse sidebar"
        >
            <x-admin.nav-icon name="bars-3-center-left" class="h-5 w-5" />
        </button>
        <button
            type="button"
            class="rounded-lg p-2 text-white/70 transition hover:bg-white/[0.06] hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-thc-royal lg:hidden"
            @click="sidebarOpen = false"
            aria-label="Close sidebar"
        >
            <span class="text-lg leading-none" aria-hidden="true">×</span>
        </button>
    </div>

    <nav class="min-h-0 flex-1 space-y-6 overflow-y-auto overscroll-y-contain px-2 py-4 text-sm" aria-label="Sections">
        @foreach ($groups as $group)
            <div class="space-y-1">
                <p class="admin-sidebar-group-label" x-show="!sidebarCollapsed" x-transition.opacity>{{ $group['label'] }}</p>
                @foreach ($group['items'] as $item)
                    <x-admin.sidebar-nav-item :item="$item" />
                @endforeach
            </div>
        @endforeach
    </nav>

    <div class="shrink-0 border-t border-white/[0.08] p-3">
        <div class="flex items-center gap-2 rounded-xl bg-white/[0.04] px-2 py-2 ring-1 ring-inset ring-white/[0.06]">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-thc-royal/30 text-xs font-bold text-white" aria-hidden="true">
                {{ strtoupper(\Illuminate\Support\Str::substr(auth()->user()->name ?? '?', 0, 1)) }}
            </span>
            <div class="min-w-0 flex-1" x-show="!sidebarCollapsed" x-transition.opacity>
                <p class="truncate text-xs font-semibold text-white">{{ auth()->user()->name }}</p>
                <p class="truncate text-[11px] text-white/45">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>
</aside>
