<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مورد جديد</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: "Cairo", sans-serif; background: #f4f5fb; margin: 0; }
        .content { margin-right: 240px; padding: 30px; }
        .form-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; }
        h2 { color: #333; margin-bottom: 25px; text-align: center; }
        label { display: block; margin-bottom: 5px; font-weight: 600; color: #374151; }
        input { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #d1d5db; border-radius: 8px; font-family: "Cairo", sans-serif; box-sizing: border-box; }
        .file-input { padding: 10px; background: #f9fafb; border: 2px dashed #d1d5db; border-radius: 8px; text-align: center; cursor: pointer; transition: 0.3s; }
        .file-input:hover { border-color: #4f46e5; background: #f0f4ff; }
        .btn { background: #4f46e5; color: white; padding: 12px 30px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; width: 100%; transition: 0.3s; }
        .btn:hover { background: #4338ca; transform: translateY(-1px); }
        .back-link { display: inline-block; margin-top: 20px; color: #4f46e5; text-decoration: none; font-weight: 600; }
        .error { color: #ef4444; font-size: 14px; margin-top: -10px; margin-bottom: 10px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 768px) { .content { margin-right: 0; padding: 15px; } .form-row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="form-box">
        <h2>إضافة مورد جديد</h2>

        @if($errors->any())
            <div style="background: #fee2e2; color: #dc2626; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-right: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.suppliers.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-row">
                <div>
                    <label>اسم المورد:</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="أدخل اسم المورد">
                </div>
                <div>
                    <label>البريد الإلكتروني:</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="example@email.com">
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label>رقم الهاتف:</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="01001234567">
                </div>
                <div>
                    <label>كلمة المرور:</label>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>
            </div>

            <div>
                <label>تأكيد كلمة المرور:</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••">
            </div>

            <label>شعار الشركة (اختياري):</label>
            <div class="file-input" onclick="document.getElementById('company_logo').click()">
                <input type="file" id="company_logo" name="company_logo" accept="image/*" style="display: none;">
                <p style="margin: 0; color: #6b7280;">انقر هنا أو اسحب الصورة (JPG, PNG - أقصى 2 ميجا)</p>
            </div>
            <p id="file-name" style="text-align: center; font-size: 14px; color: #4f46e5; margin-top: 5px;"></p>

            <button type="submit" class="btn">إضافة المورد</button>
        </form>

        <a href="{{ route('admin.suppliers.index') }}" class="back-link">← العودة للقائمة</a>
    </div>
</div>

<script>
    document.getElementById('company_logo').addEventListener('change', function() {
        if (this.files.length > 0) {
            document.getElementById('file-name').textContent = 'تم اختيار: ' + this.files[0].name;
        }
    });
</script>

</body>
</html>
