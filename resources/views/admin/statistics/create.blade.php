<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة إحصائيات جديدة</title>
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
        input[type="number"] {
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
        input[type="number"]:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }
        .btn {
            background: #8b5cf6;
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
            background: #7c3aed;
            transform: translateY(-1px);
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #8b5cf6;
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
        .stat-group {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            background: #f9fafb;
            margin-bottom: 25px;
        }
        .stat-group h4 {
            margin: 0 0 15px 0;
            color: #374151;
            font-size: 18px;
            border-bottom: 2px solid #8b5cf6;
            padding-bottom: 8px;
        }
        .optional {
            color: #6b7280;
            font-size: 14px;
            margin-top: 5px;
        }
        .stat-preview {
            background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            text-align: center;
        }
        .stat-preview .number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .stat-preview .title {
            font-size: 1rem;
            opacity: 0.9;
        }
        @media (max-width: 768px) {
            .content {
                margin-right: 0;
                padding: 15px;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
            .stat-group {
                padding: 15px;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="form-box">
        <h2>إضافة إحصائيات جديدة</h2>

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

        <form method="POST" action="{{ route('admin.statistics.store') }}">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="order">ترتيب العرض:</label>
                    <input type="number" 
                           id="order" 
                           name="order" 
                           value="{{ old('order', 0) }}" 
                           min="0" 
                           max="999"
                           placeholder="0">
                    <div class="optional">اتركه فارغاً ليتم التعيين تلقائياً</div>
                    @error('order')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- الإحصائية الأساسية -->
            <div class="stat-group">
                <h4>الإحصائية الأساسية (مطلوبة)</h4>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="title">العنوان:</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}" 
                               required 
                               placeholder="مثال: عملاء سعيدين"
                               maxlength="100">
                        @error('title')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="number">الرقم:</label>
                        <input type="text" 
                               id="number" 
                               name="number" 
                               value="{{ old('number') }}" 
                               required 
                               placeholder="مثال: 1500+"
                               maxlength="20">
                        @error('number')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- معاينة الإحصائية -->
                <div class="stat-preview">
                    <div class="number">{{ old('number', '1500+') }}</div>
                    <div class="title">{{ old('title', 'عملاء سعيدين') }}</div>
                </div>
            </div>

            <!-- الإحصائيات الإضافية -->
            <div class="stat-group">
                <h4>إحصائيات إضافية (اختيارية)</h4>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="title2">العنوان الثاني:</label>
                        <input type="text" 
                               id="title2" 
                               name="title2" 
                               value="{{ old('title2') }}" 
                               placeholder="مثال: مشاريع مكتملة"
                               maxlength="100">
                        <div class="optional">اتركه فارغاً إذا لم ترغب في إظهاره</div>
                        @error('title2')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="number2">الرقم الثاني:</label>
                        <input type="text" 
                               id="number2" 
                               name="number2" 
                               value="{{ old('number2') }}" 
                               placeholder="مثال: 500+"
                               maxlength="20">
                        <div class="optional">اتركه فارغاً إذا لم ترغب في إظهاره</div>
                        @error('number2')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if(old('title2'))
                <div class="stat-preview">
                    <div class="number">{{ old('number2', '500+') }}</div>
                    <div class="title">{{ old('title2', 'مشاريع مكتملة') }}</div>
                </div>
                @endif
            </div>

            <div class="stat-group">
                <div class="form-row">
                    <div class="form-group">
                        <label for="title3">العنوان الثالث:</label>
                        <input type="text" 
                               id="title3" 
                               name="title3" 
                               value="{{ old('title3') }}" 
                               placeholder="مثال: سنوات خبرة"
                               maxlength="100">
                        <div class="optional">اتركه فارغاً إذا لم ترغب في إظهاره</div>
                        @error('title3')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="number3">الرقم الثالث:</label>
                        <input type="text" 
                               id="number3" 
                               name="number3" 
                               value="{{ old('number3') }}" 
                               placeholder="مثال: 10+"
                               maxlength="20">
                        <div class="optional">اتركه فارغاً إذا لم ترغب في إظهاره</div>
                        @error('number3')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if(old('title3'))
                <div class="stat-preview">
                    <div class="number">{{ old('number3', '10+') }}</div>
                    <div class="title">{{ old('title3', 'سنوات خبرة') }}</div>
                </div>
                @endif
            </div>

            <div class="stat-group">
                <div class="form-row">
                    <div class="form-group">
                        <label for="title4">العنوان الرابع:</label>
                        <input type="text" 
                               id="title4" 
                               name="title4" 
                               value="{{ old('title4') }}" 
                               placeholder="مثال: فريق عمل"
                               maxlength="100">
                        <div class="optional">اتركه فارغاً إذا لم ترغب في إظهاره</div>
                        @error('title4')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="number4">الرقم الرابع:</label>
                        <input type="text" 
                               id="number4" 
                               name="number4" 
                               value="{{ old('number4') }}" 
                               placeholder="مثال: 25+"
                               maxlength="20">
                        <div class="optional">اتركه فارغاً إذا لم ترغب في إظهاره</div>
                        @error('number4')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if(old('title4'))
                <div class="stat-preview">
                    <div class="number">{{ old('number4', '25+') }}</div>
                    <div class="title">{{ old('title4', 'فريق عمل') }}</div>
                </div>
                @endif
            </div>

            <button type="submit" class="btn">إضافة الإحصائيات</button>
        </form>

        <a href="{{ route('admin.statistics.index') }}" class="back-link">← العودة إلى قائمة الإحصائيات</a>
    </div>
</div>

</body>
</html>
