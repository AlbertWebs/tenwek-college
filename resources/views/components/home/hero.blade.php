@props([])

@php
    $hero = config('tenwek.hero', []);
    $img = $hero['image'] ?? 'ctc.jpg';
    $imgAlt = $hero['image_alt'] ?? 'Tenwek Hospital College campus';
    $logoSrc = config('tenwek.brand_logo', 'images/tenwek-hospital-logo.png');
    $logoUrl = \Illuminate\Support\Str::startsWith($logoSrc, ['http://', 'https://']) ? $logoSrc : asset($logoSrc);
@endphp

<section
    class="relative flex min-h-[100svh] flex-col overflow-hidden text-white"
    aria-labelledby="home-hero-heading"
>
    <div
        class="absolute inset-0 bg-cover bg-center motion-safe:thc-hero-ken-bg"
        style="background-image: url('{{ e(asset($img)) }}');"
        role="img"
        aria-label="{{ e($imgAlt) }}"
    ></div>
    <div
        class="absolute inset-0 bg-gradient-to-b from-thc-navy/92 via-thc-navy/88 to-thc-navy/95"
        aria-hidden="true"
    ></div>
    <div
        class="pointer-events-none absolute inset-0 opacity-30"
        style="background-image: radial-gradient(circle at 30% 20%, rgba(250, 204, 21, 0.08), transparent 45%);"
        aria-hidden="true"
    ></div>

    <div class="relative z-10 flex min-h-[100svh] flex-1 flex-col">
        <div class="flex flex-1 flex-col items-center justify-center px-4 pb-28 pt-24 text-center sm:px-6 sm:pb-32 sm:pt-28 lg:px-8" data-reveal>
            <div class="mb-8 flex justify-center sm:mb-10">
                <img
                    src="{{ $logoUrl }}"
                    alt="Tenwek Hospital — official emblem (We Treat, Jesus Heals)"
                    class="h-28 w-28 object-contain drop-shadow-[0_4px_24px_rgba(0,0,0,0.45)] sm:h-36 sm:w-36 md:h-44 md:w-44"
                    width="176"
                    height="176"
                    loading="eager"
                    decoding="async"
                    fetchpriority="high"
                >
            </div>
            <h1 id="home-hero-heading" class="max-w-4xl font-serif text-4xl font-normal leading-[1.15] tracking-tight sm:text-5xl md:text-6xl lg:text-[3.5rem]">
                <span class="block">
                    <span class="thc-hero-welcome border-b-[5px] border-thc-royal pb-1">Welcome</span><span class="font-normal"> To Tenwek</span>
                </span>
                <span class="mt-1 block">Hospital College</span>
            </h1>
            <a
                href="#schools"
                class="thc-hero-school-cta group mt-10 flex max-w-md flex-col items-center gap-5 rounded-2xl px-6 py-5 text-center transition duration-300 hover:bg-white/5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-thc-royal sm:mt-12 sm:gap-6 sm:px-8 sm:py-6"
                aria-label="Select a school — go to School of Chaplaincy and College of Health Sciences"
            >
                <span class="flex flex-col items-center gap-1.5">
                    <span class="text-[10px] font-bold uppercase tracking-[0.28em] text-white/70 sm:text-[11px]">Your next step</span>
                    <span class="font-sans text-lg font-semibold leading-snug tracking-tight text-white drop-shadow-[0_2px_12px_rgba(0,0,0,0.35)] sm:text-xl">
                        Select a school
                    </span>
                </span>
                <span
                    class="relative flex h-12 w-12 items-center justify-center rounded-full border-2 border-white/50 bg-white/12 shadow-[0_8px_24px_rgba(0,0,0,0.2)] backdrop-blur-sm transition duration-300 group-hover:border-thc-royal group-hover:bg-white/20 group-hover:shadow-[0_10px_28px_rgba(0,33,71,0.35)] group-active:scale-[0.97] sm:h-14 sm:w-14"
                    aria-hidden="true"
                >
                    <svg
                        class="thc-scroll-cue h-6 w-6 text-white transition duration-300 group-hover:text-thc-royal sm:h-7 sm:w-7"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
            </a>
        </div>
    </div>
</section>
