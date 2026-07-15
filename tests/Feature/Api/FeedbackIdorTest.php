<?php

namespace Tests\Feature\Api;

use App\Models\Assessment;
use App\Models\Department;
use App\Models\Feedback;
use App\Models\Institution;
use App\Models\Rotation;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FeedbackIdorTest extends TestCase
{
    use RefreshDatabase;

    protected function makeAssessment(User $assessor): Assessment
    {
        $institution = Institution::create(['name' => 'Test University', 'slug' => 'test-university-'.uniqid()]);
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

    public function test_lecturer_can_give_feedback_on_their_own_assessment(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $assessment = $this->makeAssessment($lecturer);
        Sanctum::actingAs($lecturer);

        $response = $this->postJson('/api/feedback', [
            'assessment_id' => $assessment->id,
            'strengths' => 'Clear communication',
        ]);

        $response->assertCreated();
        $this->assertSame($assessment->student_id, Feedback::first()->student_id);
    }

    public function test_lecturer_cannot_give_feedback_on_someone_elses_assessment(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $otherLecturer = User::factory()->lecturer()->create();
        $assessment = $this->makeAssessment($otherLecturer);
        Sanctum::actingAs($lecturer);

        $response = $this->postJson('/api/feedback', [
            'assessment_id' => $assessment->id,
            'strengths' => 'Clear communication',
        ]);

        $response->assertForbidden();
        $this->assertSame(0, Feedback::count());
    }

    public function test_client_supplied_student_id_is_ignored(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $assessment = $this->makeAssessment($lecturer);
        $otherStudent = User::factory()->student()->create();
        Sanctum::actingAs($lecturer);

        $response = $this->postJson('/api/feedback', [
            'assessment_id' => $assessment->id,
            'student_id' => $otherStudent->id,
            'strengths' => 'Clear communication',
        ]);

        $response->assertCreated();
        $this->assertSame($assessment->student_id, Feedback::first()->student_id);
        $this->assertNotSame($otherStudent->id, Feedback::first()->student_id);
    }
}
