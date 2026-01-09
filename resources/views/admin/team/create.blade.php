<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة عضو فريق</title>
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
        .back-btn {
            background: #6b7280;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }
        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            padding: 30px;
            max-width: 800px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .form-control, textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-family: "Cairo", sans-serif;
            font-size: 1rem;
            box-sizing: border-box;
        }
        .form-control:focus, textarea:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 8px rgba(99, 102, 241, 0.3);
        }
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        .required {
            color: #ef4444;
        }
        button {
            background: #6366f1;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }
        button:hover {
            background: #4f46e5;
        }
        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .content {
                margin-right: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="page-header">
        <h1><i class="fas fa-user-plus me-3"></i>إضافة عضو جديد</h1>
        <a href="{{ route('admin.team.index') }}" class="back-btn">
            <i class="fas fa-arrow-right"></i> العودة
        </a>
    </div>

    @if($errors->any())
        <div class="alert-error">
            <ul style="margin: 0; padding-right: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">
        <form method="POST" action="{{ route('admin.team.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">الاسم <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="مثال: أحمد محمد" required>
            </div>

            <div class="form-group">
                <label class="form-label">المسمى الوظيفي <span class="required">*</span></label>
                <input type="text" name="job_title" class="form-control" value="{{ old('job_title') }}" placeholder="مثال: المدير التنفيذي" required>
            </div>

            <div class="form-group">
                <label class="form-label">الوصف</label>
                <textarea name="description" class="form-control" placeholder="اكتب نبذة عن العضو...">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">الصورة الشخصية</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <button type="submit">
                <i class="fas fa-save"></i> حفظ العضو
            </button>
        </form>
    </div>
</div>

</body>
</html>
