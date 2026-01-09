<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة قسم قصتنا</title>
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
        }
        .page-header h1 {
            color: #8b5cf6;
            margin: 0 0 10px 0;
            font-size: 2rem;
            font-weight: 700;
        }
        .page-header p {
            color: #6b7280;
            margin: 0;
            font-size: 1.1rem;
        }
        .preview-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .preview-section h3 {
            color: #8b5cf6;
            margin: 0 0 20px 0;
            font-size: 1.5rem;
            font-weight: 700;
        }
        .preview-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: start;
        }
        .preview-text h4 {
            color: #374151;
            font-size: 1.8rem;
            margin: 0 0 15px 0;
        }
        .preview-text .description {
            color: #6b7280;
            line-height: 1.8;
        }
        .preview-image {
            text-align: center;
        }
        .preview-image img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .no-image {
            padding: 60px 20px;
            background: #f3f4f6;
            border-radius: 12px;
            text-align: center;
            color: #9ca3af;
        }
        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            padding: 30px;
            max-width: 900px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 1rem;
        }
        .form-control, textarea {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            font-family: "Cairo", sans-serif;
            font-size: 1rem;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        .form-control:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 8px rgba(139, 92, 246, 0.3);
        }
        .file-input input[type=file] {
            width: 100%;
            padding: 12px 16px;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            background: #f9fafb;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .file-input input[type=file]:hover {
            border-color: #8b5cf6;
            background: #f3e8ff;
        }
        .current-image {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            margin-bottom: 15px;
        }
        .current-image img {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #d1d5db;
        }
        button {
            background: #8b5cf6;
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
            background: #7c3aed;
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
            background: #f3e8ff;
            color: #6b21a8;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #e9d5ff;
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
        .form-text {
            font-size: 0.9rem;
            color: #6b7280;
            margin-top: 5px;
        }
        @media (max-width: 768px) {
            .content {
                margin-right: 0;
                padding: 20px;
            }
            .preview-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="page-header">
        <h1><i class="fas fa-book-open me-3"></i>قصتنا</h1>
        <p>تحرير محتوى قسم "قصتنا" في الموقع</p>
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

    <!-- معاينة القسم -->
    <div class="preview-section">
        <h3><i class="fas fa-eye me-2"></i>معاينة قصتنا</h3>
        <div class="preview-content">
            <div class="preview-text">
                <h4>{{ $story->title ?? 'عنوان قصتنا' }}</h4>
                <div class="description">
                    {!! $story->description ?? '<p>لا يوجد محتوى بعد</p>' !!}
                </div>
            </div>
            <div class="preview-image">
                @if($story && $story->image)
                    <!-- تم التعديل هنا لإزالة storage واستخدام المسار المباشر -->
                    <img src="{{ asset($story->image) }}" alt="صورة قصتنا">
                @else
                    <div class="no-image">
                        <i class="fas fa-image" style="font-size: 3rem;"></i>
                        <p style="margin: 10px 0 0 0;">لا توجد صورة</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- نموذج التحديث -->
    <div class="form-container">
        <form method="POST" action="{{ route('admin.story.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">العنوان</label>
                <input type="text" 
                       name="title" 
                       class="form-control"
                       value="{{ old('title', $story->title ?? '') }}" 
                       placeholder="مثال: قصتنا - رحلة النجاح">
                <div class="form-text">العنوان الرئيسي للقسم</div>
            </div>

            <div class="form-group">
                <label class="form-label">الوصف (محرر نصوص)</label>
                <textarea id="description" 
                          name="description" 
                          placeholder="اكتب قصتك هنا... يمكنك استخدام التنسيق الغني">{{ old('description', $story->description ?? '') }}</textarea>
                <div class="form-text">استخدم المحرر لإضافة نصوص منسقة مع عناوين وصور</div>
            </div>

            <div class="form-group">
                @if($story && $story->image)
                    <label class="form-label">الصورة الحالية</label>
                    <div class="current-image">
                        <!-- تم التعديل هنا لإزالة storage واستخدام المسار المباشر -->
                        <img src="{{ asset($story->image) }}" alt="صورة قصتنا">
                        <div>
                            <h6 style="margin: 0 0 5px 0; color: #374151;">{{ basename($story->image) }}</h6>
                            <small style="color: #6b7280;">الصورة الحالية</small>
                        </div>
                    </div>
                @endif

                <label class="form-label">{{ $story && $story->image ? 'تغيير الصورة' : 'رفع صورة' }}</label>
                <div class="file-input">
                    <input type="file" 
                           name="image" 
                           accept="image/*">
                </div>
                <div class="form-text">PNG, JPG, GIF حتى 2 ميجابايت</div>
            </div>

            <div style="margin-top: 30px;">
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
