<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function team_member_can_authenticate_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'clinicien@example.com',
            'role' => User::ROLE_CLINICIAN,
            'password' => 'password-test',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'clinicien@example.com',
            'password' => 'password-test',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user->fresh());
        $this->assertNotNull($user->fresh()->last_login_at);
    }

    #[Test]
    public function authentication_fails_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'clinicien@example.com',
            'password' => 'password-test',
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'clinicien@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
