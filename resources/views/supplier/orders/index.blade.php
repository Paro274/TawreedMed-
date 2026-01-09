<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلباتي - لوحة المورد</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body { font-family: "Cairo", sans-serif; background: #f4f5fb; }
        .content { margin-right: 220px; padding: 30px; }
        .page-header { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        .orders-table { background: white; border-radius: 10px; overflow: hidden; }
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-confirmed { background: #d1ecf1; color: #0c5460; }
        .status-shipped { background: #d4edda; color: #155724; }
        .status-delivered { background: #d1f2eb; color: #0d7377; }
    </style>
</head>
<body>
    @include('supplier.sidebar')
    <div class="content">
        <div class="page-header">
            <h2><i class="fas fa-shopping-cart me-2"></i>طلباتي</h2>
        </div>
        <div class="orders-table">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        {{-- تم حذف عمود العميل --}}
                        <th>الإجمالي</th>
                        <th>حالة الطلب</th>
                        <th>التاريخ</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->order_number }}</strong></td>
                        {{-- تم حذف بيانات العميل --}}
                        <td>{{ number_format($order->total, 2) }} ج.م</td>
                        <td><span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span></td>
                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('supplier.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> عرض
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        {{-- تم تعديل colspan ليناسب عدد الأعمدة الجديد --}}
                        <td colspan="5" class="text-center py-4">لا توجد طلبات</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</body>
</html>
