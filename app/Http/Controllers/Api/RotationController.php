<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rotation;
use Illuminate\Http\Request;

class RotationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Rotation::query()
            ->with('department', 'student', 'supervisor')
            ->withCount('logbookEntries')
            ->orderBy('start_date', 'desc');

        if ($user->isSuperadmin()) {
            // no scoping
        } elseif ($user->isAdmin()) {
            $query->where('institution_id', $user->institution_id);
        } elseif ($user->isLecturer()) {
            $query->where('institution_id', $user->institution_id)
                ->where('supervisor_id', $user->id);
        } else {
            $query->where('student_id', $user->id);
        }

        return $query->paginate(25);
    }

    public function show(Request $request, Rotation $rotation)
    {
        $this->authorize('view', $rotation);

        $rotation->loadCount('logbookEntries');

        return $rotation->load('department', 'student', 'supervisor', 'logbookEntries', 'assessments');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Rotation::class);

        $data = $request->validate([
            'institution_id' => ['required', 'exists:institutions,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'student_id' => ['required', 'exists:users,id'],
            'supervisor_id' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'string', 'in:scheduled,active,completed'],
            'required_encounters' => ['nullable', 'integer', 'min:0'],
        ]);

        return Rotation::create($data);
    }

    public function update(Request $request, Rotation $rotation)
    {
        $this->authorize('update', $rotation);

        $data = $request->validate([
            'department_id' => ['sometimes', 'exists:departments,id'],
            'supervisor_id' => ['nullable', 'exists:users,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['sometimes', 'string', 'in:scheduled,active,completed'],
            'required_encounters' => ['nullable', 'integer', 'min:0'],
        ]);

        $rotation->update($data);

        return $rotation;
    }

    public function destroy(Request $request, Rotation $rotation)
    {
        $this->authorize('delete', $rotation);

        $rotation->delete();

        return response()->noContent();
    }
}
