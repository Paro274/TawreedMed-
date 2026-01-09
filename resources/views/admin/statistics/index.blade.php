<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الإحصائيات</title>
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
            background: #8b5cf6;
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
        .stat-item {
            display: inline-block;
            background: #f8fafc;
            padding: 10px 15px;
            border-radius: 6px;
            margin: 2px;
            border-right: 3px solid #8b5cf6;
        }
        .stat-number {
            font-weight: 600;
            color: #8b5cf6;
            font-size: 16px;
        }
        .stat-title {
            color: #374151;
            font-size: 12px;
            margin-top: 2px;
        }
        .stats-preview {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
        }
        .stats-preview h3 {
            color: #8b5cf6;
            margin-bottom: 15px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .stat-card {
            background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
        }
        .stat-card .number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }
        .stat-card .title {
            font-size: 1rem;
            opacity: 0.9;
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
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <h2>
        إدارة الإحصائيات
        <a href="{{ route('admin.statistics.create') }}" class="add-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            إضافة إحصائيات جديدة
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

    @if($statistics->count() === 0)
        <div class="empty-state">
            <h3>لا توجد إحصائيات بعد</h3>
            <p>ابدأ بإضافة إحصائيات جديدة لعرضها في الصفحة الرئيسية</p>
            <a href="{{ route('admin.statistics.create') }}" class="add-btn">إضافة الإحصائيات الأولى</a>
        </div>
    @else
        <!-- معاينة الإحصائيات -->
        <div class="stats-preview">
            <h3>معاينة الإحصائيات في الصفحة الرئيسية</h3>
            <div class="stats-grid">
                {{-- نستخدم المجموعة الكاملة للمعاينة --}}
                @foreach($allStats as $stat)
                <div class="stat-card">
                    <div class="number">{{ is_array($stat) ? $stat['number'] : $stat->number }}</div>
                    <div class="title">{{ is_array($stat) ? $stat['title'] : $stat->title }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>الترتيب</th>
                        <th>الإحصائيات</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statistics as $index => $statistic)
                    <tr>
                        <td>
                            <input type="number" 
                                   class="order-input" 
                                   value="{{ $statistic->order }}" 
                                   min="0" 
                                   onchange="updateOrder({{ $statistic->id }}, this.value)"
                                   style="margin-right: 8px;">
                        </td>
                        <td>
                            <!-- الإحصائية الأساسية -->
                            <div class="stat-item">
                                <div class="stat-number">{{ $statistic->number }}</div>
                                <div class="stat-title">{{ $statistic->title }}</div>
                            </div>
                            
                            <!-- الإحصائيات الإضافية -->
                            @if($statistic->title2)
                            <div class="stat-item">
                                <div class="stat-number">{{ $statistic->number2 }}</div>
                                <div class="stat-title">{{ $statistic->title2 }}</div>
                            </div>
                            @endif
                            
                            @if($statistic->title3)
                            <div class="stat-item">
                                <div class="stat-number">{{ $statistic->number3 }}</div>
                                <div class="stat-title">{{ $statistic->title3 }}</div>
                            </div>
                            @endif
                            
                            @if($statistic->title4)
                            <div class="stat-item">
                                <div class="stat-number">{{ $statistic->number4 }}</div>
                                <div class="stat-title">{{ $statistic->title4 }}</div>
                            </div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.statistics.edit', $statistic) }}" 
                               class="btn edit-btn"
                               title="تعديل الإحصائيات">
                                تعديل
                            </a>
                            
                            {{-- ✅ التعديل الأساسي هنا: استخدام Form للحذف بدلاً من الرابط --}}
                            <form action="{{ route('admin.statistics.destroy', $statistic->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn delete-btn" 
                                        title="حذف الإحصائيات"
                                        onclick="return confirm('هل أنت متأكد من حذف هذه الإحصائيات؟')">
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
    function updateOrder(statId, newOrder) {
        fetch(`/admin/statistics/${statId}/order`, {
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
