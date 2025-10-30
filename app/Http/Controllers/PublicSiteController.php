<?php

namespace App\Http\Controllers;

use App\Models\EducationResource;
use App\Models\Patient;
use App\Models\Screening;
use Illuminate\Contracts\View\View;

class PublicSiteController extends Controller
{
    /**
     * Display the public landing page for DermaBus+.
     */
    public function home(): View
    {
        $stats = [
            'communities' => Patient::whereNotNull('district')->distinct('district')->count('district'),
            'screened' => Screening::count(),
            'self_registered' => Patient::where('is_self_registered', true)->count(),
        ];

        $resources = EducationResource::where('is_published', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('public.home', compact('stats', 'resources'));
    }
}
