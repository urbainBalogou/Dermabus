<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\Patient;
use App\Models\Screening;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,clinician,social_worker']);
    }

    /**
     * Display a listing of the follow-ups.
     */
    public function index(Request $request): View
    {
        $timeline = $request->string('timeline', 'upcoming');

        $followUps = FollowUp::with(['patient', 'assignee', 'screening'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('assigned_user_id'), fn ($query) => $query->where('assigned_user_id', $request->integer('assigned_user_id')))
            ->when($timeline === 'upcoming', fn ($query) => $query->whereDate('scheduled_for', '>=', now()->startOfDay()))
            ->when($timeline === 'past', fn ($query) => $query->whereDate('scheduled_for', '<', now()->startOfDay()))
            ->when($timeline === 'past', fn ($query) => $query->orderByDesc('scheduled_for'), fn ($query) => $query->orderBy('scheduled_for'))
            ->paginate(10)
            ->withQueryString();

        $team = User::orderBy('name')->pluck('name', 'id');

        return view('follow-ups.index', [
            'followUps' => $followUps,
            'team' => $team,
            'timeline' => $timeline,
            'statusOptions' => $this->statusOptions(),
            'typeOptions' => $this->typeOptions(),
        ]);
    }

    /**
     * Show the form for creating a new follow-up.
     */
    public function create(Request $request): View
    {
        $patients = Patient::orderBy('full_name')->pluck('full_name', 'id');
        $team = User::orderBy('name')->pluck('name', 'id');
        $selectedPatient = $request->integer('patient_id');
        $relatedScreenings = $selectedPatient
            ? Screening::where('patient_id', $selectedPatient)->orderByDesc('screened_on')->get()
            : collect();

        return view('follow-ups.create', [
            'patients' => $patients,
            'team' => $team,
            'selectedPatient' => $selectedPatient,
            'relatedScreenings' => $relatedScreenings,
            'typeOptions' => $this->typeOptions(),
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    /**
     * Store a newly created follow-up.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        $followUp = FollowUp::create(array_merge($data, [
            'created_by' => auth()->id(),
            'completed_at' => $this->resolveCompletedAt($data),
        ]));

        return redirect()
            ->route('follow-ups.index')
            ->with('status', 'Suivi planifié avec succès.');
    }

    /**
     * Show the form for editing the specified follow-up.
     */
    public function edit(FollowUp $followUp): View
    {
        $patients = Patient::orderBy('full_name')->pluck('full_name', 'id');
        $team = User::orderBy('name')->pluck('name', 'id');
        $relatedScreenings = Screening::where('patient_id', $followUp->patient_id)->orderByDesc('screened_on')->get();

        return view('follow-ups.edit', [
            'followUp' => $followUp,
            'patients' => $patients,
            'team' => $team,
            'relatedScreenings' => $relatedScreenings,
            'typeOptions' => $this->typeOptions(),
            'statusOptions' => $this->statusOptions(),
            'selectedPatient' => $followUp->patient_id,
        ]);
    }

    /**
     * Update the specified follow-up in storage.
     */
    public function update(Request $request, FollowUp $followUp): RedirectResponse
    {
        $data = $this->validatedData($request);

        $followUp->update(array_merge($data, [
            'completed_at' => $this->resolveCompletedAt($data, $followUp->completed_at),
        ]));

        return redirect()
            ->route('follow-ups.index')
            ->with('status', 'Suivi mis à jour.');
    }

    /**
     * Remove the specified follow-up from storage.
     */
    public function destroy(FollowUp $followUp): RedirectResponse
    {
        $followUp->delete();

        return redirect()
            ->route('follow-ups.index')
            ->with('status', 'Suivi supprimé.');
    }

    /**
     * Validate follow-up payload.
     */
    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'screening_id' => ['nullable', 'exists:screenings,id'],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
            'scheduled_for' => ['required', 'date'],
            'type' => ['required', 'in:' . implode(',', array_keys($this->typeOptions()))],
            'status' => ['nullable', 'in:' . implode(',', array_keys($this->statusOptions()))],
            'location' => ['nullable', 'string', 'max:255'],
            'contact_mode' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'outcome' => ['nullable', 'string'],
            'completed_at' => ['nullable', 'date'],
        ]);

        if (empty($data['status'])) {
            $data['status'] = FollowUp::STATUS_PLANNED;
        }

        if (empty($data['completed_at'])) {
            $data['completed_at'] = null;
        }

        return $data;
    }

    /**
     * Compute the completion timestamp depending on status.
     */
    private function resolveCompletedAt(array $data, ?string $existing = null): ?string
    {
        if (in_array($data['status'], [FollowUp::STATUS_COMPLETED], true) && empty($data['completed_at'])) {
            return now()->toDateTimeString();
        }

        if (! in_array($data['status'], [FollowUp::STATUS_COMPLETED], true)) {
            return null;
        }

        return $data['completed_at'] ?? $existing;
    }

    private function typeOptions(): array
    {
        return [
            FollowUp::TYPE_MEDICAL => 'Visite médicale',
            FollowUp::TYPE_SOCIAL => 'Visite sociale',
            FollowUp::TYPE_PHONE => 'Appel de suivi',
        ];
    }

    private function statusOptions(): array
    {
        return [
            FollowUp::STATUS_PLANNED => 'Planifié',
            FollowUp::STATUS_COMPLETED => 'Terminé',
            FollowUp::STATUS_CANCELLED => 'Annulé',
            FollowUp::STATUS_MISSED => 'Manqué',
        ];
    }
}
