<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('order', 'asc')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:0|max:999',
        ], [
            'image.required' => 'الصورة مطلوبة',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.mimes' => 'يجب أن تكون الصورة من نوع: jpeg, png, jpg, gif',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت',
        ]);

        $data = $request->only(['order']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/sliders'), $imageName);
            $data['image'] = 'uploads/sliders/' . $imageName;
        }

        if (empty($data['order'])) {
            $maxOrder = Slider::max('order') ?? 0;
            $data['order'] = $maxOrder + 1;
        }

        Slider::create($data);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'تم إضافة البانر بنجاح');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:0|max:999',
        ]);

        $data = $request->only(['order']);

        if ($request->hasFile('image')) {
            if ($slider->image && File::exists(public_path($slider->image))) {
                File::delete(public_path($slider->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/sliders'), $imageName);
            $data['image'] = 'uploads/sliders/' . $imageName;
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'تم تحديث البانر بنجاح');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->image && File::exists(public_path($slider->image))) {
            File::delete(public_path($slider->image));
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')
            ->with('success', 'تم حذف البانر بنجاح');
    }

    public function updateOrder(Request $request, Slider $slider)
    {
        $request->validate([
            'order' => 'required|integer|min:0|max:999',
        ]);

        $slider->update(['order' => $request->order]);

        return response()->json([
            'success' => true, 
            'message' => 'تم تحديث الترتيب بنجاح'
        ]);
    }
}
