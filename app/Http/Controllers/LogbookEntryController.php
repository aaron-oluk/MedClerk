<?php

namespace App\Http\Controllers;

use App\Models\ClinicalSign;
use App\Models\LogbookEntry;
use App\Models\Rotation;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogbookEntryController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = LogbookEntry::with('rotation.student', 'clinicalSign', 'skill', 'signedOffBy')->orderBy('encounter_date', 'desc');

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

        $rotations = $this->rotationsFor($user);
        $canCreate = $user->can('create', LogbookEntry::class) && $rotations->isNotEmpty();

        return view('logbook-entries.index', [
            'entries' => $entries,
            'rotations' => $rotations,
            'clinicalSigns' => $canCreate ? ClinicalSign::orderBy('name')->get() : collect(),
            'skills' => $canCreate ? Skill::orderBy('name')->get() : collect(),
            'canCreate' => $canCreate,
        ]);
    }

    public function quick(Request $request): View
    {
        $user = $request->user();
        $rotations = $this->rotationsFor($user);

        return view('logbook-entries.quick', [
            'rotations' => $rotations,
            'clinicalSigns' => ClinicalSign::orderBy('name')->get(['id', 'name']),
            'skills' => Skill::orderBy('name')->get(['id', 'name']),
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
            'chief_complaint' => ['nullable', 'string'],
            'examination_findings' => ['nullable', 'string'],
            'impression' => ['nullable', 'string'],
            'plan' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->createEntry($request->user(), $data);

        return redirect()->route('logbook-entries.index');
    }

    public function sync(Request $request)
    {
        $this->authorize('create', LogbookEntry::class);

        $data = $request->validate([
            'client_uuid' => ['required', 'uuid'],
            'rotation_id' => ['required', 'exists:rotations,id'],
            'clinical_sign_id' => ['nullable', 'exists:clinical_signs,id'],
            'skill_id' => ['nullable', 'exists:skills,id'],
            'encounter_date' => ['required', 'date'],
            'encounter_type' => ['nullable', 'in:observed,assisted,performed'],
            'notes' => ['nullable', 'string'],
        ]);

        $existing = LogbookEntry::where('client_uuid', $data['client_uuid'])->first();
        if ($existing) {
            return response()->json(['status' => 'already_synced', 'id' => $existing->id]);
        }

        $entry = $this->createEntry($request->user(), $data);

        return response()->json(['status' => 'created', 'id' => $entry->id]);
    }

    public function signOff(Request $request, LogbookEntry $logbookEntry)
    {
        $this->authorize('update', $logbookEntry);

        $user = $request->user();

        abort_unless(
            $user->isSuperadmin() || $user->isAdmin() || $user->id === $logbookEntry->rotation->supervisor_id,
            403,
            'Only the supervising lecturer can sign off this entry.'
        );

        $logbookEntry->update([
            'signed_off_by' => $user->id,
            'signed_off_at' => now(),
        ]);

        return redirect()->back();
    }

    protected function createEntry(User $actingUser, array $data): LogbookEntry
    {
        $rotation = Rotation::findOrFail($data['rotation_id']);

        abort_unless(
            $actingUser->isAdmin() || $actingUser->isSuperadmin() || $actingUser->id === $rotation->student_id,
            403,
            'You can only log encounters for your own rotation.'
        );

        $data['student_id'] = $rotation->student_id;

        $data['findings'] = collect($data)
            ->only(['chief_complaint', 'examination_findings', 'impression', 'plan'])
            ->filter()
            ->all();

        $data = collect($data)->except(['chief_complaint', 'examination_findings', 'impression', 'plan'])->all();

        if (empty($data['findings'])) {
            unset($data['findings']);
        }

        return LogbookEntry::create($data);
    }

    protected function rotationsFor(User $user)
    {
        if ($user->isStudent()) {
            return Rotation::where('student_id', $user->id)->orderBy('start_date', 'desc')->get();
        }

        if ($user->isAdmin() || $user->isSuperadmin()) {
            return Rotation::with('student')
                ->when(! $user->isSuperadmin(), fn ($q) => $q->where('institution_id', $user->institution_id))
                ->orderBy('start_date', 'desc')->get();
        }

        return collect();
    }
}
