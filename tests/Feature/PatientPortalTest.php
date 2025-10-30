<?php

namespace Tests\Feature;

use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PatientPortalTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function patient_can_access_portal_with_reference_and_phone(): void
    {
        $patient = Patient::create([
            'full_name' => 'Patient Portail',
            'phone' => '+22891000000',
            'status' => 'sous surveillance',
            'registration_channel' => 'self_registration',
            'is_self_registered' => true,
        ]);

        $response = $this->from(route('patient-portal.login'))->post(route('patient-portal.authenticate'), [
            'reference_code' => $patient->reference_code,
            'phone' => $patient->phone,
        ]);

        $response->assertRedirect(route('patient-portal.dashboard'));

        $this->assertEquals($patient->id, session('patient_portal_id'));

        $this->get(route('patient-portal.dashboard'))
            ->assertOk()
            ->assertSee($patient->full_name)
            ->assertSee($patient->reference_code);
    }

    #[Test]
    public function invalid_credentials_do_not_grant_portal_access(): void
    {
        $patient = Patient::create([
            'full_name' => 'Patient Portail',
            'phone' => '+22891000000',
            'status' => 'sous surveillance',
            'registration_channel' => 'self_registration',
            'is_self_registered' => true,
        ]);

        $response = $this->from(route('patient-portal.login'))->post(route('patient-portal.authenticate'), [
            'reference_code' => $patient->reference_code,
            'phone' => '+11111111',
        ]);

        $response->assertRedirect(route('patient-portal.login'));
        $response->assertSessionHasErrors('reference_code');
        $this->assertNull(session('patient_portal_id'));
    }
}
