<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إضافة تصنيف</title>
  <style>
    body { font-family: "Cairo", sans-serif; background: #f4f5fb; margin: 0; }
    .content { margin-right: 240px; padding: 30px; }
    form { background: white; padding: 25px; border-radius: 10px; max-width: 500px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    label { display: block; margin-bottom: 5px; }
    input, select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 6px; }
    button { background: #4f46e5; color: white; padding: 10px 15px; border: none; border-radius: 6px; cursor: pointer; }
    button:hover { background: #4338ca; }
  </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
  <h2>إضافة تصنيف جديد</h2>

  <form method="POST" action="{{ route('admin.categories.store') }}">
    @csrf
    <label>اسم التصنيف:</label>
    <input type="text" name="name" required>

    <label>نوع المنتج:</label>
    <select name="product_type" required>
      <option value="">اختر النوع</option>
      <option value="أدوية">أدوية</option>
      <option value="مستلزمات طبية">مستلزمات طبية</option>
      <option value="منتجات تجميل">منتجات تجميل</option>
    </select>

    <button type="submit">حفظ</button>
  </form>
</div>

</body>
</html>
