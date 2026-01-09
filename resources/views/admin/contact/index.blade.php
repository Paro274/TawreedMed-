<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بيانات التواصل</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f3f4f6;
            overflow-x: hidden;
        }
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        .sidebar-container {
            width: 280px;
            background: #1f2937;
            flex-shrink: 0;
        }
        .main-content {
            flex-grow: 1;
            padding: 30px;
            width: calc(100% - 280px);
        }
        .page-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 700;
            color: #374151;
            margin-bottom: 8px;
        }
        .form-control {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
        }
        .form-control:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        .current-data-box {
            background: #ecfdf5;
            border: 1px solid #10b981;
            color: #065f46;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .btn-save {
            background: #10b981;
            color: white;
            padding: 12px 40px;
            border-radius: 8px;
            border: none;
            font-weight: 700;
            transition: 0.3s;
        }
        .btn-save:hover {
            background: #059669;
        }
        @media (max-width: 992px) {
            .sidebar-container { display: none; }
            .main-content { width: 100%; }
        }
    </style>
</head>
<body>

    <div class="admin-layout">
        <!-- السايد بار -->
        <div class="sidebar-container">
            @include('admin.sidebar')
        </div>

        <!-- المحتوى -->
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark"><i class="fas fa-phone-alt text-success me-2"></i> بيانات التواصل</h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success mb-4">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="page-card">
                <!-- عرض البيانات الحالية -->
                <div class="current-data-box">
                    <h5 class="fw-bold mb-3"><i class="fas fa-eye"></i> المعاينة الحالية على الموقع:</h5>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <strong><i class="fas fa-phone ms-1"></i> الهاتف:</strong> 
                            <span dir="ltr">{{ $contact->phone ?? 'غير محدد' }}</span>
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong><i class="fas fa-envelope ms-1"></i> الإيميل:</strong> 
                            {{ $contact->email ?? 'غير محدد' }}
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong><i class="fas fa-map-marker-alt ms-1"></i> العنوان:</strong> 
                            {{ $contact->address ?? 'غير محدد' }}
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- فورم التعديل -->
                <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-edit"></i> تعديل البيانات</h5>
                
                <form method="POST" action="{{ route('admin.contact.update') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label">رقم الهاتف</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $contact->phone ?? '') }}" placeholder="010xxxxxxxx" dir="ltr" style="text-align: right;">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $contact->email ?? '') }}" placeholder="info@example.com">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">العنوان</label>
                        <textarea name="address" class="form-control" rows="3" placeholder="اكتب العنوان هنا...">{{ old('address', $contact->address ?? '') }}</textarea>
                    </div>

                    <div class="text-start">
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save me-2"></i> حفظ التعديلات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
