<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OurStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

class OurStoryController extends Controller
{
    public function index()
    {
        if (!Session::has('admin')) {
            return redirect('/admin/login');
        }

        $story = OurStory::first();
        if (!$story) {
            $story = OurStory::create([
                'title' => '',
                'description' => '',
                'image' => null,
            ]);
        }

        return view('admin.story.index', compact('story'));
    }

    public function update(Request $request)
    {
        if (!Session::has('admin')) {
            return redirect('/admin/login');
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $story = OurStory::first() ?? new OurStory();

        $story->title = $request->input('title');
        $story->description = $request->input('description');

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة من public لو موجودة
            if ($story->image && File::exists(public_path($story->image))) {
                File::delete(public_path($story->image));
            }

            // رفع الصورة الجديدة داخل public/uploads/story
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/story'), $imageName);

            // نخزن المسار النسبي داخل قاعدة البيانات
            $story->image = 'uploads/story/' . $imageName;
        }

        $story->save();

        return redirect()->back()->with('success', 'تم تحديث قسم قصتنا بنجاح.');
    }
}
