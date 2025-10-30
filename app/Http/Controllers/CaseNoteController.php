<?php

namespace App\Http\Controllers;

use App\Models\CaseNote;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CaseNoteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,clinician,social_worker']);
    }

    /**
     * Store a newly created case note.
     */
    public function store(Request $request, Patient $patient): RedirectResponse
    {
        $data = $this->validatedData($request);

        $patient->caseNotes()->create(array_merge($data, [
            'user_id' => auth()->id(),
            'noted_on' => $data['noted_on'] ?? now()->toDateString(),
        ]));

        return redirect()
            ->route('patients.show', $patient)
            ->with('status', 'Note ajoutée au dossier.');
    }

    /**
     * Show the form for editing the specified note.
     */
    public function edit(CaseNote $caseNote): View
    {
        $this->authorizeNote($caseNote);

        return view('case-notes.edit', [
            'caseNote' => $caseNote,
            'patient' => $caseNote->patient,
            'categories' => $this->categoryOptions(),
            'visibilities' => $this->visibilityOptions(),
        ]);
    }

    /**
     * Update the specified note in storage.
     */
    public function update(Request $request, CaseNote $caseNote): RedirectResponse
    {
        $this->authorizeNote($caseNote);

        $data = $this->validatedData($request, false);

        $caseNote->update($data);

        return redirect()
            ->route('patients.show', $caseNote->patient)
            ->with('status', 'Note mise à jour.');
    }

    /**
     * Remove the specified note from storage.
     */
    public function destroy(CaseNote $caseNote): RedirectResponse
    {
        $this->authorizeNote($caseNote);

        $patient = $caseNote->patient;

        $caseNote->delete();

        return redirect()
            ->route('patients.show', $patient)
            ->with('status', 'Note supprimée.');
    }

    private function validatedData(Request $request, bool $requireDate = true): array
    {
        $rules = [
            'noted_on' => [$requireDate ? 'required' : 'nullable', 'date'],
            'category' => ['required', 'in:' . implode(',', array_keys($this->categoryOptions()))],
            'visibility' => ['required', 'in:' . implode(',', array_keys($this->visibilityOptions()))],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string'],
            'next_actions' => ['nullable', 'string'],
        ];

        return $request->validate($rules);
    }

    private function categoryOptions(): array
    {
        return [
            CaseNote::CATEGORY_MEDICAL => 'Suivi médical',
            CaseNote::CATEGORY_SOCIAL => 'Accompagnement social',
            CaseNote::CATEGORY_LOGISTICS => 'Logistique / transport',
        ];
    }

    private function visibilityOptions(): array
    {
        return [
            CaseNote::VISIBILITY_TEAM => 'Équipe complète',
            CaseNote::VISIBILITY_HEALTH => 'Équipe clinique uniquement',
            CaseNote::VISIBILITY_SOCIAL => 'Cellule sociale uniquement',
        ];
    }

    private function authorizeNote(CaseNote $caseNote): void
    {
        $user = auth()->user();

        if (! $user) {
            abort(403);
        }

        if ($user->isAdmin() || $caseNote->user_id === $user->id) {
            return;
        }

        if ($caseNote->visibility === CaseNote::VISIBILITY_HEALTH && ! $user->hasRole([User::ROLE_ADMIN, User::ROLE_CLINICIAN])) {
            abort(403);
        }

        if ($caseNote->visibility === CaseNote::VISIBILITY_SOCIAL && ! $user->hasRole([User::ROLE_ADMIN, User::ROLE_SOCIAL])) {
            abort(403);
        }
    }
}
