<x-layouts.admin header="New FAQ" title="New FAQ | SOC CMS | {{ config('tenwek.name') }}">
    <form method="post" action="{{ route('admin.soc.faqs.store') }}" class="admin-page-wide">
        @csrf
        <div class="admin-card p-6 sm:p-8">
            <div class="admin-form-stack max-w-4xl">
                @include('admin.soc.faqs._form', ['formRow' => $formRow])
            </div>
            <div class="admin-actions admin-actions-sticky mt-8 max-w-4xl">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save FAQ</button>
                    <a href="{{ route('admin.soc.faqs.index') }}" class="admin-btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
