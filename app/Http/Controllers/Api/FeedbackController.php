<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            'student_id' => ['required', 'exists:users,id'],
            'assessment_id' => ['nullable', 'exists:assessments,id'],
            'strengths' => ['nullable', 'string'],
            'areas_to_improve' => ['nullable', 'string'],
            'follow_up_date' => ['nullable', 'date'],
        ]);

        $data['given_by'] = $request->user()->id;

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
