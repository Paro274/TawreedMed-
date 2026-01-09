<?php
// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // التحقق من وجود session للأدمن
        if (!session()->has('admin')) {
            return redirect('/admin/login')->with('error', 'يجب تسجيل الدخول أولاً');
        }
        
        return $next($request);
    }
}
