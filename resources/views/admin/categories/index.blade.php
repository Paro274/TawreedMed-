<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>التصنيفات</title>
  <style>
    body { font-family: "Cairo", sans-serif; background: #f4f5fb; margin: 0; }
    .content { margin-right: 240px; padding: 30px; }
    table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: center; }
    th { background: #4f46e5; color: white; }
    .btn { padding: 6px 12px; color: white; border-radius: 5px; text-decoration: none; }
    .create-btn { background: #10b981; }
    .edit-btn { background: #3b82f6; }
    .delete-btn { background: #ef4444; }
  </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
  <h2>إدارة التصنيفات</h2>
  <a href="{{ route('admin.categories.create') }}" class="btn create-btn">إضافة تصنيف جديد</a>
  
  @if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
  @endif

  <table>
    <tr>
      <th>اسم التصنيف</th>
      <th>نوع المنتج</th>
      <th>إجراءات</th>
    </tr>
    @foreach($categories as $category)
    <tr>
      <td>{{ $category->name }}</td>
      <td>{{ $category->product_type }}</td>
      <td>
        <a href="{{ route('admin.categories.edit', $category) }}" class="btn edit-btn">تعديل</a>
        <a href="{{ route('admin.categories.delete', $category) }}" class="btn delete-btn" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a>
      </td>
    </tr>
    @endforeach
  </table>
</div>

</body>
</html>
