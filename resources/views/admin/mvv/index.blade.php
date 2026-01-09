<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>رسالتنا ورؤيتنا وقيمنا</title>
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
            color: #0ea5e9;
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
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        .preview-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: 0.3s;
            border-top: 4px solid;
        }
        .preview-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }
        .preview-card.mission {
            border-top-color: #3b82f6;
        }
        .preview-card.vision {
            border-top-color: #10b981;
        }
        .preview-card.values {
            border-top-color: #f59e0b;
        }
        .preview-card h3 {
            font-size: 1.5rem;
            margin: 0 0 15px 0;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .preview-card.mission h3 { color: #3b82f6; }
        .preview-card.vision h3 { color: #10b981; }
        .preview-card.values h3 { color: #f59e0b; }
        .preview-card .description {
            color: #6b7280;
            line-height: 1.8;
        }
        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        .mission .icon-circle { background: #3b82f6; }
        .vision .icon-circle { background: #10b981; }
        .values .icon-circle { background: #f59e0b; }
        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            padding: 30px;
            max-width: 1200px;
        }
        .section-divider {
            border-top: 2px solid #e5e7eb;
            margin: 40px 0 30px 0;
            padding-top: 20px;
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
        .form-control {
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
            border-color: #0ea5e9;
            box-shadow: 0 0 8px rgba(14, 165, 233, 0.3);
        }
        button {
            background: #0ea5e9;
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
            background: #0284c7;
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
            background: #dbeafe;
            color: #1e40af;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #bfdbfe;
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
        .section-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }
        @media (max-width: 768px) {
            .content {
                margin-right: 0;
                padding: 20px;
            }
            .preview-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="page-header">
        <h1><i class="fas fa-heart me-3"></i>رسالتنا ورؤيتنا وقيمنا</h1>
        <p>تحرير محتوى رسالتنا ورؤيتنا وقيمنا الخاصة بالشركة</p>
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

    <!-- معاينة الأقسام -->
    <div class="preview-section">
        <div class="preview-card mission">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                <div class="icon-circle">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <h3 style="margin: 0;">{{ $mvv->mission_title ?? 'رسالتنا' }}</h3>
            </div>
            <div class="description">
                {!! $mvv->mission_description ?? '<p>لا يوجد محتوى</p>' !!}
            </div>
        </div>

        <div class="preview-card vision">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                <div class="icon-circle">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 style="margin: 0;">{{ $mvv->vision_title ?? 'رؤيتنا' }}</h3>
            </div>
            <div class="description">
                {!! $mvv->vision_description ?? '<p>لا يوجد محتوى</p>' !!}
            </div>
        </div>

        <div class="preview-card values">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                <div class="icon-circle">
                    <i class="fas fa-gem"></i>
                </div>
                <h3 style="margin: 0;">{{ $mvv->values_title ?? 'قيمنا' }}</h3>
            </div>
            <div class="description">
                {!! $mvv->values_description ?? '<p>لا يوجد محتوى</p>' !!}
            </div>
        </div>
    </div>

    <!-- نموذج التحديث -->
    <div class="form-container">
        <form method="POST" action="{{ route('admin.mvv.update') }}">
            @csrf

            <!-- الرسالة -->
            <div class="section-header">
                <div class="icon-circle mission">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <h3 style="color: #3b82f6; margin: 0; font-size: 1.5rem;">رسالتنا (Mission)</h3>
            </div>

            <div class="form-group">
                <label class="form-label">عنوان الرسالة</label>
                <input type="text" 
                       name="mission_title" 
                       class="form-control"
                       value="{{ old('mission_title', $mvv->mission_title ?? '') }}" 
                       placeholder="مثال: رسالتنا">
                <div class="form-text">العنوان الرئيسي لقسم الرسالة</div>
            </div>

            <div class="form-group">
                <label class="form-label">وصف الرسالة</label>
                <textarea id="mission_description" 
                          name="mission_description" 
                          placeholder="اكتب رسالة الشركة هنا...">{{ old('mission_description', $mvv->mission_description ?? '') }}</textarea>
                <div class="form-text">ما الذي تسعى الشركة لتحقيقه؟</div>
            </div>

            <!-- الرؤية -->
            <div class="section-divider">
                <div class="section-header">
                    <div class="icon-circle vision">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 style="color: #10b981; margin: 0; font-size: 1.5rem;">رؤيتنا (Vision)</h3>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">عنوان الرؤية</label>
                <input type="text" 
                       name="vision_title" 
                       class="form-control"
                       value="{{ old('vision_title', $mvv->vision_title ?? '') }}" 
                       placeholder="مثال: رؤيتنا">
                <div class="form-text">العنوان الرئيسي لقسم الرؤية</div>
            </div>

            <div class="form-group">
                <label class="form-label">وصف الرؤية</label>
                <textarea id="vision_description" 
                          name="vision_description" 
                          placeholder="اكتب رؤية الشركة هنا...">{{ old('vision_description', $mvv->vision_description ?? '') }}</textarea>
                <div class="form-text">ما هو التطلع المستقبلي للشركة؟</div>
            </div>

            <!-- القيم -->
            <div class="section-divider">
                <div class="section-header">
                    <div class="icon-circle values">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3 style="color: #f59e0b; margin: 0; font-size: 1.5rem;">قيمنا (Values)</h3>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">عنوان القيم</label>
                <input type="text" 
                       name="values_title" 
                       class="form-control"
                       value="{{ old('values_title', $mvv->values_title ?? '') }}" 
                       placeholder="مثال: قيمنا">
                <div class="form-text">العنوان الرئيسي لقسم القيم</div>
            </div>

            <div class="form-group">
                <label class="form-label">وصف القيم</label>
                <textarea id="values_description" 
                          name="values_description" 
                          placeholder="اكتب قيم الشركة هنا...">{{ old('values_description', $mvv->values_description ?? '') }}</textarea>
                <div class="form-text">ما هي القيم والمبادئ التي تلتزم بها الشركة؟</div>
            </div>

            <div style="margin-top: 30px;">
                <button type="submit">
                    <i class="fas fa-save"></i>
                    حفظ جميع التغييرات
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
