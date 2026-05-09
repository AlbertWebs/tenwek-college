@props([
    'school',
    'current' => null,
    /** Optional middle segments: [['label' => '…', 'href' => '…'], …] */
    'trail' => [],
    /** Use on dark heroes: light link colours. */
    'variant' => 'default',
])

@php
    $isHero = $variant === 'hero';
    $linkClass = $isHero
        ? 'text-white/80 transition hover:text-white hover:underline'
        : 'text-thc-text/65 transition hover:text-thc-royal';
    $currentClass = $isHero ? 'font-medium text-white' : 'font-medium text-thc-navy';
    $sepClass = $isHero ? 'text-white/45' : 'text-thc-text/45';
    $navClass = $isHero ? 'text-sm text-white/70' : 'text-sm text-thc-text/65';
@endphp

<nav {{ $attributes->merge(['class' => $navClass]) }} aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-2">
        <li><a href="{{ route('home') }}" class="{{ $linkClass }}">Home</a></li>
        <li class="{{ $sepClass }}" aria-hidden="true">/</li>
        @if(filled($current))
            <li><a href="{{ route('schools.show', $school) }}" class="{{ $linkClass }}">{{ $school->name }}</a></li>
            <li class="{{ $sepClass }}" aria-hidden="true">/</li>
            @foreach($trail as $crumb)
                <li><a href="{{ $crumb['href'] }}" class="{{ $linkClass }}">{{ $crumb['label'] }}</a></li>
                <li class="{{ $sepClass }}" aria-hidden="true">/</li>
            @endforeach
            <li class="{{ $currentClass }}">{{ $current }}</li>
        @else
            <li class="{{ $currentClass }}">{{ $school->name }}</li>
        @endif
    </ol>
</nav>
