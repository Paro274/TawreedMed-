<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فريق العمل</title>
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
            color: #6366f1;
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }
        .btn-add {
            background: #6366f1;
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
            background: #4f46e5;
        }
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
        .team-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            text-align: center;
            transition: 0.3s;
        }
        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }
        .team-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            border: 4px solid #6366f1;
        }
        .no-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #9ca3af;
            font-size: 3rem;
        }
        .team-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: #374151;
            margin: 0 0 8px 0;
        }
        .team-job {
            font-size: 1rem;
            color: #6366f1;
            font-weight: 600;
            margin: 0 0 15px 0;
        }
        .team-desc {
            font-size: 0.95rem;
            color: #6b7280;
            line-height: 1.6;
            margin: 0 0 20px 0;
        }
        .team-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
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
            background: #dbeafe;
            color: #1e40af;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #bfdbfe;
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
            .team-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="page-header">
        <h1><i class="fas fa-users me-3"></i>فريق العمل</h1>
        <a href="{{ route('admin.team.create') }}" class="btn-add">
            <i class="fas fa-plus"></i>
            إضافة عضو جديد
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($members->count() > 0)
        <div class="team-grid">
            @foreach($members as $member)
                <div class="team-card">
                    @if($member->image)
                    <img src="{{ asset($member->image) }}" alt="{{ $member->name }}" class="team-image">
                    @else
                        <div class="no-image">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <h3 class="team-name">{{ $member->name }}</h3>
                    <p class="team-job">{{ $member->job_title }}</p>
                    <p class="team-desc">{{ Str::limit($member->description, 100) }}</p>
                    <div class="team-actions">
                        <a href="{{ route('admin.team.edit', $member) }}" class="btn-edit">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form method="POST" action="{{ route('admin.team.destroy', $member) }}" style="display: inline;">
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
            <i class="fas fa-users" style="font-size: 4rem; color: #d1d5db; margin-bottom: 20px;"></i>
            <p style="color: #6b7280; font-size: 1.2rem;">لا يوجد أعضاء في الفريق بعد.</p>
            <a href="{{ route('admin.team.create') }}" class="btn-add" style="margin-top: 20px;">
                <i class="fas fa-plus"></i>
                إضافة أول عضو
            </a>
        </div>
    @endif
</div>

</body>
</html>
