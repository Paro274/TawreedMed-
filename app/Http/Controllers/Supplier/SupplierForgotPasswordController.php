<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class SupplierForgotPasswordController extends Controller
{
    // 1. عرض صفحة طلب رابط الاستعادة
    public function showLinkRequestForm()
    {
        return view('supplier.auth.passwords.email');
    }

    // 2. إرسال رابط الاستعادة للإيميل
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // نستخدم Broker الموردين المعرف في auth.php
        $status = Password::broker('suppliers')->sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    // 3. عرض صفحة إعادة تعيين كلمة المرور (بعد الضغط على الرابط)
    public function showResetForm(Request $request, $token = null)
    {
        return view('supplier.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    // 4. تنفيذ تغيير كلمة المرور
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::broker('suppliers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('supplier.login')->with('status', __($status))
                    : back()->withErrors(['email' => __($status)]);
    }
}
