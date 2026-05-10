<x-layouts.admin header="Edit FAQ" title="Edit FAQ | SOC CMS | {{ config('tenwek.name') }}">
    <form method="post" action="{{ route('admin.soc.faqs.update', $faq) }}" class="admin-page-wide">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <div class="admin-form-stack max-w-4xl">
                @include('admin.soc.faqs._form', ['formRow' => $formRow])
            </div>
            <div class="admin-actions admin-actions-sticky mt-8 max-w-4xl">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save changes</button>
                    <a href="{{ route('admin.soc.faqs.index') }}" class="admin-btn-secondary">Back to list</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
