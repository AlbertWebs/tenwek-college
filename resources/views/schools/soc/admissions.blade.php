@php
    $soc = $socLanding ?? config('tenwek.soc_landing');
    $A = $soc['admissions'] ?? [];
    $pageUrl = fn (string $slug) => route('schools.pages.show', [$school, $slug]);
    $topBar = $soc['top_bar'] ?? [];
    $contactEmail = $topBar['email'] ?? 'soc@tenwekhosp.org';
    $defaultPortal = $topBar['portal_url'] ?? route('downloads.index', ['school' => $school->slug]);
    $req = $A['requirements'] ?? [];
    $resolveResourceHref = function (array $resource) use ($defaultPortal): string {
        if (! empty($resource['external'])) {
            return filled($resource['url'] ?? null) ? (string) $resource['url'] : (string) $defaultPortal;
        }
        $route = $resource['route'] ?? 'downloads.index';
        $params = $resource['route_params'] ?? [];

        return route($route, $params);
    };
@endphp

<x-layouts.public :seo="$seo" landing-header="soc" :school="$school">
    <div class="soc-page-hero">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <x-schools.soc.breadcrumbs :school="$school" :current="$page->title" class="mb-8" data-reveal />

            <header class="max-w-3xl" data-reveal>
                <p class="thc-kicker">{{ $A['kicker'] ?? 'Admissions' }}</p>
                <h1 class="mt-4 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl">
                    {{ $page->title }}
                </h1>
                @if(filled($A['intro'] ?? null))
                    <p class="mt-6 text-lg leading-relaxed text-thc-text/90">{{ $A['intro'] }}</p>
                @endif
            </header>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:flex lg:gap-12 lg:px-8 lg:py-16">
        <div class="min-w-0 flex-1 space-y-14 lg:space-y-16">
            {{-- Quick resources --}}
            <section data-reveal aria-labelledby="admissions-resources-heading">
                <h2 id="admissions-resources-heading" class="sr-only">Application resources</h2>
                <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3" role="list">
                    @foreach($A['resources'] ?? [] as $resource)
                        @php
                            $href = $resolveResourceHref($resource);
                            $isExternal = ! empty($resource['external']);
                        @endphp
                        <li>
                            <div class="flex h-full flex-col rounded-2xl border border-thc-navy/10 bg-white p-6 shadow-sm ring-1 ring-thc-navy/5">
                                <h3 class="font-serif text-lg font-semibold text-thc-navy sm:text-xl">
                                    {{ $resource['title'] ?? '' }}
                                </h3>
                                @if(filled($resource['description'] ?? null))
                                    <p class="mt-3 flex-1 text-sm leading-relaxed text-thc-text/85">{{ $resource['description'] }}</p>
                                @endif
                                <a
                                    href="{{ $href }}"
                                    @if($isExternal) target="_blank" rel="noopener noreferrer" @endif
                                    class="mt-5 inline-flex w-fit items-center gap-2 text-sm font-semibold text-thc-royal transition hover:text-thc-navy"
                                >
                                    {{ $resource['cta'] ?? 'Open' }}
                                    @if($isExternal)
                                        <span class="text-xs font-normal text-thc-text/60">(opens new tab)</span>
                                    @endif
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div
                    class="mt-10 flex flex-col items-start gap-4 rounded-2xl border border-thc-navy/10 bg-gradient-to-br from-thc-navy/[0.03] to-thc-royal/[0.06] px-6 py-6 sm:flex-row sm:items-center sm:justify-between sm:px-8"
                    data-reveal
                >
                    <p class="font-serif text-lg font-semibold text-thc-navy">
                        {{ $A['contact_prompt'] ?? 'Contact us for more details' }}
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a
                            href="mailto:{{ $contactEmail }}"
                            class="inline-flex items-center justify-center rounded-xl bg-thc-maroon px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-thc-maroon/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-thc-maroon"
                        >
                            Email the school
                        </a>
                        <a
                            href="{{ route('contact.show') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-thc-navy/15 bg-white px-5 py-2.5 text-sm font-semibold text-thc-navy shadow-sm transition hover:border-thc-royal/40 hover:bg-thc-royal/[0.04]"
                        >
                            College contact
                        </a>
                    </div>
                </div>
            </section>

            {{-- Requirements --}}
            <section data-reveal aria-labelledby="requirements-heading">
                <h2 id="requirements-heading" class="font-serif text-2xl font-semibold text-thc-navy sm:text-3xl">
                    {{ $req['title'] ?? 'Admission requirements' }}
                </h2>

                <div class="mt-8 grid gap-8 lg:grid-cols-2">
                    @if(!empty($req['certificate']))
                        <article class="rounded-2xl border border-thc-navy/10 bg-white p-6 shadow-sm sm:p-8">
                            <h3 class="font-serif text-xl font-semibold text-thc-navy">
                                {{ $req['certificate']['title'] ?? '' }}
                            </h3>
                            <p class="mt-4 leading-relaxed text-thc-text/90">{{ $req['certificate']['body'] ?? '' }}</p>
                        </article>
                    @endif

                    @if(!empty($req['diploma']['items']))
                        <article class="rounded-2xl border border-thc-navy/10 bg-white p-6 shadow-sm sm:p-8">
                            <h3 class="font-serif text-xl font-semibold text-thc-navy">
                                {{ $req['diploma']['title'] ?? '' }}
                            </h3>
                            <ol class="mt-5 list-decimal space-y-4 pl-5 leading-relaxed text-thc-text/90">
                                @foreach($req['diploma']['items'] as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ol>
                        </article>
                    @endif
                </div>

                @if(filled($req['intake_note'] ?? null))
                    <blockquote
                        class="mt-10 border-l-4 border-thc-maroon bg-thc-maroon/[0.06] px-6 py-5 font-serif text-lg italic leading-relaxed text-thc-navy"
                        cite="{{ route('schools.pages.show', [$school, 'admissions']) }}"
                    >
                        {{ $req['intake_note'] }}
                    </blockquote>
                @endif
            </section>

            <p class="text-center" data-reveal>
                <a
                    href="{{ route('schools.pages.show', [$school, 'fee']) }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-thc-royal hover:text-thc-navy"
                >
                    View fee structure →
                </a>
            </p>
        </div>

        <x-schools.soc.about-sidebar :school="$school" :page="$page" />
    </div>
</x-layouts.public>
