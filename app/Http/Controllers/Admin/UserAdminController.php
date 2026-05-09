<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserAdminController extends Controller
{
    public function index(): View
    {
        $users = User::query()->with(['school', 'roles'])->orderBy('name')->paginate(25);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $schools = $this->assignableSchools();
        $roles = $this->roleLabels();

        return view('admin.users.create', compact('schools', 'roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:10', 'confirmed'],
            'role' => ['required', 'in:super_admin,soc_admin,cohs_admin'],
            'school_id' => ['nullable', 'exists:schools,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $schoolId = $this->validatedSchoolIdForRole($validated['role'], isset($validated['school_id']) ? (int) $validated['school_id'] : null);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'school_id' => $schoolId,
            'is_active' => $request->boolean('is_active', true),
        ]);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')->with('status', __('User created.'));
    }

    public function edit(User $user): View
    {
        $schools = $this->assignableSchools();
        $roles = $this->roleLabels();
        $currentRole = $user->roles->first()?->name ?? 'soc_admin';

        return view('admin.users.edit', compact('user', 'schools', 'roles', 'currentRole'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:10', 'confirmed'],
            'role' => ['required', 'in:super_admin,soc_admin,cohs_admin'],
            'school_id' => ['nullable', 'exists:schools,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if (! $request->boolean('is_active', true) && $user->id === $request->user()->id) {
            return back()->withErrors(['is_active' => __('You cannot deactivate your own account.')])->withInput();
        }

        $schoolId = $this->validatedSchoolIdForRole($validated['role'], isset($validated['school_id']) ? (int) $validated['school_id'] : null);

        if ($user->id === $request->user()->id && $validated['role'] !== 'super_admin') {
            if (User::query()->role('super_admin')->count() < 2) {
                return back()->withErrors(['role' => __('You are the only super admin; create another super admin before changing your role.')])->withInput();
            }
        }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'school_id' => $schoolId,
            'is_active' => $request->boolean('is_active', true),
        ]);
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();
        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')->with('status', __('User updated.'));
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return redirect()->route('admin.users.index')->withErrors(['delete' => __('You cannot delete your own account.')]);
        }

        if ($user->hasRole('super_admin') && User::query()->role('super_admin')->count() <= 1) {
            return redirect()->route('admin.users.index')->withErrors(['delete' => __('You cannot delete the last super admin.')]);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', __('User removed.'));
    }

    /**
     * @return Collection<int, School>
     */
    protected function assignableSchools()
    {
        return School::query()
            ->whereIn('slug', ['soc', 'cohs'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * @return array<string, string>
     */
    protected function roleLabels(): array
    {
        return [
            'super_admin' => 'Super admin (full access)',
            'soc_admin' => 'School of Chaplaincy admin',
            'cohs_admin' => 'College of Health Sciences admin',
        ];
    }

    protected function validatedSchoolIdForRole(string $role, ?int $schoolId): ?int
    {
        if ($role === 'super_admin') {
            return null;
        }

        $socId = School::query()->where('slug', 'soc')->value('id');
        $cohsId = School::query()->where('slug', 'cohs')->value('id');

        if ($role === 'soc_admin') {
            if ($schoolId === null || $socId === null || (int) $schoolId !== (int) $socId) {
                throw ValidationException::withMessages([
                    'school_id' => __('Choose the School of Chaplaincy for this administrator.'),
                ]);
            }

            return (int) $socId;
        }

        if ($role === 'cohs_admin') {
            if ($schoolId === null || $cohsId === null || (int) $schoolId !== (int) $cohsId) {
                throw ValidationException::withMessages([
                    'school_id' => __('Choose the College of Health Sciences for this administrator.'),
                ]);
            }

            return (int) $cohsId;
        }

        return null;
    }
}
