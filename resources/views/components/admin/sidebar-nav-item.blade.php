@props(['item', 'depth' => 0])

@php
    $type = $item['type'] ?? 'link';
@endphp

@if ($type === 'branch')
    @php
        $branchActive = \App\Support\Admin\AdminNav::anyChildActive($item['children']);
    @endphp
    <details class="admin-sidebar-details group/branch" @if ($branchActive) open @endif>
        <summary
            class="admin-sidebar-branch {{ $branchActive ? 'admin-sidebar-link-active' : '' }}"
            title="{{ $item['label'] }}"
        >
            <x-admin.nav-icon :name="$item['icon']" class="h-5 w-5 shrink-0 opacity-90" />
            <span class="min-w-0 flex-1 truncate text-left font-medium" x-show="!sidebarCollapsed" x-transition.opacity>{{ $item['label'] }}</span>
            <x-admin.nav-icon
                name="chevron-down"
                class="admin-sidebar-chevron h-4 w-4 shrink-0 opacity-60 transition-transform duration-200"
                x-show="!sidebarCollapsed"
            />
        </summary>
        <div class="ml-2 space-y-0.5 border-l border-white/[0.08] pl-2 pt-0.5">
            @foreach ($item['children'] as $child)
                <x-admin.sidebar-nav-item :item="$child" :depth="$depth + 1" />
            @endforeach
        </div>
    </details>
@elseif ($type === 'disabled')
    <span
        class="admin-sidebar-link admin-sidebar-link-disabled"
        title="{{ $item['title'] ?? '' }}"
        role="button"
        tabindex="0"
    >
        <x-admin.nav-icon :name="$item['icon']" class="h-5 w-5 shrink-0 opacity-50" />
        <span class="min-w-0 flex-1 truncate" x-show="!sidebarCollapsed" x-transition.opacity>{{ $item['label'] }}</span>
        <span class="admin-sidebar-soon-pill" x-show="!sidebarCollapsed" x-transition.opacity>Soon</span>
    </span>
@else
    @php
        $active = \App\Support\Admin\AdminNav::isActive($item);
        $external = ! empty($item['external']);
    @endphp
    <a
        href="{{ $item['href'] }}"
        @if ($external) target="_blank" rel="noopener noreferrer" @endif
        @click="closeMobileSidebar()"
        class="admin-sidebar-link {{ $active ? 'admin-sidebar-link-active' : '' }}"
        @if ($active) aria-current="page" @endif
        title="{{ $item['label'] }}"
    >
        <x-admin.nav-icon :name="$item['icon']" class="h-5 w-5 shrink-0 opacity-90" />
        <span class="min-w-0 flex-1 truncate font-medium" x-show="!sidebarCollapsed" x-transition.opacity>{{ $item['label'] }}</span>
        @if ($external)
            <x-admin.nav-icon name="arrow-top-right-on-square" class="h-3.5 w-3.5 shrink-0 opacity-40" x-show="!sidebarCollapsed" />
        @endif
    </a>
@endif
