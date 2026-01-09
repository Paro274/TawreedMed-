<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index()
    {
        $allIcons = collect(config('medical-icons', []))->flatten(1)->pluck('icon')->unique()->values()->toArray();
        
        $features = Feature::ordered()->get()->filter(function ($feature) use ($allIcons) {
            return in_array($feature->icon, $allIcons);
        });
        
        return view('admin.features.index', compact('features', 'allIcons'));
    }

    public function create()
    {
        $allIcons = collect(config('medical-icons', []))->flatten(1)->pluck('icon')->unique()->values()->toArray();
        return view('admin.features.create', compact('allIcons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'icon' => 'required|string|max:50',
            'order' => 'nullable|integer|min:0|max:999',
        ], [
            'title.required' => 'العنوان مطلوب',
            'description.required' => 'الوصف مطلوب',
            'icon.required' => 'الأيقونة مطلوبة',
        ]);

        $data = $request->only(['title', 'description', 'icon', 'order']);

        // تحديد الترتيب تلقائياً إذا لم يتم تحديده
        if (empty($data['order'])) {
            $maxOrder = Feature::max('order') ?? 0;
            $data['order'] = $maxOrder + 1;
        }

        // التحقق من صحة الأيقونة
        $allIcons = collect(config('medical-icons', []))->flatten(1)->pluck('icon')->toArray();
        if (!in_array($data['icon'], $allIcons)) {
            return back()->withErrors(['icon' => 'الأيقونة المختارة غير صالحة']);
        }

        Feature::create($data);

        return redirect()->route('admin.features.index')
            ->with('success', 'تم إضافة الميزة بنجاح');
    }

    public function edit(Feature $feature)
    {
        $allIcons = collect(config('medical-icons', []))->flatten(1)->pluck('icon')->unique()->values()->toArray();
        return view('admin.features.edit', compact('feature', 'allIcons'));
    }

    public function update(Request $request, Feature $feature)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'icon' => 'required|string|max:50',
            'order' => 'nullable|integer|min:0|max:999',
        ], [
            'title.required' => 'العنوان مطلوب',
            'description.required' => 'الوصف مطلوب',
            'icon.required' => 'الأيقونة مطلوبة',
        ]);

        $data = $request->only(['title', 'description', 'icon', 'order']);

        // التحقق من صحة الأيقونة
        $allIcons = collect(config('medical-icons', []))->flatten(1)->pluck('icon')->toArray();
        if (!in_array($data['icon'], $allIcons)) {
            return back()->withErrors(['icon' => 'الأيقونة المختارة غير صالحة']);
        }

        $feature->update($data);

        return redirect()->route('admin.features.index')
            ->with('success', 'تم تحديث الميزة بنجاح');
    }

    public function destroy(Feature $feature)
    {
        $feature->delete();

        return redirect()->route('admin.features.index')
            ->with('success', 'تم حذف الميزة بنجاح');
    }

    // تحديث الترتيب عبر AJAX
    public function updateOrder(Request $request, Feature $feature)
    {
        $request->validate([
            'order' => 'required|integer|min:0|max:999',
        ]);

        $feature->update(['order' => $request->order]);

        return response()->json([
            'success' => true, 
            'message' => 'تم تحديث الترتيب بنجاح'
        ]);
    }
}
