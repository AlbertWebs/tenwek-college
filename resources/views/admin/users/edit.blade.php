<x-layouts.admin
    header="Edit user"
    :breadcrumbs="[['label' => 'Admin', 'href' => route('admin.dashboard')], ['label' => 'Users & roles', 'href' => route('admin.users.index')], ['label' => $user->name]]"
>
    <form method="post" action="{{ route('admin.users.update', $user) }}" class="admin-page-narrow" x-data="{ role: '{{ old('role', $currentRole) }}' }">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <div class="admin-form-stack">
                <x-admin.ui.group label="Full name" for="name" name="name">
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required autocomplete="name" class="admin-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="Email" for="email" name="email">
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required autocomplete="email" class="admin-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="New password" for="password" name="password" hint="Leave blank to keep the current password.">
                    <input type="password" name="password" id="password" autocomplete="new-password" class="admin-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="Confirm new password" for="password_confirmation" name="password_confirmation">
                    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" class="admin-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="Role" for="role" name="role">
                    <select name="role" id="role" x-model="role" required class="admin-select">
                        @foreach ($roles as $value => $label)
                            <option value="{{ $value }}" @selected(old('role', $currentRole) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </x-admin.ui.group>

                <div x-show="role !== 'super_admin'" x-cloak>
                    <x-admin.ui.group label="School" for="school_id" name="school_id">
                        <select name="school_id" id="school_id" class="admin-select" :disabled="role === 'super_admin'">
                            <option value="">— Select school —</option>
                            @foreach ($schools as $s)
                                <option value="{{ $s->id }}" @selected((string) old('school_id', $user->school_id) === (string) $s->id)>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </x-admin.ui.group>
                </div>

                <x-admin.ui.group label="Account status" name="is_active">
                    <label class="admin-check-row">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active)) class="admin-checkbox">
                        <span>Active (can sign in)</span>
                    </label>
                </x-admin.ui.group>
            </div>

            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save changes</button>
                    <a href="{{ route('admin.users.index') }}" class="admin-btn-secondary">Back</a>
                </div>
                @if ($user->id !== auth()->id())
                    <div class="flex flex-wrap gap-3">
                        <button type="submit" form="delete-user" class="admin-btn-danger">Delete user</button>
                    </div>
                @endif
            </div>
        </div>
    </form>

    @if ($user->id !== auth()->id())
        <form id="delete-user" method="post" action="{{ route('admin.users.destroy', $user) }}" class="hidden" onsubmit="return confirm('Remove this user permanently? They will no longer be able to sign in.');">
            @csrf
            @method('DELETE')
        </form>
    @endif
</x-layouts.admin>
