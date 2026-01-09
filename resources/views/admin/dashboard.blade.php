<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الأدمن</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: "Cairo", sans-serif; }
        .sidebar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-100">
    
    @include('admin.sidebar')

    <div class="mr-0 lg:mr-64 p-6">
        {{-- ✅ معلومات الأدمن الحالي --}}
        @php
            $adminId = session('admin');
            $admin = $adminId ? \App\Models\Admin::find($adminId) : null;
            $isSuper = $admin->is_super_admin ?? false;
            $permissions = $admin->permissions ?? [];
        @endphp

        @if($admin)
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">مرحباً {{ $admin->username }} 👋</h1>
                    @if($isSuper)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            🛡️ سوبر أدمن - صلاحيات كاملة
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            🔐 صلاحيات: {{ count($permissions) }} قسم
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ✅ قائمة الصفحات حسب الصلاحيات --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            @if($isSuper || in_array('suppliers', $permissions))
            <a href="{{ route('admin.suppliers.index') }}" class="group card-hover">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110">
                        🏪
                    </div>
                    <h3 class="text-xl font-bold mb-2">الموردين</h3>
                    <p class="opacity-90">إدارة الموردين واللوجو</p>
                </div>
            </a>
            @endif

            @if($isSuper || in_array('products', $permissions))
            <a href="{{ route('admin.products.index') }}" class="group card-hover">
                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110">
                        📦
                    </div>
                    <h3 class="text-xl font-bold mb-2">المنتجات</h3>
                    <p class="opacity-90">الموافقة والرفض</p>
                </div>
            </a>
            @endif

            @if($isSuper || in_array('categories', $permissions))
            <a href="{{ route('admin.categories.index') }}" class="group card-hover">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110">
                        📂
                    </div>
                    <h3 class="text-xl font-bold mb-2">التصنيفات</h3>
                    <p class="opacity-90">إدارة التصنيفات</p>
                </div>
            </a>
            @endif

            @if($isSuper || in_array('orders', $permissions))
            <a href="{{ route('admin.orders.index') }}" class="group card-hover">
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110">
                        🛒
                    </div>
                    <h3 class="text-xl font-bold mb-2">الطلبات</h3>
                    <p class="opacity-90">متابعة الطلبات</p>
                </div>
            </a>
            @endif

            {{-- باقي الصفحات هنا بنفس الطريقة --}}
        </div>
        @else
        <div class="text-center py-20">
            <h2 class="text-4xl font-bold text-gray-600 mb-4">🚫 غير مصرح لك</h2>
            <a href="/admin/login" class="bg-blue-600 text-white px-8 py-4 rounded-xl text-xl">تسجيل الدخول</a>
        </div>
        @endif
    </div>

    <style>
        .card-hover > div { transition: all 0.3s ease; }
        .card-hover:hover > div { transform: translateY(-8px) scale(1.02); }
    </style>
</body>
</html>
