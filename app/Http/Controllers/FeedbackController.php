<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Feedback::with('student', 'assessment.skill', 'givenBy')->orderBy('created_at', 'desc');

        if ($user->isSuperadmin()) {
            // no scoping
        } elseif ($user->isAdmin()) {
            $query->whereHas('student', fn ($q) => $q->where('institution_id', $user->institution_id));
        } elseif ($user->isLecturer()) {
            $query->where('given_by', $user->id);
        } else {
            $query->where('student_id', $user->id);
        }

        $feedback = $query->paginate(15)->withQueryString();

        $assessments = collect();
        if ($user->isLecturer()) {
            $assessments = Assessment::with('student', 'skill')->where('assessor_id', $user->id)->orderBy('assessed_at', 'desc')->get();
        }

        $canCreate = $user->can('create', Feedback::class) && $assessments->isNotEmpty();

        return view('feedback.index', [
            'feedbackEntries' => $feedback,
            'assessments' => $assessments,
            'canCreate' => $canCreate,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Feedback::class);

        $data = $request->validate([
            'assessment_id' => ['required', 'exists:assessments,id'],
            'strengths' => ['nullable', 'string'],
            'areas_to_improve' => ['nullable', 'string'],
            'follow_up_date' => ['nullable', 'date'],
        ]);

        $assessment = Assessment::findOrFail($data['assessment_id']);

        abort_unless(
            $request->user()->isSuperadmin() || $request->user()->id === $assessment->assessor_id,
            403,
            'You can only give feedback on assessments you gave.'
        );

        $data['student_id'] = $assessment->student_id;
        $data['given_by'] = $request->user()->id;

        Feedback::create($data);

        return redirect()->route('feedback.index');
    }
}
