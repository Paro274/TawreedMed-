<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ContactInfo2Controller extends Controller
{
    public function index()
    {
        if (!Session::has('admin')) {
            return redirect('/admin/login');
        }

        $contact = ContactInfo2::first();
        return view('admin.contact2.index', compact('contact'));
    }

    public function update(Request $request)
    {
        if (!Session::has('admin')) {
            return redirect('/admin/login');
        }

        $request->validate([
            'phone1_title' => 'nullable|string|max:100',
            'phone1' => 'nullable|string|max:20',
            'phone2_title' => 'nullable|string|max:100',
            'phone2' => 'nullable|string|max:20',
            'phone3_title' => 'nullable|string|max:100',
            'phone3' => 'nullable|string|max:20',
            'email1_title' => 'nullable|string|max:100',
            'email1' => 'nullable|email|max:100',
            'email2_title' => 'nullable|string|max:100',
            'email2' => 'nullable|email|max:100',
            'email3_title' => 'nullable|string|max:100',
            'email3' => 'nullable|email|max:100',
            'address_title' => 'nullable|string|max:255',
            'address_text' => 'nullable|string',
        ]);

        $contact = ContactInfo2::first();
        $contact->update($request->all());

        return redirect()->back()->with('success', 'تم تحديث بيانات الاتصال بنجاح.');
    }
}
