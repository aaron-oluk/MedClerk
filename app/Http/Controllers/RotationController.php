<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Institution;
use App\Models\Rotation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RotationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Rotation::with('department', 'student', 'supervisor')->orderBy('start_date', 'desc');

        if ($user->isSuperadmin()) {
            // no scoping
        } elseif ($user->isAdmin()) {
            $query->where('institution_id', $user->institution_id);
        } elseif ($user->isLecturer()) {
            $query->where('institution_id', $user->institution_id)->where('supervisor_id', $user->id);
        } else {
            $query->where('student_id', $user->id);
        }

        $rotations = $query->paginate(15)->withQueryString();

        $canCreate = $user->can('create', Rotation::class);

        $institutions = collect();
        $departments = collect();
        $students = collect();
        $supervisors = collect();

        if ($canCreate) {
            $institutions = $user->isSuperadmin() ? Institution::orderBy('name')->get() : Institution::where('id', $user->institution_id)->get();
            $departments = $user->isSuperadmin() ? Department::orderBy('name')->get() : Department::where('institution_id', $user->institution_id)->orderBy('name')->get();
            $students = User::where('role', User::ROLE_STUDENT)
                ->when(! $user->isSuperadmin(), fn ($q) => $q->where('institution_id', $user->institution_id))
                ->orderBy('name')->get();
            $supervisors = User::where('role', User::ROLE_LECTURER)
                ->when(! $user->isSuperadmin(), fn ($q) => $q->where('institution_id', $user->institution_id))
                ->orderBy('name')->get();
        }

        return view('rotations.index', [
            'rotations' => $rotations,
            'canCreate' => $canCreate,
            'institutions' => $institutions,
            'departments' => $departments,
            'students' => $students,
            'supervisors' => $supervisors,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Rotation::class);

        $user = $request->user();

        $data = $request->validate([
            'institution_id' => ['required', 'exists:institutions,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'student_id' => ['required', 'exists:users,id'],
            'supervisor_id' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'string', 'in:scheduled,active,completed'],
        ]);

        if (! $user->isSuperadmin()) {
            $data['institution_id'] = $user->institution_id;
        }

        Rotation::create($data);

        return redirect()->route('rotations.index');
    }
}
