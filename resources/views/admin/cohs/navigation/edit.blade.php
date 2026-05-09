<x-layouts.admin header="Edit navigation item">
    <form method="post" action="{{ route('admin.cohs.navigation.update', $navigation) }}" class="admin-page-narrow">
        <div class="admin-card p-6 sm:p-8">
            @csrf
            @method('PUT')
            <div class="admin-form-stack">
                @include('admin.cohs.navigation._form')
            </div>
            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Update</button>
                    <a href="{{ route('admin.cohs.navigation.index') }}" class="admin-btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
