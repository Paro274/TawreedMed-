<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل البانر - {{ $slider->title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
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
        .form-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        h2 {
            color: #333;
            margin-bottom: 25px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
        }
        input[type="text"], 
        input[type="url"], 
        input[type="file"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-family: "Cairo", sans-serif;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus, 
        input[type="url"]:focus, 
        input[type="file"]:focus,
        textarea:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .btn {
            background: #4f46e5;
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
            margin-top: 10px;
        }
        .btn:hover {
            background: #4338ca;
            transform: translateY(-1px);
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .error {
            color: #ef4444;
            font-size: 14px;
            margin-top: 5px;
        }
        .success {
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #bbf7d0;
            text-align: center;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .button-group {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            background: #f9fafb;
            margin-bottom: 25px;
        }
        .button-group h4 {
            margin: 0 0 15px 0;
            color: #374151;
            font-size: 18px;
        }
        .optional {
            color: #6b7280;
            font-size: 14px;
            margin-top: 5px;
        }
        .current-buttons {
            background: #f0f9ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #3b82f6;
        }
        .current-buttons h5 {
            margin: 0 0 10px 0;
            color: #1e40af;
        }
        .current-buttons p {
            margin: 5px 0;
            color: #374151;
        }
        @media (max-width: 768px) {
            .content {
                margin-right: 0;
                padding: 15px;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
            .button-group {
                padding: 15px;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="form-box">
        <h2>تعديل البانر: {{ $slider->title }}</h2>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div style="background: #fee2e2; color: #dc2626; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fecaca;">
                <ul style="margin: 0; padding-right: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.sliders.update', $slider) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label for="title">العنوان الرئيسي:</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $slider->title) }}" 
                           required 
                           placeholder="عنوان جذاب للبانر"
                           maxlength="255">
                    @error('title')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="order">ترتيب العرض:</label>
                    <input type="number" 
                           id="order" 
                           name="order" 
                           value="{{ old('order', $slider->order) }}" 
                           min="0" 
                           max="999"
                           placeholder="0">
                    @error('order')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="description">الوصف:</label>
                <textarea id="description" 
                          name="description" 
                          required 
                          placeholder="وصف مختصر للبانر">{{ old('description', $slider->description) }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="image">صورة البانر:</label>
                @if($slider->image)
                    <div style="margin-bottom: 10px;">
                        <p style="margin-bottom: 5px; color: #6b7280;">الصورة الحالية:</p>
                        <img src="{{ asset($slider->image) }}" style="max-width: 200px; height: 120px; border-radius: 8px; border: 1px solid #ddd;" alt="الصورة الحالية">
                    </div>
                @endif
                <input type="file" 
                       id="image" 
                       name="image" 
                       accept="image/jpeg,image/png,image/jpg,image/gif"
                       onchange="previewImage(event)">
                <div class="optional">اتركه فارغاً للاحتفاظ بالصورة الحالية أو اختر صورة جديدة (صيص مقبولة: jpeg, png, jpg, gif - حجم أقصى 2 ميجابايت)</div>
                <div id="imagePreview" style="margin-top: 10px; display: none;">
                    <img id="previewImg" style="max-width: 100%; height: 200px; border-radius: 8px; border: 1px solid #ddd;" alt="معاينة الصورة">
                </div>
                @error('image')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="current-buttons">
                <h5>الأزرار الحالية:</h5>
                @if($slider->button1_text)
                    <p><strong>الزر الأول:</strong> {{ $slider->button1_text }} 
                    @if($slider->button1_link) ({{ $slider->button1_link }}) @endif</p>
                @else
                    <p>الزر الأول: غير محدد</p>
                @endif
                
                @if($slider->button2_text)
                    <p><strong>الزر الثاني:</strong> {{ $slider->button2_text }} 
                    @if($slider->button2_link) ({{ $slider->button2_link }}) @endif</p>
                @else
                    <p>الزر الثاني: غير محدد</p>
                @endif
            </div>

            <div class="button-group">
                <h4>تعديل الأزرار (اختياري)</h4>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="button1_text">نص الزر الأول:</label>
                        <input type="text" 
                               id="button1_text" 
                               name="button1_text" 
                               value="{{ old('button1_text', $slider->button1_text) }}" 
                               placeholder="مثال: اقرأ المزيد"
                               maxlength="100">
                        <div class="optional">اتركه فارغاً لإخفاء الزر</div>
                        @error('button1_text')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="button1_link">رابط الزر الأول:</label>
                        <input type="url" 
                               id="button1_link" 
                               name="button1_link" 
                               value="{{ old('button1_link', $slider->button1_link) }}" 
                               placeholder="https://example.com"
                               maxlength="255">
                        <div class="optional">اتركه فارغاً لإخفاء الزر</div>
                        @error('button1_link')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="button2_text">نص الزر الثاني:</label>
                        <input type="text" 
                               id="button2_text" 
                               name="button2_text" 
                               value="{{ old('button2_text', $slider->button2_text) }}" 
                               placeholder="مثال: تواصل معنا"
                               maxlength="100">
                        <div class="optional">اتركه فارغاً لإخفاء الزر</div>
                        @error('button2_text')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="button2_link">رابط الزر الثاني:</label>
                        <input type="url" 
                               id="button2_link" 
                               name="button2_link" 
                               value="{{ old('button2_link', $slider->button2_link) }}" 
                               placeholder="https://example.com"
                               maxlength="255">
                        <div class="optional">اتركه فارغاً لإخفاء الزر</div>
                        @error('button2_link')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="btn">تحديث البانر</button>
        </form>

        <a href="{{ route('admin.sliders.index') }}" class="back-link">← العودة إلى قائمة البانرات</a>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    const img = document.getElementById('previewImg');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}
</script>

</body>
</html>
