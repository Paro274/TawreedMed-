@extends('frontend.layouts.app')

@section('title', 'إتمام الطلب - توريد ميد')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/checkout.css') }}">
@endpush

@section('content')
<div class="checkout-page">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="checkout-card">
                    <h2 class="checkout-title">
                        <i class="fas fa-shopping-cart me-2"></i>
                        إتمام الطلب
                    </h2>
                    
                    <form action="{{ route('frontend.checkout.store') }}" method="POST" id="checkoutForm">
                        @csrf
                        
                        {{-- Shipping Information --}}
                        <div class="section">
                            <h3 class="section-title">
                                <i class="fas fa-truck me-2"></i>
                                معلومات الشحن
                            </h3>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">اسم المستلم <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="shipping_name" 
                                           class="form-control @error('shipping_name') is-invalid @enderror"
                                           value="{{ old('shipping_name', $customer->name ?? '') }}"
                                           required>
                                    @error('shipping_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="shipping_phone" 
                                           class="form-control @error('shipping_phone') is-invalid @enderror"
                                           value="{{ old('shipping_phone', $customer->phone ?? '') }}"
                                           required>
                                    @error('shipping_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">البريد الإلكتروني</label>
                                    <input type="email" 
                                           name="shipping_email" 
                                           class="form-control @error('shipping_email') is-invalid @enderror"
                                           value="{{ old('shipping_email', $customer->email ?? '') }}">
                                    @error('shipping_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">المحافظة <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="shipping_governorate" 
                                           class="form-control @error('shipping_governorate') is-invalid @enderror"
                                           value="{{ old('shipping_governorate') }}"
                                           required>
                                    @error('shipping_governorate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">المدينة <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="shipping_city" 
                                           class="form-control @error('shipping_city') is-invalid @enderror"
                                           value="{{ old('shipping_city') }}"
                                           required>
                                    @error('shipping_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label class="form-label">العنوان التفصيلي <span class="text-danger">*</span></label>
                                    <textarea name="shipping_address" 
                                              class="form-control @error('shipping_address') is-invalid @enderror"
                                              rows="3"
                                              required>{{ old('shipping_address') }}</textarea>
                                    @error('shipping_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label class="form-label">ملاحظات (اختياري)</label>
                                    <textarea name="notes" 
                                              class="form-control"
                                              rows="2">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Payment Method --}}
                        <div class="section">
                            <h3 class="section-title">
                                <i class="fas fa-credit-card me-2"></i>
                                طريقة الدفع
                            </h3>
                            
                            <div class="payment-methods">
                                <div class="payment-option active">
                                    <input type="radio" 
                                           name="payment_method" 
                                           id="payment_cash" 
                                           value="cash" 
                                           checked
                                           required>
                                    <label for="payment_cash" class="payment-label">
                                        <i class="fas fa-money-bill-wave me-2"></i>
                                        <span>الدفع نقداً عند الاستلام</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="checkout-actions">
                            <a href="{{ route('frontend.cart') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة للسلة
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check me-2"></i>
                                تأكيد الطلب
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            {{-- Order Summary --}}
            <div class="col-lg-4">
                <div class="order-summary-card">
                    <h3 class="summary-title">
                        <i class="fas fa-receipt me-2"></i>
                        ملخص الطلب
                    </h3>
                    
                    <div class="order-items">
                        @foreach($items as $item)
                        <div class="order-item">
                            <div class="item-image">
                                @php
                                    $imagePath = $item->image_1;
                                    $imageUrl = $imagePath ? (filter_var($imagePath, FILTER_VALIDATE_URL) ? $imagePath : asset($imagePath)) : asset('frontend/images/default-product.png');
                                @endphp
                                <img src="{{ $imageUrl }}" 
                                     alt="{{ $item->name }}"
                                     onerror="this.src='https://via.placeholder.com/60'">
                            </div>
                            <div class="item-details">
                                <h5 class="item-name">{{ $item->name }}</h5>
                                <p class="item-quantity">الكمية: {{ $item->quantity }}</p>
                                <p class="item-price">{{ number_format($item->price * $item->quantity, 2) }} ج.م</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="order-totals">
                        <div class="total-row">
                            <span>المجموع الفرعي:</span>
                            <span>{{ number_format($total, 2) }} ج.م</span>
                        </div>


                        <div class="total-row total-final">
                            <span>الإجمالي:</span>
                            <span>{{ number_format($grandTotal, 2) }} ج.م</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection





