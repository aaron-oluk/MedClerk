<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentLookupTest extends TestCase
{
    use RefreshDatabase;

    public function test_lecturer_can_search_students_by_registration_number(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $student = User::factory()->student()->create(['student_number' => 'REG-12345']);
        User::factory()->student()->create(['student_number' => 'REG-99999']);

        $response = $this
            ->actingAs($lecturer)
            ->get('/students/search?q=12345');

        $response->assertOk();
        $response->assertSee($student->name);
        $response->assertDontSee('REG-99999');
    }

    public function test_lecturer_can_view_a_student_profile(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $student = User::factory()->student()->create();

        $response = $this
            ->actingAs($lecturer)
            ->get("/students/{$student->id}");

        $response->assertOk();
        $response->assertSee($student->name);
        $response->assertSee($student->student_number);
    }

    public function test_student_cannot_access_the_search_page(): void
    {
        $student = User::factory()->student()->create();

        $response = $this
            ->actingAs($student)
            ->get('/students/search');

        $response->assertForbidden();
    }

    public function test_lookup_returns_not_found_for_a_non_student_user(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $otherLecturer = User::factory()->lecturer()->create();

        $response = $this
            ->actingAs($lecturer)
            ->get("/students/{$otherLecturer->id}");

        $response->assertNotFound();
    }
}
