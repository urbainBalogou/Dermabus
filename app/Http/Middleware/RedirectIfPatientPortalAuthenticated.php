<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfPatientPortalAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->has('patient_portal_id')) {
            return redirect()->route('patient-portal.dashboard');
        }

        return $next($request);
    }
}
