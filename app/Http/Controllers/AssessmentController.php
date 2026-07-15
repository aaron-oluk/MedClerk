<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\LogbookEntry;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssessmentController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Assessment::with('student', 'skill', 'rotation', 'assessor')->orderBy('assessed_at', 'desc');

        if ($user->isSuperadmin()) {
            // no scoping
        } elseif ($user->isAdmin()) {
            $query->whereHas('rotation', fn ($q) => $q->where('institution_id', $user->institution_id));
        } elseif ($user->isLecturer()) {
            $query->where('assessor_id', $user->id);
        } else {
            $query->where('student_id', $user->id);
        }

        $assessments = $query->paginate(15)->withQueryString();

        $pendingLogs = collect();

        if ($user->isLecturer()) {
            $pendingLogs = LogbookEntry::whereHas('rotation', fn ($q) => $q->where('supervisor_id', $user->id))
                ->whereNotNull('skill_id')
                ->whereDoesntHave('assessments')
                ->with('student', 'skill', 'rotation')
                ->orderBy('encounter_date', 'desc')
                ->get();
        }

        $canCreate = $user->can('create', Assessment::class) && $pendingLogs->isNotEmpty();

        return view('assessments.index', [
            'assessments' => $assessments,
            'pendingLogs' => $pendingLogs,
            'canCreate' => $canCreate,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Assessment::class);

        $data = $request->validate([
            'logbook_entry_id' => ['required', 'exists:logbook_entries,id'],
            'score' => ['required', 'numeric', 'min:0'],
            'max_score' => ['required', 'numeric', 'gte:score'],
            'assessed_at' => ['required', 'date'],
        ]);

        $logbookEntry = LogbookEntry::with('rotation')->findOrFail($data['logbook_entry_id']);

        abort_unless(
            $request->user()->isSuperadmin() || $request->user()->id === $logbookEntry->rotation->supervisor_id,
            403,
            "You can only assess your own students' logged encounters."
        );

        abort_if($logbookEntry->skill_id === null, 422, 'This encounter is not linked to a skill and cannot be scored.');

        $data['rotation_id'] = $logbookEntry->rotation_id;
        $data['skill_id'] = $logbookEntry->skill_id;
        $data['student_id'] = $logbookEntry->student_id;
        $data['logbook_entry_id'] = $logbookEntry->id;
        $data['assessor_id'] = $request->user()->id;

        Assessment::create($data);

        return redirect()->route('assessments.index');
    }
}
