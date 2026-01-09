<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>تسجيل الدخول - عميل</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/customer-auth.css') }}" />
    <style>
        /* تنسيق بسيط للملحوظة */
        .password-hint {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: -10px;
            margin-bottom: 15px;
            display: block;
        }
        .password-hint i {
            color: #ffc107; /* لون أصفر للتحذير أو التنبيه */
            margin-left: 5px;
        }
    </style>
</head>
@php
    $currentTab = session('auth_tab')
        ?? ($activeTab ?? (old('name') ? 'register' : 'login'));
@endphp
<body data-active-tab="{{ $currentTab === 'register' ? 'register' : 'login' }}">
    <div class="auth-container">
        <h2>تسجيل الدخول - عميل</h2>
        
        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        {{-- Login Form --}}
        <form id="loginForm" class="{{ $currentTab === 'register' ? '' : 'active' }}" method="POST" action="{{ route('frontend.customer.login.submit') }}">
            @csrf
            <label for="emailLogin">البريد الإلكتروني</label>
            <input type="email" id="emailLogin" name="email" 
                   value="{{ old('email') }}" required 
                   placeholder="example@email.com" />
            
            <label for="passwordLogin">كلمة المرور</label>
            <input type="password" id="passwordLogin" name="password" required 
                   placeholder="أدخل كلمة المرور" />
            
            <div class="form-row">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">تذكرني</label>
                </div>
                <span class="forgot-link" data-form="forgot">نسيت كلمة المرور؟</span>
            </div>
            
            <button type="submit">تسجيل الدخول</button>
            
            <div class="form-footer">
                ليس لديك حساب؟ <a data-form="register" class="switch-form">إنشاء حساب جديد</a>
            </div>
        </form>
        
        {{-- Register Form --}}
        <form id="registerForm" class="{{ $currentTab === 'register' ? 'active' : '' }}" method="POST" action="{{ route('frontend.customer.register.submit') }}">
            @csrf
            <label for="name">الاسم الكامل</label>
            <input type="text" id="name" name="name" 
                   value="{{ old('name') }}" required 
                   placeholder="أدخل اسمك الكامل" />
            
            <label for="emailRegister">البريد الإلكتروني</label>
            <input type="email" id="emailRegister" name="email" 
                   value="{{ old('email') }}" required 
                   placeholder="example@email.com" />
            
            <label for="phone">رقم الهاتف</label>
            <input type="tel" id="phone" name="phone" 
                   value="{{ old('phone') }}" required 
                   placeholder="01xxxxxxxxx" />
            
            <label for="passwordRegister">كلمة المرور</label>
            <input type="password" id="passwordRegister" name="password" required 
                   placeholder="كلمة مرور قوية (8 أحرف على الأقل)" />
            
            {{-- ✅✅✅ الملحوظة المضافة --}}
            <span class="password-hint">
                ⚠️ <strong>تنبيه هام:</strong> يرجى حفظ كلمة المرور جيداً لتتمكن من متابعة حالة طلباتك مستقبلاً.
            </span>

            <label for="passwordConfirmation">تأكيد كلمة المرور</label>
            <input type="password" id="passwordConfirmation" name="password_confirmation" required 
                   placeholder="أعد إدخال كلمة المرور" />
            
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                <label class="form-check-label" for="terms">
                    أوافق على <a href="#" target="_blank">الشروط والأحكام</a> و <a href="#" target="_blank">سياسة الخصوصية</a>
                </label>
            </div>
            
            <button type="submit">إنشاء حساب</button>
            
            <div class="form-footer">
                لديك حساب؟ <a data-form="login" class="switch-form">تسجيل الدخول</a>
            </div>
        </form>
        
        {{-- Forgot Password Form --}}
        <form id="forgotForm" style="display:none;" method="POST" action="{{ route('frontend.customer.password.email') }}">
            @csrf
            <label for="emailForgot">البريد الإلكتروني</label>
            <input type="email" id="emailForgot" name="email" 
                   value="{{ old('email') }}" required 
                   placeholder="أدخل بريدك الإلكتروني" />
            
            <button type="submit">إرسال رابط إعادة تعيين</button>
            
            <div class="form-footer">
                تذكرت كلمة المرور؟ <a data-form="login" class="switch-form">تسجيل الدخول</a>
            </div>
        </form>
    </div>
    
    <script src="{{ asset('frontend/js/customer-auth.js') }}"></script>
</body>
</html>
