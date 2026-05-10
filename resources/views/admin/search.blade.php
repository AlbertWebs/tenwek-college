<x-layouts.admin :header="$header" :title="$title" :breadcrumbs="$breadcrumbs">
    <div class="admin-page-wide space-y-8">
        <form method="get" action="{{ route('admin.search') }}" class="admin-card flex flex-col gap-3 p-4 sm:flex-row sm:items-center" role="search">
            <label for="admin-search-q" class="sr-only">{{ __('Search admin') }}</label>
            <div class="flex min-w-0 flex-1 items-center gap-2 rounded-xl border border-thc-navy/12 bg-white px-3 py-2 shadow-inner shadow-thc-navy/[0.03]">
                <x-admin.nav-icon name="magnifying-glass" class="h-4 w-4 shrink-0 text-thc-text/45" />
                <input
                    id="admin-search-q"
                    type="search"
                    name="q"
                    value="{{ $q }}"
                    placeholder="{{ __('Search downloads, news, pages…') }}"
                    class="min-w-0 flex-1 border-0 bg-transparent text-sm text-thc-navy placeholder:text-thc-text/40 focus:ring-0"
                    autocomplete="off"
                />
            </div>
            <button type="submit" class="admin-btn-primary shrink-0 justify-center sm:w-auto">{{ __('Search') }}</button>
        </form>

        @if($q === '')
            <div class="admin-card p-6">
                <p class="text-sm text-thc-text/80">{{ __('Enter a term to search content you can manage in this admin (scoped to your school unless you are a super administrator).') }}</p>
                @if(auth()->user()->isSuperAdmin())
                    <p class="mt-2 text-sm text-thc-text/70">{{ __('Super administrators can also find staff accounts to edit.') }}</p>
                @endif
            </div>
        @else
            @php
                $total = $downloadRows->count() + $userRows->count() + $newsRows->count() + $pageRows->count();
            @endphp

            @if($total === 0)
                <div class="admin-card p-8 text-center">
                    <p class="text-sm font-medium text-thc-navy">{{ __('No admin results for ":q".', ['q' => $q]) }}</p>
                    <p class="mt-2 text-xs text-thc-text/65">{{ __('Try another keyword, or check that the item exists in your scope.') }}</p>
                </div>
            @else
                @if($downloadRows->isNotEmpty())
                    <section class="admin-card overflow-hidden">
                        <h2 class="border-b border-thc-navy/8 bg-thc-navy/[0.02] px-5 py-3 text-xs font-bold uppercase tracking-wider text-thc-navy">{{ __('Downloads') }}</h2>
                        <ul class="divide-y divide-thc-navy/[0.06]">
                            @foreach($downloadRows as $row)
                                @php
                                    $d = $row['download'];
                                @endphp
                                <li class="flex flex-wrap items-center justify-between gap-3 px-5 py-3">
                                    <div class="min-w-0">
                                        <a href="{{ $row['url'] }}" class="font-medium text-thc-royal hover:underline">{{ $d->title }}</a>
                                        <p class="mt-0.5 text-xs text-thc-text/60">
                                            @if($d->school)<span>{{ $d->school->name }}</span>@endif
                                            @if($d->category)<span class="text-thc-text/40"> · </span><span>{{ $d->category->name }}</span>@endif
                                        </p>
                                    </div>
                                    <span class="text-[11px] uppercase tracking-wide text-thc-text/45">{{ $d->is_active ? __('Active') : __('Inactive') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if($userRows->isNotEmpty())
                    <section class="admin-card overflow-hidden">
                        <h2 class="border-b border-thc-navy/8 bg-thc-navy/[0.02] px-5 py-3 text-xs font-bold uppercase tracking-wider text-thc-navy">{{ __('Users') }}</h2>
                        <ul class="divide-y divide-thc-navy/[0.06]">
                            @foreach($userRows as $row)
                                @php
                                    $u = $row['user'];
                                @endphp
                                <li class="flex flex-wrap items-center justify-between gap-3 px-5 py-3">
                                    <div class="min-w-0">
                                        <a href="{{ $row['url'] }}" class="font-medium text-thc-royal hover:underline">{{ $u->name }}</a>
                                        <p class="mt-0.5 truncate text-xs text-thc-text/60">{{ $u->email }}</p>
                                    </div>
                                    @if($u->school)
                                        <span class="text-xs text-thc-text/55">{{ $u->school->name }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if($newsRows->isNotEmpty())
                    <section class="admin-card overflow-hidden">
                        <h2 class="border-b border-thc-navy/8 bg-thc-navy/[0.02] px-5 py-3 text-xs font-bold uppercase tracking-wider text-thc-navy">{{ __('News') }}</h2>
                        <ul class="divide-y divide-thc-navy/[0.06]">
                            @foreach($newsRows as $row)
                                @php
                                    $post = $row['post'];
                                    $newsIsPublic = $post->published_at !== null && $post->published_at->lte(now());
                                @endphp
                                <li class="flex flex-wrap items-center justify-between gap-3 px-5 py-3">
                                    <div class="min-w-0">
                                        @if($row['admin_url'])
                                            <a href="{{ $row['admin_url'] }}" class="font-medium text-thc-royal hover:underline">{{ $post->title }}</a>
                                        @else
                                            <span class="font-medium text-thc-navy">{{ $post->title }}</span>
                                        @endif
                                        <p class="mt-0.5 text-xs text-thc-text/60">
                                            {{ $post->published_at?->format('Y-m-d') ?? __('Unpublished') }}
                                            @if($post->school)<span class="text-thc-text/40"> · </span>{{ $post->school->name }}@endif
                                        </p>
                                    </div>
                                    <a href="{{ $row['public_url'] }}" class="admin-btn-ghost admin-btn-sm shrink-0" target="_blank" rel="noopener noreferrer">{{ __('View') }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if($pageRows->isNotEmpty())
                    <section class="admin-card overflow-hidden">
                        <h2 class="border-b border-thc-navy/8 bg-thc-navy/[0.02] px-5 py-3 text-xs font-bold uppercase tracking-wider text-thc-navy">{{ __('Pages') }}</h2>
                        <ul class="divide-y divide-thc-navy/[0.06]">
                            @foreach($pageRows as $row)
                                @php
                                    $page = $row['page'];
                                @endphp
                                <li class="flex flex-wrap items-center justify-between gap-3 px-5 py-3">
                                    <div class="min-w-0">
                                        @if($row['admin_url'])
                                            <a href="{{ $row['admin_url'] }}" class="font-medium text-thc-royal hover:underline">{{ $page->title }}</a>
                                        @else
                                            <span class="font-medium text-thc-navy">{{ $page->title }}</span>
                                        @endif
                                        <p class="mt-0.5 text-xs text-thc-text/60">
                                            /{{ $page->slug }}
                                            @if($page->school)<span class="text-thc-text/40"> · </span>{{ $page->school->name }}@else<span class="text-thc-text/40"> · </span>{{ __('College-wide') }}@endif
                                        </p>
                                    </div>
                                    <a href="{{ $row['public_url'] }}" class="admin-btn-ghost admin-btn-sm shrink-0" target="_blank" rel="noopener noreferrer">{{ __('View') }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            @endif
        @endif
    </div>
</x-layouts.admin>
