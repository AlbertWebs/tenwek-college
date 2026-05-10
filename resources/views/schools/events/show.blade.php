<x-layouts.public :seo="$seo" :school="$school" :landing-header="$school->slug">
    <article class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
        <nav class="text-sm text-thc-text/70" aria-label="{{ __('Breadcrumb') }}">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a href="{{ route('home') }}" class="font-medium text-thc-royal hover:underline">{{ __('Home') }}</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('schools.show', $school) }}" class="font-medium text-thc-royal hover:underline">{{ $school->name }}</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('schools.events.index', $school) }}" class="font-medium text-thc-royal hover:underline">{{ __('Events') }}</a></li>
                <li aria-hidden="true">/</li>
                <li class="max-w-[12rem] truncate font-medium text-thc-navy sm:max-w-none">{{ $event->title }}</li>
            </ol>
        </nav>

        <header class="mt-8">
            <p class="text-sm font-medium text-thc-text/70">
                <time datetime="{{ $event->starts_at->toIso8601String() }}">{{ $event->starts_at->format('l, F j, Y') }}</time>
                @if ($event->ends_at)
                    <span class="text-thc-text/50"> — </span>
                    <time datetime="{{ $event->ends_at->toIso8601String() }}">{{ $event->ends_at->format('g:i a') }}</time>
                @else
                    <span class="text-thc-text/55"> · {{ $event->starts_at->format('g:i a') }}</span>
                @endif
            </p>
            @if ($event->location)
                <p class="mt-2 text-sm text-thc-text/80">{{ $event->location }}</p>
            @endif
            <h1 class="mt-4 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl">{{ $event->title }}</h1>
            @if ($event->excerpt)
                <p class="mt-4 text-xl text-thc-text/90">{{ $event->excerpt }}</p>
            @endif
        </header>

        @if ($event->imagePublicUrl())
            <figure class="mt-10 overflow-hidden rounded-2xl border border-thc-navy/12">
                <img src="{{ $event->imagePublicUrl() }}" alt="" class="w-full object-cover" loading="lazy" width="1200" height="675">
            </figure>
        @endif

        @if ($event->registration_url)
            <p class="mt-8">
                <a href="{{ $event->registration_url }}" class="thc-btn-primary inline-flex rounded-xl px-6 py-3 text-sm font-semibold" rel="noopener noreferrer" target="_blank">
                    {{ __('Register or learn more') }}
                </a>
            </p>
        @endif

        <div class="mt-10 space-y-4 text-base leading-relaxed text-thc-text [&_a]:font-medium [&_a]:text-thc-royal [&_h2]:mt-8 [&_h2]:font-serif [&_h2]:text-2xl [&_h2]:text-thc-navy [&_ul]:list-disc [&_ul]:pl-6">
            {!! $event->body !!}
        </div>
    </article>
</x-layouts.public>
