<x-layouts.admin header="Dashboard">
    <div class="admin-page-wide space-y-10">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="admin-stat-card">
                <p class="text-xs font-semibold uppercase tracking-wide text-thc-text/65">{{ __('Pages') }}</p>
                <p class="mt-2 font-serif text-3xl font-semibold text-thc-navy">{{ number_format($stats['pages']) }}</p>
                @if($isSuper && $stats['pages_college'] !== null)
                    <p class="mt-2 text-xs text-thc-text/60">{{ __('College (no school): :n', ['n' => number_format($stats['pages_college'])]) }}</p>
                @endif
            </div>
            <div class="admin-stat-card">
                <p class="text-xs font-semibold uppercase tracking-wide text-thc-text/65">{{ __('Downloads') }}</p>
                <p class="mt-2 font-serif text-3xl font-semibold text-thc-navy">{{ number_format($stats['downloads']) }}</p>
                <p class="mt-2 text-xs text-thc-text/60">
                    {{ __('Active: :active', ['active' => number_format($stats['downloads_active'])]) }}
                    @if($stats['downloads'] > $stats['downloads_active'])
                        <span class="text-thc-maroon/80"> · {{ __(':n inactive', ['n' => number_format($stats['downloads'] - $stats['downloads_active'])]) }}</span>
                    @endif
                </p>
            </div>
            <div class="admin-stat-card">
                <p class="text-xs font-semibold uppercase tracking-wide text-thc-text/65">{{ __('News') }}</p>
                <p class="mt-2 font-serif text-3xl font-semibold text-thc-navy">{{ number_format($stats['news_published']) }}</p>
                <p class="mt-2 text-xs text-thc-text/60">{{ __('Published · :total total rows', ['total' => number_format($stats['news'])]) }}</p>
            </div>
            <div class="admin-stat-card">
                <p class="text-xs font-semibold uppercase tracking-wide text-thc-text/65">{{ __('Form submissions') }}</p>
                <p class="mt-2 font-serif text-3xl font-semibold text-thc-navy">{{ number_format($stats['submissions_30d']) }}</p>
                <p class="mt-2 text-xs text-thc-text/60">
                    {{ __('Last 30 days · :t all time', ['t' => number_format($stats['submissions_total'])]) }}
                </p>
            </div>
            <div class="admin-stat-card">
                <p class="text-xs font-semibold uppercase tracking-wide text-thc-text/65">{{ __('Submissions (7 days)') }}</p>
                <p class="mt-2 font-serif text-3xl font-semibold text-thc-navy">{{ number_format($stats['submissions_7d']) }}</p>
                @if($stats['submissions_unprocessed'] > 0)
                    <p class="mt-2 text-xs font-medium text-thc-maroon">{{ __(':n not marked processed', ['n' => number_format($stats['submissions_unprocessed'])]) }}</p>
                @else
                    <p class="mt-2 text-xs text-thc-text/60">{{ __('None waiting on processed flag') }}</p>
                @endif
            </div>
            <div class="admin-stat-card">
                <p class="text-xs font-semibold uppercase tracking-wide text-thc-text/65">{{ __('Media library') }}</p>
                <p class="mt-2 font-serif text-3xl font-semibold text-thc-navy">{{ number_format($stats['media']) }}</p>
                <p class="mt-2 text-xs text-thc-text/60">{{ __('Uploaded assets (scoped)') }}</p>
            </div>
            <div class="admin-stat-card">
                <p class="text-xs font-semibold uppercase tracking-wide text-thc-text/65">{{ __('Staff accounts') }}</p>
                <p class="mt-2 font-serif text-3xl font-semibold text-thc-navy">{{ number_format($stats['users']) }}</p>
                <p class="mt-2 text-xs text-thc-text/60">{{ __('Active: :n', ['n' => number_format($stats['users_active'])]) }}</p>
            </div>
            <div class="admin-stat-card">
                <p class="text-xs font-semibold uppercase tracking-wide text-thc-text/65">{{ __('Admin actions') }}</p>
                <p class="mt-2 font-serif text-3xl font-semibold text-thc-navy">{{ number_format($stats['audit_7d']) }}</p>
                <p class="mt-2 text-xs text-thc-text/60">{{ __('Audit events (7d) · :m (30d)', ['m' => number_format($stats['audit_30d'])]) }}</p>
            </div>
        </div>

        @if($isSuper && ($stats['schools'] !== null || $stats['download_categories'] !== null))
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @if($stats['schools'] !== null)
                    <div class="admin-stat-card border-thc-royal/15 bg-gradient-to-br from-white to-thc-royal/[0.04]">
                        <p class="text-xs font-semibold uppercase tracking-wide text-thc-royal">{{ __('Active schools') }}</p>
                        <p class="mt-2 font-serif text-2xl font-semibold text-thc-navy">{{ number_format($stats['schools']) }}</p>
                    </div>
                @endif
                @if($stats['download_categories'] !== null)
                    <div class="admin-stat-card border-thc-teal/20 bg-gradient-to-br from-white to-thc-teal/[0.05]">
                        <p class="text-xs font-semibold uppercase tracking-wide text-thc-teal">{{ __('Download categories') }}</p>
                        <p class="mt-2 font-serif text-2xl font-semibold text-thc-navy">{{ number_format($stats['download_categories']) }}</p>
                    </div>
                @endif
            </div>
        @endif

        <div
            id="admin-dashboard-charts"
            class="space-y-6"
            data-charts="@json($chartData)"
        >
            <div class="admin-card p-6">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-thc-navy">{{ __('Form submissions over time') }}</h2>
                        <p class="mt-1 text-xs text-thc-text/65">{{ __('Daily count for the last 14 days (your scope).') }}</p>
                    </div>
                </div>
                <div class="relative mt-6 h-64 w-full min-w-0 sm:h-72">
                    <canvas data-chart="trend" aria-label="{{ __('Submissions trend chart') }}"></canvas>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="admin-card p-6">
                    <h2 class="text-sm font-semibold text-thc-navy">{{ __('Content mix') }}</h2>
                    <p class="mt-1 text-xs text-thc-text/65">{{ __('Published news, pages, downloads, and media files.') }}</p>
                    <div class="relative mx-auto mt-4 h-64 w-full max-w-sm min-w-0">
                        <canvas data-chart="mix" aria-label="{{ __('Content mix chart') }}"></canvas>
                    </div>
                    @php
                        $mixSum = array_sum($chartData['contentMix']);
                    @endphp
                    @if($mixSum === 0)
                        <p class="mt-4 text-center text-xs text-thc-text/55">{{ __('No items in these categories yet.') }}</p>
                    @endif
                </div>
                <div class="admin-card p-6">
                    <h2 class="text-sm font-semibold text-thc-navy">{{ __('Submissions by form') }}</h2>
                    <p class="mt-1 text-xs text-thc-text/65">{{ __('Top form keys by volume (all time, your scope).') }}</p>
                    <div class="relative mt-4 h-72 w-full min-w-0">
                        <canvas data-chart="forms" aria-label="{{ __('Submissions by form chart') }}"></canvas>
                    </div>
                    @if(count($chartData['forms']) === 0)
                        <p class="mt-4 text-center text-xs text-thc-text/55">{{ __('No form submissions recorded yet.') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="admin-card overflow-hidden">
                <div class="flex items-center justify-between border-b border-thc-navy/8 px-6 py-4">
                    <h2 class="text-sm font-semibold text-thc-navy">{{ __('Recent admin activity') }}</h2>
                    @if($isSuper)
                        <a href="{{ route('admin.activity.index') }}" class="admin-btn-ghost admin-btn-sm">{{ __('View all') }}</a>
                    @endif
                </div>
                <div class="max-h-[22rem] overflow-y-auto">
                    @forelse($recentActivity as $log)
                        <div class="flex gap-3 border-b border-thc-navy/[0.06] px-6 py-3 last:border-b-0">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-thc-navy">
                                    <span class="font-mono text-xs text-thc-royal">{{ $log->action }}</span>
                                </p>
                                <p class="mt-0.5 text-xs text-thc-text/70">
                                    {{ $log->user?->name ?? __('System') }}
                                    @if($log->auditable_type)
                                        <span class="text-thc-text/45"> · </span>
                                        <span>{{ \Illuminate\Support\Str::limit(class_basename($log->auditable_type), 32) }}</span>
                                    @endif
                                </p>
                            </div>
                            <time class="shrink-0 text-xs text-thc-text/50" datetime="{{ $log->created_at->toIso8601String() }}">
                                {{ $log->created_at->diffForHumans(short: true) }}
                            </time>
                        </div>
                    @empty
                        <p class="px-6 py-10 text-center text-sm text-thc-text/65">{{ __('No audit entries in your scope yet.') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="admin-card overflow-hidden">
                <div class="flex items-center justify-between border-b border-thc-navy/8 px-6 py-4">
                    <h2 class="text-sm font-semibold text-thc-navy">{{ __('Latest published news') }}</h2>
                    @if(auth()->user()->hasRole('soc_admin'))
                        <a href="{{ route('admin.soc.news.index') }}" class="admin-btn-ghost admin-btn-sm">{{ __('Manage') }}</a>
                    @elseif(auth()->user()->hasRole('cohs_admin'))
                        <a href="{{ route('admin.cohs.news.index') }}" class="admin-btn-ghost admin-btn-sm">{{ __('Manage') }}</a>
                    @endif
                </div>
                <ul class="divide-y divide-thc-navy/[0.06]">
                    @forelse($recentNews as $post)
                        <li class="px-6 py-3">
                            <a href="{{ route('news.show', $post) }}" class="text-sm font-semibold text-thc-navy hover:text-thc-royal" target="_blank" rel="noopener noreferrer">
                                {{ \Illuminate\Support\Str::limit($post->title, 72) }}
                            </a>
                            <p class="mt-1 text-xs text-thc-text/60">
                                {{ $post->published_at?->format('M j, Y') }}
                                @if($post->school)
                                    <span class="text-thc-text/45"> · </span>
                                    {{ $post->school->name }}
                                @endif
                            </p>
                        </li>
                    @empty
                        <li class="px-6 py-10 text-center text-sm text-thc-text/65">{{ __('No published news in your scope.') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="admin-card p-6">
            <h2 class="text-sm font-semibold text-thc-navy">{{ __('Quick links') }}</h2>
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('home') }}" class="admin-btn-secondary admin-btn-sm" target="_blank" rel="noopener noreferrer">{{ __('Public site') }}</a>
                <a href="{{ route('admin.downloads.index') }}" class="admin-btn-secondary admin-btn-sm">{{ __('Downloads') }}</a>
                @if($isSuper)
                    <a href="{{ route('admin.users.index') }}" class="admin-btn-secondary admin-btn-sm">{{ __('Users') }}</a>
                    <a href="{{ route('admin.activity.index') }}" class="admin-btn-secondary admin-btn-sm">{{ __('Activity log') }}</a>
                    <a href="{{ route('admin.settings.edit') }}" class="admin-btn-secondary admin-btn-sm">{{ __('Site settings') }}</a>
                @endif
                @if(auth()->user()->hasRole('soc_admin'))
                    <a href="{{ route('admin.soc.dashboard') }}" class="admin-btn-secondary admin-btn-sm">{{ __('SOC CMS') }}</a>
                @endif
                @if(auth()->user()->hasRole('cohs_admin'))
                    <a href="{{ route('admin.cohs.dashboard') }}" class="admin-btn-secondary admin-btn-sm">{{ __('COHS CMS') }}</a>
                @endif
            </div>
            <p class="mt-4 text-xs leading-relaxed text-thc-text/70">
                {{ __('Figures and charts respect your school scope unless you are a super administrator. Audit activity for school admins only lists actions by users in your school.') }}
            </p>
        </div>
    </div>
</x-layouts.admin>
