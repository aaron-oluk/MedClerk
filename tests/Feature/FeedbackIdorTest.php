<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\Department;
use App\Models\Feedback;
use App\Models\Institution;
use App\Models\Rotation;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedbackIdorTest extends TestCase
{
    use RefreshDatabase;

    protected function makeAssessment(User $assessor, ?int $institutionId = null): Assessment
    {
        $institution = $institutionId
            ? Institution::find($institutionId)
            : Institution::create(['name' => 'Test University', 'slug' => 'test-university-'.uniqid()]);
        $department = Department::create(['institution_id' => $institution->id, 'name' => 'Medicine']);
        $student = User::factory()->student()->create(['institution_id' => $institution->id]);
        $rotation = Rotation::create([
            'institution_id' => $institution->id,
            'department_id' => $department->id,
            'student_id' => $student->id,
            'supervisor_id' => $assessor->id,
            'name' => 'Internal Medicine',
            'start_date' => now()->subWeek(),
            'status' => 'active',
        ]);
        $skill = Skill::create(['name' => 'History taking']);

        return Assessment::create([
            'student_id' => $student->id,
            'skill_id' => $skill->id,
            'rotation_id' => $rotation->id,
            'assessor_id' => $assessor->id,
            'score' => 8,
            'max_score' => 10,
            'assessed_at' => now(),
        ]);
    }

    protected function payload(Assessment $assessment): array
    {
        return [
            'assessment_id' => $assessment->id,
            'strengths' => 'Clear communication',
            'areas_to_improve' => 'Documentation speed',
        ];
    }

    public function test_lecturer_can_give_feedback_on_their_own_assessment(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $assessment = $this->makeAssessment($lecturer);

        $response = $this->actingAs($lecturer)->post('/feedback', $this->payload($assessment));

        $response->assertRedirect('/feedback');
        $this->assertSame(1, Feedback::count());
        $this->assertSame($lecturer->id, Feedback::first()->given_by);
    }

    public function test_lecturer_cannot_give_feedback_on_someone_elses_assessment(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $otherLecturer = User::factory()->lecturer()->create();
        $assessment = $this->makeAssessment($otherLecturer);

        $response = $this->actingAs($lecturer)->post('/feedback', $this->payload($assessment));

        $response->assertForbidden();
        $this->assertSame(0, Feedback::count());
    }

    public function test_admin_cannot_give_feedback_outside_their_institution(): void
    {
        $assessment = $this->makeAssessment(User::factory()->lecturer()->create());
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $response = $this->actingAs($admin)->post('/feedback', $this->payload($assessment));

        $response->assertForbidden();
        $this->assertSame(0, Feedback::count());
    }

    public function test_admin_can_give_feedback_within_their_institution(): void
    {
        $assessor = User::factory()->lecturer()->create();
        $assessment = $this->makeAssessment($assessor);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'institution_id' => $assessment->rotation->institution_id]);

        $response = $this->actingAs($admin)->post('/feedback', $this->payload($assessment));

        $response->assertRedirect('/feedback');
        $this->assertSame(1, Feedback::count());
    }
}
