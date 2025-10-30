<?php

namespace App\Http\Middleware;

use App\Models\FollowUp;
use App\Models\Patient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class EnsurePatientPortalAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $patientId = $request->session()->get('patient_portal_id');

        if (! $patientId) {
            throw new AccessDeniedHttpException('Connexion patient requise.');
        }

        $patient = Patient::with([
            'screenings' => fn ($query) => $query->with(['agent', 'prescriptions'])->latest('screened_on'),
            'followUps' => fn ($query) => $query->where('status', FollowUp::STATUS_PLANNED)->with('assignee')->orderBy('scheduled_for'),
        ])
            ->find($patientId);

        if (! $patient || ! $patient->portal_enabled) {
            $request->session()->forget('patient_portal_id');
            throw new AccessDeniedHttpException('Votre session n\'est plus valide.');
        }

        View::share('portalPatient', $patient);
        $request->attributes->set('portalPatient', $patient);

        return $next($request);
    }
}
