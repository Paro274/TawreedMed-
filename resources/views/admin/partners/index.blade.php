<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شركاءنا</title>
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
            color: #14b8a6;
            margin: 0 0 10px 0;
            font-size: 2rem;
            font-weight: 700;
        }
        .upload-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        .upload-area {
            border: 3px dashed #14b8a6;
            border-radius: 12px;
            padding: 50px;
            text-align: center;
            background: #f0fdfa;
            cursor: pointer;
            transition: 0.3s;
        }
        .upload-area:hover {
            background: #ccfbf1;
            border-color: #0d9488;
        }
        .upload-icon {
            font-size: 4rem;
            color: #14b8a6;
            margin-bottom: 20px;
        }
        .upload-text {
            font-size: 1.2rem;
            color: #14b8a6;
            font-weight: 600;
            margin: 0 0 10px 0;
        }
        .upload-hint {
            color: #6b7280;
            font-size: 0.95rem;
        }
        input[type="file"] {
            display: none;
        }
        .btn-upload {
            background: #14b8a6;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 15px;
            transition: 0.3s;
        }
        .btn-upload:hover {
            background: #0d9488;
        }
        .partners-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
        }
        .partner-card {
            background: white;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            text-align: center;
            transition: 0.3s;
            position: relative;
        }
        .partner-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }
        .partner-image {
            width: 100%;
            height: 100px;
            object-fit: contain;
            border-radius: 8px;
            background: #f9fafb;
            padding: 10px;
        }
        .btn-delete {
            position: absolute;
            top: 5px;
            left: 5px;
            background: #ef4444;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            font-size: 0.9rem;
        }
        .btn-delete:hover {
            background: #dc2626;
            transform: scale(1.1);
        }
        .alert-success {
            background: #ccfbf1;
            color: #134e4a;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #99f6e4;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #374151;
            margin: 0 0 20px 0;
        }
        @media (max-width: 768px) {
            .content {
                margin-right: 0;
                padding: 20px;
            }
            .partners-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                gap: 15px;
            }
            .partner-image {
                height: 80px;
            }
        }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="page-header">
        <h1><i class="fas fa-handshake me-3"></i>شركاءنا</h1>
        <p style="color: #6b7280; margin: 5px 0 0 0;">إدارة شعارات الشركاء</p>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- قسم رفع صورة جديدة -->
    <div class="upload-section">
        <h3 class="section-title"><i class="fas fa-cloud-upload-alt me-2"></i>إضافة شريك جديد</h3>
        <form method="POST" action="{{ route('admin.partners.store') }}" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <div class="upload-area" onclick="document.getElementById('fileInput').click()">
                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                <p class="upload-text">اضغط لاختيار صورة الشريك</p>
                <p class="upload-hint">PNG, JPG, GIF - حتى 2 ميجابايت (يُفضل صور صغيرة)</p>
                <input type="file" id="fileInput" name="image" accept="image/*" onchange="document.getElementById('uploadForm').submit()">
            </div>
        </form>
    </div>

    <!-- قسم عرض الشركاء -->
    <div class="upload-section">
        <h3 class="section-title"><i class="fas fa-images me-2"></i>الشركاء الحاليون ({{ $partners->count() }})</h3>
        
        @if($partners->count() > 0)
            <div class="partners-grid">
                @foreach($partners as $partner)
                    <div class="partner-card">
                        <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('هل أنت متأكد من الحذف؟')" title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        <img src="{{ asset($partner->image) }}" alt="شريك" class="partner-image">
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 60px; background: #f9fafb; border-radius: 12px;">
                <i class="fas fa-handshake" style="font-size: 4rem; color: #d1d5db; margin-bottom: 20px;"></i>
                <p style="color: #6b7280; font-size: 1.1rem; margin: 0;">لا يوجد شركاء بعد. ابدأ بإضافة أول شريك من الأعلى!</p>
            </div>
        @endif
    </div>
</div>

</body>
</html>
