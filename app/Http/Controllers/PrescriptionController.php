<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Screening;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrescriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,clinician']);
    }

    /**
     * Show the form for creating a prescription for a screening.
     */
    public function create(Screening $screening): View
    {
        return view('prescriptions.create', compact('screening'));
    }

    /**
     * Store a new prescription.
     */
    public function store(Request $request, Screening $screening): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['screening_id'] = $screening->id;

        if (auth()->check() && empty($data['prescribed_by'])) {
            $data['prescribed_by'] = auth()->id();
        }

        $screening->prescriptions()->create($data);

        return redirect()
            ->route('screenings.show', $screening)
            ->with('status', 'Prescription ajoutée.');
    }

    /**
     * Show the form for editing a prescription.
     */
    public function edit(Screening $screening, Prescription $prescription): View
    {
        $this->ensureBelongsToScreening($screening, $prescription);

        return view('prescriptions.edit', compact('screening', 'prescription'));
    }

    /**
     * Update the specified prescription.
     */
    public function update(Request $request, Screening $screening, Prescription $prescription): RedirectResponse
    {
        $this->ensureBelongsToScreening($screening, $prescription);

        $data = $this->validatedData($request);

        if (auth()->check() && empty($data['prescribed_by'])) {
            $data['prescribed_by'] = auth()->id();
        }

        $prescription->update($data);

        return redirect()
            ->route('screenings.show', $screening)
            ->with('status', 'Prescription mise à jour.');
    }

    /**
     * Remove the specified prescription.
     */
    public function destroy(Screening $screening, Prescription $prescription): RedirectResponse
    {
        $this->ensureBelongsToScreening($screening, $prescription);

        $prescription->delete();

        return redirect()
            ->route('screenings.show', $screening)
            ->with('status', 'Prescription supprimée.');
    }

    /**
     * Ensure a prescription belongs to the provided screening.
     */
    private function ensureBelongsToScreening(Screening $screening, Prescription $prescription): void
    {
        if ($prescription->screening_id !== $screening->id) {
            abort(404);
        }
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'medication_name' => ['required', 'string', 'max:255'],
            'dosage' => ['nullable', 'string', 'max:255'],
            'frequency' => ['nullable', 'string', 'max:255'],
            'duration' => ['nullable', 'string', 'max:255'],
            'instructions' => ['nullable', 'string'],
            'prescribed_by' => ['nullable', 'exists:users,id'],
        ]);
    }
}
