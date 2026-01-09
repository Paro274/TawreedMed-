{{-- resources/views/admin/testimonials/index.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>إدارة التقييمات</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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
            background: #3b82f6;
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
            background: #2563eb;
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
            background: #3b82f6;
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
        .toggle-btn { background: #f59e0b; }
        .toggle-btn:hover { background: #d97706; }
        .alert {
            background: #dbeafe;
            color: #1e40af;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #bfdbfe;
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
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .empty-state h3 {
            color: #374151;
            margin-bottom: 10px;
        }
        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e5e7eb;
        }
        .no-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }
        .star-rating {
            color: #fbbf24;
        }
        .star-rating .empty-star {
            color: #d1d5db;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status-active {
            background: #dcfce7;
            color: #166534;
        }
        .status-inactive {
            background: #fef2f2;
            color: #dc2626;
        }
        .governorate-badge {
            background: #e0e7ff;
            color: #3730a3;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.85rem;
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
            إدارة التقييمات
            <a href="{{ route('admin.testimonials.create') }}" class="add-btn">
                <i class="fas fa-plus"></i> إضافة تقييم جديد
            </a>
        </h2>

        @if(session('success'))
            <div class="alert">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($testimonials->count() === 0)
            <div class="empty-state">
                <i class="fas fa-comments fa-3x mb-3" style="color: #d1d5db"></i>
                <h3>لا توجد تقييمات بعد</h3>
                <p>ابدأ بإضافة تقييم جديد لعرضه في الموقع</p>
                <a href="{{ route('admin.testimonials.create') }}" class="add-btn">إضافة أول تقييم</a>
            </div>
        @else
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>الصورة</th>
                            <th>الاسم</th>
                            <th>التقييم</th>
                            <th>التعليق</th>
                            <th>المسمى الوظيفي</th>
                            <th>المحافظة</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($testimonials as $testimonial)
                        <tr>
                            <td>
                                @if($testimonial->image)
                                    <img src="{{ $testimonial->image_url }}" alt="{{ $testimonial->name }}" class="avatar" />
                                @else
                                    <div class="no-avatar">{{ substr($testimonial->name, 0, 1) }}</div>
                                @endif
                            </td>
                            <td>{{ $testimonial->name }}</td>
                            <td>
                                <div class="star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $testimonial->rating ? '' : 'empty-star' }}"></i>
                                    @endfor
                                </div>
                            </td>
                            <td>{{ Str::limit($testimonial->review, 50) }}</td>
                            <td>{{ $testimonial->job_title ?? 'غير محدد' }}</td>
                            <td><span class="governorate-badge">{{ $testimonial->governorate }}</span></td>
                            <td>
                                @if($testimonial->status)
                                    <span class="status-badge status-active">نشط</span>
                                @else
                                    <span class="status-badge status-inactive">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn edit-btn" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.testimonials.toggle', $testimonial) }}" class="btn toggle-btn" title="تغيير الحالة" onclick="return confirm('هل تريد تغيير حالة التقييم؟')">
                                    <i class="fas fa-toggle-{{ $testimonial->status ? 'on' : 'off' }}"></i>
                                </a>
                                <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn delete-btn" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $testimonials->links() }}
        @endif
    </div>
</body>
</html>
