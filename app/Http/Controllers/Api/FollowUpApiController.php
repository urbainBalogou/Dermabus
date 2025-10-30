<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FollowUp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FollowUpApiController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $followUps = FollowUp::with(['patient', 'assignee'])
            ->when($request->filled('patient_id'), fn ($query) => $query->where('patient_id', $request->integer('patient_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->orderByDesc('scheduled_for')
            ->paginate($request->integer('per_page', 20));

        return JsonResource::collection($followUps);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validatedData($request);

        $followUp = FollowUp::create(array_merge($data, [
            'created_by' => $request->user()?->getKey(),
        ]));

        return response()->json($followUp->load(['patient', 'assignee']), 201);
    }

    public function show(FollowUp $followUp): JsonResponse
    {
        return response()->json($followUp->load(['patient', 'assignee', 'screening']));
    }

    public function update(Request $request, FollowUp $followUp): JsonResponse
    {
        $data = $this->validatedData($request);

        $followUp->update($data);

        return response()->json($followUp->load(['patient', 'assignee', 'screening']));
    }

    public function destroy(FollowUp $followUp): JsonResponse
    {
        $followUp->delete();

        return response()->json(null, 204);
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'screening_id' => ['nullable', 'exists:screenings,id'],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
            'scheduled_for' => ['required', 'date'],
            'type' => ['required', 'in:medical_visit,social_visit,phone_checkin'],
            'status' => ['nullable', 'in:planned,completed,cancelled,missed'],
            'location' => ['nullable', 'string', 'max:255'],
            'contact_mode' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'outcome' => ['nullable', 'string'],
            'completed_at' => ['nullable', 'date'],
        ]);

        if (empty($data['status'])) {
            $data['status'] = FollowUp::STATUS_PLANNED;
        }

        return $data;
    }
}
