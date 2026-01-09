<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Statistic;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index()
    {
        // عرض الإحصائيات مرتبة
        $statistics = Statistic::orderBy('order', 'asc')->get();
        
        // لو الدالة دي موجودة في الموديل عندك تمام، لو مش موجودة استخدمنا الكويري العادي فوق
        // بس هسيبلك السطر ده عشان لو معتمد عليه في حتة تانية
        $allStats = method_exists(Statistic::class, 'getAllStats') ? Statistic::getAllStats() : $statistics;
        
        return view('admin.statistics.index', compact('statistics', 'allStats'));
    }

    public function create()
    {
        return view('admin.statistics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'number' => 'required|string|max:20',
            'order' => 'nullable|integer|min:0|max:999',
            'title2' => 'nullable|string|max:100',
            'number2' => 'nullable|string|max:20',
            'title3' => 'nullable|string|max:100',
            'number3' => 'nullable|string|max:20',
            'title4' => 'nullable|string|max:100',
            'number4' => 'nullable|string|max:20',
        ], [
            'title.required' => 'العنوان الأساسي مطلوب',
            'number.required' => 'الرقم الأساسي مطلوب',
        ]);

        $data = $request->only([
            'title', 'number', 'order',
            'title2', 'number2',
            'title3', 'number3',
            'title4', 'number4'
        ]);

        if (empty($data['order'])) {
            $maxOrder = Statistic::max('order') ?? 0;
            $data['order'] = $maxOrder + 1;
        }

        Statistic::create($data);

        return redirect()->route('admin.statistics.index')
            ->with('success', 'تم إضافة الإحصائيات بنجاح');
    }

    public function edit(Statistic $statistic)
    {
        return view('admin.statistics.edit', compact('statistic'));
    }

    public function update(Request $request, Statistic $statistic)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'number' => 'required|string|max:20',
            'order' => 'nullable|integer|min:0|max:999',
            'title2' => 'nullable|string|max:100',
            'number2' => 'nullable|string|max:20',
            'title3' => 'nullable|string|max:100',
            'number3' => 'nullable|string|max:20',
            'title4' => 'nullable|string|max:100',
            'number4' => 'nullable|string|max:20',
        ], [
            'title.required' => 'العنوان الأساسي مطلوب',
            'number.required' => 'الرقم الأساسي مطلوب',
        ]);

        $data = $request->only([
            'title', 'number', 'order',
            'title2', 'number2',
            'title3', 'number3',
            'title4', 'number4'
        ]);

        $statistic->update($data);

        return redirect()->route('admin.statistics.index')
            ->with('success', 'تم تحديث الإحصائيات بنجاح');
    }

    public function destroy(Statistic $statistic)
    {
        $statistic->delete();

        return redirect()->route('admin.statistics.index')
            ->with('success', 'تم حذف الإحصائيات بنجاح');
    }

    public function updateOrder(Request $request, Statistic $statistic)
    {
        $request->validate([
            'order' => 'required|integer|min:0|max:999',
        ]);

        $statistic->update(['order' => $request->order]);

        return response()->json([
            'success' => true, 
            'message' => 'تم تحديث الترتيب بنجاح'
        ]);
    }
}
