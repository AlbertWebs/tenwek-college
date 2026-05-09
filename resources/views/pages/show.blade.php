@php
    $schoolLandingHeader = isset($school) ? match ($school->slug) {
        'soc' => 'soc',
        'cohs' => 'cohs',
        default => null,
    } : null;
@endphp
<x-layouts.public
    :seo="$seo"
    :landing-header="$schoolLandingHeader"
    :school="isset($school) && in_array($school->slug, ['soc', 'cohs'], true) ? $school : null"
>
    <article class="mx-auto max-w-3xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
        @isset($school)
            @if($school->slug === 'soc')
                <x-schools.soc.breadcrumbs :school="$school" :current="$page->title" class="mb-8" />
            @else
                <nav class="mb-8 text-sm text-thc-text/65" aria-label="Breadcrumb">
                    <ol class="flex flex-wrap gap-2">
                        <li><a href="{{ route('home') }}" class="hover:text-thc-royal">Home</a></li>
                        <li aria-hidden="true">/</li>
                        <li><a href="{{ route('schools.show', $school) }}" class="hover:text-thc-royal">{{ $school->name }}</a></li>
                        <li aria-hidden="true">/</li>
                        <li class="text-thc-navy">{{ $page->title }}</li>
                    </ol>
                </nav>
            @endif
        @else
            <nav class="mb-8 text-sm text-thc-text/65" aria-label="Breadcrumb">
                <ol class="flex flex-wrap gap-2">
                    <li><a href="{{ route('home') }}" class="hover:text-thc-royal">Home</a></li>
                    <li aria-hidden="true">/</li>
                    <li class="text-thc-navy">{{ $page->title }}</li>
                </ol>
            </nav>
        @endisset

        <header>
            <h1 class="font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl">{{ $page->title }}</h1>
            @if($page->excerpt)
                <p class="mt-4 text-lg leading-relaxed text-thc-text/90">{{ $page->excerpt }}</p>
            @endif
        </header>
        <div class="mt-10 space-y-4 text-base leading-relaxed text-thc-text [&_a]:font-medium [&_a]:text-thc-royal [&_h2]:mt-8 [&_h2]:font-serif [&_h2]:text-2xl [&_h2]:text-thc-navy [&_ul]:list-disc [&_ul]:pl-6">
            {!! $page->body !!}
        </div>
    </article>
</x-layouts.public>
