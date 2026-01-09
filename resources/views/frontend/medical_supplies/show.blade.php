@extends('frontend.layouts.app')

@section('title', $product->name . ' - المستلزمات الطبية - توريد ميد')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/product-details.css') }}">
<style>
/* ✅ تحسينات CSS للمستلزمات */
.product-price-box .price-main {
    font-size: 1.8rem;
    font-weight: bold;
    color: #16a34a;
    margin-bottom: 8px;
}

.unit-info {
    background: #f0f9ff;
    border: 1px solid #0ea5e9;
    border-radius: 6px;
    padding: 8px 12px;
    margin-top: 8px;
    font-size: 0.9rem;
}

.product-availability.available {
    background: #dcfce7;
    border: 1px solid #22c55e;
    color: #166534;
}

.product-availability.unavailable {
    background: #fef2f2;
    border: 1px solid #ef4444;
    color: #991b1b;
}

.box-info {
    background: #f8fafc;
    border-left: 4px solid #3b82f6;
    padding: 12px;
    border-radius: 4px;
    margin: 15px 0;
}

.no-reviews, .reviews-section {
    display: none !important; /* إخفاء التقييمات كاملة */
}

.product-extra-info {
    display: flex;
    gap: 15px;
    margin-top: 20px;
    flex-wrap: wrap;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 0.9rem;
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
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<section class="breadcrumb-section">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('frontend.home') }}">الرئيسية</a>
            <span>/</span>
            <a href="{{ route('frontend.medical-supplies.index') }}">المستلزمات الطبية</a>
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
                    <i class="fas fa-briefcase-medical fa-5x text-muted"></i>
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
                
                <div class="product-meta">
                    @if($product->supplier)
                    <div class="product-supplier">
                        <i class="fas fa-building"></i>
                        <a href="{{ route('frontend.companies.show', $product->supplier_id) }}">
                            {{ $product->supplier->name }}
                        </a>
                    </div>
                    @endif
                </div>

                <!-- ✅ السعر مع نوع الباكيدج -->
                <div class="product-price-box">
                    <div class="price-main">
                        {{ number_format($product->final_price, 2) }} جنيه
                    </div>
                    
                    <!-- ✅ معلومات الوحدة/الباكيدج -->
                    <div class="unit-info">
                        <i class="fas fa-box"></i>
                        <strong>السعر {{ $product->units_per_package > 1 ? 'للكرتونة' : "لل{$product->unit_type}" }}</strong>
                        @if($product->units_per_package)
                        <span class="ms-2">• يحتوي على {{ $product->units_per_package }} {{ $product->units_per_package > 1 ? 'وحدة' : 'وحدات' }}</span>
                        @endif
                        @if($product->min_order_quantity > 1)
                        <span class="ms-2">• الحد الأدنى: {{ $product->min_order_quantity }} {{ $product->unit_type }}</span>
                        @endif
                    </div>
                </div>

                <div class="product-company mt-2">
                    <span class="label"><i class="fas fa-industry"></i> الشركة المصنعة:</span>
                    <strong>{{ $product->company_name ?? 'غير محدد' }}</strong>
                </div>

                @if($product->category)
                <div class="product-category mt-2">
                    <span class="label"><i class="fas fa-tag"></i> التصنيف:</span>
                    <a href="{{ route('frontend.medical-supplies.index', ['category' => $product->category->id]) }}">
                        {{ $product->category->name }}
                    </a>
                </div>
                @endif

                <!-- ✅ معلومات الكرتونة/الباكيدج -->
                @if($product->box_info)
                <div class="box-info mt-3">
                    <strong><i class="fas fa-info-circle me-2"></i>تفاصيل التعبئة:</strong>
                    <p class="mb-0">{{ $product->box_info }}</p>
                </div>
                @endif

                <!-- ✅ التوفر المُحسّن -->
                <div class="product-availability {{ $product->is_available ? 'available' : 'unavailable' }} mt-3">
                    <span class="icon">{{ $product->is_available ? '✓' : '✗' }}</span>
                    @if($product->is_available)
                        @if($product->available_quantity != 'متوفر للطلب' && is_numeric($product->available_quantity) && $product->available_quantity > 0)
                            <strong>متوفر في المخزون</strong>
                            <span class="stock-count">({{ $product->available_quantity }} {{ $product->unit_type }})</span>
                        @else
                            <strong>متوفر للطلب</strong>
                            @if($product->min_order_quantity > 1)
                            <span class="stock-count">(حد أدنى: {{ $product->min_order_quantity }} {{ $product->unit_type }})</span>
                            @endif
                        @endif
                    @else
                        <strong>غير متوفر حالياً</strong>
                        <span class="stock-count">(يرجى التواصل مع المورد)</span>
                    @endif
                </div>

                <!-- وصف مختصر -->
                @if($product->short_description)
                <div class="product-short-desc mt-4">
                    <h4><i class="fas fa-info-circle"></i> نظرة عامة</h4>
                    <p>{{ $product->short_description }}</p>
                </div>
                @endif

                <!-- ✅ إضافة للسلة (مُحسّنة) -->
                @if($product->is_available && $product->price > 0)
                <div class="product-actions mt-4">
                    <div class="quantity-selector mb-3">
                        <label><i class="fas fa-sort-numeric-up"></i> الكمية:</label>
                        <div class="quantity-controls d-flex align-items-center">
                            <button class="qty-btn minus" type="button" onclick="changeQuantity(-1)">−</button>
                            <input type="number" 
                                   id="quantity" 
                                   class="qty-input" 
                                   value="{{ $product->min_order_quantity ?? 1 }}" 
                                   min="{{ $product->min_order_quantity ?? 1 }}"
                                   max="{{ is_numeric($product->available_quantity) ? $product->available_quantity : 999 }}"
                                   oninput="updateTotalPrice()">
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
                        <button class="btn-add-to-cart btn-primary btn-lg" onclick="addToCart({{ $product->id }})">
                            <i class="fas fa-shopping-cart me-2"></i> 
                            أضف إلى السلة 
                            <span class="price-small" id="cartButtonPrice">({{ number_format($product->final_price * ($product->min_order_quantity ?? 1), 2) }} جنيه)</span>
                        </button>
                    </div>
                </div>
                @else
                <div class="out-of-stock mt-4 p-3 bg-light rounded">
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
                    
                    <button class="btn-notify btn-outline-primary mt-2" onclick="notifyWhenAvailable({{ $product->id }})">
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
                <div class="product-extra-info">
                    <div class="info-item">
                        <span class="icon">🚚</span>
                        <span>توصيل سريع خلال 2-3 أيام</span>
                    </div>
                    <div class="info-item">
                        <span class="icon">↩️</span>
                        <span>إرجاع خلال 7 أيام</span>
                    </div>
                    <div class="info-item">
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
            <div class="description-content">
                {!! $product->full_description !!}
            </div>
        </div>
        @endif

        <!-- المواصفات الأساسية -->
        @if($product->dosage_form || $product->production_date || $product->package_type)
        <div class="product-specifications mt-5">
            <h3><i class="fas fa-list-alt me-2"></i>المواصفات</h3>
            <div class="row">
                @if($product->dosage_form)
                <div class="col-md-6 mb-2">
                    <strong>نوع المنتج:</strong> {{ $product->dosage_form }}
                </div>
                @endif
                @if($product->production_date)
                <div class="col-md-6 mb-2">
                    <strong>تاريخ الإنتاج:</strong> {{ $product->production_date->format('Y-m-d') }}
                </div>
                @endif
                @if($product->package_type)
                <div class="col-md-6 mb-2">
                    <strong>نوع التعبئة:</strong> {{ $product->package_type }}
                </div>
                @endif
                @if($product->units_per_package)
                <div class="col-md-6 mb-2">
                    <strong>عدد الوحدات:</strong> {{ $product->units_per_package }}
                </div>
                @endif
            </div>
        </div>
        @endif
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
    const maxStock = parseInt(qtyInput.max) || 999;
    const minQty = parseInt(qtyInput.min) || 1;
    
    currentQuantity = parseInt(qtyInput.value) + change;
    currentQuantity = Math.max(minQty, Math.min(currentQuantity, maxStock));
    
    qtyInput.value = currentQuantity;
    updateTotalPrice();
}

