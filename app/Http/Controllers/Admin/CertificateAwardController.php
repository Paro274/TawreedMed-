<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateAward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CertificateAwardController extends Controller
{
    private function checkAuth()
    {
        if (!Session::has('admin')) {
            return redirect('/admin/login');
        }
    }

    public function index()
    {
        $this->checkAuth();
        $allIcons = collect(config('certificate-icons', []))->flatten(1)->pluck('icon')->unique()->values()->toArray();
        
        $items = CertificateAward::orderBy('order', 'asc')->get()->filter(function ($item) use ($allIcons) {
            return in_array($item->icon, $allIcons);
        });
        
        return view('admin.certificates.index', compact('items'));
    }

    public function create()
    {
        $this->checkAuth();
        $allIcons = collect(config('certificate-icons', []))->flatten(1)->pluck('icon')->unique()->values()->toArray();
        return view('admin.certificates.create', compact('allIcons'));
    }

    public function store(Request $request)
    {
        $this->checkAuth();

        $request->validate([
            'icon' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();
        if (!$request->has('order') || $request->order === null) {
            $data['order'] = CertificateAward::max('order') + 1;
        }

        // التحقق من صحة الأيقونة
        $allIcons = collect(config('certificate-icons', []))->flatten(1)->pluck('icon')->toArray();
        if (!in_array($data['icon'], $allIcons)) {
            return back()->withErrors(['icon' => 'الأيقونة المختارة غير صالحة']);
        }

        CertificateAward::create($data);

        return redirect()->route('admin.certificates.index')->with('success', 'تم إضافة الشهادة/الجائزة بنجاح.');
    }

    public function edit(CertificateAward $certificate)
    {
        $this->checkAuth();
        $allIcons = collect(config('certificate-icons', []))->flatten(1)->pluck('icon')->unique()->values()->toArray();
        return view('admin.certificates.edit', compact('certificate', 'allIcons'));
    }

    public function update(Request $request, CertificateAward $certificate)
    {
        $this->checkAuth();

        $request->validate([
            'icon' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();

        // التحقق من صحة الأيقونة
        $allIcons = collect(config('certificate-icons', []))->flatten(1)->pluck('icon')->toArray();
        if (!in_array($data['icon'], $allIcons)) {
            return back()->withErrors(['icon' => 'الأيقونة المختارة غير صالحة']);
        }

        $certificate->update($data);

        return redirect()->route('admin.certificates.index')->with('success', 'تم تحديث الشهادة/الجائزة بنجاح.');
    }

    public function destroy(CertificateAward $certificate)
    {
        $this->checkAuth();
        $certificate->delete();
        return redirect()->route('admin.certificates.index')->with('success', 'تم حذف الشهادة/الجائزة بنجاح.');
    }
}
