<?php

namespace App\Http\Controllers;

use App\Models\Rotation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentLookupController extends Controller
{
    public function search(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));

        $students = User::where('role', User::ROLE_STUDENT)
            ->when($query !== '', fn ($q) => $q->where('student_number', 'like', "%{$query}%"))
            ->with('institution')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('students.search', [
            'students' => $students,
            'query' => $query,
        ]);
    }

    public function show(User $student): View
    {
        abort_unless($student->isStudent(), 404);

        $activeRotation = Rotation::where('student_id', $student->id)
            ->where('status', 'active')
            ->latest('start_date')
            ->first();

        return view('students.show', [
            'student' => $student,
            'profile' => $student->profileSummary($activeRotation),
        ]);
    }
}
