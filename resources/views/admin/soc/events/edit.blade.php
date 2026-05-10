<x-layouts.admin header="SOC — Edit event">
    <form method="post" action="{{ route('admin.soc.events.update', $event) }}" enctype="multipart/form-data" class="admin-page-narrow">
        <div class="admin-card p-6 sm:p-8">
            @csrf
            @method('PUT')
            <div class="admin-form-stack">
                @include('admin.school-events._form', ['event' => $event])
            </div>
            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Update</button>
                    <a href="{{ route('admin.soc.events.index') }}" class="admin-btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
