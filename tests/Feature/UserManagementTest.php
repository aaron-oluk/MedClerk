<?php

namespace Tests\Feature;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function makeInstitution(): Institution
    {
        return Institution::create(['name' => 'Test University', 'slug' => 'test-university-'.uniqid()]);
    }

    public function test_admin_only_sees_users_in_their_own_institution(): void
    {
        $institutionA = $this->makeInstitution();
        $institutionB = $this->makeInstitution();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'institution_id' => $institutionA->id]);
        $studentA = User::factory()->student()->create(['institution_id' => $institutionA->id, 'name' => 'Student In A']);
        $studentB = User::factory()->student()->create(['institution_id' => $institutionB->id, 'name' => 'Student In B']);

        $response = $this->actingAs($admin)->get('/users');

        $response->assertOk();
        $response->assertSee('Student In A');
        $response->assertDontSee('Student In B');
    }

    public function test_superadmin_sees_users_across_all_institutions(): void
    {
        $institutionA = $this->makeInstitution();
        $institutionB = $this->makeInstitution();
        $superadmin = User::factory()->create(['role' => User::ROLE_SUPERADMIN]);
        $studentA = User::factory()->student()->create(['institution_id' => $institutionA->id, 'name' => 'Student In A']);
        $studentB = User::factory()->student()->create(['institution_id' => $institutionB->id, 'name' => 'Student In B']);

        $response = $this->actingAs($superadmin)->get('/users');

        $response->assertOk();
        $response->assertSee('Student In A');
        $response->assertSee('Student In B');
    }

    public function test_admin_cannot_edit_a_user_outside_their_institution(): void
    {
        $institutionA = $this->makeInstitution();
        $institutionB = $this->makeInstitution();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'institution_id' => $institutionA->id]);
        $target = User::factory()->student()->create(['institution_id' => $institutionB->id]);

        $response = $this->actingAs($admin)->get("/users/{$target->id}/edit");
        $response->assertForbidden();

        $response = $this->actingAs($admin)->patch("/users/{$target->id}", ['role' => 'lecturer']);
        $response->assertForbidden();
    }

    public function test_admin_can_edit_a_user_in_their_own_institution(): void
    {
        $institution = $this->makeInstitution();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'institution_id' => $institution->id]);
        $target = User::factory()->student()->create(['institution_id' => $institution->id]);

        $response = $this->actingAs($admin)->patch("/users/{$target->id}", [
            'role' => 'lecturer',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/users');
        $this->assertSame('lecturer', $target->fresh()->role);
    }

    public function test_admin_cannot_grant_superadmin(): void
    {
        $institution = $this->makeInstitution();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'institution_id' => $institution->id]);
        $target = User::factory()->student()->create(['institution_id' => $institution->id]);

        $response = $this->actingAs($admin)->patch("/users/{$target->id}", [
            'role' => 'superadmin',
            'is_active' => '1',
        ]);

        $response->assertForbidden();
        $this->assertSame('student', $target->fresh()->role);
    }

    public function test_admin_cannot_move_a_user_to_a_different_institution(): void
    {
        $institutionA = $this->makeInstitution();
        $institutionB = $this->makeInstitution();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'institution_id' => $institutionA->id]);
        $target = User::factory()->student()->create(['institution_id' => $institutionA->id]);

        $this->actingAs($admin)->patch("/users/{$target->id}", [
            'role' => 'student',
            'institution_id' => $institutionB->id,
            'is_active' => '1',
        ]);

        $this->assertSame($institutionA->id, $target->fresh()->institution_id);
    }

    public function test_superadmin_can_grant_superadmin(): void
    {
        $institution = $this->makeInstitution();
        $superadmin = User::factory()->create(['role' => User::ROLE_SUPERADMIN]);
        $target = User::factory()->student()->create(['institution_id' => $institution->id]);

        $response = $this->actingAs($superadmin)->patch("/users/{$target->id}", [
            'role' => 'superadmin',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/users');
        $this->assertSame('superadmin', $target->fresh()->role);
    }

    public function test_student_cannot_access_user_management(): void
    {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get('/users');

        $response->assertForbidden();
    }

    public function test_inactive_user_cannot_log_in_via_web(): void
    {
        $user = User::factory()->create(['is_active' => false, 'password' => bcrypt('password')]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_log_in_via_api(): void
    {
        $user = User::factory()->create(['is_active' => false, 'password' => bcrypt('password')]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'test-device',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('email');
    }
}
