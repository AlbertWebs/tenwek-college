<x-layouts.public
    :seo="$seo"
    :landing-header="$school->slug === 'soc' ? 'soc' : null"
    :school="$school->slug === 'soc' ? $school : null"
>
    <section @class([
        'border-b border-thc-navy/10',
        'soc-page-hero' => $school->slug === 'soc',
        'bg-gradient-to-b from-thc-surface to-thc-navy/[0.03]' => $school->slug !== 'soc',
    ])>
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <nav class="mb-8 text-sm text-thc-text/65" aria-label="Breadcrumb">
                <ol class="flex flex-wrap gap-2">
                    <li><a href="{{ route('home') }}" class="hover:text-thc-royal">Home</a></li>
                    <li aria-hidden="true">/</li>
                    <li class="text-thc-navy">{{ $school->name }}</li>
                </ol>
            </nav>
            <h1 class="max-w-3xl font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl">{{ $school->name }}</h1>
            @if($school->tagline)
                <p class="mt-4 text-xl font-medium text-thc-royal">{{ $school->tagline }}</p>
            @endif
            @if($school->excerpt)
                <p class="mt-6 max-w-2xl text-lg leading-relaxed text-thc-text/90">{{ $school->excerpt }}</p>
            @endif
            <div class="mt-10 flex flex-wrap gap-4">
                <a href="{{ route('downloads.index', ['school' => $school->slug]) }}" class="inline-flex rounded-full bg-thc-royal px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-thc-navy">Downloads</a>
                <a href="{{ route('contact.show') }}" class="inline-flex rounded-full border border-thc-text/25 bg-thc-surface px-5 py-2.5 text-sm font-semibold text-thc-navy hover:border-thc-royal/40">Contact</a>
            </div>
        </div>
    </section>

    @if($school->body)
        <div class="mx-auto max-w-3xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="space-y-4 text-base leading-relaxed text-thc-text [&_a]:font-medium [&_a]:text-thc-royal [&_h2]:mt-8 [&_h2]:font-serif [&_h2]:text-2xl [&_h2]:text-thc-navy [&_ul]:list-disc [&_ul]:pl-6">
                {!! $school->body !!}
            </div>
        </div>
    @endif
</x-layouts.public>
