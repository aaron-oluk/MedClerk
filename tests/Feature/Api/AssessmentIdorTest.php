<?php

namespace Tests\Feature\Api;

use App\Models\Assessment;
use App\Models\Department;
use App\Models\Institution;
use App\Models\Rotation;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AssessmentIdorTest extends TestCase
{
    use RefreshDatabase;

    protected function makeRotation(?User $supervisor = null): Rotation
    {
        $institution = Institution::create(['name' => 'Test University', 'slug' => 'test-university-'.uniqid()]);
        $department = Department::create(['institution_id' => $institution->id, 'name' => 'Medicine']);
        $student = User::factory()->student()->create(['institution_id' => $institution->id]);

        return Rotation::create([
            'institution_id' => $institution->id,
            'department_id' => $department->id,
            'student_id' => $student->id,
            'supervisor_id' => $supervisor?->id,
            'name' => 'Internal Medicine',
            'start_date' => now()->subWeek(),
            'status' => 'active',
        ]);
    }

    protected function payload(Rotation $rotation, Skill $skill): array
    {
        return [
            'rotation_id' => $rotation->id,
            'skill_id' => $skill->id,
            'score' => 8,
            'max_score' => 10,
            'assessed_at' => now()->toDateString(),
        ];
    }

    public function test_lecturer_can_assess_their_own_supervised_rotation(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $rotation = $this->makeRotation($lecturer);
        $skill = Skill::create(['name' => 'History taking']);
        Sanctum::actingAs($lecturer);

        $response = $this->postJson('/api/assessments', $this->payload($rotation, $skill));

        $response->assertCreated();
        $this->assertSame($rotation->student_id, Assessment::first()->student_id);
    }

    public function test_lecturer_cannot_assess_a_rotation_they_do_not_supervise(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $otherSupervisor = User::factory()->lecturer()->create();
        $rotation = $this->makeRotation($otherSupervisor);
        $skill = Skill::create(['name' => 'History taking']);
        Sanctum::actingAs($lecturer);

        $response = $this->postJson('/api/assessments', $this->payload($rotation, $skill));

        $response->assertForbidden();
        $this->assertSame(0, Assessment::count());
    }

    public function test_client_supplied_student_id_is_ignored(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $rotation = $this->makeRotation($lecturer);
        $otherStudent = User::factory()->student()->create();
        $skill = Skill::create(['name' => 'History taking']);
        Sanctum::actingAs($lecturer);

        $response = $this->postJson('/api/assessments', array_merge(
            $this->payload($rotation, $skill),
            ['student_id' => $otherStudent->id],
        ));

        $response->assertCreated();
        $this->assertSame($rotation->student_id, Assessment::first()->student_id);
        $this->assertNotSame($otherStudent->id, Assessment::first()->student_id);
    }

    public function test_admin_cannot_assess_a_rotation_outside_their_institution(): void
    {
        $rotation = $this->makeRotation();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $skill = Skill::create(['name' => 'History taking']);
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/assessments', $this->payload($rotation, $skill));

        $response->assertForbidden();
        $this->assertSame(0, Assessment::count());
    }
}
