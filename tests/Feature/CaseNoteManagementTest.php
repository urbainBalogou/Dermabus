<?php

namespace Tests\Feature;

use App\Models\CaseNote;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CaseNoteManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_social_worker_can_add_case_note(): void
    {
        $social = User::factory()->create(['role' => User::ROLE_SOCIAL]);
        $patient = Patient::create([
            'full_name' => 'Patient Social',
            'status' => 'under_follow_up',
        ]);

        $response = $this->actingAs($social)->post(route('patients.case-notes.store', $patient), [
            'noted_on' => now()->format('Y-m-d'),
            'category' => CaseNote::CATEGORY_SOCIAL,
            'visibility' => CaseNote::VISIBILITY_TEAM,
            'title' => 'Visite à domicile',
            'summary' => 'La situation familiale est stable, poursuite du suivi psychosocial.',
        ]);

        $response->assertRedirect(route('patients.show', $patient));

        $this->assertDatabaseHas('case_notes', [
            'patient_id' => $patient->id,
            'category' => CaseNote::CATEGORY_SOCIAL,
            'title' => 'Visite à domicile',
        ]);
    }

    public function test_registrar_cannot_add_case_note(): void
    {
        $registrar = User::factory()->create(['role' => User::ROLE_REGISTRAR]);
        $patient = Patient::create([
            'full_name' => 'Patient Sans Note',
            'status' => 'under_follow_up',
        ]);

        $this->actingAs($registrar)
            ->post(route('patients.case-notes.store', $patient), [
                'noted_on' => now()->format('Y-m-d'),
                'category' => CaseNote::CATEGORY_MEDICAL,
                'visibility' => CaseNote::VISIBILITY_TEAM,
                'title' => 'Test',
                'summary' => 'Tentative non autorisée.',
            ])
            ->assertStatus(403);
    }
}
