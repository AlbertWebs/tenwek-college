@php
    $L = $socLanding ?? config('tenwek.soc_landing');
    $socLogo = $L['logo'] ?? 'logo-chaplain.png';
    $mainNav = $L['main_nav'] ?? [];
    $page = fn (string $slug): string => $slug === 'register'
        ? route('soc.register')
        : route('schools.pages.show', [$school, $slug]);
    $topBar = $L['top_bar'] ?? [];
    $navItemUrl = function (array $item) use ($page): string {
        if (! empty($item['external_url'])) {
            return $item['external_url'];
        }
        if (empty($item['slug'])) {
            return '#';
        }

        return $page($item['slug']);
    };
    $portalUrl = filled($topBar['portal_url'] ?? null)
        ? $topBar['portal_url']
        : route('downloads.index', ['school' => $school->slug]);
    $topEmail = $topBar['email'] ?? 'soc@tenwekhosp.org';
    $topCallDisplay = $topBar['call_display'] ?? '+254 728 091 900 – Ext 1315/1334';
    $topCallTel = preg_replace('/[^\d+]/', '', $topBar['call_tel'] ?? '+254728091900');
    $aboutMega = collect($mainNav)->first(fn ($i) => ! empty($i['children']));
    $socMegaId = 'soc-about';
    $megaIntro = $school->excerpt
        ?? ($school->tagline
            ? $school->tagline.' · '.config('tenwek.name').'.'
            : config('tenwek.tagline'));

    $routeSchool = request()->route('school');
    $schoolContext = $routeSchool instanceof \App\Models\School && $routeSchool->is($school);
    $pageSlug = $schoolContext && request()->routeIs('schools.pages.show')
        ? (string) request()->route('pageSlug')
        : null;
    $isSchoolLanding = $schoolContext && request()->routeIs('schools.show');
    $isSocRegister = request()->routeIs('soc.register');

    $aboutChildSlugs = collect($mainNav)
        ->flatMap(fn ($i) => $i['children'] ?? [])
        ->pluck('slug')
        ->filter()
        ->values()
        ->all();
    $isAboutNavActive = $pageSlug !== null && in_array($pageSlug, $aboutChildSlugs, true);

    $socProgrammeSlugs = $school->slug === 'soc'
        ? collect($L['academic_programmes']['groups'] ?? [])
            ->flatMap(fn (array $g) => $g['items'] ?? [])
            ->pluck('slug')
            ->filter()
            ->values()
            ->all()
        : [];

    $socMainNavItemActive = function (string $slug) use ($pageSlug, $socProgrammeSlugs): bool {
        if ($slug === 'academic-programmes') {
            return $pageSlug === 'academic-programmes'
                || ($pageSlug !== null && in_array($pageSlug, $socProgrammeSlugs, true));
        }

        return $pageSlug === $slug;
    };
@endphp

{{-- Utility strip: hidden on small screens to save vertical space; primary nav stays visible. --}}
<div class="hidden border-b border-white/15 bg-thc-navy text-white sm:block" aria-label="School contact and portal">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-2.5 text-xs sm:flex-row sm:flex-wrap sm:items-center sm:justify-between sm:gap-x-6 sm:gap-y-1 sm:px-6 sm:text-sm lg:px-8">
            <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center sm:gap-x-6 sm:gap-y-1">
                <a
                    href="mailto:{{ $topEmail }}"
                    class="inline-flex items-center gap-2 font-medium text-white/95 transition hover:text-white hover:underline"
                    aria-label="{{ __('Email :address', ['address' => $topEmail]) }}"
                >
                    <span class="inline-flex shrink-0 text-thc-royal/90" aria-hidden="true">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                    <span>{{ $topEmail }}</span>
                </a>
                <a
                    href="tel:{{ $topCallTel }}"
                    class="inline-flex items-center gap-2 font-medium text-white/95 transition hover:text-white hover:underline"
                    aria-label="{{ __('Call :number', ['number' => $topCallDisplay]) }}"
                >
                    <span class="inline-flex shrink-0 text-thc-royal/90" aria-hidden="true">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </span>
                    <span>{{ $topCallDisplay }}</span>
                </a>
            </div>
            <div class="flex flex-wrap items-center gap-2 self-start sm:self-auto">
                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center gap-1.5 rounded-full border border-white/20 bg-white/[0.07] px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.12em] text-white transition hover:border-white/35 hover:bg-white/15 sm:text-[11px]"
                >
                    {{ __('College Home') }}
                </a>
                <a
                    href="{{ $portalUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-1.5 rounded-full border border-white/25 bg-white/10 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.12em] text-white transition hover:border-white/40 hover:bg-white/15 sm:text-[11px]"
                    aria-label="{{ ($topBar['portal_label'] ?? 'Online Portal').' (opens in new tab)' }}"
                >
                    {{ $topBar['portal_label'] ?? 'Online Portal' }}
                    <svg class="h-3.5 w-3.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </div>
