<x-layouts.public :seo="$seo">
    <article class="mx-auto max-w-3xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
        <header>
            <time class="text-sm font-medium text-thc-text/65" datetime="{{ $post->published_at?->toIso8601String() }}">{{ $post->published_at?->format('F j, Y') }}</time>
            <h1 class="mt-3 font-serif text-4xl font-semibold text-thc-navy sm:text-5xl">{{ $post->title }}</h1>
            @if($post->excerpt)
                <p class="mt-4 text-xl text-thc-text/90">{{ $post->excerpt }}</p>
            @endif
        </header>
        @if($post->featured_image_path)
            <figure class="mt-10 overflow-hidden rounded-2xl border border-thc-navy/12">
                <img src="{{ $post->featuredImagePublicUrl() }}" alt="" class="w-full object-cover" loading="lazy">
            </figure>
        @endif
        <div class="mt-10 space-y-4 text-base leading-relaxed text-thc-text [&_a]:font-medium [&_a]:text-thc-royal [&_h2]:mt-8 [&_h2]:font-serif [&_h2]:text-2xl [&_h2]:text-thc-navy [&_ul]:list-disc [&_ul]:pl-6">
            {!! $post->body !!}
        </div>
    </article>
</x-layouts.public>
