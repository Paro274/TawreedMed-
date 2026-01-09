<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FaqController extends Controller
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
        $faqs = Faq::orderBy('id', 'desc')->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        $this->checkAuth();
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $this->checkAuth();

        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        Faq::create($request->all());

        return redirect()->route('admin.faqs.index')->with('success', 'تم إضافة السؤال بنجاح.');
    }

    public function edit(Faq $faq)
    {
        $this->checkAuth();
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $this->checkAuth();

        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $faq->update($request->all());

        return redirect()->route('admin.faqs.index')->with('success', 'تم تحديث السؤال بنجاح.');
    }

    public function destroy(Faq $faq)
    {
        $this->checkAuth();
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'تم حذف السؤال بنجاح.');
    }
}
