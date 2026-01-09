@extends('frontend.layouts.app')

@section('title', $product->name . ' - الأدوية - توريد ميد')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/product-details.css') }}">
<style>
/* ✅ نفس الـ CSS بتاع المستلزمات للتوحيد */
.product-price-box .price-main {
    font-size: 1.8rem;
    font-weight: bold;
    color: #16a34a;
}

/* ✅ Enhanced Price Display Styles */
.price-display {
    display: flex;
    flex-direction: column;
    gap: 5px;
    margin-right: 15px;
    min-width: 150px;
}

.unit-price {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
    color: #6b7280;
}

.unit-price span {
    font-weight: 500;
    color: #374151;
}

.total-price {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 1rem;
    font-weight: bold;
    color: #16a34a;
    padding: 5px 10px;
    background: rgba(22, 163, 74, 0.1);
    border-radius: 6px;
    border: 1px solid rgba(22, 163, 74, 0.2);
}

#totalPrice {
    transition: all 0.3s ease;
}

#cartButtonPrice {
    transition: all 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .quantity-controls {
        flex-direction: column;
        gap: 10px;
    }
    
    .price-display {
        margin-right: 0;
        min-width: 100%;
    }
    
    .unit-price,
    .total-price {
        justify-content: center;
    }
}

.unit-info {
    background: #f0f9ff;
    border: 1px solid #0ea5e9;
    border-radius: 6px;
    padding: 8px 12px;
    margin-top: 8px;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.unit-info i {
    color: #0ea5e9;
}

.product-availability.available {
    background: #dcfce7;
    border: 1px solid #22c55e;
    color: #166534;
    padding: 8px 12px;
    border-radius: 6px;
    margin: 12px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.product-availability.unavailable {
    background: #fef2f2;
    border: 1px solid #ef4444;
    color: #991b1b;
    padding: 8px 12px;
    border-radius: 6px;
    margin: 12px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.product-availability .icon {
    font-size: 1.2rem;
    font-weight: bold;
}

.stock-count {
    background: rgba(255,255,255,0.7);
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.85rem;
    margin-left: 8px;
}

.box-info {
    background: #f8fafc;
    border-left: 4px solid #3b82f6;
    padding: 12px;
    border-radius: 4px;
    margin: 15px 0;
}

.product-actions {
    margin-top: 20px;
}

.quantity-selector {
    margin-bottom: 15px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 8px;
    max-width: 200px;
}

.qty-btn {
    width: 40px;
    height: 40px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.qty-btn:hover {
    background: #f8f9fa;
}

.qty-input {
    width: 60px;
    height: 40px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-align: center;
    font-size: 1rem;
}

.action-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-add-to-cart {
    background: #16a34a;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.btn-add-to-cart:hover {
    background: #15803d;
    transform: translateY(-2px);
}

.btn-wishlist {
    background: transparent;
    border: 2px solid #16a34a;
    color: #16a34a;
    padding: 12px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.2rem;
    transition: all 0.3s;
}

.btn-wishlist:hover {
    background: #16a34a;
    color: white;
}

.out-of-stock {
    margin-top: 20px;
}

.btn-out-stock {
    background: #6b7280;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: not-allowed;
    margin-right: 10px;
}

.btn-notify {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-notify:hover {
    background: #2563eb;
}

.discount-badge {
    background: #ef4444;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: bold;
    display: inline-block;
    margin-top: 8px;
}

.product-subtitle {
    color: #6b7280;
    font-size: 1.1rem;
    margin-bottom: 10px;
    font-weight: 500;
}

.product-meta {
    margin: 10px 0;
    padding: 8px 0;
    border-bottom: 1px solid #e5e7eb;
}

.product-company, .product-category {
    margin: 10px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.product-company .label, .product-category .label {
    font-weight: 600;
    color: #374151;
    min-width: 120px;
}

.product-box-info {
    margin: 15px 0;
    background: #f8fafc;
    border-radius: 6px;
    padding: 12px;
}

.box-detail {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.box-detail .icon {
    font-size: 1.5rem;
    margin-top: 2px;
}

.product-short-desc {
    background: #f9fafb;
    padding: 15px;
    border-radius: 6px;
    margin: 15px 0;
    border-left: 4px solid #10b981;
}

.product-short-desc h4 {
    margin: 0 0 10px 0;
    color: #059669;
}
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<section class="breadcrumb-section">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('frontend.home') }}">الرئيسية</a>
            <span>/</span>
            <a href="{{ route('frontend.medicines.index') }}">الأدوية</a>
            <span>/</span>
            <span>{{ $product->name }}</span>
        </div>
    </div>
</section>

<!-- Product Details -->
<section class="product-details-section">
    <div class="container">
        <div class="product-main row">
            <!-- Product Images -->
            <div class="product-gallery col-lg-5">
                @if($product->image_1)
                <div class="main-image">
                    <img id="mainImage" src="{{ asset('storage/' . $product->image_1) }}" alt="{{ $product->name }}">
                    @if($product->featured)
                    <span class="badge-featured">مميز</span>
                    @endif
                </div>
                @else
                <div class="no-image">
                    <i class="fas fa-pills fa-5x text-muted"></i>
                </div>
                @endif

                <!-- الصور الإضافية -->
                @if($product->image_2 || $product->image_3 || $product->image_4)
                <div class="thumbnail-images mt-3">
                    @if($product->image_2)
                    <img src="{{ asset('storage/' . $product->image_2) }}" 
                         alt="صورة إضافية" 
                         class="thumbnail-img" 
                         onclick="changeMainImage('{{ asset('storage/' . $product->image_2) }}')">
                    @endif
                    @if($product->image_3)
                    <img src="{{ asset('storage/' . $product->image_3) }}" 
                         alt="صورة إضافية" 
                         class="thumbnail-img" 
                         onclick="changeMainImage('{{ asset('storage/' . $product->image_3) }}')">
                    @endif
                    @if($product->image_4)
                    <img src="{{ asset('storage/' . $product->image_4) }}" 
                         alt="صورة إضافية" 
                         class="thumbnail-img" 
                         onclick="changeMainImage('{{ asset('storage/' . $product->image_4) }}')">
                    @endif
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="product-info-main col-lg-7">
                <h1 class="product-title">{{ $product->name }}</h1>
                
                @if($product->active_ingredient)
                <div class="product-subtitle">{{ $product->active_ingredient }}</div>
                @endif

                <div class="product-meta">
                    @if($product->supplier)
                    <div class="product-supplier">
                        <i class="fas fa-building"></i>
                        <a href="{{ route('frontend.companies.show', $product->supplier_id) }}">
                            {{ $product->supplier->name }}
                        </a>
                    </div>
                    @endif
                    @if($product->sku)
                    <div class="product-sku">كود المنتج: <strong>{{ $product->sku }}</strong></div>
                    @endif
                </div>

                <!-- ✅ السعر الجديد بنفس تصميم المستلزمات -->
                <div class="product-price-box">
                    <div class="price-main">
                        {{ number_format($product->final_price, 2) }} جنيه
                    </div>
                    
                    <!-- ✅ معلومات الوحدة للأدوية (للقطعة) -->
                    <div class="unit-info">
                        <i class="fas fa-pills"></i>
                        <strong>السعر للقطعة</strong>
                        @if($product->units_per_package)
                        <span class="ms-2">• العلبة تحتوي على {{ $product->units_per_package }} {{ $product->units_per_package > 1 ? 'قرص' : 'أقراص' }}</span>
                        @endif
                        @if($product->min_order_quantity > 1)
                        <span class="ms-2">• الحد الأدنى: {{ $product->min_order_quantity }} علبة</span>
                        @endif
                    </div>

                    <!-- ✅ الخصم (لو موجود) -->
                    @if($product->has_discount)
                    <div class="discount-badge mt-2">
                        خصم {{ $product->discount_percent }}% 
                        <small class="ms-1">({{ number_format($product->price, 2) }} جنيه السعر الأصلي)</small>
                    </div>
                    @endif
                </div>

                <div class="product-company">
                    <span class="label"><i class="fas fa-industry"></i> الشركة المصنعة:</span>
                    <strong>{{ $product->company_name ?? 'غير محدد' }}</strong>
                </div>

                @if($product->category)
                <div class="product-category">
                    <span class="label"><i class="fas fa-tag"></i> التصنيف:</span>
                    <a href="{{ route('frontend.medicines.index', ['category' => $product->category->id]) }}">
                        {{ $product->category->name }}
                    </a>
                </div>
                @endif

                <!-- ✅ معلومات الكرتونة/العلبة -->
                @if($product->box_info)
                <div class="box-info">
                    <strong><i class="fas fa-info-circle me-2"></i>تفاصيل التعبئة:</strong>
                    <p class="mb-0">{{ $product->box_info }}</p>
                </div>
                @endif

                <!-- ✅ التوفر (نفس منطق المستلزمات) -->
                <div class="product-availability {{ $product->is_available ? 'available' : 'unavailable' }}">
                    <span class="icon">{{ $product->is_available ? '✓' : '✗' }}</span>
                    @if($product->is_available)
                        @if($product->available_quantity > 0)
                            <strong>متوفر في المخزون</strong>
                            <span class="stock-count">({{ $product->available_quantity }} {{ $product->unit_type }})</span>
                        @else
                            <strong>متوفر للطلب</strong>
                            @if($product->min_order_quantity > 1)
                            <span class="stock-count">(حد أدنى: {{ $product->min_order_quantity }} علبة)</span>
                            @endif
                        @endif
                    @else
                        <strong>غير متوفر حالياً</strong>
                        <span class="stock-count">(يرجى التواصل مع المورد)</span>
                    @endif
                </div>

                <!-- وصف مختصر -->
                @if($product->short_description)
                <div class="product-short-desc">
                    <h4><i class="fas fa-info-circle"></i> نظرة عامة</h4>
                    <p>{{ $product->short_description }}</p>
                </div>
                @endif

                <!-- ✅ إضافة للسلة (نفس تصميم المستلزمات) -->
                @if($product->is_available && $product->price > 0)
                <div class="product-actions">
                    <div class="quantity-selector">
                        <label><i class="fas fa-sort-numeric-up"></i> الكمية:</label>
                        <div class="quantity-controls">
                            <button class="qty-btn minus" type="button" onclick="changeQuantity(-1)">−</button>
                            <input type="number" 
                                   id="quantity" 
                                   class="qty-input" 
                                   value="{{ $product->min_order_quantity ?? 1 }}" 
                                   min="{{ $product->min_order_quantity ?? 1 }}"
                                   max="{{ $product->available_quantity ?? 999 }}"
                                   onchange="updateTotalPrice()"
                                   oninput="this.value = Math.max({{ $product->min_order_quantity ?? 1 }}, Math.min({{ $product->available_quantity ?? 999 }}, parseInt(this.value) || {{ $product->min_order_quantity ?? 1 }})); updateTotalPrice();">
                            <button class="qty-btn plus" type="button" onclick="changeQuantity(1)">+</button>
                            <div class="price-display">
                                <div class="unit-price">
                                    <small>سعر الوحدة:</small>
                                    <span>{{ number_format($product->final_price ?? $product->price, 2) }} جنيه</span>
                                </div>
                                <div class="total-price">
                                    <strong>الإجمالي:</strong>
                                    <span id="totalPrice">{{ number_format($product->final_price * ($product->min_order_quantity ?? 1), 2) }} جنيه</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <button class="btn-add-to-cart" onclick="addToCart({{ $product->id }})">
                            <i class="fas fa-shopping-cart me-2"></i> 
                            أضف إلى السلة 
                            <span class="price-small" id="cartButtonPrice">({{ number_format($product->final_price * ($product->min_order_quantity ?? 1), 2) }} جنيه)</span>
                        </button>
                    </div>
                </div>
                @else
                <div class="out-of-stock">
                    @if(!$product->is_available)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>غير متوفر حالياً</strong> - يرجى التواصل مع المورد للاستفسار عن التوفر
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>السعر غير محدد</strong> - يرجى التواصل مع المورد للاستفسار
                    </div>
                    @endif
                    
                    <button class="btn-notify" onclick="notifyWhenAvailable({{ $product->id }})">
                        <i class="fas fa-bell me-2"></i> 
                        إخطار عند التوفر
                    </button>
                    
                    @if($product->supplier)
                    <div class="contact-supplier mt-3">
                        <a href="{{ route('frontend.companies.show', $product->supplier_id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-phone me-2"></i> 
                            تواصل مع المورد
                        </a>
                    </div>
                    @endif
                </div>
                @endif

                <!-- معلومات إضافية -->
                <div class="product-extra-info mt-4" style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div class="info-item" style="display: flex; align-items: center; gap: 8px; background: #f8fafc; padding: 8px 12px; border-radius: 6px;">
                        <span class="icon">🚚</span>
                        <span>توصيل سريع خلال 2-3 أيام</span>
                    </div>
                    <div class="info-item" style="display: flex; align-items: center; gap: 8px; background: #f8fafc; padding: 8px 12px; border-radius: 6px;">
                        <span class="icon">↩️</span>
                        <span>إرجاع خلال 7 أيام</span>
                    </div>
                    <div class="info-item" style="display: flex; align-items: center; gap: 8px; background: #f8fafc; padding: 8px 12px; border-radius: 6px;">
                        <span class="icon">🔒</span>
                        <span>دفع آمن مضمون</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- الوصف التفصيلي -->
        @if($product->full_description)
        <div class="product-full-description mt-5">
            <h3><i class="fas fa-file-alt me-2"></i>تفاصيل المنتج</h3>
            <div class="description-content p-4 bg-light rounded">
                {!! $product->full_description !!}
            </div>
        </div>
        @endif

        <!-- المواصفات -->
        <div class="product-specifications mt-5">
            <h3><i class="fas fa-list-alt me-2"></i>المواصفات الطبية</h3>
            <div class="row">
                @if($product->dosage_form)
                <div class="col-md-6 mb-2">
                    <strong>الشكل الدوائي:</strong> {{ $product->dosage_form }}
                </div>
                @endif
                @if($product->active_ingredient)
                <div class="col-md-6 mb-2">
                    <strong>المادة الفعالة:</strong> {{ $product->active_ingredient }}
                </div>
                @endif
                @if($product->production_date)
                <div class="col-md-6 mb-2">
                    <strong>تاريخ الإنتاج:</strong> {{ $product->production_date->format('Y-m-d') }}
                </div>
                @endif
                @if($product->expiry_date)
                <div class="col-md-6 mb-2">
                    <strong>تاريخ الانتهاء:</strong> {{ $product->expiry_date->format('Y-m-d') }}
                </div>
                @endif
                @if($product->package_type)
                <div class="col-md-6 mb-2">
                    <strong>نوع التعبئة:</strong> {{ $product->package_type }}
                </div>
                @endif
                @if($product->units_per_package)
                <div class="col-md-6 mb-2">
                    <strong>عدد الأقراص:</strong> {{ $product->units_per_package }}
                </div>
                @endif
                <div class="col-md-6 mb-2">
                    <strong>الكمية المتوفرة:</strong> 
                    @if($product->available_quantity > 0)
                        {{ $product->available_quantity }} علبة
                    @else
                        متوفر للطلب
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
let currentQuantity = {{ $product->min_order_quantity ?? 1 }};
let productPrice = {{ $product->final_price ?? $product->price ?? 0 }};

// ✅ تغيير الصورة الرئيسية
function changeMainImage(imageSrc) {
    const mainImage = document.getElementById('mainImage');
    if (mainImage) {
        mainImage.src = imageSrc;
    }
    document.querySelectorAll('.thumbnail-img').forEach(img => {
        img.classList.remove('active');
    });
    event.target.classList.add('active');
}

// ✅ تغيير الكمية
function changeQuantity(change) {
    const qtyInput = document.getElementById('quantity');
    if (!qtyInput) {
        console.error('Quantity input not found in changeQuantity');
        return;
    }
    
    // Get the actual limits from the input field
    const minOrderQty = parseInt(qtyInput.min) || 1;
    const maxAvailableQty = parseInt(qtyInput.max) || 999;
    
    let newQuantity = parseInt(qtyInput.value) + change;
    
    // STRICT VALIDATION: Cannot go below minimum order quantity
    if (newQuantity < minOrderQty) {
        alert(`الحد الأدنى للطلب هو ${minOrderQty} {{ $product->unit_type ?? "وحدة" }}. لا يمكنك طلب كمية أقل.`);
        return;
    }
    
    // STRICT VALIDATION: Cannot exceed available stock
    if (newQuantity > maxAvailableQty && maxAvailableQty !== 999) {
        alert(`الكمية المتوفرة في المخزون هي ${maxAvailableQty} {{ $product->unit_type ?? "وحدة" }} فقط. لا يمكنك طلب كمية أكبر.`);
        return;
    }
    
    qtyInput.value = newQuantity;
    currentQuantity = newQuantity;
    
    console.log('Quantity changed to:', newQuantity);
    updateTotalPrice();
}

// ✅ تحديث السعر الإجمالي تلقائياً
function updateTotalPrice() {
    const qtyInput = document.getElementById('quantity');
    const totalPriceSpan = document.getElementById('totalPrice');
    const cartButtonPrice = document.getElementById('cartButtonPrice');
    
    // Check if elements exist
    if (!qtyInput) {
        console.error('Quantity input not found');
        return;
    }
    if (!totalPriceSpan) {
        console.error('Total price span not found');
        return;
    }
    if (!cartButtonPrice) {
        console.error('Cart button price span not found');
    }
    
    let quantity = parseInt(qtyInput.value) || 1;
    
    // Validate quantity
    const minQty = parseInt(qtyInput.min) || 1;
    const maxQty = parseInt(qtyInput.max) || 999;
    
    if (quantity < minQty) {
        quantity = minQty;
        qtyInput.value = minQty;
    } else if (quantity > maxQty) {
        quantity = maxQty;
        qtyInput.value = maxQty;
    }
    
    currentQuantity = quantity;
    
    // Calculate total price
    const totalPrice = productPrice * quantity;
    const formattedPrice = totalPrice.toFixed(2);
    
    console.log('=== Price Update ===');
    console.log('Product Price:', productPrice);
    console.log('Quantity:', quantity);
    console.log('Total Price:', totalPrice);
    console.log('Formatted Price:', formattedPrice);
    
    // Update price displays
    totalPriceSpan.textContent = formattedPrice + ' جنيه';
    if (cartButtonPrice) {
        cartButtonPrice.textContent = '(' + formattedPrice + ' جنيه)';
    }
    
    // Add visual feedback
    animatePriceUpdate();
}

// ✅ تأثير حركي عند تحديث السعر
function animatePriceUpdate() {
    const totalPriceElement = document.getElementById('totalPrice');
    const cartButtonPrice = document.getElementById('cartButtonPrice');
    
    if (totalPriceElement) {
        totalPriceElement.style.transition = 'color 0.3s ease';
        totalPriceElement.style.color = '#16a34a';
        setTimeout(() => {
            totalPriceElement.style.color = '';
        }, 300);
    }
    
    if (cartButtonPrice) {
        cartButtonPrice.style.transition = 'color 0.3s ease';
        cartButtonPrice.style.color = '#16a34a';
        setTimeout(() => {
            cartButtonPrice.style.color = '';
        }, 300);
    }
}

// ✅ Enhanced keyboard input validation
document.addEventListener('DOMContentLoaded', function() {
    const qtyInput = document.getElementById('quantity');
    if (qtyInput) {
        // Prevent invalid key input - only allow numbers and control keys
        qtyInput.addEventListener('keypress', function(e) {
            const char = String.fromCharCode(e.which);
            
            // Allow numbers, backspace, delete, tab, escape, enter
            if (!/[0-9]/.test(char) && 
                e.which !== 8 &&  // backspace
                e.which !== 46 && // delete
                e.which !== 9 &&  // tab
                e.which !== 27 && // escape
                e.which !== 13) { // enter
                e.preventDefault();
            }
        });
        
        // Real-time validation during typing - allow manual input within range
        qtyInput.addEventListener('input', function(e) {
            const value = e.target.value;
            const minOrderQty = parseInt(e.target.min) || 1;
            const maxAvailableQty = parseInt(e.target.max) || 999;
            
            // Allow empty input during typing
            if (value === '') return;
            
            const quantity = parseInt(value);
            
            // Only correct if completely invalid (NaN or negative)
            // Allow values within range to remain as typed by customer
            if (isNaN(quantity) || quantity < 0) {
                setTimeout(() => {
                    e.target.value = minOrderQty;
                    updateTotalPrice();
                }, 50);
            } else if (quantity > maxAvailableQty && maxAvailableQty !== 999) {
                // Only correct if it exceeds maximum stock
                setTimeout(() => {
                    e.target.value = maxAvailableQty;
                    updateTotalPrice();
                }, 50);
            } else {
                // Valid range - update price without changing customer's input
                updateTotalPrice();
            }
        });
        
        // Final validation on blur/enter - only correct if out of range
        qtyInput.addEventListener('change', function(e) {
            const quantity = parseInt(e.target.value) || 1;
            const minOrderQty = parseInt(e.target.min) || 1;
            const maxAvailableQty = parseInt(e.target.max) || 999;
            
            // Only correct if outside valid range
            if (quantity < minOrderQty) {
                e.target.value = minOrderQty;
            } else if (quantity > maxAvailableQty && maxAvailableQty !== 999) {
                e.target.value = maxAvailableQty;
            }
            // If within range, keep customer's choice
            
            updateTotalPrice();
        });
        
        // Prevent paste of invalid values
        qtyInput.addEventListener('paste', function(e) {
            e.preventDefault();
            return false;
        });
    }
});
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing price calculation');
    console.log('Product Price variable:', productPrice);
    console.log('Current Quantity variable:', currentQuantity);
    
    // Initialize the total price
    setTimeout(() => {
        updateTotalPrice();
        console.log('Price calculation initialized');
    }, 100);
});

// ✅ Also initialize when JavaScript loads (for cases where DOMContentLoaded already fired)
if (document.readyState === 'complete' || document.readyState === 'interactive') {
    setTimeout(() => {
        updateTotalPrice();
        console.log('Price calculation initialized (ready state)');
    }, 200);
}

// ✅ Fallback initialization
window.addEventListener('load', function() {
    setTimeout(() => {
        updateTotalPrice();
        console.log('Price calculation initialized (window load)');
    }, 300);
});

// ✅ إضافة للسلة
function addToCart(productId) {
    const qtyInput = document.getElementById('quantity');
    const quantity = parseInt(qtyInput.value) || 1;
    const totalPrice = productPrice * quantity;
    const unitType = '{{ $product->unit_type ?? "وحدة" }}';
    
    // Get the actual limits from the input field
    const minOrderQty = parseInt(qtyInput.min) || 1;
    const maxAvailableQty = parseInt(qtyInput.max) || 999;
    
    // STRICT VALIDATION: Cannot order less than minimum order quantity
    if (quantity < minOrderQty) {
        alert(`الحد الأدنى للطلب هو ${minOrderQty} ${unitType}. لا يمكنك طلب كمية أقل من هذا الحد.`);
        return;
    }
    
    // STRICT VALIDATION: Cannot order more than available stock
    if (quantity > maxAvailableQty && maxAvailableQty !== 999) {
        alert(`الكمية المطلوبة (${quantity} ${unitType}) تتجاوز الكمية المتوفرة في المخزون (${maxAvailableQty} ${unitType}). يرجى اختيار كمية أقل.`);
        return;
    }
    
    // Show loading
    const btn = event.target.closest('button');
    const originalText = btn ? btn.innerHTML : '';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
    }
    
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    fetch('{{ route("frontend.cart.add") }}', {
        method: 'POST',
        headers: {
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'تم بنجاح!',
                    text: `تم إضافة ${quantity} ${unitType} من "${$product->name}" للسلة`,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                alert(`✅ تم إضافة ${quantity} ${unitType} من "${$product->name}" للسلة بنجاح!`);
            }
            updateCartCounter(data.count);
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ!',
                    text: data.message
                });
            } else {
                alert(data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء إضافة المنتج للسلة');
    })
    .finally(() => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
}

function updateCartCounter(count) {
    const cartBadge = document.querySelector('.cart-count');
    if (cartBadge) {
        cartBadge.textContent = count;
        if (count > 0) {
            cartBadge.classList.remove('bg-secondary');
            cartBadge.classList.add('bg-danger');
        } else {
            cartBadge.classList.remove('bg-danger');
            cartBadge.classList.add('bg-secondary');
        }
    }
}


// ✅ إخطار عند التوفر
function notifyWhenAvailable(productId) {
    const email = prompt('📧 أدخل بريدك الإلكتروني لتلقي الإخطار:');
    if (email && email.includes('@')) {
        alert('✅ تم تسجيل بريدك الإلكتروني! سنخطرك عند توفر الدواء.');
        // هنا هتعمل الـ AJAX request لتسجيل الإشعار
    } else {
        alert('❌ يرجى إدخال بريد إلكتروني صحيح');
    }
}

// ✅ تهيئة الصفحة
document.addEventListener('DOMContentLoaded', function() {
    updateTotalPrice();
    
    // تأثيرات للأزرار
    const buttons = document.querySelectorAll('.btn-add-to-cart, .btn-wishlist, .btn-notify');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // التمرير التلقائي للعناصر المهمة
    if (!{{ $product->is_available ? 'true' : 'false' }}) {
        const availabilitySection = document.querySelector('.product-availability');
        if (availabilitySection) {
            availabilitySection.scrollIntoView({ behavior: 'smooth' });
        }
    }
});
</script>
@endpush
