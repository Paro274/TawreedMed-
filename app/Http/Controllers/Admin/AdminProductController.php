<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminProductController extends Controller
{
    /**
     * عرض المنتجات مع فلترة اختيارية حسب التصنيف
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Product::with(['supplier', 'category'])->latest();

        if ($request->has('category_id') && $request->category_id != 'all') {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->get(); 
        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * صفحة إنشاء منتج جديد
     */
    public function create()
    {
        // ✅ تصحيح: استخدام status بدلاً من active
        $suppliers = Supplier::where('status', 1)->get(); 
        return view('admin.products.create', compact('suppliers'));
    }

    /**
     * حفظ المنتج
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
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

        $product = new Product();
        $product->supplier_id = $request->supplier_id;
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
        
        if ($request->has('active_ingredient') && is_array($request->active_ingredient)) {
            $product->active_ingredient = array_values(array_filter($request->active_ingredient));
        } else {
            $product->active_ingredient = []; 
        }

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
        $product->status = 'مقبول'; 

        $product->package_price = ($request->price ?? 0) * ($request->units_per_package ?? 1);

        $destinationPath = public_path('uploads/product_images');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        foreach (range(1, 4) as $i) {
            $field = 'image_' . $i;
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $fileName = 'product_' . time() . '_' . uniqid() . '_' . $i . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPath, $fileName);
                $product->$field = 'uploads/product_images/' . $fileName;
            }
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'تم إضافة المنتج بنجاح');
    }

    /**
     * صفحة تعديل المنتج
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $suppliers = Supplier::all();
        return view('admin.products.edit', compact('product', 'suppliers'));
    }

    /**
     * تحديث المنتج
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_type' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'units_per_package' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($id);
        
        $product->supplier_id = $request->supplier_id;
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
        
        if ($request->has('active_ingredient') && is_array($request->active_ingredient)) {
            $product->active_ingredient = array_values(array_filter($request->active_ingredient));
        } else {
            $product->active_ingredient = [];
        }

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
        $product->status = $request->status;
        $product->package_price = ($request->price ?? 0) * ($request->units_per_package ?? 1);

        $destinationPath = public_path('uploads/product_images');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        foreach (range(1, 4) as $i) {
            $field = 'image_' . $i;
            if ($request->hasFile($field)) {
                if ($product->$field && File::exists(public_path($product->$field))) {
                    File::delete(public_path($product->$field));
                }
                
                $file = $request->file($field);
                $fileName = 'product_' . time() . '_' . uniqid() . '_' . $i . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPath, $fileName);
                $product->$field = 'uploads/product_images/' . $fileName;
            }
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        foreach (range(1, 4) as $i) {
            $field = 'image_' . $i;
            if ($product->$field && File::exists(public_path($product->$field))) {
                File::delete(public_path($product->$field));
            }
        }
        
        // Manual cleanup of relations to ensure deletion works even if DB cascade is missing
        \Illuminate\Support\Facades\DB::table('order_items')->where('product_id', $id)->delete();
        \Illuminate\Support\Facades\DB::table('cart_items')->where('product_id', $id)->delete();

        $product->delete();
        return back()->with('success', 'تم حذف المنتج بنجاح');
    }

    public function approve($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['status' => 'مقبول']);
        return back()->with('success', 'تم قبول المنتج بنجاح وأصبح متاحاً للعملاء');
    }

    public function reject($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['status' => 'مرفوض']);
        return back()->with('success', 'تم رفض المنتج');
    }

    public function getCategoriesByType($type)
    {
        $categories = Category::where('product_type', $type)->get();
        return response()->json($categories);
    }

    // ==========================================
    // AJAX Quick Add Methods
    // ==========================================

    public function storeQuickSupplier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'logo' => 'nullable|image|max:2048', // ✅ التحقق من الصورة
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // المسار الافتراضي
        $logoPath = 'frontend/images/default-company-logo.jpg';

        // ✅ معالجة رفع الصورة
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'supplier_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            $destinationPath = public_path('uploads/supplier_logos');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            
            $file->move($destinationPath, $filename);
            $logoPath = 'uploads/supplier_logos/' . $filename;
        }

        $supplier = Supplier::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => 1,
            'email_verified_at' => now(),
            'logo' => $logoPath // ✅ حفظ المسار
        ]);

        return response()->json([
            'success' => true,
            'supplier' => [
                'id' => $supplier->id,
                'name' => $supplier->name
            ],
            'message' => 'تم إضافة المورد بنجاح'
        ]);
    }


    public function storeQuickCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'product_type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $category = Category::create([
            'name' => $request->name,
            'product_type' => $request->product_type
        ]);

        return response()->json([
            'success' => true,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'product_type' => $category->product_type
            ],
            'message' => 'تم إضافة التصنيف بنجاح'
        ]);
    }
}
