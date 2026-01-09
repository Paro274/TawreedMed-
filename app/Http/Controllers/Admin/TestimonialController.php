<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TestimonialController extends Controller
{
    public function index()
    {
        if (!session()->has('admin')) {
            return redirect('/admin/login');
        }

        $testimonials = Testimonial::orderBy('order', 'asc')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        if (!session()->has('admin')) {
            return redirect('/admin/login');
        }

        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        if (!session()->has('admin')) {
            return redirect('/admin/login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
            'job_title' => 'nullable|string|max:255',
            'governorate' => 'required|string|max:255',
            'status' => 'boolean'
        ]);

        $data = $request->except('image');
        $data['status'] = $request->has('status') ? 1 : 0;

        // معالجة الصورة
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/testimonials'), $imageName);
            $data['image'] = 'uploads/testimonials/' . $imageName;
        }

        $testimonial = Testimonial::create($data);
        
        // تحديث الترتيب (يوضع في آخر القائمة)
        $maxOrder = Testimonial::max('order') ?? 0;
        $testimonial->update(['order' => $maxOrder + 1]);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'تم إضافة التقييم بنجاح!');
    }

    public function edit(Testimonial $testimonial)
    {
        if (!session()->has('admin')) {
            return redirect('/admin/login');
        }

        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        if (!session()->has('admin')) {
            return redirect('/admin/login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
            'job_title' => 'nullable|string|max:255',
            'governorate' => 'required|string|max:255',
            'status' => 'boolean'
        ]);

        $data = $request->except('image');
        $data['status'] = $request->has('status') ? 1 : 0;

        // معالجة الصورة الجديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($testimonial->image && File::exists(public_path($testimonial->image))) {
                File::delete(public_path($testimonial->image));
            }

            // رفع الصورة الجديدة
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/testimonials'), $imageName);
            $data['image'] = 'uploads/testimonials/' . $imageName;
        }

        // التعامل مع حذف الصورة الحالية
        if ($request->has('remove_image') && $request->remove_image) {
            if ($testimonial->image && File::exists(public_path($testimonial->image))) {
                File::delete(public_path($testimonial->image));
            }
            $data['image'] = null;
        }

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'تم تحديث التقييم بنجاح!');
    }

    public function destroy(Testimonial $testimonial)
    {
        if (!session()->has('admin')) {
            return redirect('/admin/login');
        }

        // حذف الصورة إذا كانت موجودة
        if ($testimonial->image && File::exists(public_path($testimonial->image))) {
            File::delete(public_path($testimonial->image));
        }

        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'تم حذف التقييم بنجاح!');
    }

    public function toggleStatus(Testimonial $testimonial)
    {
        if (!session()->has('admin')) {
            return redirect('/admin/login');
        }

        $testimonial->update(['status' => !$testimonial->status]);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'تم تغيير حالة التقييم بنجاح!');
    }

    public function updateOrder(Request $request)
    {
        if (!session()->has('admin')) {
            return response()->json(['error' => 'غير مصرح'], 401);
        }

        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:testimonials,id'
        ]);

        foreach ($request->order as $index => $id) {
            Testimonial::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'تم تحديث الترتيب بنجاح']);
    }
}
