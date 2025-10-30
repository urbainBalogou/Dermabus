<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Screening;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ScreeningApiController extends Controller
{
    /**
     * Display a listing of screenings.
     */
    public function index(Request $request): ResourceCollection
    {
        $screenings = Screening::with('patient')
            ->when($request->filled('patient_id'), fn ($query) => $query->where('patient_id', $request->integer('patient_id')))
            ->when($request->filled('severity'), fn ($query) => $query->where('severity', $request->string('severity')))
            ->when($request->filled('referral_status'), fn ($query) => $query->where('referral_status', $request->string('referral_status')))
            ->latest('screened_on')
            ->paginate($request->integer('per_page', 20));

        return JsonResource::collection($screenings);
    }

    /**
     * Store a newly created screening.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $this->validatedData($request);

        if ($request->user() && empty($data['user_id'])) {
            $data['user_id'] = $request->user()->getKey();
        }

        $screening = Screening::create($data);

        return response()->json($screening->load('patient'), 201);
    }

    /**
     * Display the specified screening.
     */
    public function show(Screening $screening): JsonResponse
    {
        return response()->json($screening->load('patient', 'agent'));
    }

    /**
     * Update the specified screening.
     */
    public function update(Request $request, Screening $screening): JsonResponse
    {
        $data = $this->validatedData($request);

        if ($request->user() && empty($data['user_id'])) {
            $data['user_id'] = $request->user()->getKey();
        }

        $screening->update($data);

        return response()->json($screening->load('patient', 'agent'));
    }

    /**
     * Remove the specified screening.
     */
    public function destroy(Screening $screening): JsonResponse
    {
        $screening->delete();

        return response()->json(null, 204);
    }

    /**
     * Validate screening payload.
     */
    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'screened_on' => ['required', 'date'],
            'screening_location' => ['nullable', 'string', 'max:255'],
            'gps_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'gps_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'symptoms' => ['nullable', 'array'],
            'symptoms.*' => ['nullable', 'string'],
            'suspected_condition' => ['nullable', 'string', 'max:255'],
            'severity' => ['required', 'in:low,medium,high'],
            'risk_score' => ['nullable', 'string', 'max:255'],
            'requires_follow_up' => ['sometimes', 'boolean'],
            'follow_up_on' => ['nullable', 'date'],
            'referral_facility' => ['nullable', 'string', 'max:255'],
            'referral_status' => ['nullable', 'in:pending,in_progress,completed,declined'],
            'treatment_status' => ['nullable', 'in:pending,in_progress,completed,not_required'],
            'treatment_plan' => ['nullable', 'string'],
            'treatment_started_at' => ['nullable', 'date'],
            'treatment_completed_at' => ['nullable', 'date', 'after_or_equal:treatment_started_at'],
            'clinical_notes' => ['nullable', 'string'],
            'community_notes' => ['nullable', 'string'],
        ]);

        if ($request->has('symptoms') && is_string($request->input('symptoms'))) {
            $data['symptoms'] = $this->explodeSymptoms($request->string('symptoms'));
        } elseif (isset($data['symptoms']) && is_array($data['symptoms'])) {
            $data['symptoms'] = $this->sanitizeSymptomsArray($data['symptoms']);
        }

        if (array_key_exists('requires_follow_up', $data)) {
            $data['requires_follow_up'] = (bool) $data['requires_follow_up'];
        }

        if (empty($data['treatment_status'])) {
            $data['treatment_status'] = 'pending';
        }

        return $data;
    }

    /**
     * Convert newline separated symptoms into an array.
     */
    private function explodeSymptoms(?string $symptoms): ?array
    {
        if (! $symptoms) {
            return null;
        }

        $items = array_filter(array_map('trim', preg_split('/\r?\n/', $symptoms) ?: []));

        return $items ? array_values($items) : null;
    }

    /**
     * Remove empty entries from an array of symptoms.
     */
    private function sanitizeSymptomsArray(array $symptoms): ?array
    {
        $items = array_filter(array_map('trim', $symptoms));

        return $items ? array_values($items) : null;
    }
}