// ✅ تحديث السعر الإجمالي تلقائياً
function updateTotalPrice() {
    const qtyInput = document.getElementById('quantity');
    const totalPriceSpan = document.getElementById('totalPrice');
    const cartButtonPrice = document.getElementById('cartButtonPrice');
    
    // Debug: Check if elements exist
    if (!qtyInput) {
        console.error('Quantity input not found');
        return;
    }
    if (!totalPriceSpan) {
        console.error('Total price span not found');
        return;
    }
    
    const quantity = parseInt(qtyInput.value) || 1;
    
    // Debug: Log values
    console.log('Product Price:', productPrice);
    console.log('Quantity:', quantity);
    
    // Validate quantity
    const minQty = parseInt(qtyInput.min) || 1;
    const maxQty = parseInt(qtyInput.max) || 999;
    
    if (quantity < minQty) {
        qtyInput.value = minQty;
        currentQuantity = minQty;
    } else if (quantity > maxQty) {
        qtyInput.value = maxQty;
        currentQuantity = maxQty;
    } else {
        currentQuantity = quantity;
    }
    
    // Calculate total price
    const totalPrice = productPrice * currentQuantity;
    const formattedPrice = new Intl.NumberFormat('ar-EG', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(totalPrice);
    
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

// ✅ Initialize price calculation on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing price calculation');
    console.log('Product Price variable:', productPrice);
    
    // Initialize the total price
    updateTotalPrice();
});

