@php
    $L = $cohsLanding ?? config('tenwek.cohs_landing');
    $A = $L['about_us'] ?? config('tenwek.cohs_landing.about_us');
    $histPath = $A['history_image'] ?? 'banner-nursing.jpg';
    $historyImage = \App\Support\Cohs\CohsLandingRepository::publicMediaUrl(is_string($histPath) ? $histPath : null) ?? asset(is_string($histPath) ? $histPath : 'banner-nursing.jpg');
    $historyAlt = $A['history_image_alt'] ?? '';
@endphp

<x-layouts.public :seo="$seo" landing-header="cohs" :school="$school">
    <div class="relative overflow-hidden cohs-page-hero">
        <div class="pointer-events-none absolute -right-24 top-1/2 hidden h-[min(28rem,50vh)] w-[min(28rem,45vw)] -translate-y-1/2 rounded-full bg-thc-royal/[0.12] blur-3xl md:block" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -left-20 top-8 h-40 w-40 rounded-full bg-thc-maroon/[0.08] blur-2xl" aria-hidden="true"></div>
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

            <header class="max-w-3xl" data-reveal>
                <p class="thc-kicker">{{ $A['kicker'] }}</p>
                <h1 class="mt-4 bg-gradient-to-r from-thc-navy via-thc-navy to-thc-royal bg-clip-text font-serif text-4xl font-semibold tracking-tight text-transparent sm:text-5xl lg:text-[3.25rem]">
                    {{ $A['headline'] }}
                </h1>
                @if(filled($page->excerpt))
                    <p class="mt-6 text-lg leading-relaxed text-thc-text/90">{{ $page->excerpt }}</p>
                @endif
            </header>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
        <section class="border-b border-thc-navy/10 pb-14 lg:pb-20" aria-labelledby="cohs-history-heading" data-reveal>
            <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-14 xl:gap-20">
                <div class="min-w-0 lg:pr-4">
                    <div class="inline-flex items-center gap-2 rounded-full border border-thc-royal/25 bg-thc-royal/[0.08] px-3 py-1 text-xs font-semibold uppercase tracking-wider text-thc-royal">
                        <span class="h-1.5 w-1.5 rounded-full bg-thc-royal" aria-hidden="true"></span>
                        Since 1987
                    </div>
                    <h2 id="cohs-history-heading" class="mt-4 font-serif text-2xl font-semibold text-thc-navy sm:text-3xl lg:text-[2rem]">
                        {{ $A['history_heading'] }}
                    </h2>
                    <div class="mt-6 space-y-4 text-base leading-relaxed text-thc-text/90 sm:text-lg">
                        @foreach($A['history_paragraphs'] as $p)
                            <p>{{ $p }}</p>
                        @endforeach
                    </div>
                </div>
                @if(filled($A['history_image'] ?? null))
                    <div class="relative mx-auto w-full max-w-lg lg:mx-0 lg:max-w-none">
                        <div class="pointer-events-none absolute -inset-3 rounded-[2rem] bg-gradient-to-br from-thc-royal/20 via-thc-maroon/10 to-transparent opacity-90 blur-sm" aria-hidden="true"></div>
                        <div class="pointer-events-none absolute -right-4 -bottom-4 hidden h-24 w-24 rounded-3xl border-2 border-dashed border-thc-royal/35 sm:block" aria-hidden="true"></div>
                        <figure class="relative overflow-hidden rounded-3xl shadow-[0_28px_60px_-12px_rgb(0_33_71/0.35)] ring-2 ring-white ring-offset-2 ring-offset-thc-royal/15">
                            <img
                                src="{{ $historyImage }}"
                                alt="{{ $historyAlt }}"
                                class="aspect-[4/5] w-full object-cover sm:aspect-[5/6] lg:aspect-[4/5] lg:min-h-[20rem]"
                                loading="lazy"
                                decoding="async"
                            >
                            <figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-thc-navy/90 via-thc-navy/40 to-transparent px-5 py-6 pt-16 text-sm font-medium text-white/95">
                                {{ $school->name }}
                            </figcaption>
                        </figure>
                    </div>
                @endif
            </div>
        </section>

        <div class="mt-14 grid gap-6 lg:mt-20 lg:grid-cols-3 lg:gap-8">
            @foreach(['vision' => ['from-thc-navy/[0.12]', 'ring-thc-navy/15'], 'mission' => ['from-thc-royal/[0.14]', 'ring-thc-royal/25'], 'motto' => ['from-thc-maroon/[0.12]', 'ring-thc-maroon/20']] as $key => $meta)
                @php [$wash, $ring] = $meta; $block = $A[$key]; @endphp
                <article
                    class="rounded-2xl border border-thc-navy/10 bg-gradient-to-br {{ $wash }} to-white p-8 shadow-[var(--shadow-thc-card)] ring-1 {{ $ring }} sm:p-9"
                    data-reveal
                >
                    <h3 class="font-serif text-xl font-semibold text-thc-navy">{{ $block['title'] }}</h3>
                    <p class="mt-4 text-base leading-relaxed text-thc-text/90">{{ $block['text'] }}</p>
                </article>
            @endforeach
        </div>

        <section class="mt-20 lg:mt-24" aria-labelledby="cohs-board-team-heading" data-reveal>
            <h2 id="cohs-board-team-heading" class="font-serif text-2xl font-semibold text-thc-navy sm:text-3xl">
                {{ $A['board_section_heading'] }}
            </h2>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-thc-text/90 sm:text-lg">
                {{ $A['board_intro'] }}
            </p>
        </section>

        <section class="mt-12 lg:mt-16" aria-labelledby="cohs-board-heading" data-reveal>
            <div class="border-b border-thc-navy/10 pb-5">
                <h2 id="cohs-board-heading" class="font-serif text-2xl font-semibold text-thc-navy sm:text-3xl">
                    {{ $A['board_heading'] }}
                </h2>
                <p class="mt-1 text-sm text-thc-text/65">Hospital board members</p>
            </div>
            <ul class="mt-10 grid gap-6 sm:grid-cols-2 xl:grid-cols-3" role="list">
                @foreach($A['board'] as $person)
                    <li>
                        <x-schools.soc.leadership-card :person="$person" accent="royal" />
                    </li>
                @endforeach
            </ul>
        </section>
    </div>
</x-layouts.public>
