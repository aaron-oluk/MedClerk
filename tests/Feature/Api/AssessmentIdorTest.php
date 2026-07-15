<?php

namespace Tests\Feature\Api;

use App\Models\Assessment;
use App\Models\ClinicalSign;
use App\Models\ClinicalSystem;
use App\Models\Department;
use App\Models\Institution;
use App\Models\LogbookEntry;
use App\Models\Rotation;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AssessmentIdorTest extends TestCase
{
    use RefreshDatabase;

    protected function makeAssessableLog(User $supervisor): LogbookEntry
    {
        $institution = Institution::create(['name' => 'Test University', 'slug' => 'test-university-'.uniqid()]);
        $department = Department::create(['institution_id' => $institution->id, 'name' => 'Medicine']);
        $student = User::factory()->student()->create(['institution_id' => $institution->id]);
        $rotation = Rotation::create([
            'institution_id' => $institution->id,
            'department_id' => $department->id,
            'student_id' => $student->id,
            'supervisor_id' => $supervisor->id,
            'name' => 'Internal Medicine',
            'start_date' => now()->subWeek(),
            'status' => 'active',
        ]);
        $skill = Skill::create(['name' => 'History taking']);

        return LogbookEntry::create([
            'rotation_id' => $rotation->id,
            'student_id' => $student->id,
            'skill_id' => $skill->id,
            'encounter_date' => now()->toDateString(),
            'consent_confirmed' => true,
            'consent_confirmed_at' => now(),
        ]);
    }

    protected function payload(LogbookEntry $log): array
    {
        return [
            'logbook_entry_id' => $log->id,
            'score' => 8,
            'max_score' => 10,
            'assessed_at' => now()->toDateString(),
        ];
    }

    public function test_lecturer_can_assess_their_own_students_logged_encounter(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $log = $this->makeAssessableLog($lecturer);
        Sanctum::actingAs($lecturer);

        $response = $this->postJson('/api/assessments', $this->payload($log));

        $response->assertCreated();
        $this->assertSame($log->student_id, Assessment::first()->student_id);
        $this->assertSame($log->id, Assessment::first()->logbook_entry_id);
    }

    public function test_lecturer_cannot_assess_a_log_they_do_not_supervise(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $otherSupervisor = User::factory()->lecturer()->create();
        $log = $this->makeAssessableLog($otherSupervisor);
        Sanctum::actingAs($lecturer);

        $response = $this->postJson('/api/assessments', $this->payload($log));

        $response->assertForbidden();
        $this->assertSame(0, Assessment::count());
    }

    public function test_client_supplied_student_id_is_ignored(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $log = $this->makeAssessableLog($lecturer);
        $otherStudent = User::factory()->student()->create();
        Sanctum::actingAs($lecturer);

        $response = $this->postJson('/api/assessments', array_merge(
            $this->payload($log),
            ['student_id' => $otherStudent->id],
        ));

        $response->assertCreated();
        $this->assertSame($log->student_id, Assessment::first()->student_id);
        $this->assertNotSame($otherStudent->id, Assessment::first()->student_id);
    }

    public function test_admin_cannot_create_an_assessment_at_all(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $log = $this->makeAssessableLog($lecturer);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'institution_id' => $log->rotation->institution_id]);
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/assessments', $this->payload($log));

        $response->assertForbidden();
        $this->assertSame(0, Assessment::count());
    }

    public function test_cannot_assess_a_log_with_no_skill(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $log = $this->makeAssessableLog($lecturer);
        $system = ClinicalSystem::create(['name' => 'Cardiovascular']);
        $sign = ClinicalSign::create(['clinical_system_id' => $system->id, 'name' => 'Test sign']);
        $log->update(['skill_id' => null, 'clinical_sign_id' => $sign->id]);
        Sanctum::actingAs($lecturer);

        $response = $this->postJson('/api/assessments', $this->payload($log));

        $response->assertStatus(422);
        $this->assertSame(0, Assessment::count());
    }
}
