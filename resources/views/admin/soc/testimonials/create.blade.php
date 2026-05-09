<x-layouts.admin header="New testimonial">
    <form method="post" action="{{ route('admin.soc.testimonials.store') }}" enctype="multipart/form-data" class="admin-page-narrow">
        <div class="admin-card p-6 sm:p-8">
            @csrf
            <div class="admin-form-stack">
                @include('admin.soc.testimonials._form', ['testimonial' => null])
            </div>
            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Create</button>
                    <a href="{{ route('admin.soc.testimonials.index') }}" class="admin-btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
