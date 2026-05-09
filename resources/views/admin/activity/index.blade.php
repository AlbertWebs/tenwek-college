<x-layouts.admin
    header="Activity log"
    :breadcrumbs="[['label' => 'Admin', 'href' => route('admin.dashboard')], ['label' => 'Activity log']]"
>
    <div class="admin-toolbar">
        <p class="admin-toolbar-note max-w-2xl">
            Admin panel requests are recorded here (route, HTTP method, status, and redacted form input). Sensitive fields such as passwords are never stored.
        </p>
    </div>

    @if ($logs->isEmpty())
        <p class="admin-table-empty mt-6 rounded-[var(--admin-radius-card)] border border-dashed border-thc-navy/15 bg-white py-12 text-center text-sm text-thc-text/75">
            No activity recorded yet.
        </p>
    @else
    <div class="admin-table-wrap">
        <div class="admin-table-scroll">
            <table class="admin-table admin-table--zebra">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">When</th>
                        <th>User</th>
                        <th class="hidden md:table-cell">Route</th>
                        <th class="whitespace-nowrap">HTTP</th>
                        <th class="hidden lg:table-cell">Status</th>
                        <th class="hidden xl:table-cell">Subject</th>
                        <th class="hidden sm:table-cell">IP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        @php
                            $meta = $log->new_values ?? [];
                            $route = $meta['route'] ?? '';
                            $method = $meta['method'] ?? '';
                            $status = $meta['status'] ?? null;
                        @endphp
                        <tr>
                            <td class="whitespace-nowrap text-sm text-thc-text/90">
                                <time datetime="{{ $log->created_at->toIso8601String() }}">{{ $log->created_at->format('Y-m-d H:i:s') }}</time>
                            </td>
                            <td class="text-sm">
                                <span class="font-medium text-thc-navy">{{ $log->user?->name ?? '—' }}</span>
                                <span class="mt-0.5 block text-xs text-thc-text/65">{{ $log->user?->email }}</span>
                            </td>
                            <td class="hidden max-w-xs truncate text-sm text-thc-text/90 md:table-cell" title="{{ $route }}">
                                {{ $log->action }}
                            </td>
                            <td class="whitespace-nowrap text-sm text-thc-text/90">{{ $method }}</td>
                            <td class="hidden lg:table-cell">
                                @if (is_numeric($status))
                                    @if ((int) $status >= 400)
                                        <x-admin.ui.badge variant="danger">{{ $status }}</x-admin.ui.badge>
                                    @elseif ((int) $status >= 300)
                                        <x-admin.ui.badge variant="muted">{{ $status }}</x-admin.ui.badge>
                                    @else
                                        <x-admin.ui.badge variant="success">{{ $status }}</x-admin.ui.badge>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td class="hidden max-w-[14rem] truncate text-xs text-thc-text/80 xl:table-cell">
                                @if ($log->auditable_type)
                                    {{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="hidden font-mono text-xs text-thc-text/70 sm:table-cell">{{ $log->ip_address ?? '—' }}</td>
                        </tr>
                        <tr class="bg-thc-navy/[0.02]">
                            <td colspan="7" class="border-t border-thc-navy/10 px-4 py-3 text-xs text-thc-text/75">
                                <span class="font-medium text-thc-navy/80">Path:</span>
                                {{ $meta['path'] ?? '—' }}
                                @if (! empty($meta['parameters']))
                                    <span class="mx-2 text-thc-text/40">·</span>
                                    <span class="font-medium text-thc-navy/80">Params:</span>
                                    <code class="rounded bg-thc-navy/5 px-1 py-0.5">{{ json_encode($meta['parameters'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</code>
                                @endif
                                @php $input = $meta['input'] ?? []; @endphp
                                @if (! empty($input))
                                    <details class="mt-2">
                                        <summary class="cursor-pointer font-medium text-thc-royal hover:underline">Request data (redacted)</summary>
                                        <pre class="mt-2 max-h-48 overflow-auto rounded-lg bg-thc-navy/[0.06] p-3 text-[11px] leading-relaxed text-thc-text/90">{{ json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </details>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="mt-6">
        {{ $logs->links() }}
    </div>
</x-layouts.admin>
