@php
    $soc = $socLanding ?? config('tenwek.soc_landing');
    $G = $soc['gallery'] ?? [];
    $items = $G['items'] ?? [];
    $lightboxItems = collect($items)
        ->filter(fn ($item) => filled($item['src'] ?? ''))
        ->map(function ($item) {
            $src = $item['src'] ?? '';

            return [
                'src' => \App\Support\Soc\SocLandingRepository::publicMediaUrl($src) ?? asset($src),
                'alt' => $item['alt'] ?? '',
                'caption' => $item['caption'] ?? '',
            ];
        })
        ->values()
        ->all();
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

    <div
        class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:flex lg:gap-12 lg:px-8 lg:py-16"
        x-data="socGallery({ items: @js($lightboxItems) })"
        @keydown.window="onKeydown($event)"
    >
        <div class="min-w-0 flex-1">
            @if(count($items) > 0)
                @php $lightboxSlot = 0; @endphp
                <ul class="grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-3 lg:gap-6" role="list">
                    @foreach($items as $index => $item)
                        @php $src = $item['src'] ?? ''; @endphp
                        <li class="flex min-h-0 min-w-0" data-reveal>
                            <figure
                                class="group flex w-full flex-col overflow-hidden rounded-2xl border border-thc-navy/10 bg-thc-navy/[0.04] shadow-sm ring-1 ring-thc-navy/5"
                            >
                                @if(filled($src))
                                    @php
                                        $imgUrl = \App\Support\Soc\SocLandingRepository::publicMediaUrl($src) ?? asset($src);
                                        $lbIndex = $lightboxSlot++;
                                    @endphp
                                    <button
                                        type="button"
                                        class="block w-full shrink-0 cursor-zoom-in text-left transition focus-visible:outline focus-visible:ring-2 focus-visible:ring-thc-royal focus-visible:ring-offset-2"
                                        @click="openAt({{ $lbIndex }})"
                                        :aria-expanded="open && activeIndex === {{ $lbIndex }} ? 'true' : 'false'"
                                        aria-haspopup="dialog"
                                    >
                                        <span class="sr-only">{{ __('View larger') }}: {{ $item['caption'] ?? $item['alt'] ?? __('Photo') }}</span>
                                        <div class="aspect-[4/3] overflow-hidden">
                                            <img
                                                src="{{ $imgUrl }}"
                                                alt="{{ $item['alt'] ?? '' }}"
                                                class="h-full w-full object-cover transition duration-500 ease-out group-hover:scale-[1.04] motion-reduce:transition-none motion-reduce:group-hover:scale-100"
                                                loading="{{ $index < 3 ? 'eager' : 'lazy' }}"
                                                decoding="async"
                                                sizes="(min-width: 1024px) 33vw, 50vw"
                                            >
                                        </div>
                                    </button>
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

        <template x-teleport="body">
            <div
                x-show="open"
                x-cloak
                x-transition:enter="transition ease-out duration-200 motion-reduce:transition-none"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150 motion-reduce:transition-none"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-[200] flex flex-col items-stretch justify-end sm:justify-center"
                role="dialog"
                aria-modal="true"
                aria-labelledby="soc-gallery-lightbox-title"
            >
                <div
                    class="absolute inset-0 bg-thc-navy/88 backdrop-blur-md motion-reduce:backdrop-blur-none"
                    @click="close()"
                    aria-hidden="true"
                ></div>

                <div class="relative z-10 flex max-h-[100dvh] w-full flex-col p-4 pb-[max(1rem,env(safe-area-inset-bottom))] sm:p-8 sm:pb-8">
                    <div class="relative mx-auto w-full max-w-5xl">
                        <div class="mb-3 flex items-center justify-between gap-3 sm:absolute sm:-top-2 sm:right-0 sm:mb-0 sm:translate-y-[-100%]">
                            <p id="soc-gallery-lightbox-title" class="min-w-0 text-left text-xs font-semibold uppercase tracking-[0.18em] text-white/70 sm:text-white/80">
                                {{ __('Gallery') }}
                                <span x-show="hasMany" class="font-mono tabular-nums text-white/50" x-text="' · ' + (activeIndex + 1) + ' / ' + items.length"></span>
                            </p>
                            <button
                                type="button"
                                class="shrink-0 rounded-full border border-white/25 bg-white/10 px-3 py-1.5 text-xs font-semibold text-white shadow-sm backdrop-blur-sm transition hover:bg-white/20 focus-visible:outline focus-visible:ring-2 focus-visible:ring-white/60"
                                @click="close()"
                            >
                                {{ __('Close') }}
                                <span class="sr-only"> ({{ __('Escape') }})</span>
                            </button>
                        </div>

                        <div
                            class="relative overflow-hidden rounded-2xl border border-white/15 bg-black/20 shadow-2xl ring-1 ring-black/20"
                            @click.stop
                            @touchstart.passive="onTouchStart($event)"
                            @touchend.passive="onTouchEnd($event)"
                        >
                            <button
                                type="button"
                                class="absolute left-1 top-1/2 z-10 inline-flex -translate-y-1/2 rounded-full border border-white/20 bg-thc-navy/70 p-2 text-white shadow-lg backdrop-blur-sm transition hover:bg-thc-navy/90 focus-visible:outline focus-visible:ring-2 focus-visible:ring-white/50 sm:left-3 sm:p-2.5"
                                x-show="hasMany"
                                x-cloak
                                @click.stop="prev()"
                                aria-label="{{ __('Previous image') }}"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button
                                type="button"
                                class="absolute right-1 top-1/2 z-10 inline-flex -translate-y-1/2 rounded-full border border-white/20 bg-thc-navy/70 p-2 text-white shadow-lg backdrop-blur-sm transition hover:bg-thc-navy/90 focus-visible:outline focus-visible:ring-2 focus-visible:ring-white/50 sm:right-3 sm:p-2.5"
                                x-show="hasMany"
                                x-cloak
                                @click.stop="next()"
                                aria-label="{{ __('Next image') }}"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>

                            <div
                                class="flex max-h-[min(78vh,720px)] min-h-[12rem] items-center justify-center bg-gradient-to-b from-thc-navy/40 to-black/30 p-2 sm:max-h-[min(82vh,800px)] sm:p-4"
                                x-transition:enter="transition ease-out duration-200 motion-reduce:transition-none"
                                x-transition:enter-start="opacity-0 scale-[0.98]"
                                x-transition:enter-end="opacity-100 scale-100"
                            >
                                <img
                                    x-show="open"
                                    :src="active.src"
                                    :alt="active.alt"
                                    class="max-h-[min(76vh,700px)] w-auto max-w-full rounded-lg object-contain shadow-lg motion-reduce:transition-none sm:max-h-[min(80vh,780px)]"
                                    width="1600"
                                    height="1200"
                                >
                            </div>
                        </div>

                        <p
                            class="mt-4 max-w-3xl px-1 text-center text-sm leading-relaxed text-white/90 sm:mx-auto sm:mt-5 sm:px-0 sm:text-base"
                            x-text="active.caption || ''"
                            x-show="active.caption"
                            x-cloak
                        ></p>
                        <p class="mt-2 text-center text-[11px] text-white/45" x-show="hasMany" x-cloak>{{ __('Swipe sideways or use arrow keys to browse') }}</p>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-layouts.public>
