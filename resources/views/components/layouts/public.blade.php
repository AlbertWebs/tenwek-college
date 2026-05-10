@props(['seo', 'landingHeader' => null, 'school' => null])

@php
    // School-scoped chrome (COHS / SOC header): infer from model if the layout only received :school.
    if ($landingHeader === null && $school instanceof \App\Models\School && in_array($school->slug, ['cohs', 'soc'], true)) {
        $landingHeader = $school->slug;
    }

    // Downloads hub: ?school=cohs|soc should use the same header even if the page forgot to pass props.
    if ($landingHeader === null && $school === null && request()->routeIs('downloads.index') && request()->filled('school')) {
        $schoolSlug = (string) request()->input('school');
        if ($schoolSlug === 'cohs' || $schoolSlug === 'soc') {
            $school = \App\Models\School::query()
                ->where('slug', $schoolSlug)
                ->where('is_active', true)
                ->first();
            if ($school !== null) {
                $landingHeader = $schoolSlug;
            }
        }
    }

    // School events listing/detail: use school header when viewing /{school}/events.
    if ($landingHeader === null && $school === null && request()->routeIs('schools.events.*')) {
        $routeSchool = request()->route('school');
        if ($routeSchool instanceof \App\Models\School && in_array($routeSchool->slug, ['soc', 'cohs'], true)) {
            $school = $routeSchool;
            $landingHeader = $routeSchool->slug;
        }
    }

    // Search: same as downloads when scoped with ?school=cohs|soc.
    if ($landingHeader === null && $school === null && request()->routeIs('search') && request()->filled('school')) {
        $schoolSlug = (string) request()->input('school');
        if ($schoolSlug === 'cohs' || $schoolSlug === 'soc') {
            $school = \App\Models\School::query()
                ->where('slug', $schoolSlug)
                ->where('is_active', true)
                ->first();
            if ($school !== null) {
                $landingHeader = $schoolSlug;
            }
        }
    }

    $showPublicHeader = ! request()->routeIs('home') || in_array($landingHeader, ['soc', 'cohs'], true);

    $primaryNavHref = function (array $item): string {
        if (! empty($item['url'])) {
            return $item['url'];
        }
        $u = route($item['route'], $item['params'] ?? []);
        if (! empty($item['fragment'])) {
            $u .= '#'.ltrim((string) $item['fragment'], '#');
        }

        return $u;
    };
@endphp

<!DOCTYPE html>
<html id="top" lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('ctc.jpg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <x-seo :seo="$seo" />
</head>
<body
    @class([
        'min-h-full bg-thc-surface text-thc-text antialiased',
        'thc-soc-skin' => $landingHeader === 'soc',
        'thc-cohs-skin' => $landingHeader === 'cohs',
    ])
    x-data="{
        mobileNavOpen: false,
        navScrolled: false,
        openMega: null,
        searchOpen: false,
        mobileExpanded: null,
        isHomePage: @json(request()->routeIs('home')),
        toggleMega(label) { this.openMega = this.openMega === label ? null : label },
        toggleMobile(label) { this.mobileExpanded = this.mobileExpanded === label ? null : label },
    }"
    @scroll.window="navScrolled = (window.pageYOffset || document.documentElement.scrollTop) > 10"
    @keydown.escape.window="openMega = null; searchOpen = false; mobileNavOpen = false"
