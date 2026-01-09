<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل {{ $certificate->title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* نفس الـ CSS من صفحة Create */
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
            color: #059669;
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
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-family: "Cairo", sans-serif;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        select {
            cursor: pointer;
            background-color: white;
            height: 48px;
        }
        .btn {
            background: #059669;
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
            background: #047857;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #059669;
            text-decoration: none;
            font-weight: 600;
        }
        .icon-preview-box {
            background: #d1fae5;
            padding: 20px;
            border-radius: 8px;
            margin-top: 10px;
            border: 2px solid #a7f3d0;
            text-align: center;
            min-height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 10px;
        }
        .icon-preview-box i {
            font-size: 48px;
            color: #059669;
        }
        @media (max-width: 768px) {
            .content {
                margin-right: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="form-box">
        <h2>تعديل {{ $certificate->title }}</h2>

        @if($errors->any())
            <div style="background: #fee2e2; color: #dc2626; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-right: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.certificates.update', $certificate) }}">
            @csrf

            <div class="form-group">
                <label for="order">ترتيب العرض:</label>
                <input type="number" id="order" name="order" value="{{ old('order', $certificate->order) }}" min="0">
            </div>

            <div class="form-group">
                <label for="title">العنوان:</label>
                <input type="text" id="title" name="title" value="{{ old('title', $certificate->title) }}" required>
            </div>

            <div class="form-group">
                <label for="description">الوصف:</label>
                <textarea id="description" name="description" required>{{ old('description', $certificate->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="icon">اختر الأيقونة:</label>
                <select id="icon" name="icon" required>
                    @php $categories = ['awards', 'certificates', 'quality', 'excellence', 'recognition', 'global']; @endphp
                    
                    @foreach($categories as $category)
                        @php $categoryData = config("certificate-icons.{$category}", []); @endphp
                        @if(!empty($categoryData))
                            @php 
                                $categoryLabels = [
                                    'awards' => '🏆 الجوائز',
                                    'certificates' => '📜 الشهادات',
                                    'quality' => '✅ الجودة والتوثيق',
                                    'excellence' => '🎓 التميز والإنجاز',
                                    'recognition' => '🤝 التقدير والاعتراف',
                                    'global' => '🌍 عالمي ودولي'
                                ];
                            @endphp
                            <optgroup label="{{ $categoryLabels[$category] }}">
                                @foreach($categoryData as $item)
                                    <option value="{{ $item['icon'] }}" 
                                            {{ old('icon', $certificate->icon) == $item['icon'] ? 'selected' : '' }}>
                                        {{ $item['label'] }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    @endforeach
                </select>

                <div class="icon-preview-box">
                    <i class="fas fa-{{ old('icon', $certificate->icon) }}"></i>
                    <div style="font-size: 14px; color: #047857;">الأيقونة الحالية</div>
                </div>
            </div>

            <button type="submit" class="btn">تحديث البيانات</button>
        </form>

        <a href="{{ route('admin.certificates.index') }}" class="back-link">← العودة للقائمة</a>
    </div>
</div>

</body>
</html>
