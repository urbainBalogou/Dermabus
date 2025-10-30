<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PatientPortalAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('patient.guest')->only(['create', 'store']);
        $this->middleware('patient.auth')->only('destroy');
    }

    public function create(): View
    {
        return view('patient-portal.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'reference_code' => ['required', 'string'],
            'phone' => ['required', 'string'],
        ]);

        $patient = Patient::where('reference_code', $credentials['reference_code'])
            ->where('portal_enabled', true)
            ->where(function ($query) use ($credentials) {
                $query->where('phone', $credentials['phone'])
                    ->orWhere('emergency_contact_phone', $credentials['phone']);
            })
            ->first();

        if (! $patient) {
            return back()
                ->withErrors(['reference_code' => 'Les informations fournies ne correspondent à aucun dossier actif.'])
                ->onlyInput('reference_code');
        }

        $request->session()->regenerate();
        $request->session()->put('patient_portal_id', $patient->id);

        $patient->forceFill([
            'portal_last_access_at' => now(),
        ])->save();

        return redirect()->route('patient-portal.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('patient_portal_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('patient-portal.login');
    }
}
