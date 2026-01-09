<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CtaSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CtaSectionController extends Controller
{
    public function index()
    {
        if (!Session::has('admin')) {
            return redirect('/admin/login');
        }

        $cta = CtaSection::first();
        return view('admin.cta.index', compact('cta'));
    }

    public function update(Request $request)
    {
        if (!Session::has('admin')) {
            return redirect('/admin/login');
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button1_text' => 'nullable|string|max:100',
            'button1_link' => 'nullable|string|max:255',
            'button2_text' => 'nullable|string|max:100',
            'button2_link' => 'nullable|string|max:255',
        ]);

        $cta = CtaSection::first();
        $cta->update($request->all());

        return redirect()->back()->with('success', 'تم تحديث قسم CTA بنجاح.');
    }
}
