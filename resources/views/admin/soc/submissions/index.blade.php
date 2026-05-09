<x-layouts.admin header="SOC — Form submissions">
    <p class="mb-6 text-sm leading-relaxed text-thc-text/80">Contact form and online registration wizard submissions for the School of Chaplaincy.</p>
    <div class="admin-toolbar">
        <a href="{{ route('admin.soc.dashboard') }}" class="admin-btn-ghost admin-btn-sm">← SOC CMS</a>
    </div>
    <div class="admin-table-wrap">
        <div class="admin-table-scroll">
            <table class="admin-table admin-table--zebra">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Form</th>
                        <th>Processed</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($submissions as $s)
                        <tr>
                            <td class="text-thc-text/85">{{ $s->created_at->format('M j, Y g:i a') }}</td>
                            <td class="font-mono text-xs text-thc-navy">{{ $s->form_key }}</td>
                            <td>
                                @if ($s->processed)
                                    <x-admin.ui.badge variant="success">Yes</x-admin.ui.badge>
                                @else
                                    <x-admin.ui.badge variant="warning">No</x-admin.ui.badge>
                                @endif
                            </td>
                            <td>
                                <div class="admin-table-actions">
                                    <a href="{{ route('admin.soc.submissions.show', $s) }}" class="admin-link">View</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="admin-table-empty">No submissions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $submissions->links() }}
</x-layouts.admin>
