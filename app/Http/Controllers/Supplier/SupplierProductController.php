<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\File;

class SupplierProductController extends Controller
{
    public function index()
    {
        $supplierId = session('supplier');
        $products = Product::where('supplier_id', $supplierId)->latest()->get();
        return view('supplier.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('supplier.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_type' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'company_name' => 'nullable|string|max:255',
            'units_per_package' => 'required|integer|min:1',
            'min_order_quantity' => 'nullable|integer|min:1',
            'min_order_package' => 'nullable|integer|min:1',
            'stock_quantity' => 'nullable|integer|min:0',
            'image_1' => 'nullable|image|max:2048',
            'image_2' => 'nullable|image|max:2048',
            'image_3' => 'nullable|image|max:2048',
            'image_4' => 'nullable|image|max:2048',
        ]);

        $product = new Product();
        $product->supplier_id = session('supplier');
        $product->product_type = $request->product_type;
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->discount = $request->discount ?? 0;
        
        $priceAfterDiscount = $product->price;
        if ($product->discount > 0 && $product->discount <= 100) {
            $priceAfterDiscount = $product->price - ($product->price * $product->discount / 100);
        }
        $product->price_after_discount = $priceAfterDiscount;
        
        $product->active_ingredient = array_filter($request->active_ingredient ?? []);
        
        $product->company_name = $request->company_name;
        $product->short_description = $request->short_description;
        $product->full_description = $request->full_description;
        $product->dosage_form = $request->dosage_form;
        $product->tablets_per_pack = $request->tablets_per_pack;
        $product->production_date = $request->production_date;
        $product->expiry_date = $request->expiry_date;
        $product->package_type = $request->package_type;
        $product->units_per_package = $request->units_per_package;
        $product->min_order_quantity = $request->min_order_quantity;
        $product->min_order_package = $request->min_order_package;
        $product->total_units = $request->stock_quantity;
        $product->status = 'معلق';

        $product->package_price = ($request->price ?? 0) * ($request->units_per_package ?? 1);

        // ✅ الرفع إلى Public Folder
        $destinationPath = public_path('uploads/product_images');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        foreach (range(1, 4) as $i) {
            $field = 'image_' . $i;
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $fileName = 'product_' . time() . '_' . uniqid() . '_' . $i . '.' . $file->getClientOriginalExtension();
                
                // نقل الملف
                $file->move($destinationPath, $fileName);
                
                // حفظ المسار النسبي
                $product->$field = 'uploads/product_images/' . $fileName;
            }
        }

        $product->save();

        return redirect()->route('supplier.products.index')->with('success', 'تمت إضافة المنتج بنجاح وهو الآن قيد المراجعة.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('supplier.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_type' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'units_per_package' => 'required|integer|min:1',
            'image_1' => 'nullable|image|max:2048',
            'image_2' => 'nullable|image|max:2048',
            'image_3' => 'nullable|image|max:2048',
            'image_4' => 'nullable|image|max:2048',
        ]);

        $product = Product::findOrFail($id);
        
        $product->product_type = $request->product_type;
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->discount = $request->discount ?? 0;
        
        $priceAfterDiscount = $request->price;
        if ($request->discount > 0 && $request->discount <= 100) {
            $priceAfterDiscount = $request->price - ($request->price * $request->discount / 100);
        }
        $product->price_after_discount = $priceAfterDiscount;
        
        $product->active_ingredient = array_filter($request->active_ingredient ?? []);
        $product->company_name = $request->company_name;
        $product->short_description = $request->short_description;
        $product->full_description = $request->full_description;
        $product->dosage_form = $request->dosage_form;
        $product->tablets_per_pack = $request->tablets_per_pack;
        $product->production_date = $request->production_date;
        $product->expiry_date = $request->expiry_date;
        $product->package_type = $request->package_type;
        $product->units_per_package = $request->units_per_package;
        $product->min_order_quantity = $request->min_order_quantity;
        $product->min_order_package = $request->min_order_package;
        $product->total_units = $request->stock_quantity;
        $product->package_price = ($request->price ?? 0) * ($request->units_per_package ?? 1);
        $product->status = 'معلق';

        // ✅ التحديث والحذف في Public Folder
        $destinationPath = public_path('uploads/product_images');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        foreach (range(1, 4) as $i) {
            $field = 'image_' . $i;
            if ($request->hasFile($field)) {
                // حذف القديم
                if ($product->$field && File::exists(public_path($product->$field))) {
                    File::delete(public_path($product->$field));
                }
                
                // رفع الجديد
                $file = $request->file($field);
                $fileName = 'product_' . time() . '_' . uniqid() . '_' . $i . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPath, $fileName);
                $product->$field = 'uploads/product_images/' . $fileName;
            }
        }

        $product->save();

        return redirect()->route('supplier.products.index')->with('success', 'تم تحديث المنتج بنجاح.');
    }

    public function getCategoriesByType($type)
    {
        try {
            $categories = Category::where('product_type', $type)->get();
            return response()->json($categories);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'message' => $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $supplierId = session('supplier');
        $product = Product::where('supplier_id', $supplierId)->findOrFail($id);
        
        foreach (range(1, 4) as $i) {
            $field = 'image_' . $i;
            if ($product->$field && File::exists(public_path($product->$field))) {
                File::delete(public_path($product->$field));
            }
        }
        
        $product->delete();
        return back()->with('success', 'تم حذف المنتج بنجاح.');
    }
}
