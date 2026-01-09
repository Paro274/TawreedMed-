<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

class AboutSectionController extends Controller
{
    public function index()
    {
        if (!Session::has('admin')) {
            return redirect('/admin/login');
        }

        $about = AboutSection::first();
        return view('admin.about.index', compact('about'));
    }

    public function update(Request $request)
    {
        if (!Session::has('admin')) {
            return redirect('/admin/login');
        }

        $about = AboutSection::first();

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // تحديث النص
        $about->title = $request->input('title');
        $about->description = $request->input('description');

        // معالجة الصورة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا موجودة
            if ($about->image && File::exists(public_path($about->image))) {
                File::delete(public_path($about->image));
            }

            // رفع الصورة الجديدة
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/about'), $imageName);
            $about->image = 'uploads/about/' . $imageName;
        }

        $about->save();

        return redirect()->back()->with('success', 'تم تحديث قسم من نحن بنجاح.');
    }
}
