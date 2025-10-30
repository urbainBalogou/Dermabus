<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CaseNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CaseNoteApiController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $notes = CaseNote::with(['patient', 'author'])
            ->when($request->filled('patient_id'), fn ($query) => $query->where('patient_id', $request->integer('patient_id')))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')))
            ->latest('noted_on')
            ->paginate($request->integer('per_page', 20));

        return JsonResource::collection($notes);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validatedData($request, requireDate: true, requirePatient: true);

        $note = CaseNote::create(array_merge($data, [
            'user_id' => $request->user()?->getKey(),
        ]));

        return response()->json($note->load(['patient', 'author']), 201);
    }

    public function show(CaseNote $caseNote): JsonResponse
    {
        return response()->json($caseNote->load(['patient', 'author']));
    }

    public function update(Request $request, CaseNote $caseNote): JsonResponse
    {
        $data = $this->validatedData($request, requireDate: false, requirePatient: false);

        $caseNote->update($data);

        return response()->json($caseNote->load(['patient', 'author']));
    }

    public function destroy(CaseNote $caseNote): JsonResponse
    {
        $caseNote->delete();

        return response()->json(null, 204);
    }

    private function validatedData(Request $request, bool $requireDate = true, bool $requirePatient = true): array
    {
        return $request->validate([
            'patient_id' => [$requirePatient ? 'required' : 'sometimes', 'exists:patients,id'],
            'noted_on' => [$requireDate ? 'required' : 'nullable', 'date'],
            'category' => ['required', 'in:medical,social,logistics'],
            'visibility' => ['required', 'in:team,health_only,social_only'],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string'],
            'next_actions' => ['nullable', 'string'],
        ]);
    }
}
