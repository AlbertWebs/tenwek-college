@props([
    'school',
    'page',
])

@php
    $soc = $socLanding ?? config('tenwek.soc_landing');
    $links = $soc['our_history']['about_sidebar'] ?? [];
    $aboutSlugs = collect($links)->pluck('slug')->filter()->values()->all();
    $show = in_array($page->slug, $aboutSlugs, true);
    $pageUrl = fn (string $slug): string => $slug === 'register'
        ? route('soc.register')
        : route('schools.pages.show', [$school, $slug]);
@endphp

@if($show && count($links) > 0)
    <aside class="mt-12 w-full shrink-0 lg:mt-0 lg:w-64 xl:w-72" aria-label="About us section links" data-reveal>
        <div class="sticky top-28 rounded-2xl border border-thc-navy/10 bg-white p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-thc-soc-teal">About Us</p>
            <ul class="mt-4 space-y-1">
                @foreach($links as $link)
                    @php $active = $link['slug'] === $page->slug; @endphp
                    <li>
                        <a
                            href="{{ $pageUrl($link['slug']) }}"
                            @class([
                                'block rounded-lg px-3 py-2.5 text-sm font-semibold transition',
                                'bg-thc-soc-teal/10 text-thc-soc-teal' => $active,
                                'text-thc-navy hover:bg-thc-royal/8' => ! $active,
                            ])
                        >{{ $link['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </aside>
@endif
