<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب جديد</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 700px; margin: auto; }
        .header { background: #10b981; color: white; padding: 15px; border-radius: 5px; text-align: center; }
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
            <h2>📦 طلب جديد لمنتجاتك</h2>
        </div>

        <p>مرحباً <strong>{{ $supplier->name }}</strong>،</p>
        <p>تم استلام طلب جديد يحتوي على منتجاتك:</p>

        <div class="info">
            <p><strong>رقم الطلب:</strong> {{ $order->order_number }}</p>
            <p><strong>التاريخ:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
            <p><strong>اسم العميل:</strong> {{ $customer->name }}</p>
            <p><strong>الهاتف:</strong> {{ $order->shipping_phone }}</p>
            <p><strong>العنوان:</strong> {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_governorate }}</p>
        </div>

        <h3>منتجاتك في هذا الطلب:</h3>
        <table>
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>السعر</th>
                    <th>الكمية</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @php $supplierTotal = 0; @endphp
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ number_format($item->unit_price, 2) }} ج.م</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->total_price, 2) }} ج.م</td>
                </tr>
                @php $supplierTotal += $item->total_price; @endphp
                @endforeach
            </tbody>
        </table>

        <p class="total">إجمالي منتجاتك: {{ number_format($supplierTotal, 2) }} جنيه مصري</p>

        <div class="footer">
            <p>يرجى تجهيز المنتجات في أقرب وقت ممكن</p>
            <p>© {{ date('Y') }} توريد ميد - جميع الحقوق محفوظة</p>
        </div>
    </div>
</body>
</html>
