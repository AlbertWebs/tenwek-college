@props(['downloads'])

<section class="thc-section bg-thc-surface" aria-labelledby="home-resources-heading">
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between" data-reveal>
            <div>
                <p class="thc-kicker">Forms &amp; resources</p>
                <h2 id="home-resources-heading" class="mt-3 font-serif text-3xl font-semibold text-thc-navy sm:text-4xl">
                    Downloads hub
                </h2>
                <p class="mt-3 max-w-xl text-thc-text/90">Search admission packs, clinical documents, and student resources.</p>
            </div>
            <form
                method="get"
                action="{{ route('downloads.index') }}"
                class="flex w-full max-w-md flex-col gap-2 sm:flex-row sm:items-center"
                role="search"
            >
                <label class="sr-only" for="home-downloads-search">Search downloads</label>
                <input
                    type="search"
                    name="q"
                    id="home-downloads-search"
                    placeholder="e.g. admission, clinical, internship"
                    class="w-full rounded-full border border-thc-navy/12 bg-thc-surface px-4 py-3 text-sm text-thc-text shadow-inner placeholder:text-thc-text/45 focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/25"
                >
                <button type="submit" class="thc-btn-primary shrink-0 rounded-full px-5 py-3 text-sm shadow-md">
                    Search
                </button>
            </form>
        </div>

        @if($downloads->isEmpty())
            <p class="mt-10 text-center text-thc-text/90" data-reveal>
                Resources are being published. <a href="{{ route('contact.show') }}" class="font-semibold text-thc-royal underline-offset-2 hover:underline">Contact the office</a> for forms.
            </p>
        @else
            <div
                class="mt-10 flex snap-x snap-mandatory gap-4 overflow-x-auto pb-2 lg:grid lg:snap-none lg:grid-cols-2 lg:overflow-visible xl:grid-cols-3"
                data-reveal
            >
                @foreach($downloads as $dl)
                    <article class="thc-card-surface min-w-[min(100%,20rem)] shrink-0 snap-start p-6 transition duration-300 hover:-translate-y-0.5 hover:shadow-[var(--shadow-thc-card-hover)] lg:min-w-0">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="font-serif text-base font-semibold text-thc-navy">
                                <a href="{{ route('downloads.show', $dl->slug) }}" class="hover:text-thc-royal hover:underline">{{ $dl->title }}</a>
                            </h3>
                            @if($dl->hasFile())
                                <span class="shrink-0 rounded-full bg-thc-royal/12 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-thc-navy">File</span>
                            @else
                                <span class="shrink-0 rounded-full bg-thc-maroon/12 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-thc-maroon">Soon</span>
                            @endif
                        </div>
                        <p class="mt-2 text-xs text-thc-text/65">
                            @if($dl->school){{ $dl->school->name }}@endif
                            @if($dl->school && $dl->category) · @endif
                            @if($dl->category){{ $dl->category->name }}@endif
                        </p>
                        @if($dl->description)
                            <p class="mt-3 line-clamp-2 text-sm text-thc-text/90">{{ $dl->description }}</p>
                        @endif
                        <p class="mt-4 text-xs text-thc-text/55">{{ number_format($dl->download_count) }} downloads</p>
                    </article>
                @endforeach
            </div>

            <div class="mt-10 text-center" data-reveal>
                <a href="{{ route('downloads.index') }}" class="thc-btn-ghost">Browse all downloads</a>
            </div>
        @endif
    </div>
</section>
