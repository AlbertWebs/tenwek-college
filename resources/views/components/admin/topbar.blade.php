@props([
    'header' => 'Dashboard',
    'breadcrumbs' => null,
    'pageHint' => null,
])

@php
    $user = auth()->user();
    $soc = \App\Models\School::query()->where('slug', 'soc')->first();
    $cohs = \App\Models\School::query()->where('slug', 'cohs')->first();
    $managesSoc = $soc && $user->managesSchool($soc);
    $parts = array_values(array_filter(preg_split('/\s+/', trim((string) ($user->name ?? ''))) ?: []));
    $initials = strtoupper(
        count($parts) >= 2
            ? mb_substr($parts[0], 0, 1).mb_substr($parts[count($parts) - 1], 0, 1)
            : (mb_substr($parts[0] ?? 'A', 0, 2) ?: 'A'),
    );
    $adminDd = fn (bool $active): string => $active ? 'admin-dropdown-item admin-dropdown-item--active' : 'admin-dropdown-item';
    $isMainDashboard = request()->routeIs('admin.dashboard');
    $isSocCms = request()->routeIs('admin.soc.*') && ! request()->routeIs('admin.soc.media.*');
    $isSocMedia = request()->routeIs('admin.soc.media.*');
    $isNewDownload = request()->routeIs('admin.downloads.create');
    $boundDownload = request()->route('download');
    $isCohsDownloads = request()->routeIs('admin.downloads.*')
        && (request()->query('school') === 'cohs'
            || ($boundDownload instanceof \App\Models\Download && $boundDownload->school?->slug === 'cohs'));
@endphp

<header
    class="admin-topbar z-20 flex min-h-14 shrink-0 flex-wrap items-center gap-3 border-b border-thc-navy/[0.08] bg-white/80 px-3 py-2 shadow-sm backdrop-blur-md sm:px-4 lg:px-6"
