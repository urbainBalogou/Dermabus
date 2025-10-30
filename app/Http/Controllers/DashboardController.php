<?php

namespace App\Http\Controllers;

use App\Models\CaseNote;
use App\Models\EducationResource;
use App\Models\FollowUp;
use App\Models\Patient;
use App\Models\Screening;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display the main analytics dashboard.
     */
    public function index(): View
    {
        $stats = [
            'patients' => Patient::count(),
            'screenings' => Screening::count(),
            'self_registered' => Patient::where('is_self_registered', true)->count(),
            'treated' => Patient::where('status', 'treated')->count(),
            'reintegrated' => Patient::where('is_reintegrated', true)->count(),
            'active_treatments' => Screening::where('treatment_status', 'in_progress')->count(),
            'upcoming_follow_ups' => FollowUp::where('status', FollowUp::STATUS_PLANNED)
                ->where('scheduled_for', '>=', now()->startOfDay())
                ->count(),
            'overdue_follow_ups' => FollowUp::whereIn('status', [FollowUp::STATUS_PLANNED, FollowUp::STATUS_MISSED])
                ->where('scheduled_for', '<', now()->startOfDay())
                ->count(),
        ];

        $recentScreenings = Screening::with(['patient', 'agent'])
            ->latest('screened_on')
            ->take(5)
            ->get();

        $upcomingFollowUps = FollowUp::with(['patient', 'assignee'])
            ->where('status', FollowUp::STATUS_PLANNED)
            ->orderBy('scheduled_for')
            ->take(5)
            ->get();

        $latestNotes = CaseNote::with(['patient', 'author'])
            ->latest('noted_on')
            ->take(5)
            ->get();

        $resources = EducationResource::where('is_published', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentScreenings', 'upcomingFollowUps', 'latestNotes', 'resources'));
    }
}
