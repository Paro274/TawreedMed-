<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة البانرات</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .add-btn:hover {
            background: #059669;
            transform: translateY(-1px);
        }
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
            padding: 6px 12px;
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
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
            background: white;
            border-radius: 10px;
        }
        .empty-state h3 {
            color: #374151;
            margin-bottom: 10px;
        }
        .order-input {
            width: 60px;
            text-align: center;
            padding: 4px 8px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 14px;
        }
        .buttons-info {
            font-size: 12px;
            text-align: right;
        }
        .buttons-info div {
            margin-bottom: 2px;
        }
        .no-buttons {
            color: #6b7280;
            font-size: 12px;
        }
        
        /* تنسيق للصورة المصغرة */
        .thumb-img {
            width: 60px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        @media (max-width: 768px) {
            .content {
                margin-right: 0;
                padding: 15px;
            }
            table {
                font-size: 12px;
            }
            h2 {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <h2>
        إدارة البانرات الرئيسية
        <a href="{{ route('admin.sliders.create') }}" class="add-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            إضافة بانر جديد
        </a>
    </h2>

    @if(session('success'))
        <div class="alert">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M22 11.08V12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10c0 .082-.002.163-.006.244M9.5 12.75l4-4M14.5 12.75l-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($sliders->count() === 0)
        <div class="empty-state">
            <h3>لا توجد بانرات بعد</h3>
            <p>ابدأ بإضافة بانر جديد لعرضه في الصفحة الرئيسية</p>
            <a href="{{ route('admin.sliders.create') }}" class="add-btn">إضافة البانر الأول</a>
        </div>
    @else
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>الترتيب</th>
                        <th>الصورة</th>
                        <th>العنوان</th>
                        <th>الوصف</th>
                        <th>الأزرار</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sliders as $index => $slider)
                    <tr>
                        <td>
                            <input type="number" 
                                   class="order-input" 
                                   value="{{ $slider->order }}" 
                                   min="0" 
                                   onchange="updateOrder({{ $slider->id }}, this.value)"
                                   style="margin-right: 8px;">
                        </td>
                        <td style="text-align: center; width: 80px;">
                            @if($slider->image)
                                <img src="{{ asset($slider->image) }}" 
                                     class="thumb-img"
                                     alt="صورة البانر">
                            @else
                                <span style="color: #6b7280; font-size: 12px;">لا توجد صورة</span>
                            @endif
                        </td>
                        <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ Str::limit($slider->title, 30) }}
                        </td>
                        <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ Str::limit($slider->description, 50) }}
                        </td>
                        <td>
                            @if($slider->button1_text || $slider->button2_text)
                                <div class="buttons-info">
                                    <div><strong>{{ $slider->button1_text ?: '-' }}</strong></div>
                                    @if($slider->button2_text)
                                        <div style="color: #6b7280;">{{ $slider->button2_text }}</div>
                                    @endif
                                </div>
                            @else
                                <span class="no-buttons">بدون أزرار</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.sliders.edit', $slider) }}" 
                               class="btn edit-btn"
                               title="تعديل البانر">
                                تعديل
                            </a>
                            
                            {{-- ✅ التعديل الأساسي هنا: استخدام Form للحذف --}}
                            <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn delete-btn" 
                                        title="حذف البانر"
                                        onclick="return confirm('هل أنت متأكد من حذف هذا البانر؟ سيتم حذفه نهائياً.')">
                                    حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script>
    function updateOrder(sliderId, newOrder) {
        fetch(`/admin/sliders/${sliderId}/order`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ order: parseInt(newOrder) })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // يمكن إضافة إشعار نجاح بسيط هنا بدلاً من إعادة التحميل الكاملة
                // location.reload(); 
                console.log('تم التحديث');
            } else {
                alert('حدث خطأ في تحديث الترتيب');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في تحديث الترتيب');
        });
    }
</script>

</body>
</html>
