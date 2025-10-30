<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminRegistrationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function first_admin_can_register_when_no_users_exist(): void
    {
        $response = $this->get(route('register'));
        $response->assertOk();

        $response = $this->post(route('register'), [
            'name' => 'Administrateur Test',
            'email' => 'admin@example.com',
            'phone' => '+22899000000',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'role' => User::ROLE_ADMIN,
        ]);

        $this->assertAuthenticated();
    }

    #[Test]
    public function registration_is_blocked_once_an_admin_exists(): void
    {
        User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->get(route('register'))->assertForbidden();
        $this->post(route('register'), [
            'name' => 'Second Admin',
            'email' => 'another@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertForbidden();
    }
}
