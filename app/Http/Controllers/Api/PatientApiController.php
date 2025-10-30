<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PatientApiController extends Controller
{
    /**
     * Display a listing of patients.
     */
    public function index(Request $request): ResourceCollection
    {
        $patients = Patient::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->string('search');
                $query->where(function ($query) use ($term) {
                    $query->where('full_name', 'like', "%{$term}%")
                        ->orWhere('external_id', 'like', "%{$term}%")
                        ->orWhere('reference_code', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate($request->integer('per_page', 20));

        return JsonResource::collection($patients);
    }

    /**
     * Store a newly created patient.
     */
    public function store(Request $request): JsonResponse
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

        $patient = Patient::create($data);

        return response()->json($patient, 201);
    }

    /**
     * Display the specified patient.
     */
    public function show(Patient $patient): JsonResponse
    {
        $patient->load('screenings');

        return response()->json($patient);
    }

    /**
     * Update the specified patient.
     */
    public function update(Request $request, Patient $patient): JsonResponse
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

        return response()->json($patient);
    }

    /**
     * Remove the specified patient.
     */
    public function destroy(Patient $patient): JsonResponse
    {
        $patient->delete();

        return response()->json(null, 204);
    }

    /**
     * Validate patient data.
     */
    private function validatedData(Request $request, ?int $patientId = null): array
    {
        return $request->validate([
            'external_id' => ['nullable', 'string', 'max:255', 'unique:patients,external_id,' . $patientId],
            'reference_code' => ['nullable', 'string', 'max:255', 'unique:patients,reference_code,' . $patientId],
            'portal_code' => ['nullable', 'string', 'max:255', 'unique:patients,portal_code,' . $patientId],
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
            'portal_enabled' => ['sometimes', 'boolean'],
            'portal_last_access_at' => ['nullable', 'date'],
            'primary_agent_id' => ['nullable', 'exists:users,id'],
        ]);
    }
}
