<?php

namespace App\Http\Controllers;

use App\Models\ClinicalSign;
use App\Models\LogbookEntry;
use App\Models\Rotation;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogbookEntryController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = LogbookEntry::with('rotation.student', 'clinicalSign', 'skill')->orderBy('encounter_date', 'desc');

        if ($user->isSuperadmin()) {
            // no scoping
        } elseif ($user->isAdmin()) {
            $query->whereHas('rotation', fn ($q) => $q->where('institution_id', $user->institution_id));
        } elseif ($user->isLecturer()) {
            $query->whereHas('rotation', fn ($q) => $q->where('supervisor_id', $user->id));
        } else {
            $query->where('student_id', $user->id);
        }

        $entries = $query->paginate(15)->withQueryString();

        $rotations = collect();
        if ($user->isStudent()) {
            $rotations = Rotation::where('student_id', $user->id)->orderBy('start_date', 'desc')->get();
        } elseif ($user->isAdmin() || $user->isSuperadmin()) {
            $rotations = Rotation::with('student')
                ->when(! $user->isSuperadmin(), fn ($q) => $q->where('institution_id', $user->institution_id))
                ->orderBy('start_date', 'desc')->get();
        }

        $canCreate = $user->can('create', LogbookEntry::class) && $rotations->isNotEmpty();

        return view('logbook-entries.index', [
            'entries' => $entries,
            'rotations' => $rotations,
            'clinicalSigns' => $canCreate ? ClinicalSign::orderBy('name')->get() : collect(),
            'skills' => $canCreate ? Skill::orderBy('name')->get() : collect(),
            'canCreate' => $canCreate,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', LogbookEntry::class);

        $data = $request->validate([
            'rotation_id' => ['required', 'exists:rotations,id'],
            'clinical_sign_id' => ['nullable', 'exists:clinical_signs,id'],
            'skill_id' => ['nullable', 'exists:skills,id'],
            'encounter_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $rotation = Rotation::findOrFail($data['rotation_id']);

        abort_unless(
            $request->user()->isAdmin() || $request->user()->isSuperadmin() || $request->user()->id === $rotation->student_id,
            403,
            'You can only log encounters for your own rotation.'
        );

        $data['student_id'] = $rotation->student_id;

        LogbookEntry::create($data);

        return redirect()->route('logbook-entries.index');
    }
}
