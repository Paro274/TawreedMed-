<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ContactInfoController extends Controller
{
    public function index()
    {
        if (!Session::has('admin')) {
            return redirect('/admin/login');
        }

        // ✅ التأكد من وجود سجل واحد على الأقل أو إنشاؤه بقيم افتراضية
        $contact = ContactInfo::firstOrCreate(
            ['id' => 1],
            [
                'phone' => '01000000000',
                'email' => 'info@example.com',
                'address' => 'القاهرة، مصر'
            ]
        );
        
        return view('admin.contact.index', compact('contact'));
    }

    public function update(Request $request)
    {
        if (!Session::has('admin')) {
            return redirect('/admin/login');
        }

        $request->validate([
            'phone' => 'nullable|string|max:100', // زودت الحد عشان يقبل رقمين
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:500',
        ]);

        // التحديث دائماً لأول سجل
        $contact = ContactInfo::first();
        
        if (!$contact) {
            $contact = new ContactInfo();
        }
        
        $contact->phone = $request->phone;
        $contact->email = $request->email;
        $contact->address = $request->address;
        $contact->save();

        return redirect()->back()->with('success', 'تم تحديث بيانات التواصل بنجاح.');
    }
}
