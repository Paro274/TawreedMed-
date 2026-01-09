<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل بيانات المورد</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: "Cairo", sans-serif; background: #f4f5fb; margin: 0; }
        .content { margin-right: 240px; padding: 30px; }
        .form-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); max-width: 700px; margin: 0 auto; }
        h2 { color: #333; margin-bottom: 25px; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; display: inline-block; }
        label { display: block; margin-bottom: 5px; font-weight: 600; color: #374151; }
        input, select { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #d1d5db; border-radius: 8px; font-family: "Cairo", sans-serif; box-sizing: border-box; }
        .current-logo { width: 100px; height: 100px; object-fit: cover; border-radius: 10px; border: 1px solid #ddd; display: block; margin: 0 auto 10px; }
        .file-input { padding: 15px; background: #f9fafb; border: 2px dashed #d1d5db; border-radius: 8px; text-align: center; cursor: pointer; transition: 0.3s; }
        .file-input:hover { border-color: #4f46e5; background: #f0f4ff; }
        .btn { background: #4f46e5; color: white; padding: 12px 30px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn:hover { background: #4338ca; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .status-badge { padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; }
        .status-active { background: #dcfce7; color: #166534; }
        .status-inactive { background: #fee2e2; color: #991b1b; }
        @media (max-width: 768px) { .content { margin-right: 0; padding: 15px; } .form-row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div class="form-box">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>تعديل بيانات المورد</h2>
            <span class="status-badge {{ $supplier->status == 1 ? 'status-active' : 'status-inactive' }}">
                {{ $supplier->status == 1 ? 'مفعل' : 'معطل' }}
            </span>
        </div>

        @if(session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #fee2e2; color: #dc2626; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-right: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.suppliers.update', $supplier->id) }}" enctype="multipart/form-data">
            @csrf
            
            <!-- عرض اللوجو الحالي -->
            <div style="text-align: center; margin-bottom: 20px;">
                <label>الشعار الحالي:</label>
                @if($supplier->logo && file_exists(public_path($supplier->logo)))
                    <img src="{{ asset($supplier->logo) }}" class="current-logo" alt="Logo">
                @elseif($supplier->logo && Storage::disk('public')->exists($supplier->logo))
                     {{-- دعم الصور القديمة --}}
                    <img src="{{ asset('storage/' . $supplier->logo) }}" class="current-logo" alt="Logo">
                @else
                    <div class="current-logo" style="background: #eee; display: flex; align-items: center; justify-content: center;">لا يوجد</div>
                @endif
            </div>

            <div class="form-row">
                <div>
                    <label>اسم المورد:</label>
                    <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required>
                </div>
                <div>
                    <label>البريد الإلكتروني:</label>
                    <input type="email" name="email" value="{{ old('email', $supplier->email) }}" required>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label>رقم الهاتف:</label>
                    <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" required>
                </div>
                <div>
                    <label>الحالة:</label>
                    <select name="status">
                        <option value="1" {{ $supplier->status == 1 ? 'selected' : '' }}>مفعل</option>
                        <option value="0" {{ $supplier->status == 0 ? 'selected' : '' }}>معطل</option>
                    </select>
                </div>
            </div>

            <div>
                <label>كلمة المرور الجديدة (اختياري):</label>
                <input type="password" name="password" placeholder="اتركها فارغة إذا لم ترد التغيير">
            </div>

            <label>تغيير الشعار:</label>
            <div class="file-input" onclick="document.getElementById('company_logo').click()">
                <input type="file" id="company_logo" name="company_logo" accept="image/*" style="display: none;">
                <p style="margin: 0; color: #6b7280;">اضغط هنا لرفع شعار جديد</p>
            </div>
            <p id="file-name" style="text-align: center; color: #4f46e5; margin-top: 5px;"></p>

            <div style="margin-top: 25px; display: flex; gap: 10px;">
                <button type="submit" class="btn">حفظ التعديلات</button>
                <a href="{{ route('admin.suppliers.index') }}" class="btn" style="background: #6b7280; text-decoration: none; text-align: center;">إلغاء</a>
            </div>
        </form>
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
