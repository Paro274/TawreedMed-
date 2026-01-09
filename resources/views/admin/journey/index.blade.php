<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رحلتنا</title>
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
            color: #ec4899;
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }
        .btn-add {
            background: #ec4899;
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
            background: #db2777;
        }
        .timeline {
            position: relative;
            padding: 20px 0;
        }
        .timeline::before {
            content: '';
            position: absolute;
            right: 50%;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, #ec4899 0%, #db2777 100%);
        }
        .timeline-item {
            position: relative;
            margin-bottom: 50px;
            width: 45%;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .timeline-item:nth-child(odd) {
            margin-right: auto;
            margin-left: 55%;
        }
        .timeline-item:nth-child(even) {
            margin-left: auto;
            margin-right: 55%;
        }
        .timeline-marker {
            position: absolute;
            top: 30px;
            width: 30px;
            height: 30px;
            background: #ec4899;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 0 0 3px #ec4899;
        }
        .timeline-item:nth-child(odd) .timeline-marker {
            left: -63px;
        }
        .timeline-item:nth-child(even) .timeline-marker {
            right: -63px;
        }
        .year-badge {
            display: inline-block;
            background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }
        .journey-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #374151;
            margin: 0 0 12px 0;
        }
        .journey-desc {
            color: #6b7280;
            line-height: 1.7;
            margin: 0 0 20px 0;
        }
        .journey-actions {
            display: flex;
            gap: 10px;
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
            background: #f59e0b;
            color: white;
        }
        .btn-edit:hover {
            background: #d97706;
        }
        .btn-delete {
            background: #ef4444;
            color: white;
        }
        .btn-delete:hover {
            background: #dc2626;
        }
        .alert-success {
            background: #fce7f3;
            color: #9f1239;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fbcfe8;
        }
        @media (max-width: 768px) {
            .content {
                margin-right: 0;
                padding: 20px;
            }
            .timeline::before {
                right: 20px;
            }
            .timeline-item {
                width: calc(100% - 60px);
                margin-left: 60px !important;
                margin-right: 0 !important;
            }
            .timeline-marker {
                right: -45px !important;
                left: auto !important;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="page-header">
        <h1><i class="fas fa-route me-3"></i>رحلتنا</h1>
        <a href="{{ route('admin.journey.create') }}" class="btn-add">
            <i class="fas fa-plus"></i>
            إضافة مرحلة جديدة
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($journeys->count() > 0)
        <div class="timeline">
            @foreach($journeys as $journey)
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <span class="year-badge">{{ $journey->year }}</span>
                    <h3 class="journey-title">{{ $journey->title }}</h3>
                    <p class="journey-desc">{{ $journey->description }}</p>
                    <div class="journey-actions">
                        <a href="{{ route('admin.journey.edit', $journey) }}" class="btn-edit">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form method="POST" action="{{ route('admin.journey.destroy', $journey) }}" style="display: inline;">
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
            <i class="fas fa-route" style="font-size: 4rem; color: #d1d5db; margin-bottom: 20px;"></i>
            <p style="color: #6b7280; font-size: 1.2rem;">لا توجد مراحل في الرحلة بعد.</p>
            <a href="{{ route('admin.journey.create') }}" class="btn-add" style="margin-top: 20px;">
                <i class="fas fa-plus"></i>
                إضافة أول مرحلة
            </a>
        </div>
    @endif
</div>

</body>
</html>
