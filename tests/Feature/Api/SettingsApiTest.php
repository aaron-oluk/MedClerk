<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SettingsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_preference_can_be_updated(): void
    {
        $user = User::factory()->create(['email_notifications_enabled' => true]);
        Sanctum::actingAs($user);

        $response = $this->patchJson('/api/settings', [
            'email_notifications_enabled' => false,
        ]);

        $response->assertOk();
        $this->assertFalse($user->refresh()->email_notifications_enabled);
    }

    public function test_notification_preference_requires_a_boolean(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->patchJson('/api/settings', []);

        $response->assertUnprocessable();
    }

    public function test_guests_cannot_update_settings(): void
    {
        $response = $this->patchJson('/api/settings', [
            'email_notifications_enabled' => false,
        ]);

        $response->assertUnauthorized();
    }
}
