<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Program::withCount('cohorts')->with('institution')->orderBy('name');

        if (! $user->isSuperadmin()) {
            $query->where('institution_id', $user->institution_id);
        }

        $programs = $query->get();

        $canCreate = $user->isAdmin() || $user->isSuperadmin();
        $institutions = $canCreate
            ? ($user->isSuperadmin() ? Institution::orderBy('name')->get() : Institution::where('id', $user->institution_id)->get())
            : collect();

        return view('programs.index', [
            'programs' => $programs,
            'canCreate' => $canCreate,
            'institutions' => $institutions,
        ]);
    }

    public function show(Request $request, Program $program): View
    {
        $this->authorize('update', $program->institution);

        $program->load(['cohorts' => fn ($q) => $q->withCount('students')->orderBy('start_date', 'desc')]);

        $students = User::where('role', User::ROLE_STUDENT)
            ->where('institution_id', $program->institution_id)
            ->orderBy('name')
            ->get();

        return view('programs.show', [
            'program' => $program,
            'students' => $students,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'institution_id' => ['required', 'exists:institutions,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);

        $institution = Institution::findOrFail($data['institution_id']);
        $this->authorize('update', $institution);

        Program::create($data);

        return redirect()->route('programs.index');
    }
}
