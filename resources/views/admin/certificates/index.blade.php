<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الشهادات والجوائز</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        .page-header {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .page-header h1 {
            color: #d97706;
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }
        .btn-add {
            background: #d97706;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-add:hover {
            background: #b45309;
        }
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }
        .item-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: 0.3s;
            border-right: 4px solid #d97706;
        }
        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }
        .item-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin: 0 auto 20px;
        }
        .item-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #374151;
            margin: 0 0 12px 0;
            text-align: center;
        }
        .item-desc {
            color: #6b7280;
            line-height: 1.7;
            text-align: center;
            margin: 0 0 20px 0;
        }
        .item-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }
        .btn-edit, .btn-delete {
            padding: 8px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn-edit {
            background: #059669;
            color: white;
        }
        .btn-edit:hover {
            background: #047857;
        }
        .btn-delete {
            background: #ef4444;
            color: white;
        }
        .btn-delete:hover {
            background: #dc2626;
        }
        .alert-success {
            background: #fef3c7;
            color: #78350f;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fde68a;
        }
        @media (max-width: 768px) {
            .content {
                margin-right: 0;
                padding: 20px;
            }
            .page-header {
                flex-direction: column;
                gap: 15px;
            }
            .items-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="page-header">
        <h1><i class="fas fa-trophy me-3"></i>الشهادات والجوائز</h1>
        <a href="{{ route('admin.certificates.create') }}" class="btn-add">
            <i class="fas fa-plus"></i>
            إضافة جديد
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($items->count() > 0)
        <div class="items-grid">
            @foreach($items as $item)
                <div class="item-card">
                    <div class="item-icon">
                        <i class="fas fa-{{ $item->icon }}"></i>
                    </div>
                    <h3 class="item-title">{{ $item->title }}</h3>
                    <p class="item-desc">{{ $item->description }}</p>
                    <div class="item-actions">
                        <a href="{{ route('admin.certificates.edit', $item) }}" class="btn-edit">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form method="POST" action="{{ route('admin.certificates.destroy', $item) }}" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 60px; background: white; border-radius: 12px;">
            <i class="fas fa-trophy" style="font-size: 4rem; color: #d1d5db; margin-bottom: 20px;"></i>
            <p style="color: #6b7280; font-size: 1.2rem;">لا توجد شهادات أو جوائز بعد.</p>
            <a href="{{ route('admin.certificates.create') }}" class="btn-add" style="margin-top: 20px;">
                <i class="fas fa-plus"></i>
                إضافة أول عنصر
            </a>
        </div>
    @endif
</div>

</body>
</html>
