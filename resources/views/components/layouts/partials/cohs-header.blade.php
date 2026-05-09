@php
    $L = $cohsLanding ?? config('tenwek.cohs_landing');
    $mainNav = $L['main_nav'] ?? [];
    $topBar = $L['top_bar'] ?? [];
    $page = fn (string $slug): string => route('schools.pages.show', [$school, $slug]);
    $portalUrl = filled($topBar['portal_url'] ?? null)
        ? $topBar['portal_url']
        : route('downloads.index', ['school' => $school->slug]);
    $topEmail = $topBar['email'] ?? 'info@tenwekhospitalcollege.ac.ke';
    $topCallDisplay = $topBar['call_display'] ?? '+254 700 000 000';
    $topCallTel = preg_replace('/[^\d+]/', '', $topBar['call_tel'] ?? '+254700000000');
    $logoPath = $L['logo'] ?? config('tenwek.cohs_landing.logo', config('tenwek.brand_logo'));
    $brandLogo = \App\Support\Cohs\CohsLandingRepository::publicMediaUrl(is_string($logoPath) ? $logoPath : null) ?? asset(is_string($logoPath) ? $logoPath : (string) config('tenwek.brand_logo'));

    $routeSchool = request()->route('school');
    $schoolContext = $routeSchool instanceof \App\Models\School && $routeSchool->is($school);
    $pageSlug = $schoolContext && request()->routeIs('schools.pages.show')
        ? (string) request()->route('pageSlug')
        : null;
    $isSchoolLanding = $schoolContext && request()->routeIs('schools.show');
    $onCampusApplication = request()->routeIs('cohs.on-campus-application');

    $megaChildSlugs = collect($mainNav)
        ->filter(fn ($i) => ! empty($i['mega_id']) && ! empty($i['children']))
        ->mapWithKeys(fn ($i) => [$i['mega_id'] => collect($i['children'])->pluck('slug')->filter()->values()->all()])
        ->all();

    $cohsChildHref = function (array $child) use ($page, $L): string {
        if (! empty($child['route'])) {
            return route($child['route']);
        }
        if (! empty($child['external_url'])) {
            return $child['external_url'];
        }
        if (! empty($child['external_config_key'])) {
            $key = $child['external_config_key'];
            $url = is_string($key) ? ($L[$key] ?? null) : null;

            return filled($url) ? (string) $url : '#';
        }
        if (! empty($child['slug'])) {
            return $page($child['slug']);
        }

        return '#';
    };
    $cohsTopLevelHref = function (array $item) use ($cohsChildHref): string {
        if (! empty($item['mega_id'])) {
            return '#';
        }

        return $cohsChildHref($item);
    };
    $cohsChildIsExternal = fn (array $child): bool => ! empty($child['external_url']) || ! empty($child['external_config_key']);

    $isMegaActive = function (string $megaId) use ($pageSlug, $megaChildSlugs, $onCampusApplication): bool {
        if ($megaId === 'cohs-application' && $onCampusApplication) {
            return true;
        }

        return $pageSlug !== null && in_array($pageSlug, $megaChildSlugs[$megaId] ?? [], true);
    };

    $navItemActive = function (array $item) use ($pageSlug, $isMegaActive): bool {
        if (! empty($item['mega_id'])) {
            return $isMegaActive($item['mega_id']);
        }
        if (! empty($item['route'])) {
            return request()->routeIs($item['route']);
        }
        if (! empty($item['slug'])) {
            return $pageSlug === $item['slug'];
        }

        return false;
    };
@endphp

<div class="hidden border-b border-white/15 bg-thc-navy text-white sm:block" aria-label="College contact and resources">
    <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-2 text-xs sm:flex-row sm:flex-wrap sm:items-center sm:justify-between sm:gap-x-6 sm:gap-y-1 sm:px-6 sm:text-sm lg:px-8">
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
                {{ __('College home') }}
            </a>
            <a
                href="{{ $portalUrl }}"
                class="inline-flex items-center gap-1.5 rounded-full border border-white/25 bg-white/10 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.12em] text-white transition hover:border-white/40 hover:bg-white/15 sm:text-[11px]"
            >
                {{ $topBar['portal_label'] ?? 'Downloads & forms' }}
                <svg class="h-3.5 w-3.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
    </div>
</div>

