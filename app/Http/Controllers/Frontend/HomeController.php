<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\Statistic;
use App\Models\Product;
use App\Models\Feature;
use App\Models\AboutSection;
use App\Models\Testimonial;
use App\Models\CtaSection;
use App\Models\ContactInfo;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::ordered()->get();
        $statistics = Statistic::getAllStats();
        $allIcons = collect(config('medical-icons', []))->flatten(1)->pluck('icon')->unique()->values()->toArray();
        $features = Feature::ordered()->get()->filter(function ($feature) use ($allIcons) {
            return in_array($feature->icon, $allIcons);
        });
        $about = AboutSection::first();
        $testimonials = Testimonial::active()->ordered()->take(6)->get();
        $cta = CtaSection::first();
        $contact = ContactInfo::first();
        
        $medicines = Product::where('status', 'مقبول')
            ->where('product_type', 'أدوية')
            ->with('supplier', 'category')
            ->latest()
            ->take(8)
            ->get();
        
        $medicalSupplies = Product::where('status', 'مقبول')
            ->where('product_type', 'مستلزمات طبية')
            ->with('supplier', 'category')
            ->latest()
            ->take(8)
            ->get();
        
$cosmetics = Product::where('status', 'مقبول')
    ->where(function($q) {
        $q->where('product_type', 'LIKE', '%تجميل%') // هيجيب "مستحضرات تجميل" أو "تجميل"
          ->orWhere('product_type', 'LIKE', '%cosmetic%'); // لو متسجلة إنجليزي
    })
    ->with('supplier', 'category')
    ->latest()
    ->take(8)
    ->get();

        
        return view('frontend.homepage.index', compact(
            'sliders', 
            'statistics', 
            'features',
            'about',
            'testimonials',
            'cta',
            'contact',
            'medicines', 
            'medicalSupplies', 
            'cosmetics'
        ));
    }
}
