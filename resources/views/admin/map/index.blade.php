<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الموقع على الخريطة</title>
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
            color: #ef4444;
            margin: 0 0 10px 0;
            font-size: 2rem;
            font-weight: 700;
        }
        .map-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        .map-container iframe {
            width: 100%;
            height: 450px;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
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
            border-color: #ef4444;
            box-shadow: 0 0 8px rgba(239, 68, 68, 0.3);
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        button {
            background: #ef4444;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
        }
        button:hover {
            background: #dc2626;
        }
        .alert-success {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fecaca;
        }
        .form-text {
            font-size: 0.9rem;
            color: #6b7280;
            margin-top: 5px;
        }
        .info-box {
            background: #fef3c7;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .info-box h4 {
            color: #92400e;
            margin: 0 0 10px 0;
            font-size: 1rem;
        }
        .info-box ol {
            margin: 5px 0 0 20px;
            padding: 0;
            color: #78350f;
        }
        .info-box li {
            margin: 5px 0;
            font-size: 0.9rem;
        }
        .info-box code {
            background: #fff;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.85rem;
            color: #ef4444;
            direction: ltr;
            display: inline-block;
        }
        @media (max-width: 768px) {
            .content {
                margin-right: 0;
                padding: 20px;
            }
            .map-container iframe {
                height: 300px;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="page-header">
        <h1><i class="fas fa-map-marked-alt me-3"></i>الموقع على الخريطة</h1>
        <p style="color: #6b7280; margin: 5px 0 0 0;">إدارة موقع الشركة على خرائط Google</p>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- معاينة الخريطة -->
    @if($location && $location->map_link)
        <div class="map-container">
            <h3 style="color: #374151; margin: 0 0 20px 0;"><i class="fas fa-map me-2"></i>معاينة الموقع الحالي</h3>
            
            @php
                // تنظيف الكود والتعامل مع أنواع الروابط المختلفة
                $mapCode = $location->map_link;
                
                // إذا كان المستخدم لصق iframe كامل، استخرج الـ src منه
                if (strpos($mapCode, '<iframe') !== false) {
                    preg_match('/src="([^"]+)"/', $mapCode, $matches);
                    $mapUrl = $matches[1] ?? $mapCode;
                } else {
                    $mapUrl = $mapCode;
                }
            @endphp
            
            <iframe src="{{ $mapUrl }}" 
                    width="100%" 
                    height="450" 
                    style="border:0; border-radius: 12px;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
            </iframe>
            
            @if($location->address)
                <p style="color: #6b7280; margin: 15px 0 0 0; text-align: center;">
                    <i class="fas fa-map-pin me-2"></i>{{ $location->address }}
                </p>
            @endif
        </div>
    @endif

    <!-- نموذج التعديل -->
    <div class="form-container">
        <h3 style="color: #374151; margin: 0 0 20px 0;"><i class="fas fa-edit me-2"></i>تحديث الموقع</h3>

        <div class="info-box">
            <h4><i class="fas fa-info-circle me-2"></i>كيف تحصل على كود الخريطة الصحيح؟</h4>
            <ol>
                <li>افتح <a href="https://www.google.com/maps" target="_blank" style="color: #ef4444; font-weight: 600;">خرائط Google</a></li>
                <li>ابحث عن موقعك أو حدده على الخريطة</li>
                <li>اضغط على زر <strong>"مشاركة"</strong> (Share)</li>
                <li>اختر تبويب <strong>"تضمين خريطة"</strong> (Embed a map)</li>
                <li>انسخ الكود الكامل أو فقط الرابط الذي يبدأ بـ odede>https://www.google.com/maps/embed?pb=...</code></li>
                <li>الصقه في الحقل أدناه</li>
            </ol>
            <p style="margin: 10px 0 0 0; padding: 10px; background: #fff; border-radius: 6px; font-size: 0.85rem; color: #78350f;">
                <strong>ملاحظة مهمة:</strong> يجب استخدام رابط "Embed" وليس رابط المشاركة العادي. رابط Embed يبدأ بـ<br>
                <code>https://www.google.com/maps/embed?pb=...</code>
            </p>
        </div>

        <form method="POST" action="{{ route('admin.map.update') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">رابط/كود Google Maps Embed <span style="color: #ef4444;">*</span></label>
                <textarea name="map_link" 
                          class="form-control"
                          rows="5"
                          placeholder="الصق كود iframe الكامل أو رابط embed هنا
مثال: https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d..."
                          required>{{ old('map_link', $location->map_link ?? '') }}</textarea>
                <div class="form-text">
                    الصق رابط embed أو كود iframe الكامل من Google Maps<br>
                    <strong>مهم:</strong> استخدم رابط "تضمين خريطة" (Embed) وليس رابط المشاركة العادي
                </div>
                @error('map_link')
                    <div style="color: #ef4444; font-size: 0.9rem; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">العنوان النصي (اختياري)</label>
                <textarea name="address" 
                          class="form-control"
                          rows="2"
                          placeholder="مثال: شارع الهرم، الجيزة، مصر">{{ old('address', $location->address ?? '') }}</textarea>
                <div class="form-text">اسم أو وصف المكان (سيظهر تحت الخريطة)</div>
            </div>

            <button type="submit">
                <i class="fas fa-save"></i>
                حفظ التغييرات
            </button>
        </form>
    </div>
</div>

</body>
</html>
