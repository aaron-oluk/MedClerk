<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Feedback;
use App\Models\Institution;
use App\Models\LogbookEntry;
use App\Models\Rotation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return match (true) {
            $user->isSuperadmin() => $this->superadminDashboard(),
            $user->isAdmin() => $this->adminDashboard($user),
            $user->isLecturer() => $this->lecturerDashboard($user),
            default => $this->studentDashboard($user),
        };
    }

    protected function superadminDashboard(): View
    {
        $stats = [
            ['label' => 'Institutions', 'value' => Institution::count()],
            ['label' => 'Students', 'value' => User::where('role', User::ROLE_STUDENT)->count()],
            ['label' => 'Lecturers', 'value' => User::where('role', User::ROLE_LECTURER)->count()],
            ['label' => 'Active rotations', 'value' => Rotation::where('status', 'active')->count()],
        ];

        $institutionBreakdown = Institution::withCount([
            'users as student_count' => fn ($q) => $q->where('role', User::ROLE_STUDENT),
            'users as lecturer_count' => fn ($q) => $q->where('role', User::ROLE_LECTURER),
            'rotations as active_rotation_count' => fn ($q) => $q->where('status', 'active'),
        ])->orderBy('name')->get();

        return view('dashboard', [
            'stats' => $stats,
            'institutionBreakdown' => $institutionBreakdown,
            'recentLogbookEntries' => collect(),
            'recentAssessments' => collect(),
            'recentFeedback' => collect(),
        ]);
    }

    protected function adminDashboard(User $user): View
    {
        $institutionId = $user->institution_id;

        $stats = [
            ['label' => 'Students', 'value' => User::where('institution_id', $institutionId)->where('role', User::ROLE_STUDENT)->count()],
            ['label' => 'Lecturers', 'value' => User::where('institution_id', $institutionId)->where('role', User::ROLE_LECTURER)->count()],
            ['label' => 'Active rotations', 'value' => Rotation::where('institution_id', $institutionId)->where('status', 'active')->count()],
            [
                'label' => 'Logbook entries this month',
                'value' => LogbookEntry::whereHas('rotation', fn ($q) => $q->where('institution_id', $institutionId))
                    ->whereMonth('encounter_date', now()->month)
                    ->whereYear('encounter_date', now()->year)
                    ->count(),
            ],
        ];

        $recentLogbookEntries = LogbookEntry::whereHas('rotation', fn ($q) => $q->where('institution_id', $institutionId))
            ->with('student', 'rotation')
            ->latest('encounter_date')
            ->take(5)
            ->get();

        $recentAssessments = Assessment::whereHas('rotation', fn ($q) => $q->where('institution_id', $institutionId))
            ->with('student', 'skill', 'assessor')
            ->latest('assessed_at')
            ->take(5)
            ->get();

        return view('dashboard', [
            'stats' => $stats,
            'institutionBreakdown' => collect(),
            'recentLogbookEntries' => $recentLogbookEntries,
            'recentAssessments' => $recentAssessments,
            'recentFeedback' => collect(),
        ]);
    }

    protected function lecturerDashboard(User $user): View
    {
        $supervisedRotationIds = Rotation::where('supervisor_id', $user->id)->pluck('id');

        $stats = [
            [
                'label' => 'Students supervised',
                'value' => Rotation::where('supervisor_id', $user->id)->distinct()->count('student_id'),
            ],
            ['label' => 'Active rotations', 'value' => Rotation::where('supervisor_id', $user->id)->where('status', 'active')->count()],
            ['label' => 'Assessments given', 'value' => Assessment::where('assessor_id', $user->id)->count()],
            ['label' => 'Feedback given', 'value' => Feedback::where('given_by', $user->id)->count()],
        ];

        $recentLogbookEntries = LogbookEntry::whereIn('rotation_id', $supervisedRotationIds)
            ->with('student', 'rotation')
            ->latest('encounter_date')
            ->take(5)
            ->get();

        $recentAssessments = Assessment::where('assessor_id', $user->id)
            ->with('student', 'skill')
            ->latest('assessed_at')
            ->take(5)
            ->get();

        return view('dashboard', [
            'stats' => $stats,
            'institutionBreakdown' => collect(),
            'recentLogbookEntries' => $recentLogbookEntries,
            'recentAssessments' => $recentAssessments,
            'recentFeedback' => collect(),
        ]);
    }

    protected function studentDashboard(User $user): View
    {
        $activeRotation = Rotation::where('student_id', $user->id)
            ->where('status', 'active')
            ->latest('start_date')
            ->first();

        $assessments = Assessment::where('student_id', $user->id)->get();
        $averageScore = $assessments->isNotEmpty()
            ? round($assessments->avg(fn ($a) => $a->max_score > 0 ? ($a->score / $a->max_score) * 100 : 0), 1).'%'
            : 'No assessments yet';

        $stats = [
            ['label' => 'Active rotation', 'value' => $activeRotation?->name ?? 'None'],
            ['label' => 'Logbook entries', 'value' => LogbookEntry::where('student_id', $user->id)->count()],
            ['label' => 'Assessments received', 'value' => $assessments->count()],
            ['label' => 'Average score', 'value' => $averageScore],
        ];

        $recentLogbookEntries = LogbookEntry::where('student_id', $user->id)
            ->with('rotation', 'clinicalSign', 'skill')
            ->latest('encounter_date')
            ->take(5)
            ->get();

        $recentFeedback = Feedback::where('student_id', $user->id)
            ->with('givenBy')
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('dashboard', [
            'stats' => $stats,
            'institutionBreakdown' => collect(),
            'recentLogbookEntries' => $recentLogbookEntries,
            'recentAssessments' => collect(),
            'recentFeedback' => $recentFeedback,
            'profile' => $user->profileSummary($activeRotation),
        ]);
    }
}
