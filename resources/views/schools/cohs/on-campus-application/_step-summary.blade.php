<div class="space-y-6">
    <h3 class="border-b border-thc-navy/10 pb-2 font-serif text-xl font-semibold text-thc-navy">Summary</h3>
    <p class="text-sm text-thc-text/85">Review your application. Use <strong class="text-thc-navy">Previous</strong> to change any section. When you submit, your application and uploads are stored securely.</p>

    <dl class="grid gap-4 rounded-xl border border-thc-navy/10 bg-thc-navy/[0.02] p-5 text-sm sm:grid-cols-2 sm:p-6">
        <div class="sm:col-span-2">
            <dt class="text-xs font-bold uppercase tracking-wider text-thc-text/55">Full name</dt>
            <dd class="mt-1 font-medium text-thc-navy" x-text="summary.name || '–'"></dd>
        </div>
        <div>
            <dt class="text-xs font-bold uppercase tracking-wider text-thc-text/55">Email</dt>
            <dd class="mt-1 font-medium text-thc-navy" x-text="summary.email || '–'"></dd>
        </div>
        <div>
            <dt class="text-xs font-bold uppercase tracking-wider text-thc-text/55">Mobile</dt>
            <dd class="mt-1 font-medium text-thc-navy" x-text="summary.mobile || '–'"></dd>
        </div>
        <div class="sm:col-span-2">
            <dt class="text-xs font-bold uppercase tracking-wider text-thc-text/55">Programme</dt>
            <dd class="mt-1 font-medium text-thc-navy" x-text="summary.programme || '–'"></dd>
        </div>
        <div>
            <dt class="text-xs font-bold uppercase tracking-wider text-thc-text/55">Mode of study</dt>
            <dd class="mt-1 font-medium text-thc-navy" x-text="summary.study_mode || '–'"></dd>
        </div>
        <div>
            <dt class="text-xs font-bold uppercase tracking-wider text-thc-text/55">Campus</dt>
            <dd class="mt-1 font-medium text-thc-navy" x-text="summary.campus || '–'"></dd>
        </div>
    </dl>
</div>
