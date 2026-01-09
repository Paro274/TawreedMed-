<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Cache; // ✅ إضافة الـ import
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckAdminPermission
{
    public function handle(Request $request, Closure $next, $permission = null)
    {
        // ✅ تحقق من وجود session admin
        if (!session()->has('admin')) {
            return redirect('/admin/login');
        }

        $adminId = session('admin');
        
        // ✅ جلب الأدمن مع cache (أو بدون لو مش عايز)
        $admin = Cache::remember("admin_{$adminId}", 3600, function () use ($adminId) {
            return Admin::find($adminId);
        });

        // ✅ لو الأدمن مش موجود
        if (!$admin) {
            session()->forget('admin');
            return redirect('/admin/login')->with('error', 'جلسة منتهية');
        }

        // ✅ Super Admin يشوف كل حاجة
        if ($admin->is_super_admin) {
            session(['admin_data' => $admin->toArray()]);
            return $next($request);
        }

        // ✅ تحقق من الصلاحية المطلوبة
        if ($permission && !$admin->hasPermission($permission)) {
            return redirect('/admin/dashboard')
                ->with('error', '❌ ليس لديك صلاحية للوصول لهذه الصفحة');
        }

        session(['admin_data' => $admin->toArray()]);
        return $next($request);
    }
}
