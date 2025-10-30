<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Screening;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PrescriptionManagementTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function clinician_can_add_a_prescription_to_a_screening(): void
    {
        $clinician = User::factory()->create(['role' => User::ROLE_CLINICIAN]);
        $this->actingAs($clinician);

        $patient = Patient::create([
            'full_name' => 'Patient Test',
            'status' => 'sous surveillance',
            'registration_channel' => 'field_agent',
            'phone' => '+22890000000',
        ]);

        $screening = Screening::create([
            'patient_id' => $patient->id,
            'user_id' => $clinician->id,
            'screened_on' => now()->toDateString(),
            'severity' => 'low',
            'referral_status' => 'pending',
        ]);

        $response = $this->post(route('screenings.prescriptions.store', $screening), [
            'medication_name' => 'Antibiotique',
            'dosage' => '500 mg',
            'frequency' => '2 fois par jour',
            'duration' => '7 jours',
            'instructions' => 'Prendre après les repas',
        ]);

        $response->assertRedirect(route('screenings.show', $screening));

        $this->assertDatabaseHas('prescriptions', [
            'medication_name' => 'Antibiotique',
            'screening_id' => $screening->id,
        ]);
    }
}
