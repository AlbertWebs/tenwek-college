@php
    $soc = $socLanding ?? config('tenwek.soc_landing');
    $H = $soc['our_history'] ?? [];
    $milestones = $H['milestones'] ?? [];
@endphp

<x-layouts.public :seo="$seo" landing-header="soc" :school="$school">
    <div class="soc-page-hero">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:flex lg:gap-12 lg:px-8 lg:py-16">
            <article class="min-w-0 flex-1">
                <x-schools.soc.breadcrumbs :school="$school" :current="$page->title" class="mb-8" data-reveal />

                <header class="mt-8" data-reveal>
                    <p class="thc-kicker">{{ $H['kicker'] ?? 'About School of Chaplaincy' }}</p>
                    <h1 class="mt-3 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl">{{ $page->title }}</h1>
                </header>

                <div class="mt-8 space-y-5 text-base leading-relaxed text-thc-text/90 sm:text-lg" data-reveal>
                    @foreach($H['intro_paragraphs'] ?? [] as $para)
                        <p>{{ $para }}</p>
                    @endforeach
                </div>

                <section class="mt-14" aria-labelledby="soc-history-milestones-heading" data-reveal>
                    <h2 id="soc-history-milestones-heading" class="font-serif text-2xl font-semibold text-thc-navy sm:text-3xl">
                        Milestones
                    </h2>
                    <p class="mt-3 max-w-2xl text-sm text-thc-text/80 sm:text-base">
                        A concise chronology of the School of Chaplaincy, from founding to today’s curriculum and partnerships.
                    </p>

                    <ol class="relative mt-10 space-y-0">
                        @foreach($milestones as $index => $m)
                            <li
                                class="group relative flex gap-4 pb-12 last:pb-0 sm:gap-8 lg:gap-10"
                                data-reveal
                            >
                                <div class="flex w-[4.5rem] shrink-0 flex-col items-end pt-0.5 sm:w-28">
                                    <span class="inline-flex rounded-lg bg-gradient-to-br from-thc-maroon to-thc-maroon/85 px-2.5 py-1 text-center font-serif text-base font-semibold tabular-nums text-white shadow-sm ring-1 ring-thc-maroon/20 sm:px-3 sm:text-lg">
                                        {{ $m['year'] }}
                                    </span>
                                </div>
                                <div class="relative min-w-0 flex-1 border-l-2 border-thc-navy/15 pb-1 pl-6 sm:pl-8">
                                    <span
                                        class="absolute -left-[9px] top-2 flex h-4 w-4 items-center justify-center rounded-full border-2 border-white bg-thc-maroon shadow-md ring-2 ring-thc-maroon/20 sm:-left-[10px]"
                                        aria-hidden="true"
                                    ></span>
                                    <h3 class="font-serif text-xl font-semibold text-thc-navy sm:text-2xl">
                                        {{ $m['title'] }}
                                    </h3>
                                    <p class="mt-3 text-sm leading-relaxed text-thc-text/90 sm:text-base">
                                        {{ $m['body'] }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </section>
            </article>

            <x-schools.soc.about-sidebar :school="$school" :page="$page" />
        </div>
    </div>
</x-layouts.public>
