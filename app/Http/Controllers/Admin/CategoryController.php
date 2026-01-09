<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_type' => 'required|string',
        ]);

        Category::create([
            'name' => $request->name,
            'product_type' => $request->product_type,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'تم إضافة التصنيف بنجاح');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_type' => 'required|string',
        ]);

        $category->update([
            'name' => $request->name,
            'product_type' => $request->product_type,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'تم تعديل التصنيف بنجاح');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'تم حذف التصنيف بنجاح');
    }
}
