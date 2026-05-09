<x-layouts.admin
    header="New user"
    :breadcrumbs="[['label' => 'Admin', 'href' => route('admin.dashboard')], ['label' => 'Users & roles', 'href' => route('admin.users.index')], ['label' => 'New']]"
>
    <form method="post" action="{{ route('admin.users.store') }}" class="admin-page-narrow" x-data="{ role: '{{ old('role', 'cohs_admin') }}' }">
        @csrf
        <div class="admin-card p-6 sm:p-8">
            <div class="admin-form-stack">
                <x-admin.ui.group label="Full name" for="name" name="name">
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autocomplete="name" class="admin-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="Email" for="email" name="email">
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email" class="admin-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="Password" for="password" name="password" hint="At least 10 characters.">
                    <input type="password" name="password" id="password" required autocomplete="new-password" class="admin-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="Confirm password" for="password_confirmation" name="password_confirmation">
                    <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" class="admin-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="Role" for="role" name="role" hint="Super admins are not tied to a single school.">
                    <select name="role" id="role" x-model="role" required class="admin-select">
                        @foreach ($roles as $value => $label)
                            <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </x-admin.ui.group>

                <div x-show="role !== 'super_admin'" x-cloak>
                    <x-admin.ui.group label="School" for="school_id" name="school_id" hint="Required for school administrators.">
                        <select name="school_id" id="school_id" class="admin-select" :disabled="role === 'super_admin'">
                            <option value="">— Select school —</option>
                            @foreach ($schools as $s)
                                <option value="{{ $s->id }}" @selected((string) old('school_id') === (string) $s->id)>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </x-admin.ui.group>
                </div>

                <x-admin.ui.group label="Account status" name="is_active">
                    <label class="admin-check-row">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="admin-checkbox">
                        <span>Active (can sign in)</span>
                    </label>
                </x-admin.ui.group>
            </div>

            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Create user</button>
                    <a href="{{ route('admin.users.index') }}" class="admin-btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
