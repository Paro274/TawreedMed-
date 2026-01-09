<?php
// app/Http/Middleware/SupplierMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SupplierMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // التحقق من وجود session للمورد
        if (!session()->has('supplier')) {
            return redirect('/supplier/login')->with('error', 'يجب تسجيل الدخول أولاً');
        }
        
        return $next($request);
    }
}
