<x-layouts.admin header="SOC — FAQs" title="FAQs | SOC CMS | {{ config('tenwek.name') }}">
    <div class="admin-page-wide">
        <div class="admin-toolbar">
            <div>
                <p class="text-sm leading-relaxed text-thc-text/85">
                    Manage accordions on
                    <a href="{{ url('/soc/faqs') }}" class="admin-link" target="_blank" rel="noopener">/soc/faqs</a>.
                    Use <a href="{{ route('admin.soc.faqs.intro.edit') }}" class="admin-link font-medium">Page intro</a> for the kicker and opening paragraph.
                </p>
            </div>
            <div class="admin-toolbar-actions">
                <a href="{{ route('admin.soc.faqs.create') }}" class="admin-btn-primary">Add FAQ</a>
            </div>
        </div>

        @if ($canImportLegacy)
            <div class="admin-card mb-6 border-thc-royal/20 bg-thc-royal/[0.06] p-4 sm:p-5">
                <p class="text-sm font-semibold text-thc-navy">Import existing FAQs</p>
                <p class="admin-hint mt-1">
                    No database FAQs yet. You can copy items from <span class="admin-code">config/tenwek.php</span> or from a previously saved <span class="admin-code">faqs</span> JSON section into editable records.
                </p>
                <form method="post" action="{{ route('admin.soc.faqs.import-legacy') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="admin-btn-secondary">Import from config / JSON</button>
                </form>
            </div>
        @endif

        <div class="admin-table-wrap">
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th scope="col">Order</th>
                            <th scope="col">Question</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td class="whitespace-nowrap tabular-nums text-thc-text/80">{{ $item->sort_order }}</td>
                                <td class="font-medium text-thc-navy">{{ \Illuminate\Support\Str::limit($item->question, 120) }}</td>
                                <td>
                                    <div class="admin-table-actions">
                                        <a href="{{ route('admin.soc.faqs.edit', $item) }}" class="admin-link">Edit</a>
                                        <form
                                            method="post"
                                            action="{{ route('admin.soc.faqs.destroy', $item) }}"
                                            class="inline"
                                            onsubmit="return confirm('Delete this FAQ?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-btn-ghost text-red-700 hover:bg-red-50">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="admin-table-empty">No FAQs yet. Add one or import from config.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <p class="mt-6 text-xs text-thc-text/60">
            Raw <span class="admin-code">faqs</span> JSON (kicker/intro merge) —
            <a href="{{ route('admin.soc.json.edit', 'faqs') }}" class="admin-link">Advanced editor</a>
        </p>
    </div>
</x-layouts.admin>
