<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the patients.
     */
    public function index(Request $request): View
    {
        $patients = Patient::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->string('search');
                $query->where(function ($query) use ($term) {
                    $query->where('full_name', 'like', "%{$term}%")
                        ->orWhere('external_id', 'like', "%{$term}%")
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
        return view('patients.create');
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

        $data['registration_channel'] = $data['registration_channel'] ?? 'field_agent';

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
        $patient->load(['screenings' => fn ($query) => $query->latest('screened_on')]);

        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Patient $patient): View
    {
        return view('patients.edit', compact('patient'));
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
