<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة قسم CTA</title>
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
            color: #f59e0b;
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
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
        }
        .preview-section h2 {
            font-size: 2.5rem;
            margin: 0 0 15px 0;
            font-weight: 700;
        }
        .preview-section p {
            font-size: 1.2rem;
            margin: 0 0 30px 0;
            opacity: 0.95;
            line-height: 1.6;
        }
        .preview-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .preview-btn {
            padding: 15px 35px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            transition: 0.3s;
            display: inline-block;
        }
        .preview-btn-primary {
            background: white;
            color: #f59e0b;
        }
        .preview-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(255,255,255,0.3);
        }
        .preview-btn-secondary {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
        }
        .preview-btn-secondary:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-3px);
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
        .form-control:focus, textarea:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 8px rgba(245, 158, 11, 0.3);
        }
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        button {
            background: #f59e0b;
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
            background: #d97706;
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
        .form-text {
            font-size: 0.9rem;
            color: #6b7280;
            margin-top: 5px;
        }
        .section-divider {
            border-top: 2px solid #e5e7eb;
            margin: 30px 0;
            padding-top: 20px;
        }
        @media (max-width: 768px) {
            .content {
                margin-right: 0;
                padding: 20px;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
            .preview-section h2 {
                font-size: 1.8rem;
            }
            .preview-section p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="page-header">
        <h1><i class="fas fa-bullhorn me-3"></i>إدارة قسم CTA</h1>
        <p>قسم الدعوة للعمل (Call to Action) - يظهر في الصفحة الرئيسية</p>
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
        <h2>{{ $cta->title ?? 'عنوان القسم' }}</h2>
        <p>{{ $cta->description ?? 'الوصف الخاص بالقسم' }}</p>
        <div class="preview-buttons">
            @if($cta->button1_text)
                <a href="{{ $cta->button1_link ?? '#' }}" class="preview-btn preview-btn-primary">
                    {{ $cta->button1_text }}
                </a>
            @endif
            @if($cta->button2_text)
                <a href="{{ $cta->button2_link ?? '#' }}" class="preview-btn preview-btn-secondary">
                    {{ $cta->button2_text }}
                </a>
            @endif
        </div>
    </div>

    <!-- نموذج التحديث -->
    <div class="form-container">
        <form method="POST" action="{{ route('admin.cta.update') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">العنوان الرئيسي</label>
                <input type="text" 
                       name="title" 
                       class="form-control"
                       value="{{ old('title', $cta->title ?? '') }}" 
                       placeholder="مثال: ابدأ رحلتك معنا الآن">
                <div class="form-text">العنوان الجذاب الذي يجذب انتباه الزوار</div>
            </div>

            <div class="form-group">
                <label class="form-label">الوصف</label>
                <textarea name="description" 
                          class="form-control"
                          placeholder="أدخل وصف مختصر يشجع الزوار على اتخاذ إجراء">{{ old('description', $cta->description ?? '') }}</textarea>
                <div class="form-text">وصف قصير يوضح الفائدة أو العرض</div>
            </div>

            <div class="section-divider">
                <h3 style="color: #374151; margin: 0 0 20px 0; font-size: 1.3rem;">
                    <i class="fas fa-mouse-pointer me-2"></i>الأزرار
                </h3>
            </div>

            <!-- الزر الأول -->
            <h4 style="color: #f59e0b; margin: 0 0 15px 0;">الزر الأول</h4>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">نص الزر الأول</label>
                    <input type="text" 
                           name="button1_text" 
                           class="form-control"
                           value="{{ old('button1_text', $cta->button1_text ?? '') }}" 
                           placeholder="مثال: اتصل بنا">
                    <div class="form-text">النص الذي يظهر على الزر</div>
                </div>

                <div class="form-group">
                    <label class="form-label">رابط الزر الأول</label>
                    <input type="text" 
                           name="button1_link" 
                           class="form-control"
                           value="{{ old('button1_link', $cta->button1_link ?? '') }}" 
                           placeholder="مثال: /contact أو https://...">
                    <div class="form-text">الرابط المؤدي إليه عند الضغط</div>
                </div>
            </div>

            <!-- الزر الثاني -->
            <h4 style="color: #f59e0b; margin: 20px 0 15px 0;">الزر الثاني</h4>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">نص الزر الثاني</label>
                    <input type="text" 
                           name="button2_text" 
                           class="form-control"
                           value="{{ old('button2_text', $cta->button2_text ?? '') }}" 
                           placeholder="مثال: تصفح المنتجات">
                    <div class="form-text">النص الذي يظهر على الزر الثاني</div>
                </div>

                <div class="form-group">
                    <label class="form-label">رابط الزر الثاني</label>
                    <input type="text" 
                           name="button2_link" 
                           class="form-control"
                           value="{{ old('button2_link', $cta->button2_link ?? '') }}" 
                           placeholder="مثال: /products أو https://...">
                    <div class="form-text">الرابط المؤدي إليه عند الضغط</div>
                </div>
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
