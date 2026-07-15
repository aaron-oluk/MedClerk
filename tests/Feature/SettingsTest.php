<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/settings');

        $response->assertOk();
    }

    public function test_notification_preference_can_be_updated(): void
    {
        $user = User::factory()->create(['email_notifications_enabled' => true]);

        $response = $this
            ->actingAs($user)
            ->patch('/settings', []);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/settings');

        $this->assertFalse($user->refresh()->email_notifications_enabled);
    }

    public function test_guests_cannot_view_settings(): void
    {
        $response = $this->get('/settings');

        $response->assertRedirect('/login');
    }
}
