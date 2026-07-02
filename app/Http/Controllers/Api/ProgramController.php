<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = Program::query()->orderBy('name');

        if (! $request->user()->isSuperadmin()) {
            $query->where('institution_id', $request->user()->institution_id);
        }

        return $query->paginate(25);
    }

    public function show(Program $program)
    {
        $this->authorize('view', $program->institution);

        return $program->load('cohorts');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'institution_id' => ['required', 'exists:institutions,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);

        $institution = \App\Models\Institution::findOrFail($data['institution_id']);
        $this->authorize('update', $institution);

        return Program::create($data);
    }

    public function update(Request $request, Program $program)
    {
        $this->authorize('update', $program->institution);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'code' => ['sometimes', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);

        $program->update($data);

        return $program;
    }

    public function destroy(Program $program)
    {
        $this->authorize('update', $program->institution);

        $program->delete();

        return response()->noContent();
    }
}
