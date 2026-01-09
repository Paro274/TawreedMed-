@extends('frontend.layouts.app')

@section('title', 'طلباتي - توريد ميد')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-boxes me-2 text-success"></i>
                طلباتك الأخيرة
            </h5>
            <a href="{{ route('frontend.customer.profile') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-user me-1"></i> الرجوع للملف الشخصي
            </a>
        </div>
        <div class="card-body p-0">
            @if($orders->isEmpty())
                <div class="p-4 text-center text-muted">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <p class="mb-0">لا توجد طلبات في الوقت الحالي.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>رقم الطلب</th>
                                <th>التاريخ</th>
                                <th>إجمالي الطلب</th>
                                <th>عدد المنتجات</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td><strong>#{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->created_at->format('Y/m/d') }}</td>
                                    <td>{{ number_format($order->total, 2) }} ج.م</td>
                                    <td>{{ $order->items->count() }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($order->status == 'delivered') bg-success
                                            @elseif($order->status == 'shipped') bg-info
                                            @elseif($order->status == 'processing') bg-warning
                                            @elseif($order->status == 'confirmed') bg-primary
                                            @elseif($order->status == 'cancelled') bg-danger
                                            @else bg-secondary
                                            @endif px-3 py-2">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('frontend.customer.orders.invoice', $order->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> عرض الفاتورة
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
