@php
    $soc = $socLanding ?? config('tenwek.soc_landing');
    $Q = $soc['faqs'] ?? [];
    $items = $Q['items'] ?? [];
@endphp

<x-layouts.public :seo="$seo" landing-header="soc" :school="$school">
    <div class="soc-page-hero">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <x-schools.soc.breadcrumbs :school="$school" :current="$page->title" class="mb-8" data-reveal />

            <header class="max-w-3xl" data-reveal>
                <p class="thc-kicker">{{ $Q['kicker'] ?? 'FAQs' }}</p>
                <h1 class="mt-4 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl">
                    {{ $page->title }}
                </h1>
                @if(filled($Q['intro'] ?? null))
                    <p class="mt-6 text-lg leading-relaxed text-thc-text/90">{{ $Q['intro'] }}</p>
                @endif
            </header>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:flex lg:gap-12 lg:px-8 lg:py-16">
        <div class="min-w-0 flex-1">
            <div class="space-y-4">
                @foreach($items as $index => $item)
                    <details
                        class="group overflow-hidden rounded-2xl border border-thc-navy/10 bg-white shadow-sm ring-1 ring-thc-navy/5 open:shadow-md"
                        @if($index === 0) open @endif
                        data-reveal
                    >
                        <summary
                            class="flex cursor-pointer list-none items-center justify-between gap-4 px-5 py-4 font-serif text-lg font-semibold text-thc-navy transition hover:bg-thc-royal/[0.04] sm:px-6 sm:py-5 sm:text-xl [&::-webkit-details-marker]:hidden"
                        >
                            <span class="min-w-0 pr-2">{{ $item['question'] ?? '' }}</span>
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-thc-navy/15 bg-thc-navy/[0.04] text-thc-navy transition group-open:rotate-180"
                                aria-hidden="true"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </summary>
                        <div class="border-t border-thc-navy/8 px-5 pb-5 pt-2 sm:px-6 sm:pb-6">
                            @if(filled($item['lead'] ?? null))
                                <p class="mt-3 text-base leading-relaxed text-thc-text/90">{{ $item['lead'] }}</p>
                            @endif

                            @if(!empty($item['comparison']['rows']))
                                @php
                                    $C = $item['comparison'];
                                @endphp
                                <div class="mt-5 overflow-hidden rounded-xl border border-thc-navy/10">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-[36rem] w-full border-collapse text-left text-sm">
                                            <thead>
                                                <tr class="bg-gradient-to-r from-thc-navy to-thc-navy/95 text-white">
                                                    <th scope="col" class="w-2/5 px-4 py-3 font-semibold sm:px-5">{{ $C['left_header'] ?? 'Pastors' }}</th>
                                                    <th scope="col" class="px-4 py-3 font-semibold sm:px-5">{{ $C['right_header'] ?? 'Chaplains' }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-thc-navy/8">
                                                @foreach($C['rows'] as $row)
                                                    <tr class="bg-white hover:bg-thc-royal/[0.03]">
                                                        <td class="align-top px-4 py-3 text-thc-text sm:px-5">{{ $row['left'] ?? '' }}</td>
                                                        <td class="align-top px-4 py-3 text-thc-text sm:px-5">{{ $row['right'] ?? '' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @foreach($item['paragraphs'] ?? [] as $para)
                                <p class="mt-4 text-base leading-relaxed text-thc-text/90 first:mt-3">{{ $para }}</p>
                            @endforeach

                            @if(!empty($item['certificate']))
                                <div class="mt-4 rounded-xl bg-thc-navy/[0.04] px-4 py-4 sm:px-5">
                                    <p class="font-semibold text-thc-navy">{{ $item['certificate']['title'] ?? '' }}</p>
                                    <p class="mt-2 text-thc-text/90">{{ $item['certificate']['body'] ?? '' }}</p>
                                </div>
                            @endif

                            @if(!empty($item['diploma']['items']))
                                <div class="mt-4">
                                    <p class="font-semibold text-thc-navy">{{ $item['diploma']['title'] ?? '' }}</p>
                                    <ol class="mt-3 list-decimal space-y-3 pl-5 text-thc-text/90">
                                        @foreach($item['diploma']['items'] as $li)
                                            <li>{{ $li }}</li>
                                        @endforeach
                                    </ol>
                                </div>
                            @endif

                            @foreach($item['lines'] ?? [] as $line)
                                <p class="mt-3 text-base leading-relaxed text-thc-text/90 first:mt-3">{{ $line }}</p>
                            @endforeach

                            @if(!empty($item['bullets']))
                                <ul class="mt-4 list-none space-y-3 text-thc-text/90" role="list">
                                    @foreach($item['bullets'] as $b)
                                        <li class="flex gap-3">
                                            <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-thc-maroon" aria-hidden="true"></span>
                                            <span>{{ $b }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </details>
                @endforeach
            </div>

            <p class="mt-12 text-center" data-reveal>
                <a
                    href="{{ route('schools.pages.show', [$school, 'admissions']) }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-thc-royal hover:text-thc-navy"
                >
                    Still unsure? View admissions →
                </a>
            </p>
        </div>

        <x-schools.soc.about-sidebar :school="$school" :page="$page" />
    </div>
</x-layouts.public>
