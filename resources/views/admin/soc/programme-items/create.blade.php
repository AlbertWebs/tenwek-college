@php $item = new \App\Models\SocProgrammeItem; @endphp
<x-layouts.admin header="New programme">
    <form method="post" action="{{ route('admin.soc.programme-groups.items.store', $programme_group) }}" class="admin-page-narrow">
        <div class="admin-card p-6 sm:p-8">
            @csrf
            <div class="admin-form-stack">
                @include('admin.soc.programme-items._form')
            </div>
            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save</button>
                    <a href="{{ route('admin.soc.programme-groups.items.index', $programme_group) }}" class="admin-btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
