<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

class SupplierAuthController extends Controller
{
    /**
     * عرض صفحة تسجيل الدخول والتسجيل
     */
    public function index(Request $request)
    {
        // إذا كان مسجل دخول بالفعل، توجه للـ dashboard
        if (session()->has('supplier')) {
            Log::info('المورد مسجل دخول بالفعل - توجيه للـ dashboard', [
                'supplier_id' => session('supplier'),
                'email' => session('supplier_email'),
                'redirect_from' => $request->fullUrl()
            ]);
            
            return redirect()->route('supplier.dashboard');
        }

        // عرض صفحة الـ auth مع الرسائل
        return view('supplier.auth', [
            'success' => $request->session()->get('success'),
            'error' => $request->session()->get('error')
        ]);
    }

    /**
     * تسجيل حساب مورد جديد - تسجيل فوري بدون أدمن
     */
    public function create(Request $request)
    {
        // التحقق من البيانات الأساسية
        $request->validate([
            'name' => 'required|string|max:255|min:2',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('suppliers', 'email'),
            ],
            'phone' => 'required|string|max:20|min:10',
            'password' => 'required|confirmed|min:6|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB
        ], [
            'name.required' => 'الاسم الكامل مطلوب',
            'name.min' => 'الاسم يجب أن يكون 2 أحرف على الأقل',
            'name.max' => 'الاسم الكامل يجب ألا يتجاوز 255 حرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'هذا البريد الإلكتروني مسجل بالفعل',
            'email.max' => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرف',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.min' => 'رقم الهاتف يجب أن يكون 10 أرقام على الأقل',
            'phone.max' => 'رقم الهاتف يجب ألا يتجاوز 20 حرف',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'كلمه المرور غير متطابقة',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
            'password.max' => 'كلمة المرور يجب ألا تتجاوز 255 حرف',
            'logo.image' => 'الشعار يجب أن يكون صورة',
            'logo.mimes' => 'الشعار يجب أن يكون من نوع: jpeg, png, jpg, gif',
            'logo.max' => 'حجم الشعار يجب ألا يتجاوز 2 ميجابايت',
        ]);

