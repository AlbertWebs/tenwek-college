@props(['pillars' => []])

@if(count($pillars) > 0)
    <section class="border-y border-thc-navy/10 bg-thc-surface shadow-sm" aria-labelledby="home-pillars-heading">
        <div class="mx-auto max-w-7xl thc-section !py-16 lg:!py-20">
            <div class="mx-auto max-w-2xl text-center" data-reveal>
                <p class="thc-kicker justify-center text-center">Institutional snapshot</p>
                <h2 id="home-pillars-heading" class="mt-3 font-serif text-3xl font-semibold text-thc-navy sm:text-4xl">
                    Training that honours calling and clinical rigour
                </h2>
            </div>

            <div class="mt-12 grid gap-8 lg:grid-cols-3">
                @foreach($pillars as $pillar)
                    <article
                        class="thc-card-surface flex flex-col p-8 transition duration-300 hover:-translate-y-0.5 hover:shadow-[var(--shadow-thc-card-hover)]"
                        data-reveal
                    >
                        <h3 class="font-serif text-xl font-semibold text-thc-navy">{{ $pillar['title'] ?? '' }}</h3>
                        <p class="mt-4 flex-1 text-sm leading-relaxed text-thc-text/90 sm:text-[0.9375rem]">
                            {{ $pillar['description'] ?? '' }}
                        </p>
                        @if(! empty($pillar['route']))
                            <a
                                href="{{ route($pillar['route'], $pillar['params'] ?? []) }}"
                                class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-thc-royal hover:text-thc-navy hover:underline"
                            >
                                {{ $pillar['cta'] ?? 'Learn more' }}
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