>
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[60] focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:shadow-lg">Skip to content</a>

    @if($showPublicHeader)
    @if($landingHeader === 'soc' && $school)
        @include('components.layouts.partials.soc-header', ['school' => $school])
    @elseif($landingHeader === 'cohs' && $school)
        @include('components.layouts.partials.cohs-header', ['school' => $school])
    @else
    <header
        class="sticky top-0 z-50 border-b transition-[box-shadow,background-color,backdrop-filter] duration-300"
        :class="!isHomePage || navScrolled ? 'border-thc-navy/10 bg-white/95 shadow-sm shadow-thc-navy/5 backdrop-blur-md' : 'border-transparent bg-transparent shadow-none backdrop-blur-none'"
    >
        <div class="relative mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-3 sm:px-6 lg:min-h-[4rem] lg:px-8">
            <a href="{{ route('home') }}" class="group flex min-w-0 items-center gap-3" @click="openMega = null">
                <span
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-sm font-semibold shadow-sm ring-2 transition"
                    :class="!isHomePage || navScrolled ? 'bg-thc-navy text-white ring-thc-navy/35' : 'bg-white/15 text-white ring-white/40'"
                >THC</span>
                <span class="min-w-0 leading-tight">
                    <span
                        class="block truncate text-sm font-semibold tracking-tight"
                        :class="!isHomePage || navScrolled ? 'text-thc-navy group-hover:text-thc-royal' : 'text-white group-hover:text-white/90'"
                    >{{ config('tenwek.name') }}</span>
                    <span
                        class="hidden truncate text-xs sm:block"
                        :class="!isHomePage || navScrolled ? 'text-thc-text/65' : 'text-white/75'"
                    >{{ config('tenwek.hospital.name') }} · Bomet, Kenya</span>
                </span>
            </a>

            <nav class="hidden items-center gap-0.5 lg:flex" aria-label="Primary">
                @foreach(config('tenwek.navigation.primary') as $item)
                    @if(!empty($item['children']) || !empty($item['groups']))
                        @php $megaId = $item['label']; @endphp
                        <div class="relative">
                            <button
                                type="button"
                                class="flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium transition"
                                :class="[
                                    openMega === @json($megaId)
                                        ? (!isHomePage || navScrolled ? 'bg-thc-royal/12 text-thc-navy' : 'bg-white/15 text-white')
                                        : '',
                                    (!isHomePage || navScrolled) ? 'text-thc-text hover:bg-thc-royal/8 hover:text-thc-navy' : 'text-white/95 hover:bg-white/10 hover:text-white',
                                ]"
                                data-mega="{{ e($megaId) }}"
                                @click="toggleMega($event.currentTarget.dataset.mega)"
                                :aria-expanded="(openMega === @json($megaId)).toString()"
                                aria-haspopup="true"
                            >
                                {{ $item['label'] }}
                                <svg class="h-4 w-4 opacity-60 transition" :class="{ 'rotate-180': openMega === @json($megaId) }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>
                    @else
                        @php
                            $isActive = false;
                            if (($item['route'] ?? '') === 'home' && (($item['fragment'] ?? '') === 'schools')) {
                                $isActive = request()->routeIs('home');
                            } elseif (($item['route'] ?? '') === 'downloads.index') {
                                $isActive = request()->routeIs('downloads.*');
                            } elseif (($item['route'] ?? '') === 'news.index') {
                                $isActive = request()->routeIs('news.*');
                            } elseif (($item['route'] ?? '') === 'pages.show') {
                                $isActive = request()->routeIs('pages.show') && request()->route('slug') === ($item['params']['slug'] ?? null);
                            }
                        @endphp
                        <a
                            href="{{ $primaryNavHref($item) }}"
                            class="rounded-lg px-3 py-2 text-sm font-medium transition"
                            :class="@json($isActive)
                                ? (!isHomePage || navScrolled ? 'bg-thc-royal/12 text-thc-navy' : 'bg-white/15 text-white')
                                : ((!isHomePage || navScrolled) ? 'text-thc-text hover:bg-thc-royal/8 hover:text-thc-navy' : 'text-white/95 hover:bg-white/10')"
                            @click="openMega = null"
                        >{{ $item['label'] }}</a>
                    @endif
                @endforeach
            </nav>

            <div class="flex shrink-0 items-center gap-1 sm:gap-2">
                <button
                    type="button"
                    class="inline-flex rounded-full p-2 transition"
                    :class="!isHomePage || navScrolled ? 'text-thc-text hover:bg-thc-royal/8 hover:text-thc-navy' : 'text-white hover:bg-white/10'"
                    @click="searchOpen = true; openMega = null; mobileNavOpen = false"
                    aria-haspopup="dialog"
                    aria-label="Open site search"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
                <a
                    href="{{ route('contact.show') }}"
                    class="hidden rounded-full border px-4 py-2 text-sm font-medium shadow-sm transition md:inline-flex"
                    :class="!isHomePage || navScrolled ? 'border-thc-navy/15 bg-white text-thc-navy hover:border-thc-navy/25 hover:bg-thc-royal/8' : 'border-white/35 bg-white/10 text-white hover:bg-white/15'"
                    @click="openMega = null"
                >Contact</a>
                <button
                    type="button"
                    class="inline-flex rounded-lg p-2 lg:hidden"
                    :class="!isHomePage || navScrolled ? 'text-thc-text hover:bg-thc-royal/8' : 'text-white hover:bg-white/10'"
                    @click="mobileNavOpen = !mobileNavOpen; openMega = null"
                    aria-label="Open menu"
                    :aria-expanded="mobileNavOpen.toString()"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        {{-- Desktop mega panels --}}
        <div
            class="relative z-40 hidden border-t border-thc-navy/8 bg-white shadow-lg lg:block"
            x-show="openMega !== null"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            style="display: none;"
        >
            <div class="mx-auto max-w-7xl px-6 py-8 lg:px-8">
                @foreach(config('tenwek.navigation.primary') as $item)
                    @if(!empty($item['groups']))
                        <div x-show="openMega === @json($item['label'])" style="display: none;">
                            <div class="grid gap-10 lg:grid-cols-2">
                                <div>
                                    <p class="thc-kicker">{{ $item['label'] }}</p>
                                    <p class="mt-3 max-w-md text-sm leading-relaxed text-thc-text/90">
                                        {{ config('tenwek.tagline') }}
                                    </p>
                                </div>
                                <div class="grid gap-8 sm:grid-cols-2">
                                    @foreach($item['groups'] as $group)
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-thc-maroon">{{ $group['heading'] }}</p>
                                            <ul class="mt-3 grid gap-2">
                                                @foreach($group['children'] as $child)
                                                    <li>
                                                        <a
                                                            href="{{ route($child['route'], $child['params'] ?? []) }}"
                                                            class="flex items-center justify-between gap-3 rounded-xl border border-thc-navy/8 bg-thc-navy/[0.04] px-4 py-3 text-sm font-semibold text-thc-navy transition hover:border-thc-royal/25 hover:bg-white hover:shadow-sm"
                                                            @click="openMega = null"
                                                        >
                                                            {{ $child['label'] }}
                                                            <svg class="h-4 w-4 shrink-0 text-thc-royal" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @elseif(!empty($item['children']))
                        <div x-show="openMega === @json($item['label'])" style="display: none;">
                            <div class="grid gap-10 md:grid-cols-2">
                                <div>
                                    <p class="thc-kicker">{{ $item['label'] }}</p>
                                    <p class="mt-3 max-w-md text-sm leading-relaxed text-thc-text/90">
                                        {{ config('tenwek.tagline') }}
                                    </p>
                                </div>
                                <ul class="grid gap-2 sm:grid-cols-2">
                                    @foreach($item['children'] as $child)
                                        <li>
                                            <a
                                                href="{{ route($child['route'], $child['params'] ?? []) }}"
                                                class="flex items-center justify-between gap-3 rounded-xl border border-thc-navy/8 bg-thc-navy/[0.04] px-4 py-3 text-sm font-semibold text-thc-navy transition hover:border-thc-royal/25 hover:bg-white hover:shadow-sm"
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
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Mobile nav --}}
        <div
            x-show="mobileNavOpen"
            x-transition
            class="max-h-[min(70vh,calc(100dvh-4rem))] overflow-y-auto border-t border-thc-navy/10 bg-white lg:hidden"
            style="display: none;"
        >
            <div class="space-y-1 px-4 py-4">
                @foreach(config('tenwek.navigation.primary') as $item)
                    @if(!empty($item['groups']))
                        @php $mid = $item['label']; @endphp
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded-lg px-3 py-3 text-left text-sm font-semibold text-thc-navy hover:bg-thc-royal/8"
                            data-mobile-section="{{ e($mid) }}"
                            @click="toggleMobile($event.currentTarget.dataset.mobileSection)"
                            :aria-expanded="(mobileExpanded === @json($mid)).toString()"
                        >
                            {{ $item['label'] }}
                            <svg class="h-4 w-4 transition" :class="{ 'rotate-180': mobileExpanded === @json($mid) }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="mobileExpanded === @json($mid)" class="pl-2" style="display: none;">
                            @foreach($item['groups'] as $group)
                                <p class="px-3 pb-1 pt-3 text-[10px] font-bold uppercase tracking-[0.18em] text-thc-text/55">{{ $group['heading'] }}</p>
                                @foreach($group['children'] as $child)
                                    <a href="{{ route($child['route'], $child['params'] ?? []) }}" class="block rounded-lg px-3 py-2.5 text-sm text-thc-text hover:bg-thc-royal/8" @click="mobileNavOpen = false">{{ $child['label'] }}</a>
                                @endforeach
                            @endforeach
                        </div>
                    @elseif(!empty($item['children']))
                        @php $mid = $item['label']; @endphp
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded-lg px-3 py-3 text-left text-sm font-semibold text-thc-navy hover:bg-thc-royal/8"
                            data-mobile-section="{{ e($mid) }}"
                            @click="toggleMobile($event.currentTarget.dataset.mobileSection)"
                            :aria-expanded="(mobileExpanded === @json($mid)).toString()"
                        >
                            {{ $item['label'] }}
                            <svg class="h-4 w-4 transition" :class="{ 'rotate-180': mobileExpanded === @json($mid) }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="mobileExpanded === @json($mid)" class="pl-2" style="display: none;">
                            @foreach($item['children'] as $child)
                                <a href="{{ route($child['route'], $child['params'] ?? []) }}" class="block rounded-lg px-3 py-2.5 text-sm text-thc-text hover:bg-thc-royal/8" @click="mobileNavOpen = false">{{ $child['label'] }}</a>
                            @endforeach
                        </div>
                    @else
                        <a href="{{ $primaryNavHref($item) }}" class="block rounded-lg px-3 py-3 text-sm font-semibold text-thc-navy hover:bg-thc-royal/8" @click="mobileNavOpen = false">{{ $item['label'] }}</a>
                    @endif
                @endforeach
                <button
                    type="button"
                    class="mt-2 w-full rounded-full border border-thc-navy/20 bg-white py-3 text-center text-sm font-semibold text-thc-navy"
                    @click="searchOpen = true; mobileNavOpen = false"
                >
                    Search resources
                </button>
                <a href="{{ route('contact.show') }}" class="mt-2 block rounded-full bg-thc-royal px-4 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-thc-navy" @click="mobileNavOpen = false">Contact admissions</a>
            </div>
        </div>
    </header>
    @endif
    @endif

    {{-- Search dialog --}}
    <div
        x-show="searchOpen"
        x-cloak
        class="fixed inset-0 z-[100] flex items-start justify-center overflow-y-auto bg-thc-navy/50 px-4 py-16 sm:py-24"
        role="dialog"
        aria-modal="true"
        aria-labelledby="search-dialog-title"
        @keydown.escape.window="searchOpen = false"
    >
        <div class="absolute inset-0" @click="searchOpen = false" aria-hidden="true"></div>
        <div class="relative w-full max-w-lg rounded-2xl border border-thc-navy/12 bg-white p-6 shadow-2xl" @click.stop>
            <h2 id="search-dialog-title" class="font-serif text-xl font-semibold text-thc-navy">Search the site</h2>
            <p class="mt-2 text-sm text-thc-text/90">Find pages, news, admission packs, clinical documents, and other downloads.</p>
            <form method="get" action="{{ route('search') }}" class="mt-6 flex flex-col gap-3 sm:flex-row" role="search">
                @if($landingHeader && $school instanceof \App\Models\School && in_array($landingHeader, ['cohs', 'soc'], true))
                    <input type="hidden" name="school" value="{{ $school->slug }}">
                @endif
                <label class="sr-only" for="nav-search-q">Search query</label>
                <input
                    type="search"
                    name="q"
                    id="nav-search-q"
                    class="w-full rounded-xl border border-thc-navy/12 px-4 py-3 text-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20"
                    placeholder="What are you looking for?"
                    autofocus
                >
                <button type="submit" class="thc-btn-primary shrink-0 rounded-xl px-6">Search</button>
            </form>
            <button type="button" class="mt-4 text-sm font-medium text-thc-text/90 hover:text-thc-navy" @click="searchOpen = false">Close</button>
        </div>
    </div>

    <main id="main">
        {{ $slot }}
    </main>

    @if(request()->routeIs('home'))
        <x-landing.compact-footer />
    @else
    <footer class="thc-site-footer text-white">
        <a href="#top" class="thc-footer-backtotop">{{ __('Back to top') }}</a>

        <div class="mx-auto max-w-7xl px-4 pb-6 pt-12 sm:px-6 lg:px-8 lg:pb-10 lg:pt-16">
            <div class="grid gap-10 sm:gap-12 lg:grid-cols-4 lg:gap-8 xl:gap-10">
                {{-- Brand --}}
                <div class="lg:max-w-sm">
                    <p class="thc-footer-eyebrow">{{ __('Part of :hospital', ['hospital' => config('tenwek.hospital.name')]) }}</p>
                    <p class="thc-footer-brand">{{ config('tenwek.name') }}</p>
                    <p class="mt-4 text-sm leading-relaxed text-white/85">{{ config('tenwek.tagline') }}</p>
                    @foreach(array_filter(config('tenwek.footer.accreditation', [])) as $line)
                        <p class="mt-4 text-xs leading-relaxed text-white/60">{{ $line }}</p>
                    @endforeach

                    @if(count(config('tenwek.social', [])) > 0)
                        <p class="thc-footer-col-title mt-8">{{ __('Follow') }}</p>
                        <ul class="mt-3 flex flex-wrap gap-2">
                            @foreach(config('tenwek.social') as $platform => $url)
                                @php $label = config('tenwek.footer.social_labels.'.$platform, ucfirst($platform)); @endphp
                                <li>
                                    <a
                                        href="{{ $url }}"
                                        class="thc-footer-social-btn"
                                        rel="noopener noreferrer"
                                        aria-label="{{ $label }}"
                                    >
                                        @if($platform === 'facebook')
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                        @elseif($platform === 'x')
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                        @else
                                            <span class="text-[11px] font-bold">{{ strtoupper(substr($label, 0, 1)) }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                {{-- Schools --}}
                <div>
                    <h3 class="thc-footer-col-title">{{ __('Schools') }}</h3>
                    <ul class="mt-5 space-y-2.5">
                        <li>
                            <a href="{{ route('home') }}#schools" class="thc-footer-link">{{ __('Choose a school') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('schools.show', ['school' => 'soc']) }}" class="thc-footer-link">{{ __('School of Chaplaincy') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('schools.show', ['school' => 'cohs']) }}" class="thc-footer-link">{{ __('College of Health Sciences') }}</a>
                        </li>
                    </ul>
                </div>

                {{-- Same destinations as main nav + search --}}
                <div>
                    <h3 class="thc-footer-col-title">{{ __('On this site') }}</h3>
                    <ul class="mt-5 space-y-2.5">
                        @foreach(config('tenwek.navigation.primary') as $item)
                            <li>
                                <a href="{{ $primaryNavHref($item) }}" class="thc-footer-link">{{ $item['label'] }}</a>
                            </li>
                        @endforeach
                        <li>
                            <a href="{{ route('search') }}" class="thc-footer-link">{{ __('Search') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('contact.show') }}" class="thc-footer-link">{{ __('Contact') }}</a>
                        </li>
                    </ul>
                </div>

                {{-- Location & related organisations --}}
                <div>
                    <h3 class="thc-footer-col-title">{{ __('Visit & connect') }}</h3>
                    <ul class="mt-5 space-y-2 text-sm text-white/80">
                        <li>{{ config('tenwek.address.street') }}</li>
                        <li>{{ config('tenwek.address.locality') }}, {{ config('tenwek.address.country_name') }}</li>
                    </ul>
                    <p class="mt-4 text-sm text-white/80">
                        <span class="block text-[11px] font-bold uppercase tracking-[0.16em] text-white/45">{{ __('Phone') }}</span>
                        <a href="tel:{{ preg_replace('/\s+/', '', config('tenwek.phone')) }}" class="thc-footer-link mt-0.5 inline-block font-semibold text-white">{{ config('tenwek.phone') }}</a>
                    </p>
                    <p class="mt-3 text-sm text-white/80">
                        <span class="block text-[11px] font-bold uppercase tracking-[0.16em] text-white/45">{{ __('Email') }}</span>
                        <a href="mailto:{{ config('tenwek.email_public') }}" class="thc-footer-link mt-0.5 inline-block break-all font-semibold text-white">{{ config('tenwek.email_public') }}</a>
                    </p>
                    <p class="thc-footer-col-title mt-8">{{ __('Also at Tenwek') }}</p>
                    <ul class="mt-3 space-y-2.5 text-sm">
                        <li>
                            <a href="{{ config('tenwek.hospital.url') }}" class="thc-footer-link" rel="noopener noreferrer">{{ config('tenwek.hospital.name') }}</a>
                        </li>
                        <li>
                            <a href="{{ config('tenwek.ctc.url') }}" class="thc-footer-link" rel="noopener noreferrer">{{ config('tenwek.ctc.name') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        @php
            $footerSchoolSlug = ($landingHeader === 'soc' || $landingHeader === 'cohs') ? $landingHeader : null;
            $footerDownloadsUrl = $footerSchoolSlug
                ? route('downloads.index', ['school' => $footerSchoolSlug])
                : route('downloads.index');
            $footerSearchUrl = $footerSchoolSlug
                ? route('search', ['school' => $footerSchoolSlug])
                : route('search');
        @endphp
        <div class="thc-footer-sub">
            <div class="thc-footer-sub-inner">
                <div class="thc-footer-sub-row">
                    <p class="thc-footer-sub-copy">
                        © <strong>{{ now()->year }}</strong> {{ config('tenwek.institution_legal') }}.
                        <span class="text-white/45">{{ __('All rights reserved.') }}</span>
                    </p>
                    <nav class="thc-footer-sub-nav" aria-label="{{ __('Site footer') }}">
                        <a href="{{ route('home') }}#schools" class="thc-footer-sub-link">{{ __('Schools') }}</a>
                        <span class="thc-footer-sub-sep" aria-hidden="true">·</span>
                        <a href="{{ $footerDownloadsUrl }}" class="thc-footer-sub-link">{{ __('Downloads') }}</a>
                        <span class="thc-footer-sub-sep" aria-hidden="true">·</span>
                        <a href="{{ route('news.index') }}" class="thc-footer-sub-link">{{ __('News & Events') }}</a>
                        @if ($footerSchoolSlug)
                            <span class="thc-footer-sub-sep" aria-hidden="true">·</span>
                            <a href="{{ route('schools.events.index', $footerSchoolSlug) }}" class="thc-footer-sub-link">{{ __('School events') }}</a>
                        @endif
                        <span class="thc-footer-sub-sep" aria-hidden="true">·</span>
                        <a href="{{ route('pages.show', ['slug' => 'about']) }}" class="thc-footer-sub-link">{{ __('About') }}</a>
                        <span class="thc-footer-sub-sep" aria-hidden="true">·</span>
                        <a href="{{ $footerSearchUrl }}" class="thc-footer-sub-link">{{ __('Search') }}</a>
                        <span class="thc-footer-sub-sep" aria-hidden="true">·</span>
                        <a href="{{ route('contact.show') }}" class="thc-footer-sub-link">{{ __('Contact') }}</a>
                    </nav>
                </div>
                <div class="thc-footer-sub-meta">
                    <span class="font-medium text-white/40">{{ __('Also at Tenwek') }}</span>
                    <span class="hidden text-white/20 sm:inline" aria-hidden="true">·</span>
                    <a href="{{ config('tenwek.hospital.url') }}" rel="noopener noreferrer">{{ config('tenwek.hospital.name') }}</a>
                    <span class="text-white/20" aria-hidden="true">·</span>
                    <a href="{{ config('tenwek.ctc.url') }}" rel="noopener noreferrer">{{ config('tenwek.ctc.name') }}</a>
                </div>
            </div>
        </div>
    </footer>
    @endif
</body>
</html>
