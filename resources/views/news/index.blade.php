<x-layouts.public :seo="$seo">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
        <header class="max-w-2xl">
            <h1 class="font-serif text-4xl font-semibold text-thc-navy sm:text-5xl">News & events</h1>
            <p class="mt-4 text-lg text-thc-text/90">Stories from our campuses, hospital partners, and learning community.</p>
        </header>

        <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($posts as $post)
                <article class="flex flex-col overflow-hidden rounded-2xl border border-thc-navy/12 bg-white shadow-sm">
                    @if($post->featured_image_path)
                        <img src="{{ asset($post->featured_image_path) }}" alt="" class="aspect-[16/10] w-full object-cover" loading="lazy">
                    @else
                        <div class="aspect-[16/10] w-full bg-gradient-to-br from-thc-royal/12 to-thc-navy/8"></div>
                    @endif
                    <div class="flex flex-1 flex-col p-6">
                        <time class="text-xs font-medium uppercase tracking-wide text-thc-text/65" datetime="{{ $post->published_at?->toIso8601String() }}">{{ $post->published_at?->format('M j, Y') }}</time>
                        <h2 class="mt-2 font-serif text-xl font-semibold text-thc-navy">
                            <a href="{{ route('news.show', $post) }}" class="hover:text-thc-royal">{{ $post->title }}</a>
                        </h2>
                        @if($post->excerpt)
                            <p class="mt-3 flex-1 text-sm leading-relaxed text-thc-text/90">{{ \Illuminate\Support\Str::limit($post->excerpt, 160) }}</p>
                        @endif
                    </div>
                </article>
            @empty
                <p class="col-span-full text-thc-text/90">News items will appear here soon.</p>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $posts->links() }}
        </div>
    </div>
</x-layouts.public>
