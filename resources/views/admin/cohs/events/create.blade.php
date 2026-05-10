@php $event = new \App\Models\SchoolEvent(['starts_at' => now()]); @endphp
<x-layouts.admin header="COHS — New event">
    <form method="post" action="{{ route('admin.cohs.events.store') }}" enctype="multipart/form-data" class="admin-page-narrow">
        <div class="admin-card p-6 sm:p-8">
            @csrf
            <div class="admin-form-stack">
                @include('admin.school-events._form', ['event' => $event])
            </div>
            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save</button>
                    <a href="{{ route('admin.cohs.events.index') }}" class="admin-btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
