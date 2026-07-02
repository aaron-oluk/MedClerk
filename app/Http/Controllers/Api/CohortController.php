<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cohort;
use App\Models\Program;
use Illuminate\Http\Request;

class CohortController extends Controller
{
    public function index(Request $request)
    {
        $query = Cohort::query()->with('program')->orderBy('start_date', 'desc');

        if (! $request->user()->isSuperadmin()) {
            $query->whereHas('program', fn ($q) => $q->where('institution_id', $request->user()->institution_id));
        }

        return $query->paginate(25);
    }

    public function show(Cohort $cohort)
    {
        $this->authorize('update', $cohort->program->institution);

        return $cohort->load('students');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $program = Program::findOrFail($data['program_id']);
        $this->authorize('update', $program->institution);

        return Cohort::create($data);
    }

    public function update(Request $request, Cohort $cohort)
    {
        $this->authorize('update', $cohort->program->institution);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $cohort->update($data);

        return $cohort;
    }

    public function destroy(Cohort $cohort)
    {
        $this->authorize('update', $cohort->program->institution);

        $cohort->delete();

        return response()->noContent();
    }
}
