@props(['schools'])

@php
    $cream = config('tenwek.landing.cream_bg', '#FFFFFF');
    $socBg = config('tenwek.landing.soc_card_image', 'banner-a.jpg');
    $cohsBg = config('tenwek.landing.cohs_card_image', 'banner-nursing.jpg');
    $ctaClasses = 'mt-8 inline-flex items-center justify-center rounded-full border-2 border-white/85 bg-white/15 px-8 py-3.5 text-sm font-semibold uppercase tracking-[0.12em] text-white shadow-md transition duration-300 group-hover:border-white group-hover:bg-white group-hover:text-thc-navy sm:px-10 sm:py-4 sm:text-base';
@endphp

<section
    id="schools"
    class="flex min-h-svh scroll-mt-20 flex-col justify-center"
    style="background-color: {{ $cream }};"
    aria-label="School selection"
>
    <div class="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 sm:py-12 lg:px-10 lg:py-14">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 md:gap-8 lg:gap-10">
        @forelse($schools as $school)
            @php $isSoc = $school->slug === 'soc'; @endphp
            @if($isSoc)
                <a
                    href="{{ route('schools.show', $school) }}"
                    class="group relative flex aspect-video w-full overflow-hidden rounded-2xl text-center text-white shadow-xl transition duration-300 hover:shadow-2xl focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-thc-maroon"
                    data-reveal
                >
                    <span class="absolute left-0 right-0 top-0 z-20 h-1.5 bg-thc-maroon" aria-hidden="true"></span>
                    <div
                        class="absolute inset-0 bg-cover bg-center transition duration-700 group-hover:scale-[1.03]"
                        style="background-image: url('{{ e(asset($socBg)) }}');"
                        aria-hidden="true"
                    ></div>
                    <div class="absolute inset-0 bg-gradient-to-br from-thc-maroon/90 via-thc-maroon/78 to-thc-navy/72" aria-hidden="true"></div>
                    <div class="pointer-events-none absolute inset-0 opacity-[0.12]" style="background-image: radial-gradient(circle at 40% 30%, rgba(255,255,255,0.35), transparent 55%);" aria-hidden="true"></div>
                    <div class="relative z-10 flex h-full w-full flex-col items-center justify-center px-8 py-10 sm:px-10 sm:py-12">
                        <h2 class="font-serif text-3xl font-semibold sm:text-4xl lg:text-[2.35rem]">
                            {{ $school->name }}
                        </h2>
                        @if($school->tagline)
                            <p class="mt-4 max-w-lg font-sans text-base leading-relaxed text-white/95 sm:text-lg">
                                {{ $school->tagline }}
                            </p>
                        @endif
                        <span class="{{ $ctaClasses }}">Explore school</span>
                    </div>
                </a>
            @else
                <a
                    href="{{ route('schools.show', $school) }}"
                    class="group relative flex aspect-video w-full overflow-hidden rounded-2xl shadow-xl transition duration-300 hover:shadow-2xl focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-thc-royal"
                    data-reveal
                >
                    <span class="absolute left-0 right-0 top-0 z-20 h-1.5 bg-thc-royal" aria-hidden="true"></span>
                    <div
                        class="absolute inset-0 bg-cover bg-center transition duration-700 group-hover:scale-[1.03]"
                        style="background-image: url('{{ e(asset($cohsBg)) }}');"
                    ></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-thc-navy/88 via-thc-navy/60 to-thc-royal/35" aria-hidden="true"></div>
                    <div class="relative z-10 flex h-full w-full flex-col justify-center px-8 py-10 text-left text-white sm:px-10 sm:py-12 md:max-w-[88%]">
                        <h2 class="font-serif text-3xl font-semibold sm:text-4xl lg:text-[2.35rem]">
                            {{ $school->name }}
                        </h2>
                        @if($school->tagline)
                            <p class="mt-4 max-w-lg font-sans text-base leading-relaxed text-white/95 sm:text-lg">
                                {{ $school->tagline }}
                            </p>
                        @endif
                        <span class="{{ $ctaClasses }} w-fit">Explore school</span>
                    </div>
                </a>
            @endif
        @empty
            <p class="col-span-full py-16 text-center text-thc-text/90">School profiles will appear here once published.</p>
        @endforelse
        </div>
    </div>
</section>
