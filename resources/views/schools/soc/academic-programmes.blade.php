@php
    $soc = $socLanding ?? config('tenwek.soc_landing');
    $A = $soc['academic_programmes'] ?? [];
    $pageUrl = fn (string $slug) => route('schools.pages.show', [$school, $slug]);
    $groups = $A['groups'] ?? [];
@endphp

<x-layouts.public :seo="$seo" landing-header="soc" :school="$school">
    <div class="soc-page-hero">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <x-schools.soc.breadcrumbs :school="$school" :current="$page->title" class="mb-8" data-reveal />

            <header class="max-w-3xl" data-reveal>
                <p class="thc-kicker">{{ $A['kicker'] ?? 'Our courses' }}</p>
                <h1 class="mt-4 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl">
                    {{ $page->title }}
                </h1>
                <div class="mt-6 space-y-4 text-lg leading-relaxed text-thc-text/90">
                    @foreach($A['intro_paragraphs'] ?? [] as $para)
                        <p>{{ $para }}</p>
                    @endforeach
                </div>
            </header>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:flex lg:gap-12 lg:px-8 lg:py-16">
        <div class="min-w-0 flex-1 space-y-14 lg:space-y-16">
            @foreach($groups as $group)
                <section data-reveal aria-labelledby="group-heading-{{ \Illuminate\Support\Str::slug($group['heading'] ?? 'group') }}">
                    <h2 id="group-heading-{{ \Illuminate\Support\Str::slug($group['heading'] ?? 'group') }}" class="font-serif text-2xl font-semibold text-thc-navy sm:text-3xl">
                        {{ $group['heading'] ?? '' }}
                    </h2>
                    @if(filled($group['description'] ?? null))
                        <p class="mt-4 max-w-3xl text-base leading-relaxed text-thc-text/90">{{ $group['description'] }}</p>
                    @endif
                    <ul class="mt-8 grid gap-4 sm:grid-cols-2" role="list">
                        @foreach($group['items'] ?? [] as $item)
                            @php
                                $href = $pageUrl($item['slug']);
                            @endphp
                            <li>
                                <a
                                    href="{{ $href }}"
                                    class="group flex h-full flex-col rounded-2xl border border-thc-navy/10 bg-white p-6 shadow-sm transition hover:border-thc-royal/35 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-thc-royal"
                                >
                                    <div class="flex flex-wrap items-start justify-between gap-2">
                                        <h3 class="font-serif text-lg font-semibold text-thc-navy group-hover:text-thc-royal sm:text-xl">
                                            {{ $item['title'] }}
                                        </h3>
                                        @if(filled($item['badge'] ?? null))
                                            <span class="shrink-0 rounded-full bg-thc-maroon/10 px-2.5 py-0.5 text-xs font-semibold text-thc-maroon">{{ $item['badge'] }}</span>
                                        @endif
                                    </div>
                                    @if(filled($item['summary'] ?? null))
                                        <p class="mt-3 flex-1 text-sm leading-relaxed text-thc-text/85">{{ $item['summary'] }}</p>
                                    @endif
                                    <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-thc-royal">
                                        View programme
                                        <span aria-hidden="true" class="transition group-hover:translate-x-0.5">→</span>
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </div>

        <x-schools.soc.about-sidebar :school="$school" :page="$page" />
    </div>
</x-layouts.public>
