@php
    $soc = $socLanding ?? config('tenwek.soc_landing');
    $G = $soc['gallery'] ?? [];
    $items = $G['items'] ?? [];
@endphp

<x-layouts.public :seo="$seo" landing-header="soc" :school="$school">
    <div class="soc-page-hero">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <x-schools.soc.breadcrumbs :school="$school" :current="$page->title" class="mb-8" data-reveal />

            <header class="max-w-3xl" data-reveal>
                <p class="thc-kicker">{{ $G['kicker'] ?? 'Our gallery' }}</p>
                <h1 class="mt-4 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl">
                    {{ $G['headline'] ?? 'SOC life' }}
                </h1>
                @if(filled($G['intro'] ?? null))
                    <p class="mt-6 text-lg leading-relaxed text-thc-text/90">{{ $G['intro'] }}</p>
                @endif
            </header>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:flex lg:gap-12 lg:px-8 lg:py-16">
        <div class="min-w-0 flex-1">
            @if(count($items) > 0)
                <ul class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 lg:gap-6" role="list">
                    @foreach($items as $index => $item)
                        @php $src = $item['src'] ?? ''; @endphp
                        <li class="flex min-h-0 min-w-0" data-reveal>
                            <figure
                                class="group flex w-full flex-col overflow-hidden rounded-2xl border border-thc-navy/10 bg-thc-navy/[0.04] shadow-sm ring-1 ring-thc-navy/5"
                            >
                                @if(filled($src))
                                    <a
                                        href="{{ \App\Support\Soc\SocLandingRepository::publicMediaUrl($src) ?? asset($src) }}"
                                        class="block shrink-0 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-thc-royal"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        <span class="sr-only">Open full size: {{ $item['caption'] ?? $item['alt'] ?? 'Photo' }}</span>
                                        <div class="aspect-[4/3] overflow-hidden">
                                            <img
                                                src="{{ \App\Support\Soc\SocLandingRepository::publicMediaUrl($src) ?? asset($src) }}"
                                                alt="{{ $item['alt'] ?? '' }}"
                                                class="h-full w-full object-cover transition duration-500 ease-out group-hover:scale-[1.04] motion-reduce:transition-none motion-reduce:group-hover:scale-100"
                                                loading="{{ $index < 3 ? 'eager' : 'lazy' }}"
                                                decoding="async"
                                                sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw"
                                            >
                                        </div>
                                    </a>
                                    <figcaption class="flex h-20 shrink-0 items-center border-t border-thc-navy/8 bg-white px-4 py-2 sm:px-5">
                                        <span class="line-clamp-2 w-full text-sm font-medium leading-snug text-thc-navy">{{ $item['caption'] ?? '' }}</span>
                                    </figcaption>
                                @endif
                            </figure>
                        </li>
                    @endforeach
                </ul>
            @else
                <div
                    class="rounded-2xl border border-dashed border-thc-navy/20 bg-thc-navy/[0.02] px-8 py-16 text-center"
                    data-reveal
                >
                    <p class="font-serif text-lg text-thc-text/80">Photographs will be published here soon.</p>
                </div>
            @endif
        </div>

        <x-schools.soc.about-sidebar :school="$school" :page="$page" />
    </div>
</x-layouts.public>
