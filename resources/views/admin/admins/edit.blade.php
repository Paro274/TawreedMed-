<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل أدمن</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Cairo', sans-serif; } </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen py-12">
    
    @include('admin.sidebar')

    <div class="mr-0 lg:mr-64 p-8">
        <div class="max-w-4xl mx-auto">
            {{-- Header --}}
            <div class="bg-white rounded-3xl shadow-2xl p-10 mb-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center text-2xl">
                        👑
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                            تعديل بيانات الأدمن
                        </h1>
                        <p class="text-gray-600 mt-2">يمكنك تعديل بيانات الدخول والصلاحيات للأدمن المحدد</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-2xl mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('admin.admins.update', $admin->id) }}" class="space-y-8">
                    @csrf
                    
                    {{-- بيانات الدخول --}}
                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-4">🔐 بيانات الدخول</h3>
                        
                        {{-- Username --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">اسم المستخدم</label>
                            <input type="text" name="username" required 
                                   value="{{ old('username', $admin->username) }}"
                                   class="w-full px-5 py-4 border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 text-lg bg-white"
                                   placeholder="Example: ahmed_admin">
                            @error('username')
                                <p class="text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password (اختياري) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">كلمة المرور الجديدة (اختياري)</label>
                                <input type="password" name="password"
                                       class="w-full px-5 py-4 border-2 border-gray-200 rounded-2xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 text-lg bg-white"
                                       placeholder="اتركه فارغاً إذا لا تريد التغيير">
                                @error('password')
                                    <p class="text-red-500 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">تأكيد كلمة المرور</label>
                                <input type="password" name="password_confirmation"
                                       class="w-full px-5 py-4 border-2 border-gray-200 rounded-2xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 text-lg bg-white"
                                       placeholder="إعادة كتابة كلمة المرور الجديدة">
                            </div>
                        </div>
                    </div>

                    {{-- Super Admin Checkbox --}}
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-200 p-6 rounded-2xl shadow-sm">
                        <label class="flex items-center gap-4 cursor-pointer group">
                            <input type="checkbox" name="is_super_admin" value="1" id="superAdminCheck"
                                   @checked(old('is_super_admin', $admin->is_super_admin))
                                   class="w-6 h-6 rounded-lg border-4 border-yellow-400 bg-yellow-100 focus:ring-yellow-500 transition-all duration-300 group-hover:scale-110">
                            <div>
                                <h3 class="text-xl font-bold text-yellow-800 mb-1 flex items-center gap-2">
                                    🛡️ سوبر أدمن (مدير عام)
                                </h3>
                                <p class="text-yellow-700 text-sm">عند تفعيل هذا الخيار، يمتلك الأدمن صلاحيات كاملة على كل الأقسام أدناه تلقائياً.</p>
                            </div>
                        </label>
                    </div>

                    {{-- Permissions --}}
                    @php
                        $allPermissions = [
                            'admins' => '👥 إدارة المشرفين',
                            'suppliers' => '🏪 الموردين',
                            'products' => '📦 المنتجات',
                            'categories' => '📂 التصنيفات',
                            'orders' => '🛒 الطلبات والفواتير',
                            'sliders' => '🖼️ البنرات (Sliders)',
                            'statistics' => '📊 الإحصائيات',
                            'features' => '✨ مميزات الموقع',
                            'about' => 'ℹ️ من نحن',
                            'story' => '📜 قصتنا',
                            'mvv' => '👁️ الرؤية والرسالة',
                            'team' => '👔 فريق العمل',
                            'journey' => '🚀 رحلتنا',
                            'partners' => '🤝 شركاء النجاح',
                            'certificates' => '🏆 الشهادات والجوائز',
                            'testimonials' => '💬 آراء العملاء',
                            'cta' => '📣 قسم (Call to Action)',
                            'faqs' => '❓ الأسئلة الشائعة',
                            'contact' => '📞 معلومات التواصل (الرئيسية)',
                            'contact2' => '📱 معلومات التواصل (الإضافية)',
                            'map' => '🗺️ الخريطة والموقع',
                        ];
                        $selectedPermissions = old('permissions', $admin->permissions ?? []);
                    @endphp

                    <div id="permissionsSection" class="transition-all duration-300">
                        <label class="block text-xl font-bold text-gray-800 mb-6 flex items-center gap-3 border-b pb-4">
                            📋 تعديل الصلاحيات
                        </label>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($allPermissions as $key => $label)
                            <label class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 cursor-pointer group transition-all duration-200 bg-white shadow-sm">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}" 
                                       class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition-all duration-200 permission-checkbox"
                                       @checked(in_array($key, $selectedPermissions))>
                                <div>
                                    <div class="font-bold text-gray-700 group-hover:text-blue-700">{{ $label }}</div>
                                    <div class="text-xs text-gray-400">صلاحية الوصول لقسم {{ $key }}</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-4 pt-8 border-t-2 border-gray-100">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-8 rounded-2xl text-lg font-bold hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            💾 حفظ التعديلات
                        </button>
                        <a href="{{ route('admin.admins.index') }}" 
                           class="px-8 py-4 border-2 border-gray-300 rounded-2xl text-lg font-semibold text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-all duration-300">
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script لتعطيل الصلاحيات عند اختيار سوبر أدمن --}}
    <script>
        const superAdminCheck = document.getElementById('superAdminCheck');
        const permissionsSection = document.getElementById('permissionsSection');
        const checkboxes = document.querySelectorAll('.permission-checkbox');

        function togglePermissions() {
            if (!superAdminCheck) return;

            if (superAdminCheck.checked) {
                permissionsSection.style.opacity = '0.5';
                permissionsSection.style.pointerEvents = 'none';
                // نسيب التشيكات زي ما هي، عشان نعرض الواقع لكن نمنع التعديل
            } else {
                permissionsSection.style.opacity = '1';
                permissionsSection.style.pointerEvents = 'auto';
            }
        }

        togglePermissions();
        if (superAdminCheck) {
            superAdminCheck.addEventListener('change', togglePermissions);
        }
    </script>
</body>
</html>
