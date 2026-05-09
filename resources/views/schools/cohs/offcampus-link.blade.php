@php
    $L = $cohsLanding ?? config('tenwek.cohs_landing');
    $applyUrl = $L['off_campus_application_url'] ?? null;
@endphp

<x-layouts.public :seo="$seo" landing-header="cohs" :school="$school">
    <div class="cohs-page-hero">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <nav class="mb-8 text-sm text-thc-text/65" aria-label="Breadcrumb" data-reveal>
                <ol class="flex flex-wrap gap-2">
                    <li><a href="{{ route('home') }}" class="hover:text-thc-royal">Home</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="{{ route('schools.show', $school) }}" class="hover:text-thc-royal">{{ $school->name }}</a></li>
                    <li aria-hidden="true">/</li>
                    <li class="text-thc-navy">{{ $page->title }}</li>
                </ol>
            </nav>

            <header class="max-w-2xl" data-reveal>
                <p class="thc-kicker">Application</p>
                <h1 class="mt-4 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl">{{ $page->title }}</h1>
                @if(filled($page->excerpt))
                    <p class="mt-5 text-lg leading-relaxed text-thc-text/88">{{ $page->excerpt }}</p>
                @endif
            </header>
        </div>
    </div>

    <div class="mx-auto max-w-3xl px-4 pb-16 sm:px-6 lg:px-8 lg:pb-24">
        <div
            class="rounded-2xl border border-thc-navy/10 bg-white p-8 shadow-[var(--shadow-thc-card)] sm:p-10"
            data-reveal
        >
            <p class="text-base leading-relaxed text-thc-text/90">
                Complete your application through the college online application portal. The form opens in a new browser tab.
            </p>
            @if(filled($applyUrl))
                <a
                    href="{{ $applyUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mt-8 inline-flex w-full items-center justify-center gap-2 rounded-full bg-thc-navy px-8 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:bg-thc-royal sm:w-auto"
                >
                    Open online application
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                <p class="mt-4 break-all text-xs text-thc-text/55">{{ $applyUrl }}</p>
            @else
                <p class="mt-6 text-sm text-thc-text/70">Application URL is not configured. Please contact the college office.</p>
            @endif
            <p class="mt-10 text-sm text-thc-text/70">
                <a href="{{ route('schools.pages.show', [$school, 'application-forms']) }}" class="font-semibold text-thc-royal hover:underline">Application forms &amp; downloads</a>
                <span class="text-thc-text/40"> · </span>
                <a href="{{ route('schools.pages.show', [$school, 'contact-us']) }}" class="font-semibold text-thc-royal hover:underline">Contact us</a>
            </p>
        </div>
    </div>
</x-layouts.public>
