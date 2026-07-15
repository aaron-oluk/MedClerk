<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rotation;
use App\Models\User;
use Illuminate\Http\Request;

class StudentLookupController extends Controller
{
    public function search(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        return User::where('role', User::ROLE_STUDENT)
            ->when($query !== '', fn ($q) => $q->where('student_number', 'like', "%{$query}%"))
            ->with('institution')
            ->orderBy('name')
            ->paginate(15);
    }

    public function show(User $student)
    {
        abort_unless($student->isStudent(), 404);

        $activeRotation = Rotation::where('student_id', $student->id)
            ->where('status', 'active')
            ->latest('start_date')
            ->first();

        return array_merge($student->load('institution')->toArray(), [
            'programme' => $student->currentCohortEnrollment()?->cohort?->program?->name,
            'current_placement' => $activeRotation?->name,
        ]);
    }
}
