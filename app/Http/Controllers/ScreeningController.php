<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Screening;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ScreeningController extends Controller
{
    /**
     * Display a listing of screenings.
     */
    public function index(Request $request): View
    {
        $screenings = Screening::with(['patient', 'agent'])
            ->when($request->filled('severity'), fn ($query) => $query->where('severity', $request->string('severity')))
            ->when($request->filled('status'), fn ($query) => $query->where('referral_status', $request->string('status')))
            ->orderByDesc('screened_on')
            ->paginate(10)
            ->withQueryString();

        return view('screenings.index', compact('screenings'));
    }

    /**
     * Show the form for creating a new screening.
     */
    public function create(Request $request): View
    {
        $patients = Patient::orderBy('full_name')->pluck('full_name', 'id');
        $selectedPatient = $request->integer('patient_id');

        return view('screenings.create', compact('patients', 'selectedPatient'));
    }

    /**
     * Store a newly created screening in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if (auth()->check() && empty($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }

        $screening = Screening::create($data);

        return redirect()
            ->route('screenings.show', $screening)
            ->with('status', 'Dépistage enregistré.');
    }

    /**
     * Display the specified screening.
     */
    public function show(Screening $screening): View
    {
        $screening->load(['patient', 'agent']);

        return view('screenings.show', compact('screening'));
    }

    /**
     * Show the form for editing the specified screening.
     */
    public function edit(Screening $screening): View
    {
        $patients = Patient::orderBy('full_name')->pluck('full_name', 'id');

        return view('screenings.edit', compact('screening', 'patients'));
    }

    /**
     * Update the specified screening in storage.
     */
    public function update(Request $request, Screening $screening): RedirectResponse
    {
        $data = $this->validatedData($request);

        if (auth()->check() && empty($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }

        $screening->update($data);

        return redirect()
            ->route('screenings.show', $screening)
            ->with('status', 'Dépistage mis à jour.');
    }

    /**
     * Remove the specified screening from storage.
     */
    public function destroy(Screening $screening): RedirectResponse
    {
        $screening->delete();

        return redirect()
            ->route('screenings.index')
            ->with('status', 'Dépistage supprimé.');
    }

    /**
     * Validate screening data.
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
            'symptoms' => ['nullable', 'string'],
            'suspected_condition' => ['nullable', 'string', 'max:255'],
            'severity' => ['required', 'in:low,medium,high'],
            'risk_score' => ['nullable', 'string', 'max:255'],
            'requires_follow_up' => ['sometimes', 'boolean'],
            'follow_up_on' => ['nullable', 'date'],
            'referral_facility' => ['nullable', 'string', 'max:255'],
            'referral_status' => ['nullable', 'in:pending,in_progress,completed,declined'],
            'clinical_notes' => ['nullable', 'string'],
            'community_notes' => ['nullable', 'string'],
        ]);

        $data['symptoms'] = $this->explodeSymptoms($data['symptoms'] ?? null);

        if (array_key_exists('requires_follow_up', $data)) {
            $data['requires_follow_up'] = (bool) $data['requires_follow_up'];
        }

        return $data;
    }

    /**
     * Transform text area input into an array of symptoms.
     */
    private function explodeSymptoms(?string $symptoms): ?array
    {
        if (! $symptoms) {
            return null;
        }

        $items = array_filter(array_map('trim', preg_split('/\r?\n/', $symptoms) ?: []));

        return $items ? array_values($items) : null;
    }
}
