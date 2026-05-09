@props([
    'person',
    /** maroon | royal: role text and highlight accents */
    'accent' => 'maroon',
])

@php
    $highlight = ! empty($person['highlight']);
    $image = $person['image'] ?? null;
    $parts = array_values(array_filter(preg_split('/\s+/', (string) ($person['name'] ?? '')) ?: []));
    $initials = '';
    if (count($parts) >= 2) {
        $initials = mb_strtoupper(mb_substr($parts[0], 0, 1).mb_substr($parts[count($parts) - 1], 0, 1));
    } elseif (count($parts) === 1) {
        $initials = mb_strtoupper(mb_substr($parts[0], 0, 2));
    } else {
        $initials = '?';
    }
    $roleClass = $accent === 'royal'
        ? 'text-thc-royal'
        : 'text-thc-soc-teal';
@endphp

<article
    {{ $attributes->class([
        'group flex h-full flex-col overflow-hidden rounded-2xl border bg-white shadow-sm transition hover:shadow-md',
        'border-thc-maroon/35 bg-gradient-to-br from-thc-maroon/[0.06] to-white ring-1 ring-thc-maroon/15' => $highlight && $accent === 'maroon',
        'border-thc-royal/30 bg-gradient-to-br from-thc-royal/[0.07] to-white ring-1 ring-thc-royal/15' => $highlight && $accent === 'royal',
        'border-thc-navy/10' => ! $highlight,
    ]) }}
>
    <div class="relative aspect-[5/6] w-full overflow-hidden bg-gradient-to-b from-thc-navy/[0.07] to-thc-navy/[0.02] sm:aspect-[4/5]">
        @if(filled($image))
            <img
                src="{{ \App\Support\Soc\SocLandingRepository::publicMediaUrl($image) ?? asset($image) }}"
                alt="{{ $person['name'] ?? '' }}"
                class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                loading="lazy"
                decoding="async"
                width="400"
                height="500"
            >
        @else
            <div class="flex h-full w-full flex-col items-center justify-center gap-3 p-6 text-center" aria-hidden="true">
                <span class="flex h-[4.5rem] w-[4.5rem] items-center justify-center rounded-full border-2 border-dashed border-thc-navy/20 bg-white font-serif text-2xl font-semibold tracking-tight text-thc-navy shadow-sm sm:h-20 sm:w-20 sm:text-3xl">
                    {{ $initials }}
                </span>
                <span class="max-w-[10rem] text-[10px] font-semibold uppercase leading-tight tracking-[0.12em] text-thc-text/45">Photo to be added</span>
            </div>
        @endif
    </div>
    <div class="flex flex-1 flex-col p-5 sm:p-6">
        <h3 class="font-serif text-lg font-semibold leading-snug text-thc-navy sm:text-xl">
            {{ $person['name'] ?? '' }}
        </h3>
        <p class="mt-3 text-[11px] font-bold uppercase leading-snug tracking-[0.14em] {{ $roleClass }} sm:text-xs">
            {{ $person['role'] ?? '' }}
        </p>
    </div>
</article>
