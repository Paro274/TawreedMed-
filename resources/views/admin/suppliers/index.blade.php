<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الموردين</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Cairo", sans-serif;
            background: #f4f5fb;
            margin: 0;
        }
        .content {
            margin-right: 240px;
            padding: 30px;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .add-btn {
            background: #10b981;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }
        .add-btn:hover {
            background: #059669;
            transform: translateY(-1px);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        th, td {
            padding: 15px 12px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #4f46e5;
            color: white;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background: #f9f9ff;
        }
        tr:hover {
            background: #f1f1fc;
        }
        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            color: white;
            text-decoration: none;
            font-size: 13px;
            margin: 2px;
            display: inline-block;
            transition: 0.3s;
        }
        .edit-btn { background: #3b82f6; }
        .edit-btn:hover { background: #2563eb; }
        .delete-btn { background: #ef4444; }
        .delete-btn:hover { background: #dc2626; }
        .toggle-btn { 
            background: #f59e0b; 
            color: white;
            border: 1px solid #d97706;
            font-size: 12px;
            padding: 6px 10px;
        }
        .toggle-btn:hover { background: #d97706; }
        .status-active {
            color: #10b981;
            font-weight: 600;
            background: #dcfce7;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
        .status-inactive {
            color: #ef4444;
            font-weight: 600;
            background: #fee2e2;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
        
        /* ✅ CSS بسيط للوجو */
        .logo-img {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            object-fit: cover;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        .logo-img:hover {
            transform: scale(1.05);
            border-color: #3b82f6;
        }
        .logo-placeholder {
            width: 40px;
            height: 40px;
            background: #e5e7eb;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
            border: 2px solid #d1d5db;
        }
        
        .search-box {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .search-box input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-family: "Cairo", sans-serif;
        }
        .alert {
            background: #dcfce7;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #bbf7d0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert.error {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fecaca;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }
        .empty-state h3 {
            color: #374151;
            margin-bottom: 10px;
        }
        .empty-state .add-btn {
            display: inline-block;
            padding: 12px 24px;
            margin-top: 20px;
            background: #4f46e5;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
        }
        .table-responsive {
            overflow-x: auto;
        }
        @media (max-width: 768px) {
            .content {
                margin-right: 0;
                padding: 15px;
            }
            table {
                font-size: 12px;
            }
            .search-box {
                flex-direction: column;
                gap: 15px;
            }
            .search-box input {
                width: 100%;
            }
            h2 {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .logo-img, .logo-placeholder {
                width: 30px;
                height: 30px;
                font-size: 10px;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <h2>
        إدارة الموردين
        <a href="{{ route('admin.suppliers.create') }}" class="add-btn">إضافة مورد جديد</a>
    </h2>

    @if(session('success'))
        <div class="alert">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22 11.08V12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10c0 .082-.002.163-.006.244M9.5 12.75l4-4M14.5 12.75l-4 4" stroke="#166534" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert error">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                ircle cx="12" cy="12" r="10" stroke="#dc2626" stroke-width="2" fillll="none"/>
                <path d="M15 9l-3 3m0 0l-3-3m3 3V9m3 3v3" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    @if($suppliers->count() === 0)
        <div class="empty-state">
            <h3>لا توجد موردين بعد</h3>
            <p>ابدأ بإضافة مورد جديد للبدء في إدارة الموردين</p>
            <a href="{{ route('admin.suppliers.create') }}" class="add-btn">إضافة المورد الأول</a>
        </div>
    @else
        <div class="search-box">
            <input type="text" id="search" placeholder="ابحث في الموردين...">
            <span style="color: #6b7280; font-size: 14px;">
                إجمالي الموردين: {{ $suppliers->count() }}
            </span>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>الشعار</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>تاريخ التسجيل</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="suppliersTable">
                    @foreach ($suppliers as $supplier)
                    <tr data-name="{{ strtolower($supplier->name) }}" data-email="{{ strtolower($supplier->email) }}" data-status="{{ $supplier->status }}">
                        {{-- ✅ عرض اللوجو البسيط --}}
                        <td>
                            {{-- DEBUG: Logo info - {{ $supplier->logo }} - {{ $supplier->hasCustomLogo() ? 'CUSTOM' : 'DEFAULT' }} --}}
                            @if($supplier->hasCustomLogo())
                                <img src="{{ $supplier->logo_url }}?v={{ time() }}" 
                                     alt="لوجو {{ $supplier->name }}" 
                                     class="logo-img"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'; console.log('Logo failed to load for: {{ $supplier->name }}');"
                                     loading="lazy">
                                <div class="logo-placeholder" style="display: none;">
                                    {{ strtoupper(substr($supplier->name, 0, 2)) }}
                                </div>
                            @else
                                <img src="{{ $supplier->logo_url }}?v={{ time() }}" 
                                     alt="لوجو افتراضي {{ $supplier->name }}" 
                                     class="logo-img"
                                     loading="lazy">
                            @endif
                        </td>
                        
                        <td>
                            <strong>{{ $supplier->name }}</strong>
                            @if($supplier->status == 0)
                                <br><small style="color: #ef4444;">(معطل)</small>
                            @endif
                        </td>
                        <td>{{ $supplier->email }}</td>
                        <td>{{ $supplier->phone ?? '-' }}</td>
                        <td>{{ $supplier->created_at->format('Y-m-d H:i') }}</td>
                        
                        {{-- ✅ عرض الحالة باستخدام status (مش active) --}}
                        <td>
                            @if($supplier->status == 1)
                                <span class="status-active">مفعل</span>
                            @else
                                <span class="status-inactive">معطل</span>
                            @endif
                        </td>
                        
                        <td>
                            {{-- ✅ زر التعديل --}}
                            <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" 
                               class="btn edit-btn" 
                               title="تعديل بيانات المورد">
                                تعديل
                            </a>
                            
                            {{-- ✅ زر التفعيل/التعطيل باستخدام status --}}
                            <a href="{{ route('admin.suppliers.toggle', $supplier->id) }}" 
                               class="btn toggle-btn" 
                               title="{{ $supplier->status == 1 ? 'تعطيل المورد' : 'تفعيل المورد' }}"
                               onclick="return confirm('هل أنت متأكد من {{ $supplier->status == 1 ? 'تعطيل' : 'تفعيل' }} هذا المورد؟')">
                                {{ $supplier->status == 1 ? 'تعطيل' : 'تفعيل' }}
                            </a>
                            
                            {{-- ✅ زر الحذف --}}
                            <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn delete-btn" 
                                        title="حذف المورد"
                                        onclick="return confirm('هل أنت متأكد من حذف هذا المورد؟ سيتم حذف جميع بياناته نهائياً.')">
                                    حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ✅ إحصائيات بسيطة باستخدام status --}}
        <div style="margin-top: 20px; padding: 15px; background: #f8fafc; border-radius: 8px; text-align: center;">
            <span style="color: #6b7280;">
                📊 إجمالي: {{ $suppliers->count() }} | 
                مفعل: <span style="color: #10b981;">{{ $suppliers->where('status', 1)->count() }}</span> | 
                معطل: <span style="color: #ef4444;">{{ $suppliers->where('status', 0)->count() }}</span> | 
                بلوجو: <span style="color: #3b82f6;">{{ $suppliers->whereNotNull('logo')->count() }}</span>
            </span>
        </div>
    @endif
</div>

<script>
    // ✅ البحث البسيط مع فلترة الحالة
    document.getElementById('search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#suppliersTable tr');
        let visibleCount = 0;
        let activeCount = 0;
        let inactiveCount = 0;
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const email = row.getAttribute('data-email') || '';
            const status = row.getAttribute('data-status') || '0';
            
            if (name.includes(searchTerm) || email.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;
                if (status == '1') activeCount++;
                else inactiveCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // ✅ تحديث العدد
        const countElement = document.querySelector('.search-box span');
        if (countElement) {
            if (searchTerm.trim()) {
                countElement.innerHTML = `النتائج: ${visibleCount} | مفعل: ${activeCount} | معطل: ${inactiveCount}`;
            } else {
                countElement.innerHTML = `إجمالي الموردين: {{ $suppliers->count() }}`;
            }
        }
    });

    // ✅ تأكيد الحذف
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('⚠️ تحذير: سيتم حذف هذا المورد نهائياً مع جميع بياناته!\n\nهل أنت متأكد؟')) {
                e.preventDefault();
                return false;
            }
            // Form will submit normally if confirmed
        });
    });

    // ✅ Auto-hide alerts
    @if(session('success') || session('error'))
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 4000);
    @endif

    // ✅ Loading state لزر التفعيل/التعطيل
    document.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('هل أنت متأكد من ' + this.textContent + ' هذا المورد؟')) {
                e.preventDefault();
                return false;
            }
            
            // إضافة loading
            const originalText = this.textContent;
            this.innerHTML = '⏳ جاري...';
            this.style.opacity = '0.7';
            this.style.pointerEvents = 'none';
            
            // إعادة النص بعد 3 ثواني (لو فيه مشكلة)
            setTimeout(() => {
                if (this.style.pointerEvents === 'none') {
                    this.innerHTML = originalText;
                    this.style.opacity = '1';
                    this.style.pointerEvents = 'auto';
                }
            }, 3000);
        });
    });

    console.log('✅ صفحة الموردين مُصححة!');
    console.log('📊 الموردين:', {{ $suppliers->count() }});
    console.log('✅ مفعل:', {{ $suppliers->where('status', 1)->count() }});
    console.log('❌ معطل:', {{ $suppliers->where('status', 0)->count() }});
    console.log('🖼️ اللوجوات:', {{ $suppliers->whereNotNull('logo')->count() }});
</script>

</body>
</html>
