<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class CustomerAuthController extends Controller
{
    /**
     * دمج سلة الزوار (Session) مع سلة العميل (Database)
     */
    private function mergeSessionCartToDb($userId)
    {
        $sessionCart = Session::get('cart', []);
        
        if (!empty($sessionCart)) {
            foreach ($sessionCart as $productId => $item) {
                // التحقق مما إذا كان المنتج موجوداً بالفعل في سلة العميل
                $dbItem = DB::table('cart_items')
                    ->where('user_id', $userId)
                    ->where('product_id', $productId)
                    ->first();
                    
                if ($dbItem) {
                    // تحديث الكمية (إضافة كمية الزائر للكمية الموجودة)
                    DB::table('cart_items')
                        ->where('id', $dbItem->id)
                        ->update([
                            'quantity' => $dbItem->quantity + $item['quantity'],
                            'updated_at' => now()
                        ]);
                } else {
                    // إضافة المنتج الجديد
                    DB::table('cart_items')->insert([
                        'user_id' => $userId,
                        'product_id' => $productId,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
            
            // تفريغ سلة الزوار بعد الدمج
            Session::forget('cart');
        }
    }
    /**
     * عرض صفحة الدخول / التسجيل.
     */
    public function showLoginForm(Request $request)
    {
        $activeTab = session('auth_tab')
            ?? $request->input('tab')
            ?? ($request->old('name') ? 'register' : 'login');

        return view('frontend.auth.login', [
            'activeTab' => $activeTab === 'register' ? 'register' : 'login',
        ]);
    }

    /**
     * إعادة استخدام نفس الصفحة لكن مع تفعيل تبويب التسجيل.
     */
    public function showRegisterForm(Request $request)
    {
        $request->merge(['tab' => 'register']);
        return $this->showLoginForm($request);
    }
    
    /**
     * تسجيل دخول العميل.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
        ]);
        
        $customer = Customer::where('email', $request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return back()
                ->withErrors(['email' => 'بيانات تسجيل الدخول غير صحيحة.'])
                ->withInput($request->only('email', 'remember'))
                ->with('auth_tab', 'login');
        }

        if ($customer->status != 1) {
            return back()
                ->withErrors(['email' => 'حسابك غير مُفعل، يرجى التواصل مع الدعم.'])
                ->withInput($request->only('email'))
                ->with('auth_tab', 'login');
        }

        Auth::guard('customer')->login($customer, $request->boolean('remember'));
        $request->session()->regenerate();

        // ✅ دمج سلة الزوار مع سلة العميل عند تسجيل الدخول
        $this->mergeSessionCartToDb($customer->id);

        $customer->forceFill([
            'last_login' => now(),
            'email_verified_at' => $customer->email_verified_at ?? now(),
        ])->save();

        session()->forget('auth_tab');

        return redirect()->intended(route('frontend.home'))
            ->with('success', 'تم تسجيل الدخول بنجاح! أهلاً بك من جديد.');
    }
    
    /**
     * تسجيل حساب جديد للعميل.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email','max:255', Rule::unique('users', 'email')],
            'phone' => ['required','string','max:15', Rule::unique('users', 'phone')],
            'password' => 'required|string|min:6|confirmed',
            'terms' => 'accepted',
        ], [
            'name.required' => 'الاسم الكامل مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.unique' => 'رقم الهاتف مسجل مسبقاً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'terms.accepted' => 'يجب الموافقة على الشروط والأحكام',
        ]);
        
        try {
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'status' => 1,
                'email_verified_at' => now(),
                'last_login' => now(),
            ]);
            
            Auth::guard('customer')->login($customer);
            $request->session()->regenerate();

            // ✅ دمج سلة الزوار مع سلة العميل عند التسجيل
            $this->mergeSessionCartToDb($customer->id);

            session()->forget('auth_tab');
            
            return redirect()
                ->route('frontend.customer.profile')
                ->with('success', 'تم إنشاء حسابك وتسجيل الدخول بنجاح!');
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withErrors(['register' => 'حدث خطأ أثناء إنشاء الحساب، يرجى المحاولة مرة أخرى.'])
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('auth_tab', 'register');
        }
    }
    
    /**
     * تسجيل خروج العميل.
     */
    public function logout(Request $request)
    {
        // ✅ 1. حفظ سلة العميل في الـ Session قبل تسجيل الخروج
        $sessionCart = [];
        if (Auth::guard('customer')->check()) {
            $userId = Auth::guard('customer')->id();
            
            // جلب عناصر السلة من قاعدة البيانات
            $dbItems = DB::table('cart_items as ci')
                ->join('products as p', 'ci.product_id', '=', 'p.id')
                ->leftJoin('suppliers as s', 'p.supplier_id', '=', 's.id')
                ->where('ci.user_id', $userId)
                ->select(
                    'ci.product_id',
                    'ci.quantity',
                    'p.name',
                    'p.price',
                    'p.discount',
                    'p.price_after_discount',
                    'p.image_1',
                    'p.slug',
                    's.name as supplier_name'
                )
                ->get();

            // تحويلها لصيغة الـ Session
            foreach ($dbItems as $item) {
                // حساب السعر النهائي
                $price = $item->price;
                if ($item->discount > 0 && $item->price_after_discount !== null) {
                    $price = $item->price_after_discount;
                } elseif ($item->discount > 0) {
                    $price = $item->price - ($item->price * $item->discount / 100);
                }

                $sessionCart[$item->product_id] = [
                    'id' => $item->product_id,
                    'product_id' => $item->product_id,
                    'name' => $item->name,
                    'price' => floatval($price),
                    'quantity' => $item->quantity,
                    'image_1' => $item->image_1,
                    'slug' => $item->slug,
                    'supplier_name' => $item->supplier_name ?? 'غير محدد'
                ];
            }
            
            // ✅ 2. حذف السلة من قاعدة البيانات
            // هذا ضروري جداً لمنع تضاعف الكميات عند تسجيل الدخول مرة أخرى
            // لأننا نقلنا العناصر للـ Session، وسيتم دمجها (إضافتها) لقاعدة البيانات عند الدخول القادم
            DB::table('cart_items')->where('user_id', $userId)->delete();
        }

        // ✅ 3. تسجيل الخروج
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->forget('auth_tab');

        // ✅ 4. استعادة السلة في الـ Session للزائر
        if (!empty($sessionCart)) {
            Session::put('cart', $sessionCart);
        }

        return redirect()
            ->route('frontend.home')
            ->with('success', 'تم تسجيل الخروج بنجاح!');
    }
}
