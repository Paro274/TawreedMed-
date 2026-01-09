<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index()
    {
        if (!Session::has('admin')) {
            return redirect('/admin/login');
        }

        $partners = Partner::orderBy('id', 'desc')->get();
        return view('admin.partners.index', compact('partners'));
    }

public function store(Request $request)
{
    if (!Session::has('admin')) {
        return redirect('/admin/login');
    }

    $request->validate([
        'image' => 'required|image|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/partners'), $imageName);

        Partner::create(['image' => 'uploads/partners/' . $imageName]);
    }

    return redirect()->back()->with('success', 'تم إضافة الشريك بنجاح.');
}

public function destroy(Partner $partner)
{
    if (!Session::has('admin')) {
        return redirect('/admin/login');
    }

    // حذف الصورة من public
    if ($partner->image && file_exists(public_path($partner->image))) {
        unlink(public_path($partner->image));
    }

    $partner->delete();

    return redirect()->back()->with('success', 'تم حذف الشريك بنجاح.');
}

}
