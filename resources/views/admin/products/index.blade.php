<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة المنتجات</title>
    <style>
        body { font-family: "Cairo", sans-serif; background: #f9fafb; margin:0; }
        .content { margin-right:240px; padding:30px; }
        table { width:100%; border-collapse: collapse; background:white; border-radius:8px; box-shadow:0 5px 15px rgba(0,0,0,0.1);}
        th, td { padding:10px; border-bottom:1px solid #ddd; text-align:center; }
        th { background:#4f46e5; color:white; }
        .btn { padding:6px 12px; border-radius:5px; text-decoration:none; color:white; display: inline-block; margin: 2px; }
        .btn-approve { background:#16a34a; }
        .btn-reject { background:#dc2626; }
        .edit-btn { background:#2563eb; }
        .status-معلق { color:orange; font-weight: bold; }
        .status-مقبول { color:green; font-weight: bold; }
        .status-مرفوض { color:red; font-weight: bold; }
        
        /* تنسيق الفلتر */
        .filter-container {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .filter-select {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            min-width: 200px;
            font-family: inherit;
        }
        .filter-btn {
            padding: 8px 20px;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .filter-btn:hover { background: #4338ca; }
    </style>
</head>
<body>

@include('admin.sidebar')

<div class="content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>إدارة المنتجات</h2>
        <a href="{{ route('admin.products.create') }}" class="btn" style="background: #4f46e5;">+ إضافة منتج جديد</a>
    </div>

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- ✅ نموذج الفلترة -->
    <form method="GET" action="{{ route('admin.products.index') }}" class="filter-container">
        <label for="category_id" style="font-weight: bold;">تصفية حسب التصنيف:</label>
        <select name="category_id" id="category_id" class="filter-select" onchange="this.form.submit()">
            <option value="all">عرض الكل</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }} ({{ $category->product_type }})
                </option>
            @endforeach
        </select>
        {{-- زرار (اختياري لأن الـ onchange بيعمل submit) --}}
        <button type="submit" class="filter-btn">بحث</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>اسم المنتج</th>
                <th>المورد</th>
                <th>التصنيف</th>
                <th>السعر النهائي</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->supplier->name ?? '-' }}</td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td>
                    {{ number_format($product->final_price, 2) }} جنيه
                    @if($product->has_discount)
                        <br><small style="text-decoration: line-through; color: #999;">{{ number_format($product->price, 2) }}</small>
                    @endif
                </td>
                <td class="status-{{ $product->status }}">{{ $product->status }}</td>
                <td>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn edit-btn">تعديل</a>
                    
                    <form action="{{ route('admin.products.delete', $product->id) }}" method="POST" style="display:inline;" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-reject" style="border:none; cursor:pointer;" onclick="confirmDelete(this)">حذف</button>
                    </form>
                    
                    @if($product->status == 'معلق')
                        <a href="{{ route('admin.products.approve',$product->id) }}" class="btn btn-approve">قبول</a>
                        <a href="{{ route('admin.products.reject',$product->id) }}" class="btn btn-reject">رفض</a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 20px; color: #666;">لا توجد منتجات مطابقة لهذا الفلتر.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- روابط التصفح (Pagination) لو استخدمت paginate --}}
    @if(method_exists($products, 'links'))
        <div style="margin-top: 20px;">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(button) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "لن تتمكن من استرجاع هذا المنتج بعد الحذف!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذفه!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>
</body>
</html>
