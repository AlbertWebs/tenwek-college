<x-layouts.public :seo="$seo">
    <div class="relative overflow-hidden pb-20 lg:pb-28">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-[min(32rem,85vh)] bg-gradient-to-b from-thc-navy/[0.08] via-thc-royal/[0.05] via-40% to-transparent" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-28 top-8 h-80 w-80 rounded-full bg-thc-maroon/[0.09] blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -left-24 top-40 h-72 w-72 rounded-full bg-thc-teal/[0.11] blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute left-1/3 top-24 h-64 w-64 -translate-x-1/2 rounded-full bg-thc-royal/[0.08] blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute bottom-40 right-10 hidden h-52 w-52 rounded-full bg-thc-soc-purple/[0.07] blur-3xl lg:block" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-7xl px-4 pt-14 sm:px-6 lg:px-8 lg:pt-20">
            <div class="mx-auto flex max-w-md justify-center gap-1.5 sm:max-w-lg" aria-hidden="true">
                <span class="h-1 flex-1 max-w-[4.5rem] rounded-full bg-thc-maroon"></span>
                <span class="h-1 flex-1 max-w-[4.5rem] rounded-full bg-thc-royal"></span>
                <span class="h-1 flex-1 max-w-[4.5rem] rounded-full bg-thc-teal"></span>
                <span class="h-1 flex-1 max-w-[4.5rem] rounded-full bg-thc-navy"></span>
                <span class="h-1 flex-1 max-w-[4.5rem] rounded-full bg-thc-soc-purple"></span>
            </div>

            <header class="mx-auto mt-10 max-w-3xl text-center">
                <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-thc-teal">{{ __('College newsroom') }}</p>
                <h1 class="mt-4 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl lg:text-[3.25rem] lg:leading-tight">
                    {{ __('News & events') }}
                </h1>
                <p class="mx-auto mt-5 max-w-2xl text-lg leading-relaxed text-thc-text/88">
                    {{ __('Stories from our campuses, hospital partners, and learning community.') }}
                </p>
                <p class="mx-auto mt-3 max-w-xl text-sm text-thc-text/65">
                    {{ __('Highlights from chaplaincy, health sciences, and the wider Tenwek Hospital College family.') }}
                </p>

                <ul class="mt-10 flex flex-wrap items-center justify-center gap-2 sm:gap-3" role="list">
                    <li>
                        <span class="inline-flex items-center gap-2 rounded-full border border-thc-navy/10 bg-white/90 px-3.5 py-2 text-[11px] font-bold uppercase tracking-[0.14em] text-thc-navy shadow-sm shadow-thc-navy/[0.04] backdrop-blur-sm">
                            <span class="h-2 w-2 shrink-0 rounded-full bg-thc-maroon" aria-hidden="true"></span>
                            {{ __('College-wide') }}
                        </span>
                    </li>
                    <li>
                        <span class="inline-flex items-center gap-2 rounded-full border border-thc-soc-purple/20 bg-white/90 px-3.5 py-2 text-[11px] font-bold uppercase tracking-[0.14em] text-thc-soc-purple shadow-sm backdrop-blur-sm">
                            <span class="h-2 w-2 shrink-0 rounded-full bg-thc-soc-purple" aria-hidden="true"></span>
                            {{ __('School of Chaplaincy') }}
                        </span>
                    </li>
                    <li>
                        <span class="inline-flex items-center gap-2 rounded-full border border-thc-royal/20 bg-white/90 px-3.5 py-2 text-[11px] font-bold uppercase tracking-[0.14em] text-thc-royal shadow-sm backdrop-blur-sm">
                            <span class="h-2 w-2 shrink-0 rounded-full bg-thc-royal" aria-hidden="true"></span>
                            {{ __('Health sciences') }}
                        </span>
                    </li>
                    <li>
                        <span class="inline-flex items-center gap-2 rounded-full border border-thc-teal/25 bg-white/90 px-3.5 py-2 text-[11px] font-bold uppercase tracking-[0.14em] text-thc-teal shadow-sm backdrop-blur-sm">
                            <span class="h-2 w-2 shrink-0 rounded-full bg-thc-teal" aria-hidden="true"></span>
                            {{ __('Clinical stories') }}
                        </span>
                    </li>
                </ul>
            </header>

            <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-3 lg:gap-10">
                @php
                    $rotationPlaceholders = [
                        'from-thc-maroon/25 via-thc-royal/15 to-thc-navy/20',
                        'from-thc-royal/20 via-thc-teal/18 to-thc-navy/15',
                        'from-thc-teal/22 via-thc-maroon/12 to-thc-royal/14',
                        'from-thc-soc-purple/20 via-thc-teal/14 to-thc-navy/18',
                        'from-thc-navy/18 via-thc-maroon/15 to-thc-teal/16',
                    ];
                @endphp
                @forelse($posts as $post)
                    @php
                        $slug = $post->school?->slug;
                        $topBar = match ($slug) {
                            'soc' => 'from-thc-soc-purple via-thc-maroon/75 to-thc-teal/80',
                            'cohs' => 'from-thc-royal via-thc-teal/70 to-thc-navy/65',
                            default => [
                                'from-thc-maroon via-thc-royal/75 to-thc-navy/55',
                                'from-thc-teal via-thc-maroon/65 to-thc-royal/70',
                                'from-thc-navy via-thc-teal/60 to-thc-maroon/55',
                                'from-thc-royal via-thc-soc-purple/50 to-thc-teal/75',
                            ][$loop->index % 4],
                        };
                        $placeholderGrad = $rotationPlaceholders[$loop->index % count($rotationPlaceholders)];
                        $schoolLabel = match ($slug) {
                            'soc' => __('Chaplaincy'),
                            'cohs' => __('Health sciences'),
                            default => __('College'),
                        };
                        $schoolPillClass = match ($slug) {
                            'soc' => 'border-thc-soc-purple/25 bg-thc-soc-purple/10 text-thc-soc-purple',
                            'cohs' => 'border-thc-royal/25 bg-thc-royal/10 text-thc-royal',
                            default => 'border-thc-maroon/25 bg-thc-maroon/10 text-thc-maroon',
                        };
                    @endphp
                    <article class="group relative flex flex-col overflow-hidden rounded-2xl border border-thc-navy/10 bg-white shadow-md shadow-thc-navy/[0.05] ring-1 ring-thc-navy/[0.04] transition duration-300 hover:-translate-y-1 hover:border-thc-royal/20 hover:shadow-xl hover:shadow-thc-navy/[0.08]">
                        <span class="absolute inset-x-0 top-0 z-10 h-1.5 bg-gradient-to-r {{ $topBar }}" aria-hidden="true"></span>
                        @if($post->featured_image_path)
                            <div class="relative mt-1.5 overflow-hidden">
                                <img
                                    src="{{ $post->featuredImagePublicUrl() }}"
                                    alt="{{ $post->title }}"
                                    class="aspect-[16/10] w-full object-cover transition duration-500 group-hover:scale-[1.02]"
                                    loading="lazy"
                                >
                                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-thc-navy/25 via-transparent to-transparent opacity-0 transition group-hover:opacity-100" aria-hidden="true"></div>
                            </div>
                        @else
                            <div class="relative mt-1.5 aspect-[16/10] w-full overflow-hidden bg-gradient-to-br {{ $placeholderGrad }}" aria-hidden="true">
                                <div class="absolute inset-0 opacity-40 mix-blend-overlay bg-[radial-gradient(circle_at_30%_20%,white,transparent_55%)]"></div>
                            </div>
                        @endif
                        <div class="flex flex-1 flex-col p-6 sm:p-7">
                            <div class="flex flex-wrap items-center gap-2 gap-y-2">
                                <time class="text-xs font-semibold uppercase tracking-[0.12em] text-thc-text/60" datetime="{{ $post->published_at?->toIso8601String() }}">
                                    {{ $post->published_at?->format('M j, Y') }}
                                </time>
                                <span class="rounded-full border px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-[0.14em] {{ $schoolPillClass }}">
                                    {{ $schoolLabel }}
                                </span>
                            </div>
                            <h2 class="mt-4 font-serif text-xl font-semibold leading-snug text-thc-navy lg:text-[1.35rem]">
                                <a href="{{ route('news.show', $post) }}" class="transition hover:text-thc-royal focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-thc-royal/35 focus-visible:ring-offset-2">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            @if($post->excerpt)
                                <p class="mt-3 flex-1 text-sm leading-relaxed text-thc-text/88">
                                    {{ \Illuminate\Support\Str::limit($post->excerpt, 160) }}
                                </p>
                            @endif
                            <p class="mt-5">
                                <a
                                    href="{{ route('news.show', $post) }}"
                                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-thc-teal transition group-hover:gap-2 hover:text-thc-royal"
                                >
                                    {{ __('Read story') }}
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            </p>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full">
                        <div class="relative mx-auto max-w-lg overflow-hidden rounded-2xl border border-dashed border-thc-navy/20 bg-gradient-to-br from-white via-thc-royal/[0.04] to-thc-teal/[0.06] px-8 py-14 text-center shadow-inner shadow-thc-navy/[0.04]">
                            <div class="pointer-events-none absolute -right-8 -top-8 h-32 w-32 rounded-full bg-thc-maroon/10 blur-2xl" aria-hidden="true"></div>
                            <div class="pointer-events-none absolute -bottom-10 -left-10 h-36 w-36 rounded-full bg-thc-soc-purple/10 blur-2xl" aria-hidden="true"></div>
                            <p class="relative text-sm font-medium text-thc-navy">{{ __('News items will appear here soon.') }}</p>
                            <p class="relative mt-2 text-xs text-thc-text/65">{{ __('Check back for announcements, events, and stories from our community.') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($posts->hasPages())
                <div class="mt-14 flex justify-center border-t border-thc-navy/8 pt-10">
                    {{ $posts->links() }}
                </div>
            @endif

            <section class="relative mx-auto mt-16 max-w-4xl overflow-hidden rounded-2xl border border-thc-navy/10 bg-gradient-to-br from-thc-navy/[0.04] via-white to-thc-teal/[0.06] px-6 py-10 text-center shadow-sm sm:px-10 sm:py-12">
                <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-thc-maroon/30 via-thc-royal/30 via-thc-teal/30 to-transparent" aria-hidden="true"></div>
                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-thc-teal">{{ __('Stay connected') }}</p>
                <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-thc-text/80">
                    {{ __('For admissions, downloads, and how to reach each school, explore the rest of the site or send us a message.') }}
                </p>
                <div class="mt-8 flex flex-col items-stretch justify-center gap-3 sm:flex-row sm:flex-wrap">
                    <a href="{{ route('home') }}" class="thc-btn-ghost justify-center sm:min-w-[9rem]">{{ __('Home') }}</a>
                    <a href="{{ route('downloads.index') }}" class="thc-btn-ghost justify-center sm:min-w-[9rem]">{{ __('Downloads') }}</a>
                    <a href="{{ route('contact.show') }}" class="thc-btn-primary justify-center sm:min-w-[10rem]">{{ __('Contact') }}</a>
                </div>
            </section>
        </div>
    </div>
</x-layouts.public>
