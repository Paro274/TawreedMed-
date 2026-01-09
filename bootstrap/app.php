<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AuthenticateSupplier;
use App\Http\Middleware\CheckAdminPermission;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__.'/../routes/web.php',
            __DIR__.'/../routes/frontend.php',
        ],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ✅ Middleware الموردين
        $middleware->alias([
            'supplier.auth' => AuthenticateSupplier::class,
        ]);
        
        // ✅ Middleware الأدمن الجديد
        $middleware->alias([
            'admin.permission' => CheckAdminPermission::class,
        ]);
        
        // ✅ مجموعة الموردين
        $middleware->group('supplier', [
            'supplier.auth',
        ]);
        
        $middleware->web(append: [
            // middleware عام للويب
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ✅ معالجة أخطاء الموردين
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('supplier/*') && !$request->is('supplier/auth')) {
                return redirect()->route('supplier.auth')
                               ->with('error', 'الصفحة غير موجودة. يرجى تسجيل الدخول.');
            }
        });
    })->create();
