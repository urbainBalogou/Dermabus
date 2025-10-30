<?php

namespace App\Http\Controllers;

use App\Models\CaseNote;
use App\Models\FollowUp;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,clinician,registrar,social_worker']);
    }

    /**
     * Display a listing of the patients.
     */
    public function index(Request $request): View
    {
        $patients = Patient::with('primaryAgent')
            ->withCount(['screenings'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->string('search');
                $query->where(function ($query) use ($term) {
                    $query->where('full_name', 'like', "%{$term}%")
                        ->orWhere('external_id', 'like', "%{$term}%")
                        ->orWhere('reference_code', 'like', "%{$term}%")
                        ->orWhere('village', 'like', "%{$term}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create(): View
    {
        $agents = User::orderBy('name')->pluck('name', 'id');

        return view('patients.create', compact('agents'));
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if (array_key_exists('is_reintegrated', $data)) {
            $data['is_reintegrated'] = (bool) $data['is_reintegrated'];
        }

        if (array_key_exists('is_self_registered', $data)) {
            $data['is_self_registered'] = (bool) $data['is_self_registered'];
        }

        if (array_key_exists('portal_enabled', $data)) {
            $data['portal_enabled'] = (bool) $data['portal_enabled'];
        }

        $data['registration_channel'] = $data['registration_channel'] ?? 'field_agent';

        if (auth()->check() && empty($data['primary_agent_id'])) {
            $data['primary_agent_id'] = auth()->id();
        }

        $patient = Patient::create($data);

        return redirect()
            ->route('patients.show', $patient)
            ->with('status', 'Patient enregistré avec succès.');
    }

    /**
     * Display the specified patient.
     */
    public function show(Patient $patient): View
    {
        $patient->load([
            'screenings' => fn ($query) => $query->with(['agent', 'prescriptions'])->latest('screened_on'),
            'primaryAgent',
            'prescriptions.screening',
            'followUps' => fn ($query) => $query->with(['assignee', 'screening'])->latest('scheduled_for'),
            'caseNotes' => fn ($query) => $query->with('author'),
        ]);

        $team = User::orderBy('name')->pluck('name', 'id');

        return view('patients.show', [
            'patient' => $patient,
            'team' => $team,
            'followUpTypes' => [
                FollowUp::TYPE_MEDICAL => 'Visite médicale',
                FollowUp::TYPE_SOCIAL => 'Visite sociale',
                FollowUp::TYPE_PHONE => 'Appel de suivi',
            ],
            'followUpStatuses' => [
                FollowUp::STATUS_PLANNED => 'Planifié',
                FollowUp::STATUS_COMPLETED => 'Terminé',
                FollowUp::STATUS_CANCELLED => 'Annulé',
                FollowUp::STATUS_MISSED => 'Manqué',
            ],
            'caseNoteCategories' => [
                CaseNote::CATEGORY_MEDICAL => 'Suivi médical',
                CaseNote::CATEGORY_SOCIAL => 'Accompagnement social',
                CaseNote::CATEGORY_LOGISTICS => 'Logistique / transport',
            ],
            'caseNoteVisibilities' => [
                CaseNote::VISIBILITY_TEAM => 'Équipe complète',
                CaseNote::VISIBILITY_HEALTH => 'Équipe clinique uniquement',
                CaseNote::VISIBILITY_SOCIAL => 'Cellule sociale uniquement',
            ],
        ]);
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Patient $patient): View
    {
        $agents = User::orderBy('name')->pluck('name', 'id');

        return view('patients.edit', compact('patient', 'agents'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, Patient $patient): RedirectResponse
    {
        $data = $this->validatedData($request, $patient->id);

        if (array_key_exists('is_reintegrated', $data)) {
            $data['is_reintegrated'] = (bool) $data['is_reintegrated'];
        }

        if (array_key_exists('is_self_registered', $data)) {
            $data['is_self_registered'] = (bool) $data['is_self_registered'];
        }

        if (array_key_exists('portal_enabled', $data)) {
            $data['portal_enabled'] = (bool) $data['portal_enabled'];
        }

        $data['registration_channel'] = $data['registration_channel'] ?? $patient->registration_channel;

        $patient->update($data);

        return redirect()
            ->route('patients.show', $patient)
            ->with('status', 'Profil patient mis à jour.');
    }

    /**
     * Remove the specified patient from storage.
     */
    public function destroy(Patient $patient): RedirectResponse
    {
        $patient->delete();

        return redirect()
            ->route('patients.index')
            ->with('status', 'Patient supprimé.');
    }

    /**
     * Validate the incoming request data.
     */
    private function validatedData(Request $request, ?int $patientId = null): array
    {
        return $request->validate([
            'external_id' => ['nullable', 'string', 'max:255', 'unique:patients,external_id,' . $patientId],
            'reference_code' => ['nullable', 'string', 'max:255', 'unique:patients,reference_code,' . $patientId],
            'portal_code' => ['nullable', 'string', 'max:255', 'unique:patients,portal_code,' . $patientId],
            'portal_enabled' => ['sometimes', 'boolean'],
            'portal_last_access_at' => ['nullable', 'date'],
            'primary_agent_id' => ['nullable', 'exists:users,id'],
            'full_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:female,male,other'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address_line' => ['nullable', 'string', 'max:255'],
            'village' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
            'medical_history' => ['nullable', 'string'],
            'psychosocial_notes' => ['nullable', 'string'],
            'care_plan' => ['nullable', 'string'],
            'is_reintegrated' => ['sometimes', 'boolean'],
            'reintegrated_at' => ['nullable', 'date'],
            'gps_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'gps_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'status' => ['nullable', 'string', 'max:255'],
            'registration_channel' => ['nullable', 'string', 'max:255'],
            'consent_signed_at' => ['nullable', 'date'],
            'preferred_language' => ['nullable', 'string', 'max:100'],
            'self_report_notes' => ['nullable', 'string'],
            'is_self_registered' => ['sometimes', 'boolean'],
        ]);
    }
}
