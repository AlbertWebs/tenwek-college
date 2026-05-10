@php $post = new \App\Models\NewsPost; @endphp
<x-layouts.admin header="New news post">
    <form method="post" action="{{ route('admin.soc.news.store') }}" class="admin-page-narrow" enctype="multipart/form-data">
        <div class="admin-card p-6 sm:p-8">
            @csrf
            <div class="admin-form-stack">
                @include('admin.soc.news._form')
            </div>
            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Publish</button>
                    <a href="{{ route('admin.soc.news.index') }}" class="admin-btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
