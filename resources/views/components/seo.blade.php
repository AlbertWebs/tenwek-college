@props(['seo'])

<title>{{ $seo['title'] }}</title>
<meta name="description" content="{{ $seo['description'] }}">
@if(!empty($seo['keywords']))
    <meta name="keywords" content="{{ is_array($seo['keywords']) ? implode(', ', $seo['keywords']) : $seo['keywords'] }}">
@endif
<meta name="robots" content="{{ $seo['robots'] }}">
<link rel="canonical" href="{{ $seo['canonical'] }}">

<meta property="og:type" content="{{ $seo['og']['type'] }}">
<meta property="og:title" content="{{ $seo['og']['title'] }}">
<meta property="og:description" content="{{ $seo['og']['description'] }}">
<meta property="og:image" content="{{ $seo['og']['image'] }}">
<meta property="og:url" content="{{ $seo['og']['url'] }}">
<meta property="og:site_name" content="{{ $seo['og']['site_name'] }}">
<meta property="og:locale" content="{{ $seo['og']['locale'] }}">

<meta name="twitter:card" content="{{ $seo['twitter']['card'] }}">
<meta name="twitter:title" content="{{ $seo['twitter']['title'] }}">
<meta name="twitter:description" content="{{ $seo['twitter']['description'] }}">
<meta name="twitter:image" content="{{ $seo['twitter']['image'] }}">

@if(config('tenwek.geo.latitude') && config('tenwek.geo.longitude'))
    <meta name="geo.region" content="{{ config('tenwek.address.country') }}">
    <meta name="geo.placename" content="{{ config('tenwek.address.locality') }}">
    <meta name="geo.position" content="{{ config('tenwek.geo.latitude') }};{{ config('tenwek.geo.longitude') }}">
    <meta name="ICBM" content="{{ config('tenwek.geo.latitude') }}, {{ config('tenwek.geo.longitude') }}">
@endif

@foreach($seo['json_ld'] as $block)
    <script type="application/ld+json">{!! json_encode($block, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!}</script>
@endforeach
