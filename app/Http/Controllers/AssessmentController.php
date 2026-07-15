<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Rotation;
use App\Models\Skill;
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

        $rotations = collect();
        $skills = collect();

        if ($user->isLecturer()) {
            $rotations = Rotation::with('student')->where('supervisor_id', $user->id)->orderBy('start_date', 'desc')->get();
            $skills = Skill::orderBy('name')->get();
        }

        $canCreate = $user->can('create', Assessment::class) && $rotations->isNotEmpty();

        return view('assessments.index', [
            'assessments' => $assessments,
            'rotations' => $rotations,
            'skills' => $skills,
            'canCreate' => $canCreate,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Assessment::class);

        $data = $request->validate([
            'rotation_id' => ['required', 'exists:rotations,id'],
            'skill_id' => ['required', 'exists:skills,id'],
            'score' => ['required', 'numeric', 'min:0'],
            'max_score' => ['required', 'numeric', 'gte:score'],
            'assessed_at' => ['required', 'date'],
        ]);

        $rotation = Rotation::findOrFail($data['rotation_id']);

        abort_unless(
            $request->user()->isSuperadmin()
                || ($request->user()->isAdmin() && $request->user()->institution_id === $rotation->institution_id)
                || ($request->user()->isLecturer() && $request->user()->id === $rotation->supervisor_id),
            403,
            'You can only assess students on your own supervised rotations.'
        );

        $data['student_id'] = $rotation->student_id;
        $data['assessor_id'] = $request->user()->id;

        Assessment::create($data);

        return redirect()->route('assessments.index');
    }
}
