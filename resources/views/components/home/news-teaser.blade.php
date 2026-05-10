@props(['posts'])

@if($posts->isNotEmpty())
    <section class="thc-section bg-thc-navy/[0.03]" aria-labelledby="home-news-heading">
        <div class="mx-auto max-w-7xl">
            <div class="flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-end" data-reveal>
                <div>
                    <p class="thc-kicker">News &amp; events</p>
                    <h2 id="home-news-heading" class="mt-3 font-serif text-3xl font-semibold text-thc-navy sm:text-4xl">
                        Latest from the college
                    </h2>
                    <p class="mt-3 max-w-xl text-thc-text/90">Announcements, academic updates, and stories from our community.</p>
                </div>
                <a href="{{ route('news.index') }}" class="thc-btn-ghost shrink-0">View all news</a>
            </div>

            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($posts as $post)
                    <article
                        class="group thc-card-surface flex flex-col overflow-hidden transition duration-300 hover:-translate-y-0.5 hover:shadow-[var(--shadow-thc-card-hover)]"
                        data-reveal
                    >
                        <a href="{{ route('news.show', $post) }}" class="block focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-thc-royal">
                            @if($post->featured_image_path)
                                <div class="aspect-[16/10] w-full overflow-hidden bg-thc-navy/[0.06]">
                                    <img
                                        src="{{ $post->featuredImagePublicUrl() }}"
                                        alt=""
                                        loading="lazy"
                                        decoding="async"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.02]"
                                    >
                                </div>
                            @else
                                <div class="aspect-[16/10] w-full bg-gradient-to-br from-thc-royal/15 via-thc-surface to-thc-navy/10" aria-hidden="true"></div>
                            @endif
                            <div class="flex flex-1 flex-col p-6">
                                <time class="text-xs font-semibold uppercase tracking-wide text-thc-text/65" datetime="{{ $post->published_at?->toIso8601String() }}">
                                    {{ $post->published_at?->format('M j, Y') }}
                                </time>
                                <h3 class="mt-3 font-serif text-lg font-semibold text-thc-navy group-hover:text-thc-royal">
                                    {{ $post->title }}
                                </h3>
                                @if($post->excerpt)
                                    <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-thc-text/90">{{ $post->excerpt }}</p>
                                @endif
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