        try {
            // ✅ حفظ الشعار إذا وُجد في Public Folder
            $logoPath = $this->handleLogoUpload($request);

            // ✅ البيانات الأساسية للمورد - مفعّل فوراً
            $supplierData = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->input('password')),
                'status' => 1, // مفعّل فوراً - بدون أدمن
                'last_login_at' => now(), // تسجيل الدخول الأول
                'email_verified_at' => now(), // تحقق تلقائي
                'created_by' => 'auto-registration', // مصدر التسجيل
                'login_count' => 1, // أول تسجيل دخول
                'last_activity_at' => now(),
            ];

            // ✅ إضافة الشعار (خاص أو null)
            if ($logoPath) {
                $supplierData['logo'] = $logoPath;
            }

            // ✅ إنشاء المورد الجديد
            $supplier = Supplier::create($supplierData);

            // ✅ التحقق من الإنشاء
            if (!$supplier) {
                throw new \Exception('فشل في إنشاء حساب المورد');
            }

            Log::info('✅ تم إنشاء المورد بنجاح!', [
                'supplier_id' => $supplier->id,
                'email' => $supplier->email,
                'logo' => $supplier->logo
            ]);

            // ✅ تسجيل الدخول التلقائي للمورد الجديد
            $this->setSupplierSession($supplier);

            // ✅ تسجيل النجاح في الـ log مع تفاصيل اللوجو
            $this->logRegistrationSuccess($supplier, $request, $logoPath);

            // ✅ إعادة التوجيه للوحة التحكم الموجودة مع رسالة ترحيب
            $welcomeMessage = '🎉 تم إنشاء حسابك بنجاح! مرحباً بك في توريد ميد، ' . 
                             $supplier->name . '. يمكنك البدء الآن في إدارة منتجاتك والطلبات.';

            return redirect()->route('supplier.dashboard')
                           ->with('success', $welcomeMessage);

        } catch (\Illuminate\Database\QueryException $e) {
            // ✅ أخطاء قاعدة البيانات
            Log::error('❌ خطأ قاعدة البيانات في تسجيل المورد!', [
                'error' => $e->getMessage(),
                'request_data' => $request->except(['password', 'password_confirmation']),
            ]);
            
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return back()->withErrors([
                    'email' => 'هذا البريد الإلكتروني مسجل بالفعل. يرجى استخدام بريد إلكتروني آخر أو تسجيل الدخول.'
                ])->withInput();
            }
            
            return back()->withErrors([
                'register_error' => 'حدث خطأ في قاعدة البيانات أثناء إنشاء الحساب. يرجى المحاولة مرة أخرى.'
            ])->withInput();
            
        } catch (\Exception $e) {
            // ✅ أخطاء عامة
            Log::error('❌ خطأ عام في تسجيل المورد: ' . $e->getMessage(), [
                'request_data' => $request->except(['password', 'password_confirmation']),
            ]);
            
            return back()->withErrors([
                'register_error' => 'حدث خطأ غير متوقع أثناء إنشاء الحساب. يرجى المحاولة مرة أخرى.'
            ])->withInput();
        }
    }

    /**
     * تسجيل الدخول للمورد
     */
    public function store(Request $request)
    {
        // التحقق من البيانات
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.max' => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرف',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
        ]);

        try {
            // البحث عن المورد
            $supplier = Supplier::where('email', $request->email)->first();

            // التحقق من وجود المورد وصحة كلمة المرور
            if (!$supplier || !Hash::check($request->password, $supplier->password)) {
                return back()->withErrors([
                    'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.'
                ])->withInput(['email']);
            }

            // التأكد من أن المورد مفعّل
            if ($supplier->status != 1) {
                return back()->withErrors([
                    'email' => 'حسابك غير مفعّل. يرجى التواصل مع الدعم الفني.'
                ])->withInput(['email']);
            }

            // تسجيل الدخول الناجح
            $this->setSupplierSession($supplier, 'manual');

            // تحديث آخر تسجيل دخول
            $supplier->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
                'login_count' => ($supplier->login_count ?? 0) + 1,
            ]);

            // إعادة التوجيه للوحة التحكم
            $loginMessage = '✅ تم تسجيل الدخول بنجاح! مرحباً بك مرة أخرى يا ' . 
                           $supplier->name . ' في توريد ميد.';

            return redirect()->route('supplier.dashboard')
                           ->with('success', $loginMessage);

        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'حدث خطأ أثناء تسجيل الدخول. يرجى المحاولة مرة أخرى.'
            ])->withInput(['email']);
        }
    }

    /**
     * تسجيل الخروج
     */
    public function destroy(Request $request)
    {
        // مسح كل بيانات الجلسة الخاصة بالمورد
        $request->session()->forget([
            'supplier', 
            'supplier_name', 
            'supplier_email', 
            'supplier_phone', 
            'supplier_logo', 
            'supplier_status',
            'last_login',
            'last_activity',
            'login_method',
            'registration_method'
        ]);
        
        // إعادة إنشاء الـ session للأمان
        $request->session()->regenerate();

        return redirect()->route('supplier.auth')
                        ->with('success', '👋 تم تسجيل الخروج بنجاح. نراك قريباً في توريد ميد!');
    }

    /**
     * التحقق من حالة تسجيل الدخول (للـ AJAX)
     */
    public function check(Request $request)
    {
        if (session()->has('supplier')) {
            $supplierId = session('supplier');
            $supplier = Supplier::find($supplierId);
            
            if ($supplier && $supplier->status == 1) {
                return response()->json([
                    'authenticated' => true,
                    'supplier' => [
                        'id' => $supplier->id,
                        'name' => $supplier->name,
                        'email' => $supplier->email,
                        'phone' => $supplier->phone,
                        'status' => $supplier->status,
                        'last_login' => $supplier->last_login_at?->format('Y-m-d H:i'),
                        'logo' => $supplier->logo_url,
                    ],
                    'dashboard_available' => true
                ]);
            }
        }

        return response()->json([
            'authenticated' => false,
            'message' => 'غير مسجل دخول',
            'redirect_to' => route('supplier.auth')
        ]);
    }

    /**
     * معالجة رفع الشعار - تخزين في Public Folder
     */
    private function handleLogoUpload(Request $request): ?string
    {
        $logoPath = null;
        
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $logoFile = $request->file('logo');
            
            // التحقق من حجم الملف
            if ($logoFile->getSize() > 2048 * 1024) { // 2MB
                return back()->withErrors([
                    'logo' => 'حجم الشعار كبير جداً (الحد الأقصى 2 ميجابايت)'
                ])->withInput();
            }
            
            // ✅ تحديد مسار Public مباشرة
            $destinationPath = public_path('uploads/supplier_logos');

            // التأكد من وجود المجلد
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            // حفظ الملف
            $filename = 'supplier_' . time() . '_' . uniqid() . '.' . $logoFile->getClientOriginalExtension();
            $logoFile->move($destinationPath, $filename);
            
            // المسار النسبي للداتابيز
            $logoPath = 'uploads/supplier_logos/' . $filename;
            
            Log::info('✅ تم حفظ الشعار في Public: ' . $logoPath);
        }
        
        return $logoPath;
    }

    /**
     * الحصول على مسار اللوجو الافتراضي
     */
    private function getDefaultLogoPath(): ?string
    {
        // مسار اللوجو الافتراضي في Public
        // ✅ تصحيح المسار هنا كمان
$defaultLogoPath = 'frontend/images/default-company-logo.jpg';
        
        if (File::exists(public_path($defaultLogoPath))) {
            return $defaultLogoPath;
        }
        
        return null;
    }

    /**
     * تعيين بيانات الجلسة للمورد
     */
    private function setSupplierSession(Supplier $supplier, string $method = 'instant'): void
    {
        session([
            'supplier' => $supplier->id,
            'supplier_name' => $supplier->name,
            'supplier_email' => $supplier->email,
            'supplier_phone' => $supplier->phone,
            'supplier_logo' => $supplier->logo,
            'supplier_status' => $supplier->status,
            'last_login' => now()->toDateTimeString(),
            'login_method' => $method,
        ]);
        
        if ($method === 'instant') {
            session(['registration_method' => 'instant']);
        }
    }

    /**
     * تسجيل نجاح التسجيل في الـ log
     */
    private function logRegistrationSuccess(Supplier $supplier, Request $request, ?string $logoPath): void
    {
        Log::info('تم إنشاء وتسجيل دخول مورد جديد بنجاح - تسجيل فوري', [
            'supplier_id' => $supplier->id,
            'email' => $supplier->email,
            'logo' => $logoPath ?? 'لا يوجد',
            'status' => $supplier->status,
            'ip_address' => $request->ip(),
        ]);
    }
}