>
    <button
        type="button"
        class="inline-flex rounded-lg p-2 text-thc-navy/90 transition hover:bg-thc-navy/[0.05] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-thc-royal lg:hidden"
        @click="sidebarOpen = true"
        aria-controls="admin-sidebar"
        aria-label="Open sidebar"
    >
        <x-admin.nav-icon name="bars-3" class="h-6 w-6 text-thc-navy" />
    </button>

    <div class="min-w-0 flex-1">
        @if (is_array($breadcrumbs) && count($breadcrumbs) > 0)
            <nav class="text-xs text-thc-text/60" aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-x-1.5 gap-y-1">
                    @foreach ($breadcrumbs as $i => $crumb)
                        <li class="flex items-center gap-1.5">
                            @if ($i > 0)
                                <span class="text-thc-navy/25" aria-hidden="true">/</span>
                            @endif
                            @if (! empty($crumb['href']) && $i < count($breadcrumbs) - 1)
                                <a href="{{ $crumb['href'] }}" class="font-medium text-thc-royal hover:underline">{{ $crumb['label'] }}</a>
                            @else
                                <span class="font-semibold text-thc-navy">{{ $crumb['label'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif
        <div @class([
            'flex min-w-0 items-center gap-2',
            'mt-0.5' => is_array($breadcrumbs) && count($breadcrumbs) > 0,
        ])>
            <h1 class="min-w-0 truncate text-base font-semibold text-thc-navy sm:text-lg">{{ $header }}</h1>
            @if (filled($pageHint))
                <x-admin.page-hint :text="$pageHint" />
            @endif
        </div>
    </div>

    <form action="{{ route('admin.search') }}" method="get" class="hidden max-w-[14rem] flex-1 items-center gap-2 rounded-xl border border-thc-navy/12 bg-white/90 px-2 py-1.5 shadow-sm sm:flex lg:max-w-xs" role="search">
        <x-admin.nav-icon name="magnifying-glass" class="h-4 w-4 text-thc-text/45" />
        <input
            type="search"
            name="q"
            value="{{ request()->routeIs('admin.search') ? request('q') : '' }}"
            placeholder="{{ __('Search admin…') }}"
            class="min-w-0 flex-1 border-0 bg-transparent text-sm text-thc-navy placeholder:text-thc-text/40 focus:ring-0"
            autocomplete="off"
            aria-label="{{ __('Search admin') }}"
        />
        <kbd class="hidden rounded border border-thc-navy/15 bg-thc-navy/[0.03] px-1.5 py-0.5 text-[10px] font-medium text-thc-text/50 lg:inline">/</kbd>
    </form>

    <div class="ml-auto flex items-center gap-1 sm:gap-2">
        <button
            type="button"
            class="rounded-lg p-2 text-thc-navy/70 transition hover:bg-thc-navy/[0.05] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-thc-royal"
            title="Notifications — coming soon"
            disabled
            aria-disabled="true"
        >
            <x-admin.nav-icon name="bell" class="h-5 w-5" />
        </button>

        <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
            <button
                type="button"
                class="inline-flex items-center gap-1 rounded-lg border border-thc-navy/10 bg-thc-navy/[0.03] px-2 py-1.5 text-xs font-semibold text-thc-navy transition hover:bg-thc-navy/[0.06] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-thc-royal"
                @click="open = !open"
                aria-haspopup="true"
                :aria-expanded="open.toString()"
            >
                <x-admin.nav-icon name="plus" class="h-4 w-4" />
                <span class="hidden sm:inline">Quick</span>
                <x-admin.nav-icon name="chevron-down" class="h-3.5 w-3.5 opacity-60" />
            </button>
            <div
                x-show="open"
                x-cloak
                @click.outside="open = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-xl border border-thc-navy/10 bg-white py-1 shadow-lg ring-1 ring-black/5"
                role="menu"
            >
                <a href="{{ route('admin.dashboard') }}" class="{{ $adminDd($isMainDashboard) }}" role="menuitem" @click="open = false" @if($isMainDashboard) aria-current="page" @endif>Main dashboard</a>
                @if ($managesSoc)
                    <a href="{{ route('admin.soc.dashboard') }}" class="{{ $adminDd($isSocCms) }}" role="menuitem" @click="open = false" @if($isSocCms) aria-current="page" @endif>SOC dashboard</a>
                @endif
                @can('create', App\Models\Download::class)
                    <a href="{{ route('admin.downloads.create') }}" class="{{ $adminDd($isNewDownload) }}" role="menuitem" @click="open = false" @if($isNewDownload) aria-current="page" @endif>New download</a>
                @endcan
                @if ($cohs && ($user->hasRole('super_admin') || $user->hasRole('cohs_admin')))
                    <a href="{{ route('admin.downloads.index', ['school' => 'cohs']) }}" class="{{ $adminDd($isCohsDownloads) }}" role="menuitem" @click="open = false" @if($isCohsDownloads) aria-current="page" @endif>COHS downloads</a>
                @endif
            </div>
        </div>

        <div class="relative" x-data="{ profileOpen: false }" @keydown.escape.window="profileOpen = false">
            <button
                type="button"
                class="flex items-center gap-2 rounded-full border border-thc-navy/10 bg-white py-1 pl-1 pr-2 shadow-sm transition hover:border-thc-royal/35 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-thc-royal"
                @click="profileOpen = !profileOpen"
                aria-haspopup="true"
                :aria-expanded="profileOpen.toString()"
                aria-label="Account menu"
            >
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-thc-navy to-thc-royal text-xs font-bold text-white">{{ $initials }}</span>
                <x-admin.nav-icon name="chevron-down" class="hidden h-3.5 w-3.5 text-thc-navy/50 sm:block" />
            </button>
            <div
                x-show="profileOpen"
                x-cloak
                @click.outside="profileOpen = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="absolute right-0 z-50 mt-2 w-64 origin-top-right rounded-xl border border-thc-navy/10 bg-white py-1 shadow-lg ring-1 ring-black/5"
                role="menu"
            >
                <p class="border-b border-thc-navy/8 px-3 py-2 text-xs text-thc-text/70">
                    Signed in as<br />
                    <span class="font-semibold text-thc-navy">{{ $user->email }}</span>
                </p>
                <a href="{{ route('home') }}" class="{{ $adminDd(request()->routeIs('home')) }}" role="menuitem" @click="profileOpen = false" @if(request()->routeIs('home')) aria-current="page" @endif>Website preview</a>
                @if ($managesSoc)
                    <a href="{{ route('admin.soc.dashboard') }}" class="{{ $adminDd($isSocCms) }}" role="menuitem" @click="profileOpen = false" @if($isSocCms) aria-current="page" @endif>Manage SOC</a>
                    <a href="{{ route('admin.soc.media.index') }}" class="{{ $adminDd($isSocMedia) }}" role="menuitem" @click="profileOpen = false" @if($isSocMedia) aria-current="page" @endif>Media library</a>
                @endif
                @if ($cohs && ($user->hasRole('super_admin') || $user->hasRole('cohs_admin')))
                    <a href="{{ route('admin.downloads.index', ['school' => 'cohs']) }}" class="{{ $adminDd($isCohsDownloads) }}" role="menuitem" @click="profileOpen = false" @if($isCohsDownloads) aria-current="page" @endif>Manage COHS downloads</a>
                    <a href="{{ route('schools.show', $cohs) }}" class="{{ $adminDd(request()->routeIs('schools.show') && request()->route('school')?->is($cohs)) }}" role="menuitem" rel="noopener" target="_blank" @click="profileOpen = false" @if(request()->routeIs('schools.show') && request()->route('school')?->is($cohs)) aria-current="page" @endif>Preview COHS site</a>
                @endif
                <span class="admin-dropdown-item cursor-not-allowed opacity-50" role="menuitem" title="Coming soon">My account</span>
                <span class="admin-dropdown-item cursor-not-allowed opacity-50" role="menuitem" title="Coming soon">Settings</span>
                <div class="my-1 border-t border-thc-navy/8"></div>
                <form method="post" action="{{ route('logout') }}" role="none">
                    @csrf
                    <button type="submit" class="admin-dropdown-item w-full text-left text-thc-maroon hover:bg-red-50" role="menuitem">Sign out</button>
                </form>
            </div>
        </div>
    </div>
</header>
