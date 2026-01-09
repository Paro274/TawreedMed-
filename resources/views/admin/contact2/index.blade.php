<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بيانات الاتصال 2</title>
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
            color: #7c3aed;
            margin: 0 0 10px 0;
            font-size: 2rem;
            font-weight: 700;
        }
        .preview-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .preview-section h3 {
            color: #7c3aed;
            margin: 0 0 25px 0;
            font-size: 1.5rem;
        }
        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .contact-item {
            padding: 20px;
            background: #faf5ff;
            border-radius: 10px;
            border-right: 4px solid #7c3aed;
        }
        .contact-label {
            font-size: 0.9rem;
            color: #6b7280;
            margin: 0 0 8px 0;
            font-weight: 600;
        }
        .contact-value {
            font-size: 1.1rem;
            color: #374151;
            font-weight: 700;
            direction: ltr;
            text-align: right;
        }
        .address-box {
            background: #faf5ff;
            padding: 25px;
            border-radius: 10px;
            border-right: 4px solid #7c3aed;
        }
        .address-box h4 {
            color: #7c3aed;
            margin: 0 0 15px 0;
            font-size: 1.2rem;
        }
        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            padding: 30px;
        }
        .section-divider {
            border-top: 2px solid #e5e7eb;
            margin: 35px 0 25px 0;
            padding-top: 20px;
        }
        .section-title {
            color: #7c3aed;
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0 0 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-family: "Cairo", sans-serif;
            font-size: 1rem;
            box-sizing: border-box;
        }
        .form-control:focus {
            outline: none;
            border-color: #7c3aed;
            box-shadow: 0 0 8px rgba(124, 58, 237, 0.3);
        }
        button {
            background: #7c3aed;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
        }
        button:hover {
            background: #6d28d9;
        }
        .alert-success {
            background: #f3e8ff;
            color: #6b21a8;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #e9d5ff;
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
            .preview-grid {
                grid-template-columns: 1fr;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="page-header">
        <h1><i class="fas fa-address-card me-3"></i>بيانات الاتصال 2</h1>
        <p style="color: #6b7280; margin: 5px 0 0 0;">إدارة أرقام التليفون والإيميلات والعنوان</p>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- معاينة البيانات -->
    <div class="preview-section">
        <h3><i class="fas fa-eye me-2"></i>معاينة بيانات الاتصال</h3>
        
        <h4 style="color: #7c3aed; margin: 0 0 15px 0;">📞 أرقام التليفون</h4>
        <div class="preview-grid">
            @if($contact->phone1)
                <div class="contact-item">
                    <p class="contact-label">{{ $contact->phone1_title ?? 'تليفون 1' }}</p>
                    <p class="contact-value">{{ $contact->phone1 }}</p>
                </div>
            @endif
            @if($contact->phone2)
                <div class="contact-item">
                    <p class="contact-label">{{ $contact->phone2_title ?? 'تليفون 2' }}</p>
                    <p class="contact-value">{{ $contact->phone2 }}</p>
                </div>
            @endif
            @if($contact->phone3)
                <div class="contact-item">
                    <p class="contact-label">{{ $contact->phone3_title ?? 'تليفون 3' }}</p>
                    <p class="contact-value">{{ $contact->phone3 }}</p>
                </div>
            @endif
        </div>

        <h4 style="color: #7c3aed; margin: 25px 0 15px 0;">✉️ البريد الإلكتروني</h4>
        <div class="preview-grid">
            @if($contact->email1)
                <div class="contact-item">
                    <p class="contact-label">{{ $contact->email1_title ?? 'إيميل 1' }}</p>
                    <p class="contact-value">{{ $contact->email1 }}</p>
                </div>
            @endif
            @if($contact->email2)
                <div class="contact-item">
                    <p class="contact-label">{{ $contact->email2_title ?? 'إيميل 2' }}</p>
                    <p class="contact-value">{{ $contact->email2 }}</p>
                </div>
            @endif
            @if($contact->email3)
                <div class="contact-item">
                    <p class="contact-label">{{ $contact->email3_title ?? 'إيميل 3' }}</p>
                    <p class="contact-value">{{ $contact->email3 }}</p>
                </div>
            @endif
        </div>

        <h4 style="color: #7c3aed; margin: 25px 0 15px 0;">📍 العنوان</h4>
        <div class="address-box">
            <h4>{{ $contact->address_title ?? 'العنوان' }}</h4>
            <div>{!! $contact->address_text ?? '<p>لا يوجد نص</p>' !!}</div>
        </div>
    </div>

    <!-- نموذج التعديل -->
    <div class="form-container">
        <form method="POST" action="{{ route('admin.contact2.update') }}">
            @csrf

            <!-- أرقام التليفون -->
            <div class="section-title">
                <i class="fas fa-phone-alt"></i>
                أرقام التليفون
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">عنوان الرقم الأول</label>
                    <input type="text" name="phone1_title" class="form-control" value="{{ old('phone1_title', $contact->phone1_title) }}" placeholder="خدمة العملاء">
                </div>
                <div class="form-group">
                    <label class="form-label">رقم التليفون الأول</label>
                    <input type="text" name="phone1" class="form-control" value="{{ old('phone1', $contact->phone1) }}" placeholder="01000000000">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">عنوان الرقم الثاني</label>
                    <input type="text" name="phone2_title" class="form-control" value="{{ old('phone2_title', $contact->phone2_title) }}" placeholder="المبيعات">
                </div>
                <div class="form-group">
                    <label class="form-label">رقم التليفون الثاني</label>
                    <input type="text" name="phone2" class="form-control" value="{{ old('phone2', $contact->phone2) }}" placeholder="01000000001">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">عنوان الرقم الثالث</label>
                    <input type="text" name="phone3_title" class="form-control" value="{{ old('phone3_title', $contact->phone3_title) }}" placeholder="الدعم الفني">
                </div>
                <div class="form-group">
                    <label class="form-label">رقم التليفون الثالث</label>
                    <input type="text" name="phone3" class="form-control" value="{{ old('phone3', $contact->phone3) }}" placeholder="01000000002">
                </div>
            </div>

            <!-- الإيميلات -->
            <div class="section-divider">
                <div class="section-title">
                    <i class="fas fa-envelope"></i>
                    عناوين البريد الإلكتروني
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">عنوان الإيميل الأول</label>
                    <input type="text" name="email1_title" class="form-control" value="{{ old('email1_title', $contact->email1_title) }}" placeholder="البريد الرئيسي">
                </div>
                <div class="form-group">
                    <label class="form-label">الإيميل الأول</label>
                    <input type="email" name="email1" class="form-control" value="{{ old('email1', $contact->email1) }}" placeholder="info@example.com">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">عنوان الإيميل الثاني</label>
                    <input type="text" name="email2_title" class="form-control" value="{{ old('email2_title', $contact->email2_title) }}" placeholder="المبيعات">
                </div>
                <div class="form-group">
                    <label class="form-label">الإيميل الثاني</label>
                    <input type="email" name="email2" class="form-control" value="{{ old('email2', $contact->email2) }}" placeholder="sales@example.com">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">عنوان الإيميل الثالث</label>
                    <input type="text" name="email3_title" class="form-control" value="{{ old('email3_title', $contact->email3_title) }}" placeholder="الدعم">
                </div>
                <div class="form-group">
                    <label class="form-label">الإيميل الثالث</label>
                    <input type="email" name="email3" class="form-control" value="{{ old('email3', $contact->email3) }}" placeholder="support@example.com">
                </div>
            </div>

            <!-- العنوان -->
            <div class="section-divider">
                <div class="section-title">
                    <i class="fas fa-map-marker-alt"></i>
                    العنوان
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">عنوان القسم</label>
                <input type="text" name="address_title" class="form-control" value="{{ old('address_title', $contact->address_title) }}" placeholder="مثال: عنواننا">
                <div class="form-text">العنوان الذي سيظهر فوق النص</div>
            </div>

            <div class="form-group">
                <label class="form-label">نص العنوان (محرر غني)</label>
                <textarea id="address_text" name="address_text" placeholder="اكتب العنوان التفصيلي هنا...">{{ old('address_text', $contact->address_text) }}</textarea>
                <div class="form-text">استخدم المحرر لتنسيق النص</div>
            </div>

            <div style="margin-top: 30px;">
                <button type="submit">
                    <i class="fas fa-save"></i>
                    حفظ جميع التغييرات
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
