<footer class="border-t border-thc-navy/12 bg-thc-surface px-4 py-10 sm:px-6">
    <div class="mx-auto flex max-w-4xl flex-col items-center gap-6">
        @if(count(config('tenwek.social', [])) > 0)
            <ul class="flex flex-wrap items-center justify-center gap-4">
                @foreach(config('tenwek.social') as $platform => $url)
                    <li>
                        <a
                            href="{{ $url }}"
                            class="flex h-11 w-11 items-center justify-center rounded-full border border-thc-text/20 bg-thc-surface text-thc-text shadow-sm transition hover:border-thc-royal hover:bg-thc-royal/5 hover:text-thc-navy"
                            rel="noopener noreferrer"
                            aria-label="{{ config('tenwek.footer.social_labels.'.$platform, ucfirst($platform)) }}"
                        >
                            @if($platform === 'facebook')
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            @elseif($platform === 'x' || str_contains($platform, 'twitter'))
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            @else
                                <span class="text-xs font-bold">{{ strtoupper(substr(config('tenwek.footer.social_labels.'.$platform, $platform), 0, 1)) }}</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
        <p class="text-center text-sm text-thc-text/90">
            © {{ now()->year }} {{ config('tenwek.institution_legal') }}.
        </p>
    </div>
</footer>
