@php
    $soc = $socLanding ?? config('tenwek.soc_landing');
    $S = $soc['strategic_partners'] ?? [];
    $pageUrl = fn (string $slug) => route('schools.pages.show', [$school, $slug]);
    $partners = $S['partners'] ?? [];
@endphp

<x-layouts.public :seo="$seo" landing-header="soc" :school="$school">
    <div class="soc-page-hero">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <x-schools.soc.breadcrumbs :school="$school" :current="$page->title" class="mb-8" data-reveal />

            <header class="max-w-3xl" data-reveal>
                <p class="thc-kicker">Strategic partnerships</p>
                <h1 class="mt-4 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl">
                    {{ $page->title }}
                </h1>
                <div class="mt-6 space-y-4 text-lg leading-relaxed text-thc-text/90">
                    @foreach($S['intro'] ?? [] as $para)
                        <p>{{ $para }}</p>
                    @endforeach
                </div>
            </header>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:flex lg:gap-12 lg:px-8 lg:py-16">
        <div class="min-w-0 flex-1 space-y-12 lg:space-y-14">
            @foreach($partners as $index => $partner)
                <article
                    id="partner-{{ \Illuminate\Support\Str::slug($partner['name']) }}"
                    class="scroll-mt-28 overflow-hidden rounded-2xl border border-thc-navy/10 bg-white shadow-sm"
                    data-reveal
                    aria-labelledby="partner-heading-{{ $index }}"
                >
                    <div class="flex flex-col gap-8 p-6 sm:p-8 lg:flex-row lg:gap-10 lg:p-10">
                        <figure class="mx-auto w-full max-w-[20rem] shrink-0 lg:mx-0 lg:w-[min(100%,18rem)] xl:w-[20rem]">
                            @php $pImage = $partner['image'] ?? null; @endphp
                            @if(filled($pImage))
                                <div class="overflow-hidden rounded-2xl border border-thc-navy/10 bg-thc-navy/[0.03] shadow-sm ring-1 ring-thc-navy/5">
                                    <img
                                        src="{{ \App\Support\Soc\SocLandingRepository::publicMediaUrl($pImage) ?? asset($pImage) }}"
                                        alt="{{ $partner['name'] }}"
                                        class="aspect-[4/3] w-full object-cover"
                                        loading="lazy"
                                        decoding="async"
                                        width="640"
                                        height="480"
                                    >
                                </div>
                                <figcaption class="mt-2 text-center text-xs text-thc-text/60">{{ $partner['name'] }}</figcaption>
                            @else
                                <div
                                    class="flex aspect-[4/3] w-full flex-col items-center justify-center gap-3 rounded-2xl border-2 border-dashed border-thc-navy/20 bg-gradient-to-b from-thc-navy/[0.04] to-thc-royal/[0.04] px-4 text-center"
                                    role="img"
                                    aria-label="Logo or photo placeholder for {{ $partner['name'] }}"
                                >
                                    <span class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-thc-navy to-thc-royal font-serif text-lg font-bold text-white shadow-md ring-2 ring-white sm:h-16 sm:w-16 sm:text-xl">
                                        {{ $partner['abbr'] ?? \Illuminate\Support\Str::substr($partner['name'], 0, 3) }}
                                    </span>
                                    <span class="text-[10px] font-semibold uppercase tracking-[0.14em] text-thc-text/50">Logo / image</span>
                                </div>
                            @endif
                        </figure>

                        <div class="min-w-0 flex-1">
                            <h2 id="partner-heading-{{ $index }}" class="font-serif text-2xl font-semibold text-thc-navy sm:text-3xl">
                                {{ $partner['name'] }}
                            </h2>
                            <div class="mt-5 space-y-4 text-base leading-relaxed text-thc-text/90">
                                @foreach($partner['paragraphs'] ?? [] as $para)
                                    <p>{{ $para }}</p>
                                @endforeach
                            </div>
                            @if(!empty($partner['list_intro']))
                                <p class="mt-5 font-medium text-thc-navy">{{ $partner['list_intro'] }}</p>
                            @endif
                            @if(!empty($partner['bullets']))
                                <ul class="mt-4 list-none space-y-4 text-thc-text/90" role="list">
                                    @foreach($partner['bullets'] as $item)
                                        <li class="flex gap-3">
                                            <span
                                                class="mt-[0.55rem] h-2 w-2 shrink-0 rounded-full bg-thc-maroon shadow-[0_0_0_3px_rgb(122_0_223_/_0.12)]"
                                                aria-hidden="true"
                                            ></span>
                                            <span class="min-w-0 flex-1 text-base leading-relaxed">{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            @if(!empty($partner['closing']))
                                <p class="mt-6 border-t border-thc-navy/10 pt-6 text-base font-medium italic text-thc-navy/90">
                                    {{ $partner['closing'] }}
                                </p>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <x-schools.soc.about-sidebar :school="$school" :page="$page" />
    </div>
</x-layouts.public>
