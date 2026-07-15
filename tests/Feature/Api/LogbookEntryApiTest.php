<?php

namespace Tests\Feature\Api;

use App\Models\Department;
use App\Models\Institution;
use App\Models\LogbookEntry;
use App\Models\Rotation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogbookEntryApiTest extends TestCase
{
    use RefreshDatabase;

    protected function makeActiveRotation(User $student): Rotation
    {
        $institution = Institution::create(['name' => 'Test University', 'slug' => 'test-university-'.uniqid()]);
        $department = Department::create(['institution_id' => $institution->id, 'name' => 'Medicine']);

        return Rotation::create([
            'institution_id' => $institution->id,
            'department_id' => $department->id,
            'student_id' => $student->id,
            'name' => 'Internal Medicine',
            'start_date' => now()->subWeek(),
            'status' => 'active',
        ]);
    }

    public function test_entry_cannot_be_created_without_consent(): void
    {
        $student = User::factory()->student()->create();
        $rotation = $this->makeActiveRotation($student);
        Sanctum::actingAs($student);

        $response = $this->postJson('/api/logbook-entries', [
            'rotation_id' => $rotation->id,
            'encounter_date' => now()->toDateString(),
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('consent_confirmed');
        $this->assertSame(0, LogbookEntry::count());
    }

    public function test_entry_can_be_created_with_consent_confirmed(): void
    {
        $student = User::factory()->student()->create();
        $rotation = $this->makeActiveRotation($student);
        Sanctum::actingAs($student);

        $response = $this->postJson('/api/logbook-entries', [
            'rotation_id' => $rotation->id,
            'encounter_date' => now()->toDateString(),
            'consent_confirmed' => true,
        ]);

        $response->assertCreated();

        $entry = LogbookEntry::first();
        $this->assertTrue($entry->consent_confirmed);
        $this->assertNotNull($entry->consent_confirmed_at);
    }

    public function test_lecturer_cannot_create_a_logbook_entry(): void
    {
        $student = User::factory()->student()->create();
        $rotation = $this->makeActiveRotation($student);
        $lecturer = User::factory()->lecturer()->create(['institution_id' => $rotation->institution_id]);
        Sanctum::actingAs($lecturer);

        $response = $this->postJson('/api/logbook-entries', [
            'rotation_id' => $rotation->id,
            'encounter_date' => now()->toDateString(),
            'consent_confirmed' => true,
        ]);

        $response->assertForbidden();
        $this->assertSame(0, LogbookEntry::count());
    }

    public function test_admin_cannot_create_a_logbook_entry(): void
    {
        $student = User::factory()->student()->create();
        $rotation = $this->makeActiveRotation($student);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'institution_id' => $rotation->institution_id]);
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/logbook-entries', [
            'rotation_id' => $rotation->id,
            'encounter_date' => now()->toDateString(),
            'consent_confirmed' => true,
        ]);

        $response->assertForbidden();
        $this->assertSame(0, LogbookEntry::count());
    }
}
