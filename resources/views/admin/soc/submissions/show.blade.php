<x-layouts.admin header="Submission #{{ $submission->id }}">
    @if (session('status'))
        <div class="admin-alert-success mb-4" role="status">{{ session('status') }}</div>
    @endif
    <div class="admin-page-wide max-w-3xl space-y-6">
        <div class="admin-card p-6">
            <dl class="grid gap-4 text-sm sm:grid-cols-2">
                <div>
                    <dt class="font-semibold text-thc-navy">Form</dt>
                    <dd class="mt-1 font-mono text-xs">{{ $submission->form_key }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-thc-navy">Received</dt>
                    <dd class="mt-1">{{ $submission->created_at->format('M j, Y g:i a') }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-thc-navy">IP</dt>
                    <dd class="mt-1 font-mono text-xs">{{ $submission->ip_address ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        <form method="post" action="{{ route('admin.soc.submissions.update', $submission) }}" class="admin-card flex flex-wrap items-center gap-4 p-4 sm:p-5">
            @csrf
            @method('PATCH')
            <span class="text-sm font-semibold text-thc-navy">Status</span>
            <input type="hidden" name="processed" value="0">
            <label class="admin-check-row">
                <input type="checkbox" name="processed" value="1" @checked(old('processed', $submission->processed)) class="admin-checkbox">
                <span>Mark as processed</span>
            </label>
            <button type="submit" class="admin-btn-primary admin-btn-sm">Save</button>
        </form>

        <div class="admin-field-inset">
            <p class="admin-field-inset-title">Payload</p>
            <pre class="mt-3 max-h-[32rem] overflow-auto rounded-lg bg-white p-4 text-xs leading-relaxed text-thc-navy shadow-inner ring-1 ring-thc-navy/8">{{ json_encode($submission->payload ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>

        <p class="text-sm"><a href="{{ route('admin.soc.submissions.index') }}" class="admin-link">← All submissions</a></p>
    </div>
</x-layouts.admin>
