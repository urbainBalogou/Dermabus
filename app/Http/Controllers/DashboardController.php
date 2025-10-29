<?php

namespace App\Http\Controllers;

use App\Models\EducationResource;
use App\Models\Patient;
use App\Models\Screening;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Display the main analytics dashboard.
     */
    public function index(): View
    {
        $stats = [
            'patients' => Patient::count(),
            'screenings' => Screening::count(),
            'treated' => Patient::where('status', 'treated')->count(),
            'reintegrated' => Patient::where('is_reintegrated', true)->count(),
        ];

        $recentScreenings = Screening::with(['patient', 'agent'])
            ->latest('screened_on')
            ->take(5)
            ->get();

        $upcomingFollowUps = Screening::where('requires_follow_up', true)
            ->whereDate('follow_up_on', '>=', now()->toDateString())
            ->orderBy('follow_up_on')
            ->take(5)
            ->get();

        $resources = EducationResource::where('is_published', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentScreenings', 'upcomingFollowUps', 'resources'));
    }
}
