<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الأسئلة الشائعة</title>
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
            color: #0ea5e9;
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }
        .btn-add {
            background: #0ea5e9;
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
            background: #0284c7;
        }
        .faq-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .faq-item {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: 0.3s;
            border-right: 4px solid #0ea5e9;
        }
        .faq-item:hover {
            transform: translateX(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }
        .faq-question {
            font-size: 1.2rem;
            font-weight: 700;
            color: #374151;
            margin: 0 0 15px 0;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .faq-question i {
            color: #0ea5e9;
            margin-top: 3px;
        }
        .faq-answer {
            color: #6b7280;
            line-height: 1.7;
            margin: 0 0 20px 0;
            padding-right: 28px;
        }
        .faq-actions {
            display: flex;
            gap: 10px;
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
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="page-header">
        <h1><i class="fas fa-question-circle me-3"></i>الأسئلة الشائعة</h1>
        <a href="{{ route('admin.faqs.create') }}" class="btn-add">
            <i class="fas fa-plus"></i>
            إضافة سؤال جديد
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($faqs->count() > 0)
        <div class="faq-list">
            @foreach($faqs as $faq)
                <div class="faq-item">
                    <h3 class="faq-question">
                        <i class="fas fa-question-circle"></i>
                        <span>{{ $faq->question }}</span>
                    </h3>
                    <p class="faq-answer">{{ $faq->answer }}</p>
                    <div class="faq-actions">
                        <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn-edit">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" style="display: inline;">
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
            <i class="fas fa-question-circle" style="font-size: 4rem; color: #d1d5db; margin-bottom: 20px;"></i>
            <p style="color: #6b7280; font-size: 1.2rem;">لا توجد أسئلة شائعة بعد.</p>
            <a href="{{ route('admin.faqs.create') }}" class="btn-add" style="margin-top: 20px;">
                <i class="fas fa-plus"></i>
                إضافة أول سؤال
            </a>
        </div>
    @endif
</div>

</body>
</html>
