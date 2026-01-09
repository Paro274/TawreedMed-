<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المميزات</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            color: #059669;
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }
        .btn-add {
            background: #059669;
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
            background: #047857;
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
            border-right: 4px solid #059669;
            position: relative;
        }
        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }
        .item-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
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
            height: 60px; /* Fixed height for description */
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
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
            background: #3b82f6;
            color: white;
        }
        .btn-edit:hover {
            background: #2563eb;
        }
        .btn-delete {
            background: #ef4444;
            color: white;
        }
        .btn-delete:hover {
            background: #dc2626;
        }
        .alert-success {
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
        .order-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #f3f4f6;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .order-input {
            width: 40px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            text-align: center;
            padding: 2px;
            font-size: 0.8rem;
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
        <h1><i class="fas fa-star me-3"></i>إدارة المميزات</h1>
        <a href="{{ route('admin.features.create') }}" class="btn-add">
            <i class="fas fa-plus"></i>
            إضافة ميزة جديدة
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($features->count() > 0)
        <div class="items-grid">
            @foreach($features as $feature)
                <div class="item-card">
                    <div class="order-badge">
                        <i class="fas fa-sort"></i>
                        <input type="number" 
                               class="order-input" 
                               value="{{ $feature->order }}" 
                               min="0" 
                               onchange="updateOrder({{ $feature->id }}, this.value)"
                               title="تغيير الترتيب">
                    </div>
                    
                    <div class="item-icon">
                        <i class="fas fa-{{ $feature->icon }}"></i>
                    </div>
                    <h3 class="item-title">{{ $feature->title }}</h3>
                    <p class="item-desc">{{ $feature->description }}</p>
                    <div class="item-actions">
                        <a href="{{ route('admin.features.edit', $feature) }}" class="btn-edit">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form method="POST" action="{{ route('admin.features.destroy', $feature) }}" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('هل أنت متأكد من حذف ميزة &quot;{{ $feature->title }}&quot;؟')">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 60px; background: white; border-radius: 12px;">
            <i class="fas fa-star" style="font-size: 4rem; color: #d1d5db; margin-bottom: 20px;"></i>
            <p style="color: #6b7280; font-size: 1.2rem;">لا توجد مميزات مضافة بعد.</p>
            <a href="{{ route('admin.features.create') }}" class="btn-add" style="margin-top: 20px;">
                <i class="fas fa-plus"></i>
                إضافة أول ميزة
            </a>
        </div>
    @endif
</div>

<script>
    function updateOrder(featureId, newOrder) {
        fetch(`/admin/features/${featureId}/order`, {
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
                // Optional: Show a toast notification
                // location.reload(); // Reloading might be annoying, maybe just visual feedback
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
