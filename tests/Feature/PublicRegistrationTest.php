<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PublicRegistrationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_patient_can_self_register_from_the_public_form(): void
    {
        $response = $this->post(route('registration.store'), [
            'full_name' => 'Patient Test',
            'phone' => '+22890000000',
            'village' => 'Kpélé',
            'district' => 'Agou',
            'region' => 'Plateaux',
            'preferred_language' => 'Ewé',
            'health_concerns' => 'Lésions cutanées depuis 3 mois',
            'consent' => '1',
        ]);

        $response
            ->assertRedirect(route('registration.create'))
            ->assertSessionHas('status')
            ->assertSessionHas('reference');

        $this->assertDatabaseHas('patients', [
            'full_name' => 'Patient Test',
            'registration_channel' => 'self_registration',
            'is_self_registered' => true,
            'status' => 'en_attente',
        ]);
    }

    #[Test]
    public function consent_is_required_for_self_registration(): void
    {
        $response = $this->from(route('registration.create'))->post(route('registration.store'), [
            'full_name' => 'Sans Consentement',
        ]);

        $response
            ->assertRedirect(route('registration.create'))
            ->assertSessionHasErrors('consent');

        $this->assertDatabaseMissing('patients', [
            'full_name' => 'Sans Consentement',
        ]);
    }
}
