<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LogbookEntry;
use App\Models\Rotation;
use Illuminate\Http\Request;

class LogbookEntryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = LogbookEntry::query()->with('rotation', 'clinicalSign', 'skill')->orderBy('encounter_date', 'desc');

        if ($user->isSuperadmin()) {
            // no scoping
        } elseif ($user->isAdmin()) {
            $query->whereHas('rotation', fn ($q) => $q->where('institution_id', $user->institution_id));
        } elseif ($user->isLecturer()) {
            $query->whereHas('rotation', fn ($q) => $q->where('supervisor_id', $user->id));
        } else {
            $query->where('student_id', $user->id);
        }

        return $query->paginate(25);
    }

    public function show(Request $request, LogbookEntry $logbookEntry)
    {
        $this->authorize('view', $logbookEntry);

        return $logbookEntry->load('rotation', 'clinicalSign', 'skill');
    }

    public function store(Request $request)
    {
        $this->authorize('create', LogbookEntry::class);

        $data = $request->validate([
            'rotation_id' => ['required', 'exists:rotations,id'],
            'clinical_sign_id' => ['nullable', 'exists:clinical_signs,id'],
            'skill_id' => ['nullable', 'exists:skills,id'],
            'encounter_date' => ['required', 'date'],
            'findings' => ['nullable', 'array'],
            'notes' => ['nullable', 'string'],
        ]);

        $rotation = Rotation::findOrFail($data['rotation_id']);
        abort_unless(
            $request->user()->isAdmin() || $request->user()->isSuperadmin() || $request->user()->id === $rotation->student_id,
            403,
            'You can only log encounters for your own rotation.'
        );

        $data['student_id'] = $rotation->student_id;

        return LogbookEntry::create($data);
    }

    public function update(Request $request, LogbookEntry $logbookEntry)
    {
        $this->authorize('update', $logbookEntry);

        $data = $request->validate([
            'clinical_sign_id' => ['nullable', 'exists:clinical_signs,id'],
            'skill_id' => ['nullable', 'exists:skills,id'],
            'encounter_date' => ['sometimes', 'date'],
            'findings' => ['nullable', 'array'],
            'notes' => ['nullable', 'string'],
        ]);

        $logbookEntry->update($data);

        return $logbookEntry;
    }

    public function destroy(Request $request, LogbookEntry $logbookEntry)
    {
        $this->authorize('delete', $logbookEntry);

        $logbookEntry->delete();

        return response()->noContent();
    }
}
