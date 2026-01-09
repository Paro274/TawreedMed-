@extends('frontend.layouts.app')

@section('title', 'سلة المشتريات - توريد ميد')

@push('styles')
<style>
    .cart-page {
        padding: 40px 0;
        min-height: 60vh;
    }
    .cart-item {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .cart-item-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
    }
    .cart-item-details {
        flex: 1;
    }
    .cart-item-name {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }
    .cart-item-price {
        font-size: 16px;
        color: #667eea;
        font-weight: 600;
    }
    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 10px 0;
    }
    .quantity-btn {
        width: 35px;
        height: 35px;
        border: 1px solid #ddd;
        background: white;
        border-radius: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .quantity-btn:hover {
        background: #f0f0f0;
    }
    .quantity-input {
        width: 60px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 5px;
    }
    .remove-item {
        color: #dc3545;
        cursor: pointer;
        font-size: 20px;
    }
    .remove-item:hover {
        color: #c82333;
    }
    .cart-summary {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        position: sticky;
        top: 100px;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    .summary-row:last-child {
        border-bottom: none;
        font-size: 20px;
        font-weight: bold;
        color: #667eea;
        margin-top: 10px;
        padding-top: 15px;
        border-top: 2px solid #667eea;
    }
    .empty-cart {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-cart-icon {
        font-size: 80px;
        color: #ddd;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="cart-page">
    <div class="container">
        <h2 class="mb-4">
            <i class="fas fa-shopping-cart me-2"></i>
            سلة المشتريات
        </h2>
        
        @if($items->isEmpty())
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>سلة المشتريات فارغة</h3>
                <p class="text-muted">لم تقم بإضافة أي منتجات للسلة بعد</p>
                <a href="{{ route('frontend.home') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-arrow-left me-2"></i>
                    العودة للتسوق
                </a>
            </div>
        @else
            <div class="row">
                <div class="col-lg-8">
                    @foreach($items as $item)
                    <div class="cart-item" data-product-id="{{ $item->id }}">
                        @php
                            $imagePath = $item->image_1;
                            $imageUrl = $imagePath ? (filter_var($imagePath, FILTER_VALIDATE_URL) ? $imagePath : asset($imagePath)) : asset('frontend/images/default-product.png');
                        @endphp
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $item->name }}" 
                             class="cart-item-image"
                             onerror="this.src='https://via.placeholder.com/100'">
                        
                        <div class="cart-item-details">
                            <div class="cart-item-name">{{ $item->name }}</div>
                            <div class="cart-item-price">{{ number_format($item->price, 2) }} ج.م</div>
                            
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="updateQuantity({{ $item->id }}, -1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" 
                                       class="quantity-input" 
                                       value="{{ $item->quantity }}" 
                                       min="1" 
                                       id="qty-{{ $item->id }}"
                                       onchange="updateQuantity({{ $item->id }}, 0, this.value)">
                                <button class="quantity-btn" onclick="updateQuantity({{ $item->id }}, 1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <div class="cart-item-price mb-2">
                                {{ number_format($item->price * $item->quantity, 2) }} ج.م
                            </div>
                            <button class="remove-item" onclick="removeItem({{ $item->id }})" title="إزالة">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="text-end mt-3">
                        <button class="btn btn-outline-danger" onclick="clearCart()">
                            <i class="fas fa-trash me-2"></i>
                            تفريغ السلة
                        </button>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="cart-summary">
                        <h4 class="mb-3">ملخص الطلب</h4>
                        
                        <div class="summary-row">
                            <span>عدد المنتجات:</span>
                            <span>{{ $items->count() }}</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>المجموع الفرعي:</span>
                            <span>{{ number_format($total, 2) }} ج.م</span>
                        </div>
                        

                        

                        
                        <div class="summary-row">
                            <span>الإجمالي:</span>
                            <span>{{ number_format($total, 2) }} ج.م</span>
                        </div>
                        
                        <a href="{{ route('frontend.cart.checkout') }}" class="btn btn-primary w-100 mt-3">
                            <i class="fas fa-credit-card me-2"></i>
                            إتمام الطلب
                        </a>
                        
                        <a href="{{ route('frontend.home') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-arrow-right me-2"></i>
                            متابعة التسوق
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateQuantity(productId, change, newValue = null) {
    const input = document.getElementById('qty-' + productId);
    let quantity = parseInt(input.value) || 1;
    
    if (newValue !== null) {
        quantity = parseInt(newValue) || 1;
    } else {
        quantity += change;
    }
    
    quantity = Math.max(1, quantity);
    
    fetch(`{{ url('/cart/update') }}/${productId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            // Revert input value if failed
            if (newValue !== null) {
                // If we knew the old value we could revert to it, but reloading is safer or just alerting
                // For now, let's just alert. The user can correct it.
            }
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: data.icon || 'error',
                    title: 'تنبيه',
                    text: data.message
                });
            } else {
                alert(data.message);
            }
            
            // Optional: Reload to reset the input to valid state from server
            // location.reload(); 
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء تحديث الكمية');
    });
}

function removeItem(productId) {
    if (!confirm('هل أنت متأكد من إزالة هذا المنتج من السلة؟')) {
        return;
    }
    
    fetch(`{{ url('/cart/remove') }}/${productId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء إزالة المنتج');
    });
}

function clearCart() {
    if (!confirm('هل أنت متأكد من تفريغ السلة بالكامل؟')) {
        return;
    }
    
    fetch('{{ route("frontend.cart.clear") }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء تفريغ السلة');
    });
}
</script>
@endpush





