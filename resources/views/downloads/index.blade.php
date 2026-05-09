@php
    $landingHeader = match ($filterSchool?->slug) {
        'cohs' => 'cohs',
        'soc' => 'soc',
        default => null,
    };
    $hasFilters = filled(request('q')) || filled(request('category')) || filled(request('school'));
    $isCohsHub = $filterSchool?->slug === 'cohs';
@endphp

<x-layouts.public :seo="$seo" :landingHeader="$landingHeader" :school="$filterSchool">
    <div @class([
        'relative overflow-hidden border-b border-thc-navy/8',
        'cohs-page-hero' => $isCohsHub,
        'bg-gradient-to-b from-thc-navy/[0.04] via-white to-white' => ! $isCohsHub,
    ])>
        @if($isCohsHub)
            <div class="pointer-events-none absolute -right-16 top-0 h-72 w-72 rounded-full bg-thc-royal/[0.12] blur-3xl" aria-hidden="true"></div>
        @endif
        <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <nav class="mb-8 text-sm text-thc-text/65" aria-label="Breadcrumb" data-reveal>
                <ol class="flex flex-wrap items-center gap-2">
                    <li><a href="{{ route('home') }}" class="transition hover:text-thc-royal">Home</a></li>
                    <li aria-hidden="true" class="text-thc-text/35">/</li>
                    @if($filterSchool)
                        <li><a href="{{ route('schools.show', $filterSchool) }}" class="transition hover:text-thc-royal">{{ $filterSchool->name }}</a></li>
                        <li aria-hidden="true" class="text-thc-text/35">/</li>
                    @endif
                    <li class="font-medium text-thc-navy">Downloads</li>
                </ol>
            </nav>

            <header class="max-w-3xl" data-reveal>
                @if($isCohsHub)
                    <p class="thc-kicker">College of Health Sciences</p>
                @elseif($filterSchool?->slug === 'soc')
                    <p class="thc-kicker">School of Chaplaincy</p>
                @endif
                <h1 class="mt-3 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl lg:text-[3.25rem]">
                    @if($filterSchool)
                        <span class="text-thc-navy">{{ $filterSchool->name }}</span>
                        <span class="text-thc-text/80"> — </span>
                    @endif
                    Downloads <span class="italic text-thc-royal">&amp; forms</span>
                </h1>
                <p class="mt-5 text-lg leading-relaxed text-thc-text/85 sm:text-xl">
                    @if($isCohsHub)
                        Application PDFs, policies, and student resources for the College of Health Sciences. Filter by category or search by keyword.
                    @elseif($filterSchool)
                        Documents published for {{ $filterSchool->name }}. Use search and categories to narrow the list.
                    @else
                        Search admission packs, clinical documents, policies, and resources from across Tenwek Hospital College.
                    @endif
                </p>
                @if($isCohsHub)
                    <p class="mt-6" data-reveal>
                        <a href="{{ route('schools.pages.show', [$filterSchool, 'application-forms']) }}" class="thc-btn-ghost inline-flex items-center">
                            Application forms page
                            <svg class="h-4 w-4 text-thc-royal" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </p>
                @endif
            </header>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 pb-16 pt-10 sm:px-6 lg:px-8 lg:pb-24 lg:pt-12">
        @if($hasFilters)
            <div class="mb-8 flex flex-wrap items-center gap-2" data-reveal aria-label="Active filters">
                <span class="text-xs font-bold uppercase tracking-[0.14em] text-thc-text/50">Filtered</span>
                @if(filled(request('school')) && $filterSchool)
                    <a
                        href="{{ route('downloads.index', array_filter(['q' => request('q'), 'category' => request('category')])) }}"
                        class="inline-flex items-center gap-1.5 rounded-full border border-thc-navy/15 bg-thc-navy/[0.04] px-3 py-1 text-xs font-semibold text-thc-navy transition hover:border-thc-royal/35 hover:bg-thc-royal/8"
                    >
                        {{ $filterSchool->name }}
                        <span class="text-thc-text/45" aria-hidden="true">×</span>
                    </a>
                @elseif(filled(request('school')))
                    <span class="rounded-full bg-thc-navy/8 px-3 py-1 text-xs font-semibold text-thc-navy">School: {{ request('school') }}</span>
                @endif
                @if(filled(request('category')))
                    @php $activeCat = $categories->firstWhere('slug', request('category')); @endphp
                    <a
                        href="{{ route('downloads.index', array_filter(['q' => request('q'), 'school' => request('school')])) }}"
                        class="inline-flex items-center gap-1.5 rounded-full border border-thc-navy/15 bg-white px-3 py-1 text-xs font-semibold text-thc-navy transition hover:border-thc-royal/35"
                    >
                        {{ $activeCat?->name ?? request('category') }}
                        <span class="text-thc-text/45" aria-hidden="true">×</span>
                    </a>
                @endif
                @if(filled(request('q')))
                    <a
                        href="{{ route('downloads.index', array_filter(['school' => request('school'), 'category' => request('category')])) }}"
                        class="inline-flex items-center gap-1.5 rounded-full border border-thc-navy/15 bg-white px-3 py-1 text-xs font-semibold text-thc-navy transition hover:border-thc-royal/35"
                    >
                        “{{ \Illuminate\Support\Str::limit(request('q'), 32) }}”
                        <span class="text-thc-text/45" aria-hidden="true">×</span>
                    </a>
                @endif
                <a href="{{ route('downloads.index') }}" class="ml-1 text-xs font-semibold text-thc-royal underline decoration-thc-royal/30 underline-offset-2 hover:decoration-thc-royal">Clear all</a>
            </div>
        @endif

        <form
            method="get"
            action="{{ route('downloads.index') }}"
            class="rounded-[var(--radius-thc-card)] border border-thc-navy/10 bg-white p-5 shadow-[var(--shadow-thc-card)] ring-1 ring-thc-navy/[0.04] sm:p-6"
            data-reveal
        >
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-12 lg:items-end lg:gap-6">
                <div class="lg:col-span-5">
                    <label for="q" class="block text-xs font-bold uppercase tracking-[0.12em] text-thc-text/55">Search</label>
                    <div class="relative mt-2">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-thc-text/40" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input
                            type="search"
                            name="q"
                            id="q"
                            value="{{ request('q') }}"
                            placeholder="e.g. admission, clinical, policy…"
                            class="w-full rounded-xl border border-thc-navy/12 bg-thc-surface/50 py-2.5 pl-10 pr-3 text-sm text-thc-text transition placeholder:text-thc-text/40 focus:border-thc-royal/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-thc-royal/20"
                            autocomplete="off"
                        >
                    </div>
                </div>
                <div class="sm:col-span-1 lg:col-span-3">
                    <label for="category" class="block text-xs font-bold uppercase tracking-[0.12em] text-thc-text/55">Category</label>
                    <select
                        name="category"
                        id="category"
                        class="mt-2 w-full rounded-xl border border-thc-navy/12 bg-thc-surface/50 px-3 py-2.5 text-sm text-thc-text focus:border-thc-royal/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-thc-royal/20"
                    >
                        <option value="">All categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" @selected(request('category') === $cat->slug)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-1 lg:col-span-3">
                    <label for="school" class="block text-xs font-bold uppercase tracking-[0.12em] text-thc-text/55">School</label>
                    <select
                        name="school"
                        id="school"
                        class="mt-2 w-full rounded-xl border border-thc-navy/12 bg-thc-surface/50 px-3 py-2.5 text-sm text-thc-text focus:border-thc-royal/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-thc-royal/20"
                    >
                        <option value="">All schools</option>
                        @foreach($schools as $s)
                            <option value="{{ $s->slug }}" @selected(request('school') === $s->slug)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-wrap gap-3 lg:col-span-1 lg:flex-col">
                    <button type="submit" class="thc-btn-primary w-full justify-center sm:w-auto lg:w-full">Apply</button>
                </div>
            </div>
        </form>

        <ul class="mt-12 grid list-none gap-6 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8" role="list">
            @forelse($downloads as $dl)
                @php
                    $desc = $dl->description
                        ? \Illuminate\Support\Str::limit(\Illuminate\Support\Str::squish(strip_tags($dl->description)), 160)
                        : null;
                    $dlUrl = $dl->primaryDownloadUrl();
                @endphp
                <li class="min-w-0" data-reveal>
                    <article
                        class="group flex h-full flex-col overflow-hidden rounded-[var(--radius-thc-card)] border border-thc-navy/10 bg-white shadow-[var(--shadow-thc-card)] ring-1 ring-thc-navy/[0.04] transition duration-300 hover:-translate-y-0.5 hover:shadow-[var(--shadow-thc-card-hover)]"
                    >
                        <div class="h-1 shrink-0 bg-gradient-to-r from-thc-navy via-thc-royal to-thc-navy/75" aria-hidden="true"></div>
                        <div class="flex flex-1 flex-col p-6">
                            <div class="flex gap-3">
                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-thc-navy/[0.08] to-thc-royal/[0.12] text-thc-navy ring-1 ring-thc-navy/10"
                                    aria-hidden="true"
                                >
                                    <svg class="h-5 w-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h2 class="font-serif text-lg font-semibold leading-snug text-thc-navy">
                                        <a href="{{ route('downloads.show', $dl->slug) }}" class="transition hover:text-thc-royal">{{ $dl->title }}</a>
                                    </h2>
                                    <p class="mt-1.5 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-thc-text/60">
                                        @if($dl->school)
                                            <span>{{ $dl->school->name }}</span>
                                        @endif
                                        @if($dl->school && $dl->category)
                                            <span aria-hidden="true" class="text-thc-text/30">·</span>
                                        @endif
                                        @if($dl->category)
                                            <span>{{ $dl->category->name }}</span>
                                        @endif
                                        @if(filled($dl->extension))
                                            <span class="inline-flex items-center rounded-full bg-thc-navy/[0.07] px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.12em] text-thc-navy/90">{{ strtoupper($dl->extension) }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if($desc)
                                <p class="mt-4 flex-1 text-sm leading-relaxed text-thc-text/85">{{ $desc }}</p>
                            @endif
                            <p class="mt-4 text-xs text-thc-text/50">
                                {{ $dl->published_at?->format('M j, Y') }}
                                <span aria-hidden="true"> · </span>
                                {{ number_format($dl->download_count) }} {{ \Illuminate\Support\Str::plural('download', $dl->download_count) }}
                            </p>
                            <div class="mt-5 flex flex-wrap items-center gap-2 border-t border-thc-navy/8 pt-5">
                                @if($dlUrl)
                                    <a
                                        href="{{ $dlUrl }}"
                                        @if($dl->primaryDownloadOpensNewTab()) target="_blank" rel="noopener noreferrer" @endif
                                        class="thc-btn-primary !px-5 !py-2.5 text-xs sm:text-sm"
                                    >
                                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Get file
                                    </a>
                                @endif
                                <a
                                    href="{{ route('downloads.show', $dl->slug) }}"
                                    class="inline-flex items-center gap-1 rounded-full px-3 py-2 text-sm font-semibold text-thc-royal transition hover:bg-thc-royal/10"
                                >
                                    Details
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                </li>
            @empty
                <li class="col-span-full flex justify-center px-0 sm:px-4" data-reveal>
                    <div class="w-full max-w-lg rounded-[var(--radius-thc-card)] border border-thc-navy/10 bg-white p-10 text-center shadow-[var(--shadow-thc-card)] sm:p-12">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-thc-navy/[0.06] text-thc-navy ring-1 ring-thc-navy/10" aria-hidden="true">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                            </svg>
                        </div>
                        <p class="mt-6 font-serif text-lg font-semibold text-thc-navy">No matching documents</p>
                        <p class="mt-3 text-sm leading-relaxed text-thc-text/80">
                            Try clearing a filter or searching with a different keyword.
                        </p>
                        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                            <a href="{{ route('downloads.index', array_filter(['school' => request('school'), 'category' => request('category')])) }}" class="thc-btn-ghost justify-center border-thc-navy/12">Clear search</a>
                            <a href="{{ route('downloads.index') }}" class="thc-btn-primary justify-center">All downloads</a>
                        </div>
                    </div>
                </li>
            @endforelse
        </ul>

        @if($downloads->hasPages())
            <div class="mt-12" data-reveal>
                {{ $downloads->onEachSide(1)->links('pagination.public') }}
            </div>
        @endif
    </div>
</x-layouts.public>
