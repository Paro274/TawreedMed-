@extends('frontend.layouts.app')

@section('title', 'فاتورة الطلب #' . $order->order_number)

@push('styles')
<style>
    .invoice-page {
        background: #f5f5f5;
        min-height: 100vh;
        padding: 40px 0;
    }
    .invoice-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 40px;
        max-width: 900px;
        margin: 0 auto;
    }
    .invoice-header {
        border-bottom: 2px solid #667eea;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    .invoice-title {
        color: #667eea;
        font-size: 28px;
        font-weight: bold;
        margin: 0;
    }
    .invoice-meta {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        flex-wrap: wrap;
        gap: 20px;
    }
    .meta-item {
        display: flex;
        flex-direction: column;
    }
    .meta-label {
        color: #666;
        font-size: 14px;
        margin-bottom: 5px;
    }
    .meta-value {
        color: #333;
        font-weight: 600;
        font-size: 16px;
    }
    .invoice-section {
        margin: 30px 0;
    }
    .section-title {
        color: #333;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    .invoice-table th,
    .invoice-table td {
        padding: 12px;
        text-align: right;
        border-bottom: 1px solid #eee;
    }
    .invoice-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #333;
    }
    .invoice-table td {
        color: #666;
    }
    .invoice-totals {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
    }
    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #ddd;
    }
    .total-row:last-child {
        border-bottom: none;
        font-size: 20px;
        font-weight: bold;
        color: #667eea;
        margin-top: 10px;
        padding-top: 15px;
        border-top: 2px solid #667eea;
    }
    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-confirmed { background: #d1ecf1; color: #0c5460; }
    .status-processing { background: #cce5ff; color: #004085; }
    .status-shipped { background: #d4edda; color: #155724; }
    .status-delivered { background: #d1f2eb; color: #0d7377; }
    .status-cancelled { background: #f8d7da; color: #721c24; }
    .invoice-actions {
        margin-top: 30px;
        display: flex;
        gap: 10px;
        justify-content: center;
    }
    @media print {
        .invoice-actions { display: none; }
        .invoice-page { background: white; padding: 0; }
    }
</style>
@endpush

@section('content')
<div class="invoice-page">
    <div class="container">
        <div class="invoice-card">
            <div class="invoice-header">
                <h1 class="invoice-title">
                    <i class="fas fa-receipt me-2"></i>
                    فاتورة الطلب
                </h1>
                <div class="invoice-meta">
                    <div class="meta-item">
                        <span class="meta-label">رقم الطلب:</span>
                        <span class="meta-value">#{{ $order->order_number }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">تاريخ الطلب:</span>
                        <span class="meta-value">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">حالة الطلب:</span>
                        <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">حالة الدفع:</span>
                        <span class="status-badge status-{{ $order->payment_status }}">{{ $order->payment_status_label }}</span>
                    </div>
                </div>
            </div>
            
            <div class="invoice-section">
                <h3 class="section-title">معلومات الشحن</h3>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>الاسم:</strong> {{ $order->shipping_name }}</p>
                        <p><strong>الهاتف:</strong> {{ $order->shipping_phone }}</p>
                        <p><strong>البريد:</strong> {{ $order->shipping_email ?? 'غير محدد' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>المحافظة:</strong> {{ $order->shipping_governorate }}</p>
                        <p><strong>المدينة:</strong> {{ $order->shipping_city }}</p>
                        <p><strong>العنوان:</strong> {{ $order->shipping_address }}</p>
                    </div>
                </div>
                @if($order->notes)
                <p class="mt-3"><strong>ملاحظات:</strong> {{ $order->notes }}</p>
                @endif
            </div>
            
            <div class="invoice-section">
                <h3 class="section-title">تفاصيل الطلب</h3>
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
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3" style="width: 50px; height: 50px;">
                                        @php
                                            $imagePath = $item->product_image;
                                            $imageUrl = $imagePath ? (filter_var($imagePath, FILTER_VALIDATE_URL) ? $imagePath : asset($imagePath)) : asset('frontend/images/default-product.png');
                                        @endphp
                                        <img src="{{ $imageUrl }}" 
                                             alt="{{ $item->product_name }}" 
                                             class="img-fluid rounded border"
                                             style="width: 50px; height: 50px; object-fit: contain;"
                                             onerror="this.src='https://via.placeholder.com/50'">
                                    </div>
                                    <div>
                                        <strong>{{ $item->product_name }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }} ج.م</td>
                            <td><strong>{{ number_format($item->total_price, 2) }} ج.م</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="invoice-totals">
                <div class="total-row">
                    <span>المجموع الفرعي:</span>
                    <span>{{ number_format($order->subtotal, 2) }} ج.م</span>
                </div>


                <div class="total-row">
                    <span>الإجمالي النهائي:</span>
                    <span>{{ number_format($order->total, 2) }} ج.م</span>
                </div>
            </div>
            
            <div class="invoice-actions">
                <a href="{{ route('frontend.customer.orders') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-right me-2"></i>العودة للطلبات
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print me-2"></i>طباعة الفاتورة
                </button>
            </div>
        </div>
    </div>
</div>
@endsection













