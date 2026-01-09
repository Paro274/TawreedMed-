<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة الطلب #{{ $order->order_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body { font-family: "Cairo", sans-serif; background: #f5f5f5; padding: 40px 0; }
        .invoice-card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 40px; max-width: 900px; margin: 0 auto; }
        .invoice-header { border-bottom: 2px solid #16a34a; padding-bottom: 20px; margin-bottom: 30px; }
        .invoice-title { color: #16a34a; font-size: 28px; font-weight: bold; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 600; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-confirmed { background: #d1ecf1; color: #0c5460; }
        .status-shipped { background: #d4edda; color: #155724; }
        .status-delivered { background: #d1f2eb; color: #0d7377; }
        .invoice-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .invoice-table th, .invoice-table td { padding: 12px; text-align: right; border-bottom: 1px solid #eee; }
        .invoice-table th { background: #f8f9fa; font-weight: 600; }
        .invoice-totals { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 20px; }
        .total-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #ddd; }
        .total-row:last-child { border-bottom: none; font-size: 20px; font-weight: bold; color: #16a34a; margin-top: 10px; padding-top: 15px; border-top: 2px solid #16a34a; }
        
        @media print {
            body { 
                padding: 0;
                background: white;
                font-size: 12px;
            }
            .content {
                margin-right: 0 !important;
                padding: 0 !important;
            }
            .invoice-card {
                box-shadow: none;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .print-only {
                display: block !important;
            }
            .invoice-table th, 
            .invoice-table td {
                padding: 6px 8px;
            }
        }
    </style>
</head>
<body>
    @include('supplier.sidebar')
    <div class="content" style="margin-right: 220px; padding: 30px;">
        <div class="invoice-card">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <a href="{{ route('supplier.orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-right me-1"></i> العودة للقائمة
                </a>
                <button onclick="window.print()" class="btn btn-success">
                    <i class="fas fa-print me-1"></i> طباعة الفاتورة
                </button>
            </div>
            
            <div class="invoice-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="invoice-title"><i class="fas fa-receipt me-2"></i>فاتورة الطلب #{{ $order->order_number }}</h1>
                    <div>
                        <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
                        <span class="status-badge status-{{ $order->payment_status }} ms-2">{{ $order->payment_status_label }}</span>
                    </div>
                </div>
                <p class="text-muted mt-2">
                    <i class="far fa-calendar-alt me-1"></i> تاريخ الطلب: {{ $order->created_at->format('Y/m/d h:i A') }}
                </p>
            </div>
            
            
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price, 2) }} ج.م</td>
                        <td>{{ number_format($item->total_price, 2) }} ج.م</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="invoice-totals">
                <div class="total-row">
                    <span>المجموع الفرعي:</span>
                    <span>{{ number_format($order->subtotal, 2) }} ج.م</span>
                </div>


                <div class="total-row">
                    <span>الإجمالي:</span>
                    <span>{{ number_format($order->total, 2) }} ج.م</span>
                </div>
            </div>
            
            <div class="mt-4">
                <form action="{{ route('supplier.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    <select name="status" class="form-select d-inline-block" style="width: auto;">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>في الطريق</option>
                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                    </select>
                    <button type="submit" class="btn btn-success btn-sm">تحديث الحالة</button>
                </form>
                
                <a href="{{ route('supplier.orders.index') }}" class="btn btn-secondary btn-sm ms-2">العودة</a>
                <button onclick="window.print()" class="btn btn-info btn-sm ms-2"><i class="fas fa-print"></i> طباعة</button>
            </div>
        </div>
    </div>
</body>
</html>













