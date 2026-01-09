<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\OurStory;
use App\Models\MissionVisionValues;
use App\Models\TeamMember;
use App\Models\Journey;
use App\Models\Partner;
use App\Models\CertificateAward;
use App\Models\CtaSection;

class AboutController extends Controller
{
    public function index()
    {
        $story = OurStory::first();
        $mvv = MissionVisionValues::first();
        $team = TeamMember::all();
        $journeys = Journey::ordered()->get();
        $partners = Partner::all();
        $allIcons = collect(config('certificate-icons', []))->flatten(1)->pluck('icon')->unique()->values()->toArray();
        $certificates = CertificateAward::ordered()->get()->filter(function ($cert) use ($allIcons) {
            return in_array($cert->icon, $allIcons);
        });
        $cta = CtaSection::first();
        
        return view('frontend.about.index', compact('story', 'mvv', 'team', 'journeys', 'partners', 'certificates', 'cta'));
    }
}
