@php
    $soc = $socLanding ?? config('tenwek.soc_landing');
    $B = $soc['board_and_management'] ?? [];
@endphp

<x-layouts.public :seo="$seo" landing-header="soc" :school="$school">
    <div class="soc-page-hero">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <x-schools.soc.breadcrumbs :school="$school" :current="$page->title" class="mb-8" data-reveal />

            <header class="max-w-3xl" data-reveal>
                <p class="thc-kicker">Governance</p>
                <h1 class="mt-4 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl">
                    {{ $page->title }}
                </h1>
                <p class="mt-6 text-lg leading-relaxed text-thc-text/90">
                    {{ $B['intro'] ?? '' }}
                </p>
                @if(!empty($B['photo_note']))
                    <p class="mt-4 text-sm text-thc-text/65">{{ $B['photo_note'] }}</p>
                @endif
            </header>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:flex lg:gap-12 lg:px-8 lg:py-16">
        <div class="min-w-0 flex-1 space-y-20">
            <section aria-labelledby="soc-board-heading" data-reveal>
                <div class="flex flex-col gap-2 border-b border-thc-navy/10 pb-5 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 id="soc-board-heading" class="font-serif text-2xl font-semibold text-thc-navy sm:text-3xl">
                            {{ $B['board_heading'] ?? 'Board' }}
                        </h2>
                        <p class="mt-1 text-sm text-thc-text/65">Hospital board members</p>
                    </div>
                </div>
                <ul class="mt-10 grid gap-6 sm:grid-cols-2 xl:grid-cols-3" role="list">
                    @foreach($B['board'] ?? [] as $person)
                        <li>
                            <x-schools.soc.leadership-card :person="$person" accent="maroon" />
                        </li>
                    @endforeach
                </ul>
            </section>

            <section aria-labelledby="soc-management-heading" data-reveal>
                <div class="flex flex-col gap-2 border-b border-thc-navy/10 pb-5 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 id="soc-management-heading" class="font-serif text-2xl font-semibold text-thc-navy sm:text-3xl">
                            {{ $B['management_heading'] ?? 'School management' }}
                        </h2>
                        <p class="mt-1 text-sm text-thc-text/65">School and hospital leadership</p>
                    </div>
                </div>
                <ul class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-2" role="list">
                    @foreach($B['management'] ?? [] as $person)
                        <li>
                            <x-schools.soc.leadership-card :person="$person" accent="royal" />
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>

        <x-schools.soc.about-sidebar :school="$school" :page="$page" />
    </div>
</x-layouts.public>
