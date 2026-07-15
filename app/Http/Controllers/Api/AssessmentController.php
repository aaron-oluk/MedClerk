<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Rotation;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Assessment::query()->with('student', 'skill', 'rotation', 'assessor')->orderBy('assessed_at', 'desc');

        if ($user->isSuperadmin()) {
            // no scoping
        } elseif ($user->isAdmin()) {
            $query->whereHas('rotation', fn ($q) => $q->where('institution_id', $user->institution_id));
        } elseif ($user->isLecturer()) {
            $query->where('assessor_id', $user->id);
        } else {
            $query->where('student_id', $user->id);
        }

        return $query->paginate(25);
    }

    public function show(Request $request, Assessment $assessment)
    {
        $this->authorize('view', $assessment);

        return $assessment->load('student', 'skill', 'rotation', 'assessor', 'feedback');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Assessment::class);

        $data = $request->validate([
            'rotation_id' => ['required', 'exists:rotations,id'],
            'skill_id' => ['required', 'exists:skills,id'],
            'score' => ['required', 'numeric', 'min:0'],
            'max_score' => ['required', 'numeric', 'gte:score'],
            'curriculum_version' => ['nullable', 'string', 'max:50'],
            'assessed_at' => ['required', 'date'],
        ]);

        $user = $request->user();
        $rotation = Rotation::findOrFail($data['rotation_id']);

        abort_unless(
            $user->isSuperadmin()
                || ($user->isAdmin() && $user->institution_id === $rotation->institution_id)
                || ($user->isLecturer() && $user->id === $rotation->supervisor_id),
            403,
            'You can only assess students on your own supervised rotations.'
        );

        $data['student_id'] = $rotation->student_id;
        $data['assessor_id'] = $user->id;

        return Assessment::create($data);
    }

    public function update(Request $request, Assessment $assessment)
    {
        $this->authorize('update', $assessment);

        $data = $request->validate([
            'score' => ['sometimes', 'numeric', 'min:0'],
            'max_score' => ['sometimes', 'numeric', 'gte:score'],
            'curriculum_version' => ['nullable', 'string', 'max:50'],
            'assessed_at' => ['sometimes', 'date'],
        ]);

        $assessment->update($data);

        return $assessment;
    }

    public function destroy(Request $request, Assessment $assessment)
    {
        $this->authorize('delete', $assessment);

        $assessment->delete();

        return response()->noContent();
    }
}
