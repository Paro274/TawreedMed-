<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    {{-- ✅ إضافة CSRF Token ضروري لمنع خطأ 419 --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>تسجيل الدخول - مورد</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: "Cairo", sans-serif; background: linear-gradient(135deg, #16a34a, #22c55e); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; margin: 0; }
        .auth-container { width: 100%; max-width: 450px; background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden; }
        .auth-header { background: linear-gradient(45deg, #16a34a, #22c55e); color: white; padding: 25px; text-align: center; }
        .auth-header h2 { margin: 0 0 5px 0; font-weight: 700; font-size: 1.6rem; }
        .auth-header p { margin: 0; opacity: 0.9; font-size: 0.9rem; }
        .auth-body { padding: 25px; }
        .form-tabs { display: flex; margin-bottom: 20px; border-bottom: 2px solid #e1e5e9; }
        .form-tab { flex: 1; padding: 12px 8px; text-align: center; background: none; border: none; font-weight: 600; cursor: pointer; color: #6b7280; transition: all 0.3s ease; font-size: 0.95rem; position: relative; }
        .form-tab:hover, .form-tab.active { color: #16a34a; }
        .form-tab.active::after { content: ''; position: absolute; bottom: -2px; left: 0; right: 0; height: 3px; background: #16a34a; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 0.95rem; }
        .form-control { width: 100%; padding: 12px 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: all 0.3s ease; background: #f8fafc; font-family: inherit; box-sizing: border-box; }
        .form-control:focus { border-color: #16a34a; box-shadow: 0 0 0 0.2rem rgba(22, 163, 74, 0.25); background: white; outline: none; }
        .form-control.is-invalid { border-color: #dc3545; background: #fff5f5; }
        .invalid-feedback { display: block; width: 100%; margin-top: 5px; font-size: 0.85rem; color: #dc3545; font-weight: 500; }
        .btn-primary { width: 100%; background: linear-gradient(45deg, #16a34a, #22c55e); border: none; border-radius: 8px; padding: 12px; color: white; font-weight: 600; font-size: 15px; cursor: pointer; transition: all 0.3s ease; font-family: inherit; }
        .btn-primary:hover:not(:disabled) { background: linear-gradient(45deg, #15803d, #16a34a); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3); }
        .btn-primary:disabled { background: #9ca3af; cursor: not-allowed; transform: none; box-shadow: none; }
        .form-footer { text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid #e1e5e9; font-size: 0.9rem; }
        .form-footer a { color: #16a34a; cursor: pointer; text-decoration: underline; font-weight: 500; transition: color 0.3s ease; }
        .form-footer a:hover { color: #15803d; }
        .hidden { display: none !important; }
        .alert { border-radius: 8px; border: none; margin-bottom: 20px; padding: 12px 15px; font-weight: 500; }
        .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; }
        .alert-danger { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }
        .file-upload-info { font-size: 0.85rem; color: #6b7280; margin-top: 5px; display: flex; align-items: center; gap: 8px; }
        .file-upload-info i { color: #16a34a; }
        .form-row { display: flex; gap: 15px; margin-bottom: 18px; }
        .form-row .form-group { flex: 1; margin-bottom: 0; }
        .loading-spinner { display: inline-block; width: 16px; height: 16px; border: 2px solid #ffffff; border-radius: 50%; border-top-color: transparent; animation: spin 1s ease-in-out infinite; margin-right: 8px; }
        @keyframes spin { to { transform: rotate(360deg); } }
        @media (max-width: 576px) { .form-row { flex-direction: column; gap: 0; } .auth-body, .auth-header { padding: 20px; } .auth-header h2 { font-size: 1.4rem; } }
        
        /* Logo Preview Styles */
        .logo-preview-container { background: #f8fafc; border: 2px dashed #d1d5db; border-radius: 10px; padding: 15px; text-align: center; margin-top: 10px; transition: all 0.3s ease; position: relative; }
        .logo-preview-container:hover { border-color: #16a34a; background: #f0fdf4; }
        .logo-preview { max-width: 100px; max-height: 100px; width: auto; height: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: block; margin: 0 auto 10px; transition: transform 0.3s ease; }
        .logo-preview:hover { transform: scale(1.05); }
        .default-logo-info { font-size: 0.85rem; color: #6b7280; margin-bottom: 5px; font-weight: 500; }
        .default-logo-status { display: inline-flex; align-items: center; gap: 5px; font-size: 0.8rem; padding: 4px 8px; border-radius: 12px; font-weight: 600; }
        .default-logo-status.default { background: #dbeafe; color: #1e40af; }
        .default-logo-status.custom { background: #dcfce7; color: #166534; }
        .default-logo-status.info { background: #fef3c7; color: #92400e; }
        .logo-preview-error { color: #dc3545; font-size: 0.8rem; margin-top: 5px; }
        .custom-logo-preview { max-width: 120px; max-height: 120px; border-radius: 8px; margin: 10px auto 0; display: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    </style>
</head>
<body>

<div class="container-fluid p-3">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="auth-container">
                
                {{-- Header --}}
                <div class="auth-header">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="fas fa-truck-medical fs-3 me-2"></i>
                        <h2>توريد ميد</h2>
                    </div>
                    <p>نظام إدارة الموردين الطبيين</p>
                </div>
                
                <div class="auth-body">
                    
                    {{-- Flash Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center registration-success">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>الرجاء تصحيح الأخطاء التالية:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form Tabs --}}
                    <div class="form-tabs mb-4">
                        <button type="button" class="form-tab active" onclick="showForm('loginForm')">
                            <i class="fas fa-sign-in-alt me-1"></i>تسجيل الدخول
                        </button>
                        <button type="button" class="form-tab" onclick="showForm('registerForm')">
                            <i class="fas fa-user-plus me-1"></i>إنشاء حساب
                        </button>
                    </div>

                    {{-- Login Form --}}
                    <form id="loginForm" method="POST" action="{{ route('supplier.auth.submit') }}">
                        @csrf
                        <div class="form-group">
                            <label for="emailLogin"><i class="fas fa-envelope me-1 text-primary"></i>البريد الإلكتروني</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="emailLogin" name="email" value="{{ old('email') }}" placeholder="example@supplier.com" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="passwordLogin"><i class="fas fa-lock me-1 text-primary"></i>كلمة المرور</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="passwordLogin" name="password" placeholder="••••••••" required>
                        </div>
                        <div class="text-center mb-3">
                            <a href="#" class="text-decoration-none" onclick="showForm('forgotForm'); return false;">
                                <i class="fas fa-question-circle me-1 text-primary"></i>نسيت كلمة المرور؟
                            </a>
                        </div>
                        <button type="submit" class="btn btn-primary mb-3"><i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول</button>
                        <div class="form-footer">ليس لديك حساب؟ <a onclick="showForm('registerForm'); return false;">إنشاء حساب جديد</a></div>
                    </form>

                    {{-- Register Form --}}
                    <form id="registerForm" class="hidden" method="POST" action="{{ route('supplier.register') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name"><i class="fas fa-user me-1 text-primary"></i>الاسم الكامل *</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="الاسم الكامل للشركة" required>
                        </div>
                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope me-1 text-primary"></i>البريد الإلكتروني *</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="company@supplier.com" required>
                        </div>
                        <div class="form-group">
                            <label for="phone"><i class="fas fa-phone me-1 text-primary"></i>رقم الهاتف *</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="01234567890" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="password"><i class="fas fa-lock me-1 text-primary"></i>كلمة المرور *</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required minlength="6">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation"><i class="fas fa-lock me-1 text-primary"></i>تأكيد كلمة المرور *</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required minlength="6">
                            </div>
                        </div>

                        {{-- Logo Upload --}}
                        <div class="form-group">
                            <label for="company_logo"><i class="fas fa-image me-1 text-primary"></i>شعار الشركة</label>
                            <input type="file" class="form-control" id="company_logo" name="logo" accept="image/jpeg,image/png,image/jpg,image/gif">
                            <div class="file-upload-info">
                                <i class="fas fa-info-circle"></i><span>اختياري - الحد الأقصى 2 ميجابايت</span>
                            </div>
                            
                            {{-- Logo Preview --}}
                            <div id="logoPreviewContainer" class="logo-preview-container mt-3" style="display: none;">
                                {{-- ✅✅✅ تعديل مسار اللوجو ليشير لملف في Public --}}
                                <img id="defaultLogoPreview" 
                                     src="{{ asset('frontend/images/default-company-logo.jpg') }}" 
                                     class="logo-preview" 
                                     style="display: block;" 
                                     onerror="this.style.display='none'; document.getElementById('logoErrorMessage').style.display='block'; document.getElementById('logoErrorMessage').innerText='لم يتم العثور على الشعار الافتراضي'">
                                
                                <img id="customLogoPreview" src="#" class="logo-preview custom-logo-preview">
                                <div class="default-logo-info">
                                    <i class="fas fa-info-circle me-1"></i><span id="logoStatusText">الشعار الافتراضي</span>
                                </div>
                                <div class="default-logo-status default" id="logoStatusBadge">
                                    <span class="logo-status-indicator"><i class="fas fa-image"></i></span> افتراضي - سيُطبق تلقائياً
                                </div>
                                <div id="logoErrorMessage" class="logo-preview-error" style="display: none;"></div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mb-3" id="registerBtn"><i class="fas fa-user-plus me-2"></i>إنشاء الحساب</button>
                        <div class="form-footer">لديك حساب بالفعل؟ <a onclick="showForm('loginForm'); return false;">تسجيل الدخول</a></div>
                        <div class="alert alert-success mt-3" style="font-size: 0.9rem;">
                            <i class="fas fa-bolt me-1 text-success"></i><strong>تسجيل فوري:</strong> ستدخل مباشرة للوحة التحكم بعد التسجيل!
                        </div>
                    </form>

                    {{-- Forgot Password Form (الجديد) --}}
                    <form id="forgotForm" class="hidden" method="POST" action="{{ route('supplier.password.email') }}">
                        @csrf
                        <div class="form-group">
                            <label for="forgotEmail"><i class="fas fa-envelope me-1 text-primary"></i>البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="forgotEmail" name="email" placeholder="example@supplier.com" required>
                        </div>
                        <button type="submit" class="btn btn-primary mb-3"><i class="fas fa-paper-plane me-2"></i>إرسال رابط إعادة تعيين</button>
                        <button type="button" class="btn btn-outline-primary w-100" onclick="showForm('loginForm')"><i class="fas fa-arrow-right me-2"></i>عودة لتسجيل الدخول</button>
                    </form>
                </div>
                <div class="text-center py-3 bg-light small"><small class="text-muted">&copy; {{ date('Y') }} توريد ميد</small></div>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // دالة تبديل النماذج
    window.showForm = function(formId) {
        document.querySelectorAll('#loginForm, #registerForm, #forgotForm').forEach(form => form.classList.add('hidden'));
        document.querySelectorAll('.form-tab').forEach(tab => tab.classList.remove('active'));
        
        const targetForm = document.getElementById(formId);
        if (targetForm) {
            targetForm.classList.remove('hidden');
            const tab = document.querySelector(`[onclick="showForm('${formId}')"]`);
            if (tab) tab.classList.add('active');
            targetForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };
    
    // إخفاء التنبيهات
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => alert.style.display = 'none');
    }, 5000);

    // معالجة اللوجو
    const fileInput = document.getElementById('company_logo');
    const previewContainer = document.getElementById('logoPreviewContainer');
    const defaultPreview = document.getElementById('defaultLogoPreview');
    const customPreview = document.getElementById('customLogoPreview');
    const statusBadge = document.getElementById('logoStatusBadge');
    const statusText = document.getElementById('logoStatusText');

    // إظهار اللوجو الافتراضي عند فتح التسجيل
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.target.id === 'registerForm' && !mutation.target.classList.contains('hidden')) {
                previewContainer.style.display = 'block';
            }
        });
    });
    const regForm = document.getElementById('registerForm');
    if (regForm) observer.observe(regForm, { attributes: true, attributeFilter: ['class'] });

    // تغيير الملف
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    customPreview.src = e.target.result;
                    customPreview.style.display = 'block';
                    defaultPreview.style.display = 'none';
                    statusBadge.className = 'default-logo-status custom';
                    statusBadge.innerHTML = '<i class="fas fa-check-circle"></i> مخصص';
                    statusText.textContent = 'تم اختيار: ' + file.name;
                }
                reader.readAsDataURL(file);
            } else {
                customPreview.style.display = 'none';
                defaultPreview.style.display = 'block';
                statusBadge.className = 'default-logo-status default';
                statusBadge.innerHTML = '<i class="fas fa-image"></i> افتراضي';
                statusText.textContent = 'الشعار الافتراضي';
            }
        });
    }

    // تهيئة افتراضية
    showForm('{{ $errors->has("register") || old("name") ? "registerForm" : "loginForm" }}');
});
</script>
</body>
</html>
