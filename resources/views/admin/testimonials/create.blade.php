<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إضافة تقييم جديد</title>
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
            color: #3b82f6;
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
        }
        .back-btn {
            background: #6b7280;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .back-btn:hover {
            background: #4b5563;
        }
        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            padding: 30px;
            max-width: 900px;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 25px;
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
        .form-control, .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: "Cairo", sans-serif;
            box-sizing: border-box;
        }
        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }
        .required {
            color: #ef4444;
        }
        .form-text {
            font-size: 0.9rem;
            color: #6b7280;
            margin-top: 5px;
        }
        .file-input {
            position: relative;
            display: inline-block;
            width: 100%;
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
            border-color: #3b82f6;
            background: #eff6ff;
        }
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: "Cairo", sans-serif;
            font-size: 1rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
        }
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-2px);
        }
        .alert {
            background: #fef2f2;
            color: #dc2626;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 1px solid #fecaca;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }
        .form-row-full {
            grid-column: 1 / -1;
        }
        .switch-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .form-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .form-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.3s;
            border-radius: 34px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            right: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #3b82f6;
        }
        input:checked + .slider:before {
            transform: translateX(-26px);
        }
        .switch-label {
            font-weight: 600;
            color: #374151;
        }
        @media (max-width: 768px) {
            .content {
                margin-right: 0;
                padding: 20px;
            }
            .form-container {
                padding: 20px;
            }
            .form-row {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .page-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="page-header">
        <h1>
            <i class="fas fa-star me-2"></i>
            إضافة تقييم جديد
        </h1>
        <a href="{{ route('admin.testimonials.index') }}" class="back-btn">
            <i class="fas fa-arrow-right"></i>
            العودة للقائمة
        </a>
    </div>

    @if($errors->any())
        <div class="alert">
            <i class="fas fa-exclamation-triangle"></i>
            يرجى تصحيح الأخطاء التالية:
            <ul style="margin: 10px 0 0 20px; text-align: right;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">
        <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">الاسم <span class="required">*</span></label>
                    <input type="text" 
                           name="name" 
                           class="form-control @error('name') border-red @enderror"
                           value="{{ old('name') }}" 
                           placeholder="أدخل اسم العميل الكامل"
                           required>
                    @error('name')
                        <span style="color: #dc2626; font-size: 0.9rem;">{{ $message }}</span>
                    @enderror
                    <div class="form-text">مثال: أحمد محمد أحمد</div>
                </div>

                <div class="form-group">
                    <label class="form-label">المحافظة <span class="required">*</span></label>
                    <select name="governorate" class="form-select @error('governorate') border-red @enderror" required>
                        <option value="">اختر المحافظة</option>
                        <option value="القاهرة" {{ old('governorate') == 'القاهرة' ? 'selected' : '' }}>القاهرة</option>
                        <option value="الجيزة" {{ old('governorate') == 'الجيزة' ? 'selected' : '' }}>الجيزة</option>
                        <option value="الإسكندرية" {{ old('governorate') == 'الإسكندرية' ? 'selected' : '' }}>الإسكندرية</option>
                        <option value="الدقهلية" {{ old('governorate') == 'الدقهلية' ? 'selected' : '' }}>الدقهلية</option>
                        <option value="الشرقية" {{ old('governorate') == 'الشرقية' ? 'selected' : '' }}>الشرقية</option>
                        <option value="الغربية" {{ old('governorate') == 'الغربية' ? 'selected' : '' }}>الغربية</option>
                        <option value="المنوفية" {{ old('governorate') == 'المنوفية' ? 'selected' : '' }}>المنوفية</option>
                        <option value="القليوبية" {{ old('governorate') == 'القليوبية' ? 'selected' : '' }}>القليوبية</option>
                        <option value="كفر الشيخ" {{ old('governorate') == 'كفر الشيخ' ? 'selected' : '' }}>كفر الشيخ</option>
                        <option value="دمياط" {{ old('governorate') == 'دمياط' ? 'selected' : '' }}>دمياط</option>
                        <option value="بورسعيد" {{ old('governorate') == 'بورسعيد' ? 'selected' : '' }}>بورسعيد</option>
                        <option value="الإسماعيلية" {{ old('governorate') == 'الإسماعيلية' ? 'selected' : '' }}>الإسماعيلية</option>
                        <option value="السويس" {{ old('governorate') == 'السويس' ? 'selected' : '' }}>السويس</option>
                        <option value="البحيرة" {{ old('governorate') == 'البحيرة' ? 'selected' : '' }}>البحيرة</option>
                        <option value="الفيوم" {{ old('governorate') == 'الفيوم' ? 'selected' : '' }}>الفيوم</option>
                        <option value="بني سويف" {{ old('governorate') == 'بني سويف' ? 'selected' : '' }}>بني سويف</option>
                        <option value="المنيا" {{ old('governorate') == 'المنيا' ? 'selected' : '' }}>المنيا</option>
                        <option value="أسيوط" {{ old('governorate') == 'أسيوط' ? 'selected' : '' }}>أسيوط</option>
                        <option value="سوهاج" {{ old('governorate') == 'سوهاج' ? 'selected' : '' }}>سوهاج</option>
                        <option value="قنا" {{ old('governorate') == 'قنا' ? 'selected' : '' }}>قنا</option>
                        <option value="الأقصر" {{ old('governorate') == 'الأقصر' ? 'selected' : '' }}>الأقصر</option>
                        <option value="أسوان" {{ old('governorate') == 'أسوان' ? 'selected' : '' }}>أسوان</option>
                    </select>
                    @error('governorate')
                        <span style="color: #dc2626; font-size: 0.9rem;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">المسمى الوظيفي</label>
                    <input type="text" 
                           name="job_title" 
                           class="form-control @error('job_title') border-red @enderror"
                           value="{{ old('job_title') }}" 
                           placeholder="مثال: مدير تسويق">
                    @error('job_title')
                        <span style="color: #dc2626; font-size: 0.9rem;">{{ $message }}</span>
                    @enderror
                    <div class="form-text">يمكن تركه فارغاً إذا لم يكن محدد</div>
                </div>

                <div class="form-group">
                    <label class="form-label">التقييم <span class="required">*</span></label>
                    <select name="rating" class="form-select @error('rating') border-red @enderror" required>
                        <option value="">اختر التقييم</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                {{ $i }} نجوم
                            </option>
                        @endfor
                    </select>
                    @error('rating')
                        <span style="color: #dc2626; font-size: 0.9rem;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">صورة العميل (اختياري)</label>
                    <div class="file-input">
                        <input type="file" 
                               name="image" 
                               class="form-control @error('image') border-red @enderror"
                               accept="image/*">
                    </div>
                    @error('image')
                        <span style="color: #dc2626; font-size: 0.9rem;">{{ $message }}</span>
                    @enderror
                    <div class="form-text">PNG, JPG, GIF حتى 2 ميجابايت</div>
                </div>

                <div class="form-group">
                    <label class="form-label">حالة العرض</label>
                    <div class="switch-container">
                        <label class="form-switch">
                            <input type="checkbox" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                        <span class="switch-label">نشط (يظهر في الموقع)</span>
                    </div>
                    <div class="form-text">التقييمات النشطة فقط تظهر في الموقع العام</div>
                </div>
            </div>

            <div class="form-row form-row-full">
                <div class="form-group">
                    <label class="form-label">تعليق العميل <span class="required">*</span></label>
                    <textarea name="review" 
                              class="form-control @error('review') border-red @enderror"
                              placeholder="اكتب تعليق العميل هنا... مثال: خدمة ممتازة وسريعة، أنصح بشدة بالتعامل مع هذا المتجر!"
                              required>{{ old('review') }}</textarea>
                    @error('review')
                        <span style="color: #dc2626; font-size: 0.9rem;">{{ $message }}</span>
                    @enderror
                    <div class="form-text">الحد الأقصى 1000 حرف - اكتب تعليقاً واقعياً يعكس تجربة العميل</div>
                </div>
            </div>

            <div style="display: flex; gap: 15px; justify-content: flex-start;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    حفظ التقييم
                </button>
                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
