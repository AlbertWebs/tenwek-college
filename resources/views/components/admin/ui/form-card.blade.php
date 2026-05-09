@props(['title' => null, 'description' => null])

<div {{ $attributes->class(['admin-card p-6 sm:p-8']) }}>
    @if ($title || $description)
        <header class="mb-6 border-b border-thc-navy/8 pb-4">
            @if ($title)
                <h2 class="text-base font-semibold text-thc-navy">{{ $title }}</h2>
            @endif
            @if ($description)
                <p class="mt-1 text-sm text-thc-text/75">{{ $description }}</p>
            @endif
        </header>
    @endif

    {{ $slot }}
</div>
