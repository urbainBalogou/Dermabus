<?php

namespace Tests\Feature;

use App\Models\FollowUp;
use App\Models\Patient;
use App\Models\Screening;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowUpManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_clinician_can_schedule_follow_up(): void
    {
        $clinician = User::factory()->create(['role' => User::ROLE_CLINICIAN]);
        $patient = Patient::create([
            'full_name' => 'Patient Demo',
            'status' => 'under_review',
        ]);
        $screening = Screening::create([
            'patient_id' => $patient->id,
            'user_id' => $clinician->id,
            'screened_on' => now()->toDateString(),
            'severity' => 'medium',
        ]);

        $response = $this->actingAs($clinician)->post(route('follow-ups.store'), [
            'patient_id' => $patient->id,
            'screening_id' => $screening->id,
            'scheduled_for' => now()->addDay()->format('Y-m-d H:i:s'),
            'type' => FollowUp::TYPE_MEDICAL,
            'status' => FollowUp::STATUS_PLANNED,
            'location' => 'Village Pilote',
            'contact_mode' => 'Visite terrain',
            'notes' => 'Préparer le suivi clinique.',
        ]);

        $response->assertRedirect(route('follow-ups.index'));

        $this->assertDatabaseHas('follow_ups', [
            'patient_id' => $patient->id,
            'screening_id' => $screening->id,
            'type' => FollowUp::TYPE_MEDICAL,
            'status' => FollowUp::STATUS_PLANNED,
        ]);
    }

    public function test_registrar_cannot_manage_follow_ups(): void
    {
        $registrar = User::factory()->create(['role' => User::ROLE_REGISTRAR]);
        $patient = Patient::create([
            'full_name' => 'Patient Terrain',
            'status' => 'under_review',
        ]);

        $this->actingAs($registrar)
            ->get(route('follow-ups.index'))
            ->assertStatus(403);

        $this->actingAs($registrar)
            ->post(route('follow-ups.store'), [
                'patient_id' => $patient->id,
                'scheduled_for' => now()->addDay()->format('Y-m-d H:i:s'),
                'type' => FollowUp::TYPE_MEDICAL,
            ])
            ->assertStatus(403);
    }
}
