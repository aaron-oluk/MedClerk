<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $actingUser = $request->user();

        $users = User::with('institution', 'department')
            ->when(! $actingUser->isSuperadmin(), fn ($q) => $q->where('institution_id', $actingUser->institution_id))
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->query('role')))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'roleFilter' => $request->query('role', ''),
        ]);
    }

    public function edit(Request $request, User $user): View
    {
        $this->authorize('update', $user);

        $actingUser = $request->user();

        return view('users.edit', [
            'targetUser' => $user,
            'institutions' => $actingUser->isSuperadmin() ? Institution::orderBy('name')->get() : collect(),
            'departments' => Department::where('institution_id', $user->institution_id)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $actingUser = $request->user();

        $data = $request->validate([
            'role' => ['required', 'in:student,lecturer,admin,superadmin'],
            'institution_id' => ['nullable', 'exists:institutions,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        abort_if(
            $data['role'] === User::ROLE_SUPERADMIN && ! $actingUser->isSuperadmin(),
            403,
            'Only a superadmin can grant superadmin access.'
        );

        if (! $actingUser->isSuperadmin()) {
            $data['institution_id'] = $user->institution_id;
        }

        $data['is_active'] = $request->boolean('is_active');

        $user->update($data);

        return redirect()->route('users.index')->with('status', 'user-updated');
    }
}
