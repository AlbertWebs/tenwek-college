@php
    $totalHits = $schoolTotal + $pageTotal + $newsTotal + $downloadTotal;
    $landingHeader = match ($filterSchool?->slug) {
        'cohs' => 'cohs',
        'soc' => 'soc',
        default => null,
    };
@endphp

<x-layouts.public :seo="$seo" :landingHeader="$landingHeader" :school="$filterSchool">
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
        <header class="border-b border-thc-navy/10 pb-8">
            <p class="thc-kicker">Search</p>
            <h1 class="mt-2 font-serif text-3xl font-semibold tracking-tight text-thc-navy sm:text-4xl">
                @if($q !== '')
                    Results for “{{ $q }}”
                @else
                    Search the site
                @endif
            </h1>
            @if($q !== '')
                <p class="mt-3 text-sm text-thc-text/80">
                    {{ number_format($totalHits) }} {{ Str::plural('match', $totalHits) }} across schools, pages, news, and downloads.
                </p>
            @else
                <p class="mt-3 text-sm text-thc-text/80">Enter a term to search pages, news, downloadable forms, and school profiles.</p>
            @endif

            <form method="get" action="{{ route('search') }}" class="mt-8 flex flex-col gap-3 sm:flex-row" role="search">
                @if($filterSchool)
                    <input type="hidden" name="school" value="{{ $filterSchool->slug }}">
                @endif
                <label class="sr-only" for="site-search-q">Search</label>
                <input
                    type="search"
                    name="q"
                    id="site-search-q"
                    value="{{ $q }}"
                    placeholder="e.g. admission, chaplaincy, clinical"
                    class="w-full rounded-xl border border-thc-navy/12 px-4 py-3 text-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20"
                    autocomplete="off"
                >
                <button type="submit" class="thc-btn-primary shrink-0 rounded-xl px-6 py-3 text-sm font-semibold">Search</button>
            </form>
            @if($filterSchool)
                <p class="mt-3 text-sm text-thc-text/75">
                    <a href="{{ route('search', array_filter(['q' => $q !== '' ? $q : null])) }}" class="font-medium text-thc-royal hover:underline">Search the entire college site</a>
                    <span class="text-thc-text/55"> (not only {{ $filterSchool->name }}).</span>
                </p>
            @endif
        </header>

        @if($q === '')
            {{-- Prompt only --}}
        @elseif($totalHits === 0)
            <p class="mt-10 text-thc-text/90">No results found. Try different keywords, or browse <a href="{{ route('downloads.index', array_filter(['school' => $filterSchool?->slug])) }}" class="font-semibold text-thc-royal hover:underline">downloads</a> and <a href="{{ route('news.index') }}" class="font-semibold text-thc-royal hover:underline">news</a>.</p>
        @else
            <div class="mt-10 space-y-12">
                @if($schoolTotal > 0)
                    <section aria-labelledby="search-schools">
                        <h2 id="search-schools" class="text-xs font-bold uppercase tracking-[0.18em] text-thc-maroon">Schools</h2>
                        <ul class="mt-4 divide-y divide-thc-navy/10 border-y border-thc-navy/10">
                            @foreach($schools as $school)
                                <li class="py-4">
                                    <a href="{{ route('schools.show', $school) }}" class="font-serif text-lg font-semibold text-thc-navy hover:text-thc-royal">{{ $school->name }}</a>
                                    @if($school->tagline)
                                        <p class="mt-1 text-sm text-thc-text/85">{{ Str::limit($school->tagline, 160) }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        @if($schoolTotal > $schools->count())
                            <p class="mt-2 text-xs text-thc-text/60">{{ number_format($schoolTotal - $schools->count()) }} more not shown; refine your search.</p>
                        @endif
                    </section>
                @endif

                @if($pageTotal > 0)
                    <section aria-labelledby="search-pages">
                        <h2 id="search-pages" class="text-xs font-bold uppercase tracking-[0.18em] text-thc-maroon">Pages</h2>
                        <ul class="mt-4 divide-y divide-thc-navy/10 border-y border-thc-navy/10">
                            @foreach($pages as $page)
                                <li class="py-4">
                                    <a href="{{ $page->publicUrl() }}" class="font-serif text-lg font-semibold text-thc-navy hover:text-thc-royal">{{ $page->title }}</a>
                                    <p class="mt-1 text-xs text-thc-text/55">
                                        @if($page->school){{ $page->school->name }} · @endif Page
                                    </p>
                                    @if($page->excerpt)
                                        <p class="mt-1 text-sm text-thc-text/85">{{ Str::limit(strip_tags($page->excerpt), 180) }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        @if($pageTotal > $pages->count())
                            <p class="mt-2 text-xs text-thc-text/60">Showing {{ $pages->count() }} of {{ number_format($pageTotal) }} page matches.</p>
                        @endif
                    </section>
                @endif

                @if($newsTotal > 0)
                    <section aria-labelledby="search-news">
                        <h2 id="search-news" class="text-xs font-bold uppercase tracking-[0.18em] text-thc-maroon">News</h2>
                        <ul class="mt-4 divide-y divide-thc-navy/10 border-y border-thc-navy/10">
                            @foreach($newsPosts as $post)
                                <li class="py-4">
                                    <a href="{{ route('news.show', $post) }}" class="font-serif text-lg font-semibold text-thc-navy hover:text-thc-royal">{{ $post->title }}</a>
                                    <p class="mt-1 text-xs text-thc-text/55">
                                        {{ $post->published_at?->format('M j, Y') }}
                                        @if($post->school) · {{ $post->school->name }} @endif
                                    </p>
                                    @if($post->excerpt)
                                        <p class="mt-1 text-sm text-thc-text/85">{{ Str::limit(strip_tags($post->excerpt), 180) }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        @if($newsTotal > $newsPosts->count())
                            <p class="mt-2 text-xs text-thc-text/60">Showing {{ $newsPosts->count() }} of {{ number_format($newsTotal) }} news matches. <a href="{{ route('news.index') }}" class="font-semibold text-thc-royal hover:underline">Latest news</a></p>
                        @endif
                    </section>
                @endif

                @if($downloadTotal > 0)
                    <section aria-labelledby="search-downloads">
                        <h2 id="search-downloads" class="text-xs font-bold uppercase tracking-[0.18em] text-thc-maroon">Downloads &amp; forms</h2>
                        <ul class="mt-4 divide-y divide-thc-navy/10 border-y border-thc-navy/10">
                            @foreach($downloads as $dl)
                                <li class="py-4">
                                    <a href="{{ route('downloads.show', $dl) }}" class="font-serif text-lg font-semibold text-thc-navy hover:text-thc-royal">{{ $dl->title }}</a>
                                    <p class="mt-1 text-xs text-thc-text/55">
                                        @if($dl->school){{ $dl->school->name }} · @endif
                                        @if($dl->category){{ $dl->category->name }}@endif
                                    </p>
                                    @if($dl->description)
                                        <p class="mt-1 text-sm text-thc-text/85">{{ Str::limit($dl->description, 180) }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        @if($downloadTotal > $downloads->count())
                            <p class="mt-2 text-sm">
                                <a href="{{ route('downloads.index', array_filter(['q' => $q, 'school' => $filterSchool?->slug])) }}" class="font-semibold text-thc-royal hover:underline">View all {{ number_format($downloadTotal) }} matching downloads</a>
                            </p>
                        @else
                            <p class="mt-4 text-sm">
                                <a href="{{ route('downloads.index', array_filter(['q' => $q, 'school' => $filterSchool?->slug])) }}" class="font-semibold text-thc-royal hover:underline">Open in downloads hub</a>
                            </p>
                        @endif
                    </section>
                @endif
            </div>
        @endif
    </div>
</x-layouts.public>
