<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    /**
     * عرض قائمة الموردين
     */
    public function index()
    {
        $suppliers = Supplier::orderBy('created_at', 'desc')->get();
        
        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * صفحة إضافة مورد جديد
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * حفظ المورد الجديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'اسم المورد مطلوب',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'logo.max' => 'حجم الصورة يجب أن يكون أقل من 2 ميجا',
        ]);

        $data = $request->only(['name', 'email', 'phone']);
        $data['password'] = Hash::make($request->password);
        $data['status'] = 1;
        $data['email_verified_at'] = now();
        
        // مسار اللوجو الافتراضي (يفضل وجوده في public/uploads/default)
        $data['logo'] = 'frontend/images/default-company-logo.jpg';

        // ✅ معالجة رفع الشعار في Public Folder مباشرة
        if ($request->hasFile('logo') || $request->hasFile('company_logo')) {
            $file = $request->hasFile('logo') ? $request->file('logo') : $request->file('company_logo');
            
            // تحديد المسار: public/uploads/supplier_logos
            $destinationPath = public_path('uploads/supplier_logos');
            
            // التأكد من وجود المجلد
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            // اسم الملف الفريد
            $filename = 'supplier_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // نقل الملف
            $file->move($destinationPath, $filename);
            
            // حفظ المسار النسبي في قاعدة البيانات
            $data['logo'] = 'uploads/supplier_logos/' . $filename;
        }

        $supplier = Supplier::create($data);

        Log::info('New Supplier Created by Admin', ['id' => $supplier->id]);

        return redirect()->route('admin.suppliers.index')->with('success', 'تم إضافة المورد بنجاح');
    }

    /**
     * عرض صفحة تعديل المورد
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * تحديث بيانات المورد
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:suppliers,email,' . $id,
            'phone' => 'required|string|max:20',
            'status' => 'required|in:0,1',
            'password' => 'nullable|string|min:6',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'status' => $validatedData['status'],
        ];

        // تحديث كلمة المرور إذا وجدت
        if (!empty($validatedData['password'])) {
            $data['password'] = Hash::make($validatedData['password']);
        }

        // ✅ تحديث الشعار وحفظه في Public Folder
        if ($request->hasFile('logo') || $request->hasFile('company_logo')) {
            $file = $request->hasFile('logo') ? $request->file('logo') : $request->file('company_logo');
            
            // حذف الشعار القديم إذا كان موجوداً ومخصصاً (ليس الافتراضي)
            if ($supplier->logo && 
                !str_contains($supplier->logo, 'default') && 
                File::exists(public_path($supplier->logo))) {
                
                File::delete(public_path($supplier->logo));
            }
            
            // تحديد المسار الجديد
            $destinationPath = public_path('uploads/supplier_logos');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            
            $filename = 'supplier_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // نقل الملف الجديد
            $file->move($destinationPath, $filename);
            
            $data['logo'] = 'uploads/supplier_logos/' . $filename;
        }

        $supplier->update($data);
        
        return redirect()->route('admin.suppliers.index')
            ->with('success', "تم تحديث بيانات المورد {$supplier->name} بنجاح");
    }

    /**
     * تبديل حالة المورد
     */
    public function toggleStatus($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->status = $supplier->status == 1 ? 0 : 1;
        $supplier->save();

        $action = $supplier->status == 1 ? 'تفعيل' : 'تعطيل';
        return redirect()->route('admin.suppliers.index')
            ->with('success', "تم {$action} المورد {$supplier->name}");
    }

    /**
     * حذف المورد
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplierName = $supplier->name;
        
        // ✅ حذف صورة المورد من Public Folder
        if ($supplier->logo && !str_contains($supplier->logo, 'default')) {
            if (File::exists(public_path($supplier->logo))) {
                File::delete(public_path($supplier->logo));
            } elseif (Storage::disk('public')->exists($supplier->logo)) {
                // تنظيف الملفات القديمة من Storage لو موجودة
                Storage::disk('public')->delete($supplier->logo);
            }
        }
        
        $supplier->delete();
        
        return redirect()->route('admin.suppliers.index')
            ->with('success', "تم حذف المورد {$supplierName}");
    }
}
