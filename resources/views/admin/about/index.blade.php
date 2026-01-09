<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>إدارة - من نحن</title>
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
        .page-header {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        .page-header h1 {
            color: #059669;
            margin: 0 0 10px 0;
            font-size: 2rem;
            font-weight: 700;
        }
        .page-header p {
            color: #6b7280;
            margin: 0;
            font-size: 1.1rem;
        }
        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            padding: 30px;
            max-width: 800px;
        }
        label {
            font-weight: 600;
            color: #374151;
            display: block;
            margin-bottom: 8px;
        }
        input[type="text"], textarea, input[type="file"] {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            font-family: "Cairo", sans-serif;
            font-size: 1rem;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        input[type="text"]:focus, textarea:focus, input[type="file"]:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 8px rgba(5, 150, 105, 0.3);
        }
        textarea {
            min-height: 250px;
            resize: vertical;
        }
        input[type="file"] {
            border: 1px dashed #d1d5db;
            background: #f9fafb;
        }
        button {
            background: #059669;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background: #047857;
        }
        a.btn-secondary {
            background: #6b7280;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin-left: 15px;
            transition: background-color 0.3s;
        }
        a.btn-secondary:hover {
            background: #4b5563;
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
            font-weight: 500;
        }
        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fecaca;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }
        .about-preview {
            background: #f9fafb;
            padding: 20px;
            border-radius: 10px;
            color: #374151;
            margin-bottom: 30px;
            line-height: 1.6;
            box-shadow: inset 0 0 1px #ccc;
        }
        .image-preview {
            max-width: 300px;
            max-height: 200px;
            border-radius: 8px;
            margin-top: 10px;
            display: block;
            object-fit: cover;
        }
        .no-image {
            color: #9ca3af;
            font-style: italic;
            padding: 20px;
            text-align: center;
            background: #f3f4f6;
            border-radius: 8px;
            margin-top: 10px;
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
            <h1><i class="fas fa-info-circle me-3"></i>من نحن</h1>
            <p>تحرير محتوى قسم "من نحن" في الموقع</p>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                يرجى تصحيح الأخطاء التالية:
                <ul style="margin: 10px 0 0 20px; text-align: right;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Preview للمحتوى الحالي -->
        <div class="about-preview">
            <h3><i class="fas fa-eye"></i> معاينة المحتوى الحالي:</h3>
            @if($about && $about->title)
                <h4>{{ $about->title }}</h4>
                <p>{!! $about->description ?? '<p class="text-muted">لا توجد محتويات بعد لعرضها.</p>' !!}</p>
                
                @if($about->image)
                    <img src="{{ asset($about->image) }}" alt="صورة من نحن" class="image-preview">
                @else
                    <div class="no-image">
                        <i class="fas fa-image" style="font-size: 3rem; color: #d1d5db;"></i>
                        <p>لا توجد صورة حالياً. أضف صورة من أسفل.</p>
                    </div>
                @endif
            @else
                <p class="text-muted">لا توجد بيانات محفوظة بعد. أدخل المحتوى أدناه.</p>
            @endif
        </div>

        <div class="form-container">
            <form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data">
                @csrf
                <label for="title">العنوان</label>
                <input type="text" id="title" name="title" maxlength="100" value="{{ old('title', $about->title ?? '') }}" placeholder="مثل: من نحن؟" />

                <label for="description" style="margin-top: 20px;">المحتوى (يمكن إضافة HTML)</label>
                <textarea id="description" name="description" placeholder="أدخل محتوى قسم من نحن بحيث يمكن إضافة HTML">{{ old('description', $about->description ?? '') }}</textarea>

                <label for="image" style="margin-top: 20px;">صورة قسم من نحن (اختياري، PNG/JPG، max 2MB)</label>
                <input type="file" id="image" name="image" accept="image/*" />
                @if($about && $about->image)
                    <small style="color: #6b7280;">الصورة الحالية: <a href="{{ asset($about->image) }}" target="_blank">عرض الصورة</a></small>
                @endif

                <div style="margin-top: 20px;">
                    <button type="submit">
                        <i class="fas fa-save"></i>
                        حفظ التغييرات
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                        العودة للرئيسية
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
