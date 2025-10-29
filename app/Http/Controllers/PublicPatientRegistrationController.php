<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PublicPatientRegistrationController extends Controller
{
    /**
     * Display the public registration form.
     */
    public function create(): View
    {
        return view('public.register');
    }

    /**
     * Store a self-registered patient coming from the public form.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'village' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'preferred_language' => ['nullable', 'string', 'max:100'],
            'health_concerns' => ['nullable', 'string'],
            'consent' => ['accepted'],
        ]);

        $patient = Patient::create([
            'full_name' => $data['full_name'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'village' => $data['village'] ?? null,
            'district' => $data['district'] ?? null,
            'region' => $data['region'] ?? null,
            'preferred_language' => $data['preferred_language'] ?? null,
            'self_report_notes' => $data['health_concerns'] ?? null,
            'registration_channel' => 'self_registration',
            'is_self_registered' => true,
            'status' => 'en_attente',
            'consent_signed_at' => now(),
        ]);

        return redirect()
            ->route('registration.create')
            ->with('status', 'Merci ! Votre demande a bien été transmise à l’équipe DermaBus+.')
            ->with('reference', $patient->external_id);
    }
}
