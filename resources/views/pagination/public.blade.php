@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col items-center gap-4 sm:flex-row sm:justify-between">
        <p class="order-2 text-sm text-thc-text/65 sm:order-1">
            @if ($paginator->firstItem())
                Showing <span class="font-semibold text-thc-navy">{{ $paginator->firstItem() }}</span>–<span class="font-semibold text-thc-navy">{{ $paginator->lastItem() }}</span>
                of <span class="font-semibold text-thc-navy">{{ $paginator->total() }}</span>
            @else
                {{ $paginator->count() }} {{ \Illuminate\Support\Str::plural('item', $paginator->count()) }}
            @endif
        </p>

        <div class="order-1 flex items-center gap-1 sm:order-2">
            @if ($paginator->onFirstPage())
                <span class="inline-flex min-h-[2.5rem] min-w-[2.5rem] items-center justify-center rounded-l-xl border border-thc-navy/12 bg-thc-navy/[0.04] px-3 text-sm font-medium text-thc-text/35">‹</span>
            @else
                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    rel="prev"
                    class="inline-flex min-h-[2.5rem] min-w-[2.5rem] items-center justify-center rounded-l-xl border border-thc-navy/15 bg-white px-3 text-sm font-semibold text-thc-navy shadow-sm transition hover:border-thc-royal/35 hover:bg-thc-royal/5"
                    aria-label="{{ __('pagination.previous') }}"
                >‹</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex min-h-[2.5rem] min-w-[2.5rem] items-center justify-center border border-thc-navy/12 bg-thc-navy/[0.03] px-2 text-sm text-thc-text/45">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex min-h-[2.5rem] min-w-[2.5rem] items-center justify-center border border-thc-royal/40 bg-thc-royal/10 px-3 text-sm font-bold text-thc-navy">{{ $page }}</span>
                        @else
                            <a
                                href="{{ $url }}"
                                class="inline-flex min-h-[2.5rem] min-w-[2.5rem] items-center justify-center border border-thc-navy/12 bg-white px-3 text-sm font-semibold text-thc-navy shadow-sm transition hover:border-thc-royal/35 hover:bg-thc-royal/5"
                            >{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    rel="next"
                    class="inline-flex min-h-[2.5rem] min-w-[2.5rem] items-center justify-center rounded-r-xl border border-thc-navy/15 bg-white px-3 text-sm font-semibold text-thc-navy shadow-sm transition hover:border-thc-royal/35 hover:bg-thc-royal/5"
                    aria-label="{{ __('pagination.next') }}"
                >›</a>
            @else
                <span class="inline-flex min-h-[2.5rem] min-w-[2.5rem] items-center justify-center rounded-r-xl border border-thc-navy/12 bg-thc-navy/[0.04] px-3 text-sm font-medium text-thc-text/35">›</span>
            @endif
        </div>
    </nav>
@endif
