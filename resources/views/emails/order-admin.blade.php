<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب جديد</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 700px; margin: auto; }
        .header { background: #3b82f6; color: white; padding: 15px; border-radius: 5px; text-align: center; }
        .info { margin: 20px 0; }
        .info strong { display: inline-block; width: 150px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: right; }
        th { background: #f8f9fa; }
        .total { font-size: 1.2em; font-weight: bold; color: #16a34a; text-align: left; }
        .footer { text-align: center; color: #666; margin-top: 30px; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🎉 طلب جديد من توريد ميد</h2>
        </div>

        <div class="info">
            <p><strong>رقم الطلب:</strong> {{ $order->order_number }}</p>
            <p><strong>التاريخ:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
            <p><strong>اسم العميل:</strong> {{ $customer->name }}</p>
            <p><strong>الهاتف:</strong> {{ $order->shipping_phone }}</p>
            <p><strong>العنوان:</strong> {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_governorate }}</p>
        </div>

        <h3>تفاصيل الطلب:</h3>
        <table>
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>المورد</th>
                    <th>السعر</th>
                    <th>الكمية</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->product->supplier->name ?? 'غير محدد' }}</td>
                    <td>{{ number_format($item->unit_price, 2) }} ج.م</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->total_price, 2) }} ج.م</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">الإجمالي الكلي: {{ number_format($order->total, 2) }} جنيه مصري</p>

        @if($order->notes)
        <div style="background: #fef3c7; padding: 10px; border-radius: 5px; margin-top: 15px;">
            <strong>ملاحظات العميل:</strong> {{ $order->notes }}
        </div>
        @endif

        <div class="footer">
            <p>© {{ date('Y') }} توريد ميد - جميع الحقوق محفوظة</p>
        </div>
    </div>
</body>
</html>
