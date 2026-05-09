@php
    $pageUrl = fn (string $slug) => route('schools.pages.show', [$school, $slug]);
    $programmesIndex = route('schools.pages.show', [$school, 'academic-programmes']);
    $trail = [['label' => 'Academic programmes', 'href' => $programmesIndex]];
@endphp

<x-layouts.public :seo="$seo" landing-header="soc" :school="$school">
    <div class="soc-page-hero">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <x-schools.soc.breadcrumbs :school="$school" :trail="$trail" :current="$page->title" class="mb-8" data-reveal />

            <header class="max-w-3xl" data-reveal>
                <h1 class="font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl">
                    {{ $page->title }}
                </h1>
                @if($page->excerpt)
                    <p class="mt-4 text-lg leading-relaxed text-thc-text/90">{{ $page->excerpt }}</p>
                @endif
            </header>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:flex lg:gap-12 lg:px-8 lg:py-16">
        <article class="min-w-0 flex-1">
            <div
                class="max-w-none space-y-4 text-base leading-relaxed text-thc-text [&_a]:font-medium [&_a]:text-thc-royal [&_h2]:mt-8 [&_h2]:font-serif [&_h2]:text-2xl [&_h2]:text-thc-navy [&_ul]:list-disc [&_ul]:pl-6"
                data-reveal
            >
                {!! $page->body !!}
            </div>
            <p class="mt-10" data-reveal>
                <a
                    href="{{ $programmesIndex }}"
                    class="inline-flex items-center gap-2 font-semibold text-thc-royal transition hover:text-thc-navy"
                >
                    ← Back to academic programmes
                </a>
            </p>
        </article>

        <x-schools.soc.about-sidebar :school="$school" :page="$page" />
    </div>
</x-layouts.public>
