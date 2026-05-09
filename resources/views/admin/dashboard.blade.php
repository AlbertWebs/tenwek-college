<x-layouts.admin header="Dashboard">
    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <div class="admin-stat-card">
            <p class="text-xs font-semibold uppercase tracking-wide text-thc-text/65">Pages</p>
            <p class="mt-2 font-serif text-3xl font-semibold text-thc-navy">{{ number_format($stats['pages']) }}</p>
        </div>
        <div class="admin-stat-card">
            <p class="text-xs font-semibold uppercase tracking-wide text-thc-text/65">Downloads</p>
            <p class="mt-2 font-serif text-3xl font-semibold text-thc-navy">{{ number_format($stats['downloads']) }}</p>
        </div>
        <div class="admin-stat-card">
            <p class="text-xs font-semibold uppercase tracking-wide text-thc-text/65">News posts</p>
            <p class="mt-2 font-serif text-3xl font-semibold text-thc-navy">{{ number_format($stats['news']) }}</p>
        </div>
        <div class="admin-stat-card">
            <p class="text-xs font-semibold uppercase tracking-wide text-thc-text/65">Form submissions (7d)</p>
            <p class="mt-2 font-serif text-3xl font-semibold text-thc-navy">{{ number_format($stats['submissions']) }}</p>
        </div>
    </div>

    <div class="admin-card mt-10 p-6">
        <p class="text-sm leading-relaxed text-thc-text/90">
            Use the sidebar for downloads, public site shortcuts, and (when available) the School of Chaplaincy CMS. Stats above reflect your school scope unless you are a super administrator.
        </p>
    </div>
</x-layouts.admin>
