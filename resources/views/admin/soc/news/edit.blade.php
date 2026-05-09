<x-layouts.admin header="Edit news post">
    <form method="post" action="{{ route('admin.soc.news.update', $news) }}" class="admin-page-narrow">
        <div class="admin-card p-6 sm:p-8">
            @csrf
            @method('PUT')
            <div class="admin-form-stack">
                @include('admin.soc.news._form', ['post' => $news])
            </div>
            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save changes</button>
                    <a href="{{ route('admin.soc.news.index') }}" class="admin-btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
