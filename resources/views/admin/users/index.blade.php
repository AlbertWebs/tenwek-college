<x-layouts.admin
    header="Users & roles"
    :breadcrumbs="[['label' => 'Admin', 'href' => route('admin.dashboard')], ['label' => 'Users & roles']]"
>
    <div class="admin-toolbar">
        <p class="admin-toolbar-note max-w-xl">Manage who can sign in to the admin panel. School admins are limited to their college or chaplaincy scope; super admins have full access.</p>
        <a href="{{ route('admin.users.create') }}" class="admin-btn-primary">New user</a>
    </div>

    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table admin-table--zebra">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="hidden sm:table-cell">Email</th>
                        <th class="hidden md:table-cell">Role</th>
                        <th class="hidden lg:table-cell">School</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                        <tr>
                            <td class="font-medium text-thc-navy">{{ $u->name }}</td>
                            <td class="hidden text-thc-text/90 sm:table-cell">{{ $u->email }}</td>
                            <td class="hidden text-thc-text/90 md:table-cell">
                                {{ $u->roles->pluck('name')->join(', ') ?: '—' }}
                            </td>
                            <td class="hidden text-thc-text/90 lg:table-cell">{{ $u->school?->name ?? '—' }}</td>
                            <td>
                                @if ($u->is_active)
                                    <x-admin.ui.badge variant="success">Active</x-admin.ui.badge>
                                @else
                                    <x-admin.ui.badge variant="muted">Inactive</x-admin.ui.badge>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.users.edit', $u) }}" class="font-medium text-thc-royal hover:underline">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</x-layouts.admin>