</div>

<header
    class="sticky top-0 z-50 overflow-visible border-b border-thc-navy/10 bg-white/95 shadow-sm shadow-thc-navy/5 backdrop-blur-md"
>
    {{-- overflow-x-auto on nav clips dropdowns (overflow-y becomes non-visible). Use wrap instead. --}}
    <div class="relative mx-auto flex max-w-7xl items-center justify-between gap-2 overflow-visible px-4 py-3 sm:px-6 lg:min-h-[4rem] lg:px-8">
        <a
            href="{{ route('schools.show', $school) }}"
            class="group flex min-w-0 shrink-0 items-center gap-2 sm:gap-3"
            @if($isSchoolLanding) aria-current="page" @endif
            @click="openMega = null"
        >
            <span class="flex h-10 shrink-0 items-center justify-center rounded-lg bg-thc-navy/[0.06] px-1 py-0.5 ring-1 ring-thc-navy/15 transition group-hover:bg-thc-navy/[0.09] group-hover:ring-thc-navy/25">
                <img
                    src="{{ \App\Support\Soc\SocLandingRepository::publicMediaUrl($socLogo) }}"
                    alt="{{ $school->name }} logo"
                    width="144"
                    height="48"
                    class="h-9 w-auto max-w-[9rem] object-contain object-center sm:max-w-[9.5rem]"
                    decoding="async"
                >
            </span>
            <span class="min-w-0 leading-tight">
                <span class="block truncate text-sm font-semibold tracking-tight text-thc-navy group-hover:text-thc-royal">{{ $school->name }}</span>
                <span class="hidden truncate text-xs text-thc-text/65 sm:block">{{ config('tenwek.name') }}</span>
            </span>
        </a>

        <nav class="hidden min-w-0 flex-1 items-center justify-end gap-0.5 py-1 lg:flex lg:flex-wrap lg:justify-center lg:gap-x-0.5 lg:gap-y-2 lg:px-2 xl:px-4" aria-label="School of Chaplaincy">
            @foreach($mainNav as $item)
                @if(!empty($item['children']))
                    <div class="relative shrink-0">
                        <button
                            type="button"
                            class="flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium text-thc-navy/90 transition hover:bg-thc-navy/[0.06] hover:text-thc-royal"
                            @click.stop="toggleMega('{{ $socMegaId }}')"
                            :class="(openMega === '{{ $socMegaId }}' || @js($isAboutNavActive)) ? 'bg-thc-navy/10 text-thc-navy font-semibold ring-1 ring-thc-navy/18' : ''"
                            :aria-expanded="(openMega === '{{ $socMegaId }}').toString()"
                            aria-haspopup="true"
                        >
                            {{ $item['label'] }}
                            <svg class="h-4 w-4 shrink-0 opacity-60 transition" :class="{ 'rotate-180': openMega === '{{ $socMegaId }}' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                @else
                    @php
                        $isHighlight = !empty($item['highlight']);
                        $navActive = isset($item['slug']) ? $socMainNavItemActive($item['slug']) : false;
                        $linkClass = $isHighlight
                            ? 'shrink-0 rounded-lg border border-thc-maroon/35 bg-thc-maroon/8 px-3 py-2 text-sm font-semibold text-thc-maroon transition hover:bg-thc-maroon hover:text-white'
                            : 'shrink-0 rounded-lg px-3 py-2 text-sm font-medium text-thc-navy/90 transition hover:bg-thc-navy/[0.06] hover:text-thc-royal';
                        $activeClass = $navActive
                            ? ($isHighlight
                                ? 'bg-thc-maroon text-white ring-2 ring-thc-maroon/40 hover:bg-thc-maroon hover:text-white'
                                : 'bg-thc-navy/10 text-thc-navy font-semibold ring-1 ring-thc-navy/18')
                            : '';
                    @endphp
                    <a
                        href="{{ $navItemUrl($item) }}"
                        @if(!empty($item['open_new_tab'])) target="_blank" rel="noopener noreferrer" @endif
                        class="{{ trim($linkClass.' '.$activeClass) }}"
                        @if($navActive) aria-current="page" @endif
                        @click="openMega = null"
                    >{{ $item['label'] }}</a>
                @endif
            @endforeach


        </nav>

        <div class="flex shrink-0 items-center gap-0.5 sm:gap-2">
            <button
                type="button"
                class="inline-flex rounded-full p-2 text-thc-navy/75 transition hover:bg-thc-navy/[0.06] hover:text-thc-royal"
                @click="searchOpen = true; openMega = null; mobileNavOpen = false"
                aria-haspopup="dialog"
                aria-label="Open site search"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
            <a
                href="{{ route('soc.register') }}"
                class="hidden rounded-full border border-thc-maroon/25 bg-thc-maroon/8 px-3 py-2 text-xs font-semibold text-thc-maroon shadow-sm transition hover:bg-thc-maroon hover:text-white min-[480px]:inline-flex xl:px-4 xl:text-sm {{ $isSocRegister ? 'bg-thc-maroon text-white ring-2 ring-thc-maroon/45 hover:bg-thc-maroon hover:text-white' : '' }}"
                @if($isSocRegister) aria-current="page" @endif
                @click="openMega = null"
            >Register Online</a>
            <button
                type="button"
                class="inline-flex rounded-lg p-2 text-thc-navy/75 hover:bg-thc-navy/[0.06] hover:text-thc-royal lg:hidden"
                @click="mobileNavOpen = !mobileNavOpen; openMega = null"
                aria-label="Open menu"
                :aria-expanded="mobileNavOpen.toString()"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    @if($aboutMega)
        {{-- Full-width mega panel: same pattern as college header (hospital-style card links). --}}
        <div
            class="relative z-40 hidden border-t border-thc-navy/8 bg-white shadow-lg lg:block"
            x-show="openMega === '{{ $socMegaId }}'"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            style="display: none;"
        >
            <div class="mx-auto max-w-7xl px-6 py-8 lg:px-8">
                <div class="grid gap-10 md:grid-cols-2">
                    <div>
                        <p class="thc-kicker">{{ $aboutMega['label'] }}</p>
                        <p class="mt-3 max-w-md text-sm leading-relaxed text-thc-text/90">
                            {{ $megaIntro }}
                        </p>
                    </div>
                    <ul class="grid gap-2 sm:grid-cols-2" role="list">
                        @foreach($aboutMega['children'] as $child)
                            @php $childNavActive = isset($child['slug']) && $pageSlug === $child['slug']; @endphp
                            <li>
                                <a
                                    href="{{ $navItemUrl($child) }}"
                                    @if(!empty($child['open_new_tab'])) target="_blank" rel="noopener noreferrer" @endif
                                    class="flex items-center justify-between gap-3 rounded-xl border px-4 py-3 text-sm font-semibold transition hover:shadow-sm {{ $childNavActive ? 'border-thc-navy/30 bg-thc-navy/[0.08] text-thc-navy ring-1 ring-thc-navy/20 hover:border-thc-navy/40 hover:bg-thc-navy/[0.10]' : 'border-thc-navy/10 bg-thc-navy/[0.03] text-thc-navy hover:border-thc-navy/22 hover:bg-thc-navy/[0.06] hover:text-thc-royal' }}"
                                    @if($childNavActive) aria-current="page" @endif
                                    @click="openMega = null"
                                >
                                    {{ $child['label'] }}
                                    <svg class="h-4 w-4 shrink-0 text-thc-royal" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div
        x-show="mobileNavOpen"
        x-transition
        class="max-h-[min(85vh,calc(100dvh-4rem))] overflow-y-auto border-t border-thc-navy/10 bg-white lg:hidden"
        style="display: none;"
    >
        <div class="space-y-1 px-4 py-4">
            @foreach($mainNav as $item)
                @if(!empty($item['children']))
                    @php $aboutId = 'soc-about-mobile'; @endphp
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-3 text-left text-sm font-semibold text-thc-navy hover:bg-thc-navy/[0.06] hover:text-thc-royal {{ $isAboutNavActive ? 'bg-thc-navy/10 font-semibold text-thc-navy ring-1 ring-inset ring-thc-navy/18' : '' }}"
                        data-mobile-section="{{ $aboutId }}"
                        @click="toggleMobile($event.currentTarget.dataset.mobileSection)"
                        :aria-expanded="(mobileExpanded === @json($aboutId)).toString()"
                    >
                        {{ $item['label'] }}
                        <svg class="h-4 w-4 transition" :class="{ 'rotate-180': mobileExpanded === @json($aboutId) }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="mobileExpanded === @json($aboutId)" class="pl-2" style="display: none;">
                        @foreach($item['children'] as $child)
                            @php $childNavActive = $pageSlug === $child['slug']; @endphp
                            <a
                                href="{{ $page($child['slug']) }}"
                                class="block rounded-lg px-3 py-2.5 text-sm {{ $childNavActive ? 'bg-thc-navy/10 font-semibold text-thc-navy' : 'text-thc-navy/90 hover:bg-thc-navy/[0.06] hover:text-thc-royal' }}"
                                @if($childNavActive) aria-current="page" @endif
                                @click="mobileNavOpen = false"
                            >{{ $child['label'] }}</a>
                        @endforeach
                    </div>
                @else
                    @php
                        $isHighlight = !empty($item['highlight']);
                        $navActive = isset($item['slug']) ? $socMainNavItemActive($item['slug']) : false;
                        $mobileBase = $isHighlight
                            ? 'border border-thc-maroon/30 bg-thc-maroon/8 text-thc-maroon'
                            : 'text-thc-navy/90 hover:bg-thc-navy/[0.06] hover:text-thc-royal';
                        $mobileActive = $navActive
                            ? ($isHighlight
                                ? 'bg-thc-maroon text-white ring-2 ring-thc-maroon/35'
                                : 'bg-thc-navy/10 text-thc-navy ring-1 ring-inset ring-thc-navy/18')
                            : '';
                    @endphp
                    <a
                        href="{{ $navItemUrl($item) }}"
                        @if(!empty($item['open_new_tab'])) target="_blank" rel="noopener noreferrer" @endif
                        class="block rounded-lg px-3 py-3 text-sm font-semibold {{ trim($mobileBase.' '.$mobileActive) }}"
                        @if($navActive) aria-current="page" @endif
                        @click="mobileNavOpen = false"
                    >{{ $item['label'] }}</a>
                @endif
            @endforeach

            <!-- <a href="{{ route('home') }}" class="mt-2 block rounded-lg border border-thc-navy/15 px-3 py-3 text-center text-sm font-semibold text-thc-navy" @click="mobileNavOpen = false">College home</a> -->
            <button
                type="button"
                class="mt-2 w-full rounded-full border border-thc-navy/20 bg-white py-3 text-center text-sm font-semibold text-thc-navy"
                @click="searchOpen = true; mobileNavOpen = false"
            >Search resources</button>
            <a
                href="{{ route('soc.register') }}"
                class="mt-2 block rounded-full bg-thc-maroon px-4 py-3 text-center text-sm font-semibold text-white shadow-sm {{ $isSocRegister ? 'ring-2 ring-thc-maroon/50 ring-offset-2' : '' }}"
                @if($isSocRegister) aria-current="page" @endif
                @click="mobileNavOpen = false"
            >Register Online</a>
        </div>
    </div>
</header>
