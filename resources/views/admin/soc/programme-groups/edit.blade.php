<x-layouts.admin header="Edit programme group">
    <form method="post" action="{{ route('admin.soc.programme-groups.update', $programme_group) }}" class="admin-page-narrow">
        <div class="admin-card p-6 sm:p-8">
            @csrf
            @method('PUT')
            <div class="admin-form-stack">
                @include('admin.soc.programme-groups._form')
            </div>
            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save changes</button>
                    <a href="{{ route('admin.soc.programme-groups.index') }}" class="admin-btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
