@php
    $L = $cohsLanding ?? config('tenwek.cohs_landing');
    $P = $L['programmes']['diploma_in_nursing'] ?? config('tenwek.cohs_landing.programmes.diploma_in_nursing');
    $admissions = $P['admissions'] ?? [];
    $cardPath = config('tenwek.landing.cohs_card_image', 'banner-nursing.jpg');
    $heroImage = \App\Support\Cohs\CohsLandingRepository::publicMediaUrl(is_string($cardPath) ? $cardPath : null) ?? asset(is_string($cardPath) ? $cardPath : 'banner-nursing.jpg');
@endphp

<x-layouts.public :seo="$seo" landing-header="cohs" :school="$school">
    <div class="relative overflow-hidden cohs-page-hero">
        <div class="pointer-events-none absolute inset-y-0 right-0 hidden w-[42%] opacity-[0.18] lg:block" aria-hidden="true">
            <div class="h-full bg-cover bg-center" style="background-image: url('{{ e($heroImage) }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/90 to-transparent"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
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
                <p class="thc-kicker">{{ $P['kicker'] }}</p>
                <h1 class="mt-4 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl lg:text-[3.25rem]">
                    {{ $page->title }}
                </h1>
                @if(filled($page->excerpt))
                    <p class="mt-5 text-lg leading-relaxed text-thc-text/88 sm:text-xl">{{ $page->excerpt }}</p>
                @endif
            </header>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
        <div class="lg:grid lg:grid-cols-12 lg:gap-12 xl:gap-16">
            <div class="lg:col-span-7 xl:col-span-8">
                <section class="relative" aria-labelledby="cohs-nursing-overview" data-reveal>
                    <div class="absolute -left-4 top-0 hidden h-full w-1 rounded-full bg-gradient-to-b from-thc-royal to-thc-navy/30 sm:block" aria-hidden="true"></div>
                    <h2 id="cohs-nursing-overview" class="font-serif text-2xl font-semibold text-thc-navy sm:text-3xl">
                        Diploma in Nursing
                    </h2>
                    <p class="mt-6 text-base leading-[1.75] text-thc-text/90 sm:text-lg">
                        {{ $P['overview'] }}
                    </p>

                    <ul class="mt-10 grid gap-4 sm:grid-cols-3" role="list">
                        @foreach($P['pillars'] as $pillar)
                            <li class="rounded-2xl border border-thc-navy/10 bg-white p-5 shadow-[var(--shadow-thc-card)] ring-1 ring-thc-navy/[0.04] transition hover:border-thc-royal/25 hover:shadow-[var(--shadow-thc-card-hover)]">
                                <p class="text-sm font-semibold text-thc-navy">{{ $pillar['label'] }}</p>
                                <p class="mt-2 text-sm leading-relaxed text-thc-text/80">{{ $pillar['description'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <section
                    class="mt-14 scroll-mt-28 rounded-2xl border border-thc-navy/10 bg-gradient-to-br from-thc-royal/[0.06] via-white to-white p-8 shadow-[var(--shadow-thc-card)] ring-1 ring-thc-navy/[0.05] sm:p-10 lg:mt-16"
                    aria-labelledby="cohs-nursing-admissions"
                    data-reveal
                >
                    <h2 id="cohs-nursing-admissions" class="font-serif text-2xl font-semibold text-thc-navy sm:text-3xl">
                        {{ $admissions['heading'] }}
                    </h2>
                    <p class="mt-4 text-base font-medium text-thc-navy/90">{{ $admissions['lead'] }}</p>

                    <div class="mt-8 rounded-xl border border-thc-royal/20 bg-thc-royal/[0.07] px-5 py-4 sm:px-6">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-thc-royal">Mean grade</p>
                        <p class="mt-2 text-base font-semibold leading-snug text-thc-navy">{{ $admissions['mean_grade'] }}</p>
                    </div>

                    <p class="mt-8 text-sm font-semibold uppercase tracking-[0.12em] text-thc-text/55">Subject requirements</p>
                    <ul class="mt-4 space-y-3" role="list">
                        @foreach($admissions['subject_rules'] as $rule)
                            <li class="flex gap-3 text-base leading-relaxed text-thc-text/90">
                                <span class="mt-1.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-thc-royal text-[10px] font-bold text-white" aria-hidden="true">✓</span>
                                <span>{{ $rule }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            </div>

            <aside class="mt-12 lg:col-span-5 xl:col-span-4 lg:mt-0">
                <div class="lg:sticky lg:top-28" data-reveal>
                    <div class="overflow-hidden rounded-2xl border border-thc-navy/10 bg-thc-navy text-white shadow-[var(--shadow-thc-card)]">
                        <div class="bg-gradient-to-br from-thc-royal/35 to-transparent px-6 pb-6 pt-8 sm:px-8 sm:pb-8 sm:pt-10">
                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-white/70">Next steps</p>
                            <p class="mt-3 font-serif text-xl font-semibold leading-snug sm:text-2xl">Ready to apply?</p>
                            <p class="mt-3 text-sm leading-relaxed text-white/85">Download forms, ask admissions a question, or explore our other diploma.</p>
                        </div>
                        <div class="space-y-2 border-t border-white/10 bg-thc-navy px-6 py-6 sm:px-8">
                            <a
                                href="{{ route('schools.pages.show', [$school, 'application-forms']) }}"
                                class="flex w-full items-center justify-center rounded-full bg-white px-5 py-3 text-sm font-semibold text-thc-navy shadow-sm transition hover:bg-white/95"
                            >
                                Application forms
                            </a>
                            <a
                                href="{{ route('downloads.index', ['school' => $school->slug]) }}"
                                class="flex w-full items-center justify-center rounded-full border border-white/25 bg-white/10 px-5 py-3 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/15"
                            >
                                Downloads hub
                            </a>
                            <a
                                href="{{ route('schools.pages.show', [$school, 'contact-us']) }}"
                                class="flex w-full items-center justify-center rounded-full border border-transparent px-5 py-3 text-sm font-semibold text-white/90 underline-offset-4 hover:text-white hover:underline"
                            >
                                Contact the college
                            </a>
                        </div>
                    </div>

                    <div class="mt-6 rounded-2xl border border-thc-navy/10 bg-white p-6 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-thc-text/50">Related programme</p>
                        <a
                            href="{{ route('schools.pages.show', [$school, 'diploma-in-clinical-medicine']) }}"
                            class="mt-3 block font-serif text-lg font-semibold text-thc-navy transition hover:text-thc-royal"
                        >
                            Diploma in Clinical Medicine →
                        </a>
                        <p class="mt-2 text-sm text-thc-text/75">Clinical medicine &amp; surgery pathway at the College of Health Sciences.</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-layouts.public>
