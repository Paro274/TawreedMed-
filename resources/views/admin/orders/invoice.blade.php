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
        .invoice-header { border-bottom: 2px solid #667eea; padding-bottom: 20px; margin-bottom: 30px; }
        .invoice-title { color: #667eea; font-size: 28px; font-weight: bold; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 600; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #cff4fc; color: #055160; }
        .status-shipped { background: #cfe2ff; color: #084298; }
        .status-delivered { background: #d1e7dd; color: #0f5132; }
        .status-cancelled { background: #e2e3e5; color: #41464b; }
        .status-rejected { background: #f8d7da; color: #842029; }
        
        .invoice-totals { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 20px; }
        .total-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #ddd; }
        .total-row:last-child { border-bottom: none; font-size: 20px; font-weight: bold; color: #667eea; margin-top: 10px; padding-top: 15px; border-top: 2px solid #667eea; }

        .supplier-section { border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; margin-bottom: 20px; }
        .supplier-header { background: #f9fafb; padding: 10px 15px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
        .commission-box { background: #f0f9ff; padding: 15px; border-top: 1px solid #e5e7eb; }
        
        @media print {
            body { padding: 0; background: white; font-size: 12px; }
            .content { margin-right: 0 !important; padding: 0 !important; }
            .invoice-card { box-shadow: none; padding: 0; }
            .no-print, .btn, form, .commission-box { display: none !important; }
        }
    </style>
</head>
<body>
    @include('admin.sidebar')
    <div class="content" style="margin-right: 240px; padding: 30px;">
        <div class="invoice-card">
            <div class="d-flex justify-content-between align-items-start mb-4 no-print">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-right me-1"></i> العودة للقائمة
                </a>
                <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="btn btn-primary">
                    <i class="fas fa-print me-1"></i> طباعة الفاتورة
                </a>
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

            @if(session('error'))
            <div class="alert alert-danger no-print">{{ session('error') }}</div>
            @endif
            @if(session('success'))
            <div class="alert alert-success no-print">{{ session('success') }}</div>
            @endif
            
            <div class="mb-4">
                <h4>معلومات الشحن</h4>
                <p><strong>الاسم:</strong> {{ $order->shipping_name }}</p>
                <p><strong>الهاتف:</strong> {{ $order->shipping_phone }}</p>
                <p><strong>العنوان:</strong> {{ $order->shipping_governorate }}, {{ $order->shipping_city }}, {{ $order->shipping_address }}</p>
            </div>
            
            <hr>
            <h4 class="mb-3">تفاصيل المنتجات حسب المورد</h4>

            @php
                $groupedItems = $order->items->groupBy(function($item) {
                    return $item->product->supplier_id ?? 'unknown';
                });
            @endphp

            @foreach($groupedItems as $supplierId => $items)
                @php
                    $supplier = \App\Models\Supplier::find($supplierId);
                    $orderSupplier = \App\Models\OrderSupplier::where('order_id', $order->id)
                                        ->where('supplier_id', $supplierId)->first();
                @endphp

                <div class="supplier-section">
                    <div class="supplier-header">
                        <h5 class="mb-0 text-primary">
                            <i class="fas fa-store me-2"></i> 
                            {{ $supplier ? $supplier->name : 'غير محدد/متجر' }}
                        </h5>
                        @if($orderSupplier)
                            <span class="badge {{ $orderSupplier->commission_collected ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $orderSupplier->commission_collected ? '✅ تم استلام العمولة' : '⏳ بانتظار العمولة' }}
                            </span>
                        @endif
                    </div>
                    
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }} ج.م</td>
                                <td>{{ number_format($item->total_price, 2) }} ج.م</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($supplier && $orderSupplier)
                    <div class="commission-box no-print">
                        <form action="{{ route('admin.orders.updateCommission', $order->id) }}" method="POST" class="row align-items-end g-3">
                            @csrf
                            <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                            
                            <div class="col-md-3">
                                <label class="form-label small text-muted">إجمالي مبيعات المورد</label>
                                <input type="text" class="form-control form-control-sm" value="{{ number_format($orderSupplier->subtotal, 2) }}" readonly>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small">نوع العمولة</label>
                                <select name="commission_type" class="form-select form-select-sm">
                                    <option value="percentage" {{ $orderSupplier->commission_type == 'percentage' ? 'selected' : '' }}>نسبة %</option>
                                    <option value="fixed" {{ $orderSupplier->commission_type == 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small">القيمة</label>
                                <input type="number" name="commission_value" step="0.01" class="form-control form-control-sm" value="{{ $orderSupplier->commission_value }}" required>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="commission_collected" value="1" id="paid_{{ $supplier->id }}" {{ $orderSupplier->commission_collected ? 'checked' : '' }}>
                                    <label class="form-check-label small fw-bold" for="paid_{{ $supplier->id }}">
                                        تم استلام العمولة
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-sm w-100">حفظ العمولة</button>
                            </div>
                        </form>

                        @if($orderSupplier->commission_amount > 0)
                        <div class="mt-2 pt-2 border-top d-flex justify-content-between small text-dark">
                            <span>العمولة المستحقة: <strong class="text-danger">{{ number_format($orderSupplier->commission_amount, 2) }} ج.م</strong></span>
                            <span>صافي للمورد: <strong class="text-success">{{ number_format($orderSupplier->supplier_due, 2) }} ج.م</strong></span>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            @endforeach
            
            <div class="invoice-totals">
                <div class="total-row">
                    <span>المجموع الفرعي الكلي:</span>
                    <span>{{ number_format($order->subtotal, 2) }} ج.م</span>
                </div>
                <div class="total-row">
                    <span>الإجمالي الكلي للطلب:</span>
                    <span>{{ number_format($order->total, 2) }} ج.م</span>
                </div>
            </div>
            
            <div class="mt-4 no-print">
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    <select name="status" class="form-select d-inline-block" style="width: auto; font-weight: bold;">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>بإنتظار الموافقة</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>جاري التجهيز</option>
                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                        <option value="rejected" {{ $order->status == 'rejected' ? 'selected' : '' }}>تم الرفض</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">تحديث الحالة</button>
                </form>
                
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm ms-2">العودة</a>
            </div>
        </div>
    </div>
</body>
</html>
