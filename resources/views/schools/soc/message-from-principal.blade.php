@php
    $soc = $socLanding ?? config('tenwek.soc_landing');
    $P = $soc['message_from_principal'] ?? [];
    $pageUrl = fn (string $slug) => route('schools.pages.show', [$school, $slug]);
    $scripture = $P['scripture'] ?? [];
    $principalImg = $P['principal_image'] ?? null;
    $principalAlt = $P['principal_image_alt'] ?? 'Principal, School of Chaplaincy';
    $placeholderCaption = $P['principal_placeholder_caption'] ?? 'Principal photo';
@endphp

<x-layouts.public :seo="$seo" landing-header="soc" :school="$school">
    {{-- Hero band --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-thc-navy via-thc-soc-teal/50 to-thc-maroon/88 text-white">
        <div class="pointer-events-none absolute inset-0 opacity-[0.12]" style="background-image: radial-gradient(circle at 15% 20%, rgba(255,255,255,0.35), transparent 42%), radial-gradient(circle at 90% 80%, rgba(21,104,116,0.45), transparent 45%);" aria-hidden="true"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8 lg:py-20">
            <x-schools.soc.breadcrumbs :school="$school" :current="$page->title" variant="hero" class="mb-8" data-reveal />
            <div class="mt-8 flex flex-col gap-10 lg:flex-row lg:items-end lg:justify-between lg:gap-12">
                <div class="min-w-0 flex-1">
                    <p class="max-w-2xl text-[11px] font-bold uppercase tracking-[0.22em] text-thc-soc-teal/95 sm:text-xs" data-reveal>
                        {{ $P['kicker'] ?? '' }}
                    </p>
                    <h1 class="mt-4 max-w-3xl font-serif text-4xl font-semibold leading-tight tracking-tight sm:text-5xl lg:text-[3.25rem]" data-reveal>
                        {{ $page->title }}
                    </h1>
                    <p class="mt-8 max-w-xl border-l-4 border-thc-royal/90 pl-5 font-serif text-lg italic leading-relaxed text-white/95 sm:text-xl" data-reveal>
                        “{{ $P['motto'] ?? 'Equip Chaplains for Wholistic Service' }}”
                    </p>
                </div>
                <figure class="mx-auto w-full max-w-[16rem] shrink-0 lg:mx-0 lg:max-w-[18rem]" data-reveal>
                    @if(filled($principalImg))
                        <div class="overflow-hidden rounded-2xl border border-white/25 bg-white/10 shadow-xl ring-1 ring-white/20">
                            <img
                                src="{{ \App\Support\Soc\SocLandingRepository::publicMediaUrl($principalImg) ?? asset($principalImg) }}"
                                alt="{{ $principalAlt }}"
                                class="aspect-[4/5] w-full object-cover"
                                width="400"
                                height="500"
                                loading="eager"
                                decoding="async"
                            >
                        </div>
                        <figcaption class="mt-3 text-center text-xs text-white/75">{{ $principalAlt }}</figcaption>
                    @else
                        <div
                            class="flex aspect-[4/5] w-full flex-col items-center justify-center gap-3 rounded-2xl border-2 border-dashed border-white/40 bg-white/[0.07] px-4 text-center shadow-inner backdrop-blur-[2px]"
                            role="img"
                            aria-label="{{ $placeholderCaption }}"
                        >
                            <span class="flex h-20 w-20 items-center justify-center rounded-full bg-white/15 ring-2 ring-white/25">
                                <svg class="h-10 w-10 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <span class="text-[11px] font-semibold uppercase tracking-[0.14em] text-white/85">Principal</span>
                            <span class="text-xs leading-snug text-white/65">{{ $placeholderCaption }}</span>
                        </div>
                    @endif
                </figure>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-b from-white via-thc-navy/[0.02] to-white">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:flex lg:gap-12 lg:px-8 lg:py-16">
            <article class="min-w-0 flex-1">
                {{-- Opening letter card --}}
                <div
                    class="relative overflow-hidden rounded-2xl border border-thc-navy/10 bg-white p-6 shadow-[var(--shadow-thc-card)] sm:p-10 lg:p-12"
                    data-reveal
                >
                    <span class="pointer-events-none absolute -right-4 -top-6 font-serif text-[8rem] font-bold leading-none text-thc-maroon/[0.07] sm:text-[10rem]" aria-hidden="true">“</span>
                    <div class="relative space-y-6 text-base leading-relaxed text-thc-text/90 sm:text-lg">
                        @foreach($P['paragraphs'] ?? [] as $i => $para)
                            <p @class(['first-letter:float-left first-letter:mr-3 first-letter:mt-1 first-letter:font-serif first-letter:text-4xl first-letter:font-semibold first-letter:text-thc-maroon sm:first-letter:text-5xl' => $i === 0])>
                                {{ $para }}
                            </p>
                        @endforeach
                    </div>
                </div>

                {{-- Scripture feature --}}
                <div class="mt-10 sm:mt-14" data-reveal>
                    <p class="max-w-3xl text-base font-medium text-thc-navy sm:text-lg">
                        {{ $P['belief'] ?? '' }}
                    </p>
                    <figure class="relative mt-8 overflow-hidden rounded-2xl border border-thc-soc-teal/25 bg-gradient-to-br from-thc-soc-teal/[0.07] via-white to-thc-royal/[0.05] p-8 shadow-[var(--shadow-thc-card)] sm:p-10">
                        <div class="absolute left-0 top-0 h-full w-1.5 bg-gradient-to-b from-thc-soc-teal via-thc-soc-teal/75 to-thc-royal" aria-hidden="true"></div>
                        <blockquote class="relative pl-4 sm:pl-6">
                            <p class="font-serif text-xl leading-relaxed text-thc-navy sm:text-2xl lg:text-[1.65rem]">
                                “{{ $scripture['text'] ?? '' }}”
                            </p>
                            <figcaption class="mt-6 text-sm font-semibold uppercase tracking-[0.12em] text-thc-soc-teal">
                                {{ $scripture['reference'] ?? '' }}
                            </figcaption>
                        </blockquote>
                    </figure>
                </div>

                {{-- Closing invitation --}}
                <div
                    class="mt-10 rounded-2xl border border-thc-royal/20 bg-thc-royal/[0.06] p-6 sm:mt-14 sm:p-8"
                    data-reveal
                >
                    <p class="text-base font-medium leading-relaxed text-thc-navy sm:text-lg">
                        {{ $P['invitation'] ?? '' }}
                    </p>
                    <a
                        href="{{ $pageUrl('admissions') }}"
                        class="mt-6 inline-flex items-center gap-2 rounded-full bg-thc-maroon px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-thc-navy"
                    >
                        Explore admissions
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </article>

            <x-schools.soc.about-sidebar :school="$school" :page="$page" />
        </div>
    </div>
</x-layouts.public>
