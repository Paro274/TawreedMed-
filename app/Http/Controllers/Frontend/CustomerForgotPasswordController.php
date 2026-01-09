<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class CustomerForgotPasswordController extends Controller
{
    // 1. عرض صفحة طلب الرابط
    public function showLinkRequestForm()
    {
        return view('frontend.auth.passwords.email');
    }

    // 2. إرسال الرابط للإيميل
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // نستخدم broker العملاء
        $status = Password::broker('customers')->sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    // 3. عرض فورم تغيير الباسورد
    public function showResetForm(Request $request, $token = null)
    {
        return view('frontend.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    // 4. تغيير الباسورد فعلياً
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::broker('customers')->reset(
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
                    ? redirect()->route('frontend.customer.login')->with('status', __($status))
                    : back()->withErrors(['email' => __($status)]);
    }
}