// ✅ Also initialize when JavaScript loads (for cases where DOMContentLoaded already fired)
if (document.readyState === 'complete' || document.readyState === 'interactive') {
    setTimeout(updateTotalPrice, 100);
}

// ✅ إضافة للسلة
function addToCart(productId) {
    const qtyInput = document.getElementById('quantity');
    const quantity = parseInt(qtyInput.value) || 1;
    const totalPrice = productPrice * quantity;
    const unitType = '{{ $product->unit_type ?? "قطعة" }}';
    
    // Get the actual limits from the input field
    const minOrderQty = parseInt(qtyInput.min) || 1;
    const maxAvailableQty = parseInt(qtyInput.max) || 999;
    
    // STRICT VALIDATION: Cannot order less than minimum order quantity
    if (quantity < minOrderQty) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه',
                text: `الحد الأدنى للطلب هو ${minOrderQty} ${unitType}. لا يمكنك طلب كمية أقل من هذا الحد.`,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            alert(`الحد الأدنى للطلب هو ${minOrderQty} ${unitType}. لا يمكنك طلب كمية أقل.`);
        }
        return;
    }
    
    // STRICT VALIDATION: Cannot order more than available stock
    if (quantity > maxAvailableQty && maxAvailableQty !== 999) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'كمية غير متوفرة',
                text: `الكمية المطلوبة (${quantity} ${unitType}) تتجاوز الكمية المتوفرة في المخزون (${maxAvailableQty} ${unitType}). يرجى اختيار كمية أقل.`
            });
        } else {
            alert(`الكمية المطلوبة (${quantity} ${unitType}) تتجاوز الكمية المتوفرة في المخزون (${maxAvailableQty} ${unitType}).`);
        }
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
        alert('✅ تم تسجيل بريدك الإلكتروني! سنخطرك عند توفر المستلزم.');
        // هنا هتعمل الـ AJAX request لتسجيل الإشعار
    } else {
        alert('❌ يرجى إدخال بريد إلكتروني صحيح');
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
