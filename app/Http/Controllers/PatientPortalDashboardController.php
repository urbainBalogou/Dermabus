<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PatientPortalDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('patient.auth');
    }

    public function index(Request $request): View
    {
        return view('patient-portal.dashboard', [
            'patient' => $request->attributes->get('portalPatient'),
        ]);
    }
}
