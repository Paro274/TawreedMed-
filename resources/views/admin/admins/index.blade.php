<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الأدمنز</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    @include('admin.sidebar')
    
    <div class="mr-0 lg:mr-64 p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800">👥 إدارة الأدمنز</h1>
            <a href="{{ route('admin.admins.create') }}" 
               class="bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-4 rounded-2xl font-bold hover:from-green-600 hover:to-green-700 shadow-xl">
                ➕ إضافة أدمن جديد
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-8 py-6 text-right text-xl font-bold text-gray-700">#</th>
                            <th class="px-8 py-6 text-right text-xl font-bold text-gray-700">الاسم</th>
                            <th class="px-8 py-6 text-right text-xl font-bold text-gray-700">الصلاحيات</th>
                            <th class="px-8 py-6 text-right text-xl font-bold text-gray-700">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($admins as $admin)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-8 py-6 font-semibold text-lg text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center text-xl">
                                        {{ substr($admin->username, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-xl text-gray-900">{{ $admin->username }}</div>
                                        <div class="text-sm text-gray-500">آخر دخول: منذ قليل</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @if($admin->is_super_admin)
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-green-400 to-green-600 text-white shadow-lg">
                                        🛡️ سوبر أدمن
                                    </span>
                                @else
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($admin->permissions ?? [] as $perm)
                                            @if(isset(\App\Models\Admin::availablePermissions()[$perm]))
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full font-medium">
                                                {{ \App\Models\Admin::availablePermissions()[$perm] }}
                                            </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <a href="{{ route('admin.admins.edit', $admin->id) }}" 
                                   class="bg-blue-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-600 mr-3">
                                    تعديل
                                </a>
                                <form method="POST" action="{{ route('admin.admins.destroy', $admin->id) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-red-600"
                                            onclick="return confirm('متأكد؟')">
                                        حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center text-gray-500">
                                <div class="text-6xl mb-4">👨‍💼</div>
                                <h3 class="text-2xl font-bold mb-2">لا يوجد أدمنز بعد</h3>
                                <a href="{{ route('admin.admins.create') }}" class="bg-green-500 text-white px-8 py-4 rounded-2xl font-bold">
                                    أضف الأول الآن
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
