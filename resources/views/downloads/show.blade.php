@php
    $landingHeader = match ($download->school?->slug) {
        'cohs' => 'cohs',
        'soc' => 'soc',
        default => null,
    };
@endphp

<x-layouts.public :seo="$seo" :landingHeader="$landingHeader" :school="$download->school">
    <article class="mx-auto max-w-3xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
        <header>
            <h1 class="font-serif text-4xl font-semibold text-thc-navy sm:text-5xl">{{ $download->title }}</h1>
            <p class="mt-3 text-sm text-thc-text/65">
                @if($download->school)<span>{{ $download->school->name }}</span> · @endif
                @if($download->category)<span>{{ $download->category->name }}</span>@endif
                · Updated {{ $download->updated_at->format('M j, Y') }}
            </p>
        </header>
        @if($download->description)
            <div class="mt-8 space-y-3 text-base leading-relaxed text-thc-text">{{ $download->description }}</div>
        @endif

        @if($download->hasFile())
            <div class="mt-10 rounded-2xl border border-thc-navy/15 bg-thc-royal/8 p-6">
                <p class="text-sm font-medium text-thc-navy">Secure download</p>
                <p class="mt-1 text-sm text-thc-text/90">{{ strtoupper($download->extension ?? '') }} · @if($download->size_bytes){{ number_format($download->size_bytes / 1024, 0) }} KB @endif</p>
                <a href="{{ route('downloads.file', $download->slug) }}" class="mt-4 inline-flex rounded-full bg-thc-royal px-5 py-2.5 text-sm font-semibold text-white hover:bg-thc-navy">Download file</a>
            </div>
        @elseif(filled($download->external_url))
            <div class="mt-10 rounded-2xl border border-thc-navy/15 bg-thc-royal/8 p-6">
                <p class="text-sm font-medium text-thc-navy">Download</p>
                <p class="mt-1 text-sm text-thc-text/90">PDF hosted on the College website.</p>
                <a href="{{ $download->external_url }}" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex rounded-full bg-thc-royal px-5 py-2.5 text-sm font-semibold text-white hover:bg-thc-navy">Open PDF</a>
            </div>
        @else
            <p class="mt-8 rounded-xl border border-thc-maroon/25 bg-thc-maroon/8 px-4 py-3 text-sm text-thc-maroon">This resource is being prepared. Please contact the office for a copy.</p>
        @endif

        @if($download->relatedDownloads->isNotEmpty())
            <section class="mt-14">
                <h2 class="font-serif text-2xl font-semibold text-thc-navy">Related downloads</h2>
                <ul class="mt-4 space-y-2">
                    @foreach($download->relatedDownloads as $rel)
                        <li><a href="{{ route('downloads.show', $rel->slug) }}" class="text-sm font-medium text-thc-royal hover:underline">{{ $rel->title }}</a></li>
                    @endforeach
                </ul>
            </section>
        @endif
    </article>
</x-layouts.public>
