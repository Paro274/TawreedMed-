<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة بانر جديد</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: "Cairo", sans-serif; background: #f4f5fb; margin: 0; }
        .content { margin-right: 240px; padding: 30px; }
        .form-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; }
        h2 { color: #333; margin-bottom: 25px; text-align: center; }
        .form-group { margin-bottom: 25px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #374151; }
        input[type="number"], input[type="file"] { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-family: "Cairo", sans-serif; box-sizing: border-box; }
        .btn { background: #4f46e5; color: white; padding: 14px 30px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; width: 100%; transition: 0.3s; margin-top: 10px; }
        .btn:hover { background: #4338ca; transform: translateY(-1px); }
        .back-link { display: inline-block; margin-top: 20px; color: #4f46e5; text-decoration: none; font-weight: 600; }
        .error { color: #ef4444; font-size: 14px; margin-top: 5px; }
        .optional { color: #6b7280; font-size: 14px; margin-top: 5px; }
        @media (max-width: 768px) { .content { margin-right: 0; padding: 15px; } }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="form-box">
        <h2>إضافة بانر جديد</h2>

        @if($errors->any())
            <div style="background: #fee2e2; color: #dc2626; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fecaca;">
                <ul style="margin: 0; padding-right: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.sliders.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="image">صورة البانر (مطلوب):</label>
                <input type="file" id="image" name="image" accept="image/*" required onchange="previewImage(event)">
                <div class="optional">الصيغ المقبولة: jpg, png, jpeg (يفضل مقاس 1920x600)</div>
                <div id="imagePreview" style="margin-top: 15px; display: none;">
                    <img id="previewImg" style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                @error('image') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="order">ترتيب العرض:</label>
                <input type="number" id="order" name="order" value="{{ old('order', 0) }}" min="0" max="999">
                <div class="optional">كلما قل الرقم ظهر أولاً (اتركه 0 للوضع الافتراضي)</div>
            </div>

            <button type="submit" class="btn">حفظ البانر</button>
        </form>

        <a href="{{ route('admin.sliders.index') }}" class="back-link">← العودة للقائمة</a>
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