<header
    class="sticky top-0 z-50 overflow-visible border-b border-thc-navy/10 bg-white/95 shadow-sm shadow-thc-navy/5 backdrop-blur-md"
>
    <div class="relative mx-auto flex max-w-7xl items-center justify-between gap-2 overflow-visible px-4 py-2.5 sm:px-6 sm:py-3 lg:px-8 lg:py-3">
        <a
            href="{{ route('schools.show', $school) }}"
            class="group flex min-w-0 shrink-0 items-center gap-2 sm:gap-3"
            @if($isSchoolLanding) aria-current="page" @endif
            @click="openMega = null"
        >
            <img
                src="{{ $brandLogo }}"
                alt="{{ $school->name }}"
                width="320"
                height="64"
                class="h-12 w-auto max-w-[11.5rem] shrink-0 object-contain object-left sm:h-14 sm:max-w-[14rem] lg:h-16 lg:max-w-[18rem]"
                decoding="async"
            >
        </a>

        <nav class="hidden min-w-0 flex-1 items-center justify-end gap-0.5 py-1 lg:flex lg:flex-wrap lg:justify-center lg:gap-x-0.5 lg:gap-y-2 lg:px-2 xl:px-4" aria-label="College of Health Sciences">
            @foreach($mainNav as $item)
                @if(! empty($item['mega_id']) && ! empty($item['children']))
                    <div class="relative shrink-0">
                        <button
                            type="button"
                            class="flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium text-thc-navy/90 transition hover:bg-thc-navy/[0.06] hover:text-thc-royal"
                            @click.stop="toggleMega('{{ $item['mega_id'] }}')"
                            :class="(openMega === '{{ $item['mega_id'] }}' || @js($navItemActive($item))) ? 'bg-thc-navy/10 text-thc-navy font-semibold ring-1 ring-thc-navy/18' : ''"
                            :aria-expanded="(openMega === '{{ $item['mega_id'] }}').toString()"
                            aria-haspopup="true"
                        >
                            {{ $item['label'] }}
                            <svg class="h-4 w-4 shrink-0 opacity-60 transition" :class="{ 'rotate-180': openMega === '{{ $item['mega_id'] }}' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                @else
                    @php
                        $navActive = $navItemActive($item);
                        $slug = $item['slug'] ?? null;
                    @endphp
                    <a
                        href="{{ $slug ? $page($slug) : '#' }}"
                        class="shrink-0 rounded-lg px-3 py-2 text-sm font-medium transition {{ $navActive ? 'bg-thc-navy/10 text-thc-navy font-semibold ring-1 ring-thc-navy/18' : 'text-thc-navy/90 hover:bg-thc-navy/[0.06] hover:text-thc-royal' }}"
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

    @foreach($mainNav as $item)
        @if(! empty($item['mega_id']) && ! empty($item['children']))
            <div
                class="relative z-40 hidden border-t border-thc-navy/8 bg-white shadow-lg lg:block"
                x-show="openMega === '{{ $item['mega_id'] }}'"
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
                            <p class="thc-kicker">{{ $item['label'] }}</p>
                            <p class="mt-3 max-w-md text-sm leading-relaxed text-thc-text/90">
                                {{ $school->excerpt ?? $school->tagline ?? config('tenwek.tagline') }}
                            </p>
                        </div>
                        <ul class="grid gap-2 sm:grid-cols-2" role="list">
                            @foreach($item['children'] as $child)
                                @php
                                    if ($cohsChildIsExternal($child)) {
                                        $childNavActive = isset($child['slug']) && $pageSlug === $child['slug'];
                                    } elseif (! empty($child['route'])) {
                                        $childNavActive = request()->routeIs($child['route']);
                                    } else {
                                        $childNavActive = isset($child['slug']) && $pageSlug === $child['slug'];
                                    }
                                    $childHref = $cohsChildHref($child);
                                    $childNewTab = ! empty($child['open_new_tab']) && $cohsChildIsExternal($child);
                                @endphp
                                <li>
                                    <a
                                        href="{{ $childHref }}"
                                        @if($childNewTab) target="_blank" rel="noopener noreferrer" @endif
                                        class="flex items-center justify-between gap-3 rounded-xl border px-4 py-3 text-sm font-semibold transition hover:shadow-sm {{ $childNavActive ? 'border-thc-navy/30 bg-thc-navy/[0.08] text-thc-navy ring-1 ring-thc-navy/20 hover:border-thc-navy/40 hover:bg-thc-navy/[0.10]' : 'border-thc-navy/10 bg-thc-navy/[0.03] text-thc-navy hover:border-thc-navy/22 hover:bg-thc-navy/[0.06] hover:text-thc-royal' }}"
                                        @if($childNavActive) aria-current="page" @endif
                                        @click="openMega = null"
                                    >
                                        {{ $child['label'] }}
                                        @if($childNewTab)
                                            <svg class="h-4 w-4 shrink-0 text-thc-royal" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        @else
                                            <svg class="h-4 w-4 shrink-0 text-thc-royal" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <div
        x-show="mobileNavOpen"
        x-transition
        class="max-h-[min(85vh,calc(100dvh-4rem))] overflow-y-auto border-t border-thc-navy/10 bg-white lg:hidden"
        style="display: none;"
    >
        <div class="space-y-1 px-4 py-4">
            @foreach($mainNav as $item)
                @if(! empty($item['mega_id']) && ! empty($item['children']))
                    @php $mId = $item['mega_id']; @endphp
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-3 text-left text-sm font-semibold text-thc-navy hover:bg-thc-navy/[0.06] hover:text-thc-royal {{ $navItemActive($item) ? 'bg-thc-navy/10 font-semibold text-thc-navy ring-1 ring-inset ring-thc-navy/18' : '' }}"
                        data-mobile-section="{{ $mId }}"
                        @click="toggleMobile($event.currentTarget.dataset.mobileSection)"
                        :aria-expanded="(mobileExpanded === @json($mId)).toString()"
                    >
                        {{ $item['label'] }}
                        <svg class="h-4 w-4 transition" :class="{ 'rotate-180': mobileExpanded === @json($mId) }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="mobileExpanded === @json($mId)" class="pl-2" style="display: none;">
                        @foreach($item['children'] as $child)
                            @php
                                if ($cohsChildIsExternal($child)) {
                                    $childNavActive = isset($child['slug']) && $pageSlug === $child['slug'];
                                } elseif (! empty($child['route'])) {
                                    $childNavActive = request()->routeIs($child['route']);
                                } else {
                                    $childNavActive = isset($child['slug']) && $pageSlug === $child['slug'];
                                }
                                $childHref = $cohsChildHref($child);
                                $childNewTab = ! empty($child['open_new_tab']) && $cohsChildIsExternal($child);
                            @endphp
                            <a
                                href="{{ $childHref }}"
                                @if($childNewTab) target="_blank" rel="noopener noreferrer" @endif
                                class="block rounded-lg px-3 py-2.5 text-sm {{ $childNavActive ? 'bg-thc-navy/10 font-semibold text-thc-navy' : 'text-thc-navy/90 hover:bg-thc-navy/[0.06] hover:text-thc-royal' }}"
                                @if($childNavActive) aria-current="page" @endif
                                @click="mobileNavOpen = false"
                            >{{ $child['label'] }}</a>
                        @endforeach
                    </div>
                @else
                    @php
                        $navActive = $navItemActive($item);
                        $topHref = $cohsTopLevelHref($item);
                        $topExternal = $cohsChildIsExternal($item);
                        $topNewTab = ! empty($item['open_new_tab']) && $topExternal;
                    @endphp
                    <a
                        href="{{ $topHref }}"
                        @if($topNewTab) target="_blank" rel="noopener noreferrer" @endif
                        class="block rounded-lg px-3 py-3 text-sm font-semibold {{ $navActive ? 'bg-thc-navy/10 text-thc-navy ring-1 ring-inset ring-thc-navy/18' : 'text-thc-navy/90 hover:bg-thc-navy/[0.06] hover:text-thc-royal' }}"
                        @if($navActive) aria-current="page" @endif
                        @click="mobileNavOpen = false"
                    >{{ $item['label'] }}</a>
                @endif
            @endforeach

            <button
                type="button"
                class="mt-2 w-full rounded-full border border-thc-navy/20 bg-white py-3 text-center text-sm font-semibold text-thc-navy"
                @click="searchOpen = true; mobileNavOpen = false"
            >Search resources</button>
        </div>
    </div>
</header>
