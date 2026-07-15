<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StudentLookupApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_lecturer_can_search_students_by_registration_number(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $student = User::factory()->student()->create(['student_number' => 'REG-12345']);
        User::factory()->student()->create(['student_number' => 'REG-99999']);
        Sanctum::actingAs($lecturer);

        $response = $this->getJson('/api/students/search?q=12345');

        $response->assertOk();
        $response->assertJsonFragment(['id' => $student->id]);
        $response->assertJsonMissing(['student_number' => 'REG-99999']);
    }

    public function test_lecturer_can_view_a_student_profile(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $student = User::factory()->student()->create();
        Sanctum::actingAs($lecturer);

        $response = $this->getJson("/api/students/{$student->id}");

        $response->assertOk();
        $response->assertJson([
            'id' => $student->id,
            'name' => $student->name,
            'student_number' => $student->student_number,
        ]);
        $response->assertJsonStructure(['programme', 'current_placement']);
    }

    public function test_student_cannot_access_the_search_endpoint(): void
    {
        $student = User::factory()->student()->create();
        Sanctum::actingAs($student);

        $response = $this->getJson('/api/students/search');

        $response->assertForbidden();
    }

    public function test_lookup_returns_not_found_for_a_non_student_user(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $otherLecturer = User::factory()->lecturer()->create();
        Sanctum::actingAs($lecturer);

        $response = $this->getJson("/api/students/{$otherLecturer->id}");

        $response->assertNotFound();
    }
}
