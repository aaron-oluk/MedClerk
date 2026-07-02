<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\CohortEnrollment;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;

class CohortController extends Controller
{
    public function store(Request $request, Program $program)
    {
        $this->authorize('update', $program->institution);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $data['program_id'] = $program->id;

        Cohort::create($data);

        return redirect()->route('programs.show', $program);
    }

    public function storeEnrollment(Request $request, Cohort $cohort)
    {
        $this->authorize('update', $cohort->program->institution);

        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'enrolled_at' => ['required', 'date'],
        ]);

        $student = User::where('id', $data['user_id'])->where('role', User::ROLE_STUDENT)->firstOrFail();

        CohortEnrollment::firstOrCreate(
            ['cohort_id' => $cohort->id, 'user_id' => $student->id],
            ['status' => 'active', 'enrolled_at' => $data['enrolled_at']]
        );

        return redirect()->route('programs.show', $cohort->program);
    }
}
