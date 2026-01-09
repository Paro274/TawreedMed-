<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthenticateSupplier
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ التحقق من وجود session المورد
        if (!session()->has('supplier')) {
            Log::warning('محاولة الوصول لصفحة مورد بدون تسجيل دخول', [
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->headers->get('referer')
            ]);
            
            return redirect()->route('supplier.auth')
                           ->with('error', 'يجب تسجيل الدخول أولاً للوصول لهذه الصفحة.')
                           ->withInput($request->only('email', 'name', 'phone'));
        }

        try {
            // ✅ التحقق من صحة المورد في الـ database
            $supplierId = session('supplier');
            $supplier = \App\Models\Supplier::findOrFail($supplierId);
            
            // ✅ التأكد من أن المورد مفعّل
            if ($supplier->status != 1) {
                Log::warning('محاولة الوصول لحساب مورد معلق', [
                    'supplier_id' => $supplier->id,
                    'email' => $supplier->email,
                    'status' => $supplier->status,
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl()
                ]);
                
                // مسح الـ session للحسابات المعلقة
                $this->clearSupplierSession();
                
                return redirect()->route('supplier.auth')
                               ->with('error', 'حسابك غير مفعّل. يرجى التواصل مع الدعم الفني.')
                               ->withInput($request->only('email'));
            }

            // ✅ تحديث معلومات الـ session
            session([
                'supplier' => $supplier->id,
                'supplier_name' => $supplier->name,
                'supplier_email' => $supplier->email,
                'supplier_phone' => $supplier->phone,
                'supplier_logo' => $supplier->logo,
                'supplier_status' => $supplier->status,
                'last_activity' => now()->toDateTimeString(),
            ]);

            // ✅ تسجيل النشاط
            Log::info('تم التحقق من session المورد بنجاح', [
                'supplier_id' => $supplier->id,
                'email' => $supplier->email,
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'verified_at' => now()->toDateTimeString()
            ]);

            // ✅ تمرير الطلب مع المورد
            $request->attributes->set('supplier', $supplier);
            
            return $next($request);

        } catch (ModelNotFoundException $e) {
            // المورد محذوف من الـ database
            Log::error('session لمورد محذوف', [
                'supplier_id' => $supplierId ?? 'unknown',
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'error' => $e->getMessage()
            ]);
            
            // مسح الـ session
            $this->clearSupplierSession();
            
            return redirect()->route('supplier.auth')
                           ->with('error', 'حسابك غير موجود. يرجى إعادة التسجيل.')
                           ->withInput($request->only('email'));
                           
        } catch (\Exception $e) {
            // أخطاء أخرى
            Log::error('خطأ في middleware المورد: ' . $e->getMessage(), [
                'supplier_id' => $supplierId ?? 'unknown',
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('supplier.auth')
                           ->with('error', 'حدث خطأ في التحقق من حسابك. يرجى المحاولة مرة أخرى.')
                           ->withInput($request->only('email'));
        }
    }

    /**
     * مسح بيانات session المورد
     */
    private function clearSupplierSession(): void
    {
        session()->forget([
            'supplier',
            'supplier_name',
            'supplier_email',
            'supplier_phone',
            'supplier_logo',
            'supplier_status',
            'last_login',
            'last_activity'
        ]);
        
        // إعادة إنشاء الـ session للأمان
        session()->regenerate();
    }
}
