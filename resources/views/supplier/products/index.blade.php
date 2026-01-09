<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>منتجاتي</title>
<style>
body { font-family: "Cairo", sans-serif; background:#f9fafb; }
table { width:100%; border-collapse: collapse; margin-top:20px; background:white; border-radius:8px; overflow:hidden; box-shadow:0 5px 15px rgba(0,0,0,0.1);}
th, td { border-bottom:1px solid #ddd; padding:12px; text-align:center; font-size:15px; }
th { background:#4f46e5; color:white; font-size:16px; }
.status-معلق { color:gray; }
.status-مقبول { color:green; }
.status-مرفوض { color:red; }
.btn { padding:6px 12px; border-radius:5px; text-decoration:none; color:white; margin:0 3px; }
.edit-btn { background:#2563eb; }
.create-btn { background:#16a34a; padding:8px 15px; }

/* Enhanced price display styles */
.price-discounted {
    color: #16a34a;
    font-weight: bold;
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #86efac;
    display: inline-block;
}

.price-no-discount {
    color: #6b7280;
    background: #f9fafb;
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    display: inline-block;
}

.discount-badge {
    background: #10b981;
    color: white;
    padding: 2px 6px;
    border-radius: 12px;
    font-size: 0.75rem;
    margin-right: 5px;
}

.no-discount-text {
    color: #9ca3af;
    font-size: 0.85rem;
}
</style>
</head>
<body>

@include('supplier.sidebar')

<div style="margin-right:240px;padding:30px;">
<h2>المنتجات الخاصة بي</h2>
<a href="{{ route('supplier.products.create') }}" class="btn create-btn">+ إضافة منتج جديد</a>

@if(session('success'))
<p style="color:green;">{{ session('success') }}</p>
@endif

<table>
<tr>
<th>الاسم</th>
<th>التصنيف</th>
<th>السعر قبل الخصم</th>
<th>السعر بعد الخصم</th>
<th>الحالة</th>
<th>خيارات</th>
</tr>

@foreach($products as $p)
<tr>
<td>{{ $p->name }}</td>
<td>{{ $p->category->name ?? '-' }}</td>

<td>{{ number_format($p->price, 2) }} جنيه</td>

<td>
@if($p->price_after_discount && $p->price_after_discount < $p->price)
    <div class="price-discounted">
        {{ number_format($p->price_after_discount, 2) }} جنيه
        @if($p->discount)
            <div class="discount-badge">{{ $p->discount }}%</div>
        @endif
    </div>
@else
    <div class="price-no-discount">
        {{ number_format($p->price, 2) }} جنيه
        <div class="no-discount-text">(لا يوجد خصم)</div>
    </div>
@endif
</td>

<td class="status-{{ $p->status }}">{{ $p->status }}</td>
<td>
    <a href="{{ route('supplier.products.edit', $p->id) }}" class="btn edit-btn">تعديل</a>
    <a href="{{ route('supplier.products.delete', $p->id) }}" class="btn" style="background:#dc2626;" onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">حذف</a>
</td>
</tr>
@endforeach
</table>

</div>
</body>
</html>
