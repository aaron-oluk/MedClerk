<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Feedback::query()->with('student', 'assessment', 'givenBy')->orderBy('created_at', 'desc');

        if ($user->isSuperadmin()) {
            // no scoping
        } elseif ($user->isAdmin()) {
            $query->whereHas('student', fn ($q) => $q->where('institution_id', $user->institution_id));
        } elseif ($user->isLecturer()) {
            $query->where('given_by', $user->id);
        } else {
            $query->where('student_id', $user->id);
        }

        return $query->paginate(25);
    }

    public function show(Request $request, Feedback $feedback)
    {
        $this->authorize('view', $feedback);

        return $feedback->load('student', 'assessment', 'givenBy');
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

        $user = $request->user();
        $assessment = Assessment::findOrFail($data['assessment_id']);

        abort_unless(
            $user->isSuperadmin() || $user->id === $assessment->assessor_id,
            403,
            'You can only give feedback on assessments you gave.'
        );

        $data['student_id'] = $assessment->student_id;
        $data['given_by'] = $user->id;

        return Feedback::create($data);
    }

    public function update(Request $request, Feedback $feedback)
    {
        $this->authorize('update', $feedback);

        $data = $request->validate([
            'strengths' => ['nullable', 'string'],
            'areas_to_improve' => ['nullable', 'string'],
            'follow_up_date' => ['nullable', 'date'],
        ]);

        $feedback->update($data);

        return $feedback;
    }

    public function destroy(Request $request, Feedback $feedback)
    {
        $this->authorize('delete', $feedback);

        $feedback->delete();

        return response()->noContent();
    }
}
