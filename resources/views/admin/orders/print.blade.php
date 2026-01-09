<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة طلب #{{ $order->order_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Cairo", sans-serif; background: white; padding: 30px; }
        .invoice-container { max-width: 900px; margin: 0 auto; }
        
        /* Header */
        .invoice-header { display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 30px; border-bottom: 3px solid #333; margin-bottom: 30px; }
        .company-info { flex: 1; }
        .company-logo { width: 180px; height: auto; margin-bottom: 15px; }
        .company-name { font-size: 24px; font-weight: 700; color: #1e3a8a; margin-bottom: 8px; }
        .company-details { font-size: 13px; line-height: 1.8; color: #555; }
        .company-details div { margin-bottom: 4px; }
        
        .invoice-info { text-align: left; }
        .invoice-badge { background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white; padding: 8px 20px; border-radius: 25px; font-size: 16px; font-weight: 600; display: inline-block; margin-bottom: 10px; }
        
        /* Customer Info */
        .customer-section { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px; }
        .section-title { font-size: 18px; font-weight: 700; color: #1e3a8a; margin-bottom: 15px; border-right: 4px solid #1e3a8a; padding-right: 12px; }
        .customer-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .info-item { display: flex; }
        .info-label { font-weight: 600; color: #333; min-width: 120px; }
        .info-value { color: #666; }
        
        /* Products Table */
        .products-table { width: 100%; border-collapse: collapse; margin: 25px 0; }
        .products-table thead { background: #1e3a8a; color: white; }
        .products-table th { padding: 12px; text-align: center; font-weight: 600; font-size: 14px; }
        .products-table td { padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        .products-table tbody tr:hover { background: #f9fafb; }
        .products-table tbody tr:last-child td { border-bottom: 2px solid #1e3a8a; }
        
        /* Totals */
        .totals-section { background: #f8f9fa; padding: 25px; border-radius: 8px; margin-top: 25px; }
        .total-row { display: flex; justify-content: space-between; padding: 12px 0; font-size: 16px; border-bottom: 1px solid #dee2e6; }
        .total-row:last-child { border-bottom: none; border-top: 3px solid #1e3a8a; padding-top: 15px; margin-top: 10px; font-size: 20px; font-weight: 700; color: #1e3a8a; }
        
        /* Status */
        .status-section { text-align: center; margin-top: 30px; padding: 20px; background: #d1f2eb; border-radius: 8px; }
        .status-text { font-size: 18px; font-weight: 700; color: #0d7377; }
        
        @media print {
            body { padding: 10px; }
            @page { margin: 10mm; }
        }
    </style>
</head>
<body onload="window.print(); window.onafterprint = function(){ window.close(); }">
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <img src="{{ asset('frontend/images/logo.png') }}" alt="Logo" class="company-logo">
                <h1 class="company-name">توريد ميد</h1>
                <div class="company-details">
                    <div>📍 <strong>العنوان:</strong> مصر - القاهرة</div>
                    <div>☎️ <strong>للتواصل:</strong> 01000000000</div>
                    <div>✉️ <strong>البريد الإلكتروني:</strong> info@tawreedmed.com</div>
                    <div>🌐 <strong>الموقع:</strong> www.tawreedmed.com</div>
                </div>
            </div>
            
            <div class="invoice-info">
                <div class="invoice-badge">🧾 توريد ميد "شريكك في التوريدات الطبية"</div>
                <div style="text-align: right; margin-top: 15px; font-size: 14px; color: #666;">
                    <div><strong>تاريخ الطلب:</strong> {{ $order->created_at->format('Y/m/d') }}</div>
                    <div><strong>رقم الطلبية:</strong> {{ $order->order_number }}</div>
                </div>
            </div>
        </div>
        
        <!-- Customer Info -->
        <div class="customer-section">
            <h2 class="section-title">المرسل إليه</h2>
            <div class="customer-grid">
                <div class="info-item">
                    <span class="info-label">الاسم:</span>
                    <span class="info-value">{{ $order->shipping_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">البريد الإلكتروني:</span>
                    <span class="info-value">{{ $order->customer->email ?? 'غير محدد' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">الهاتف:</span>
                    <span class="info-value">{{ $order->shipping_phone }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">طريقة الدفع:</span>
                    <span class="info-value">{{ $order->payment_method == 'cod' ? 'الدفع عند الاستلام' : 'دفع إلكتروني' }}</span>
                </div>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <span class="info-label">العنوان:</span>
                    <span class="info-value">{{ $order->shipping_governorate }}, {{ $order->shipping_city }}, {{ $order->shipping_address }}</span>
                </div>
            </div>
        </div>
        
        <!-- Products Table -->
        <table class="products-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم الصنف</th>
                    <th>العدد</th>
                    <th>السعر (قبل الخصم)</th>
                    <th>السعر (بعد الخصم)</th>
                    <th>الإجمالي بعد الخصم</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="text-align: right;">{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 1) }}</td>
                    <td>{{ number_format($item->unit_price * 0.9, 1) }}</td>
                    <td>{{ number_format($item->total_price, 1) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Totals -->
        <div class="totals-section">
            <div class="total-row">
                <span>الإجمالي:</span>
                <span>{{ number_format($order->subtotal, 1) }}</span>
            </div>
            <div class="total-row">
                <span>الشحن:</span>
                <span>{{ number_format($order->shipping_cost ?? 0, 1) }}</span>
            </div>
            <div class="total-row">
                <span>الصافي:</span>
                <span>{{ number_format($order->total, 1) }}</span>
            </div>
        </div>
        
        <!-- Status -->
        <div class="status-section">
            <div class="status-text">حالة الطلبية: {{ $order->status_label }}</div>
        </div>
    </div>
</body>
</html>
