<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        $admin = Admin::where('username', $credentials['username'])->first();

        if ($admin && Hash::check($credentials['password'], $admin->password)) {
            $request->session()->put('admin', $admin->id);
            return redirect('/admin/dashboard');
        }

        return back()->withErrors(['login_error' => 'اسم المستخدم أو كلمة المرور غير صحيحة']);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin');
        return redirect('/admin/login');
    }
}
