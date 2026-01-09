@extends('frontend.layouts.app')

@section('title', $product->name . ' - تفاصيل المنتج - توريد ميد')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/product-details.css') }}">
<style>
/* ✅ صور مضبوطة بدون مط */
.product-gallery .main-image {
    width: 100%;
    height: 400px;
    background: #fff;
    border: 1px solid #f1f5f9;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin-bottom: 15px;
}

.product-gallery .main-image img {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
    object-fit: contain;
}

.thumbnail-images {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 5px;
}

.thumbnail-img {
    width: 80px;
    height: 80px;
    object-fit: contain;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.thumbnail-img:hover { border-color: #3b82f6; }

/* ✅ تحسينات الأسعار */
.medicine-price-breakdown {
    background: #f8fafc;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    margin-bottom: 15px;
}

.price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    font-size: 1rem;
}

.price-row.original { color: #64748b; text-decoration: line-through; }
.price-row.discount { color: #ef4444; font-weight: 600; }
.price-row.final {
    color: #16a34a;
    font-weight: 700;
    font-size: 1.4rem;
    border-top: 1px solid #e2e8f0;
    padding-top: 8px;
    margin-top: 8px;
}

/* ✅ سلكتور النوع */
.order-type-selector {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.type-option {
    flex: 1;
    padding: 12px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #fff;
}

.type-option.active {
    border-color: #3b82f6;
    background: #eff6ff;
    color: #1d4ed8;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.1);
}

.type-option small { display: block; font-size: 0.85rem; color: #64748b; margin-top: 4px; }

/* القوائم */
.active-ingredients-list { list-style: none; padding: 0; margin: 10px 0; }
.active-ingredients-list li { position: relative; padding-right: 15px; margin-bottom: 4px; color: #475569; }
.active-ingredients-list li::before { content: "•"; color: #3b82f6; position: absolute; right: 0; }

/* أزرار التحكم */
.quantity-controls { display: flex; align-items: center; gap: 8px; margin-bottom: 15px; }
.qty-btn { width: 40px; height: 40px; border: 1px solid #ddd; background: white; border-radius: 4px; cursor: pointer; font-size: 1.2rem; }
.qty-input { width: 70px; height: 40px; border: 1px solid #ddd; border-radius: 4px; text-align: center; font-size: 1.1rem; font-weight: bold; }
.btn-add-to-cart { background: #16a34a; color: white; border: none; padding: 12px; border-radius: 6px; font-size: 1rem; cursor: pointer; width: 100%; transition: 0.3s; }
.btn-add-to-cart:hover { background: #15803d; }
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<section class="breadcrumb-section">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('frontend.home') }}">الرئيسية</a>
            <span>/</span>
            <a href="{{ route('frontend.products.index') }}">المنتجات</a>
            <span>/</span>
            <span>{{ $product->name }}</span>
        </div>
    </div>
</section>

<!-- Product Details -->
<section class="product-details-section">
    <div class="container">
        <div class="product-main row">
            
            <!-- الصور -->
            <div class="product-gallery col-lg-5">
                {{-- ✅ استخدام الدالة الصحيحة للصور --}}
                @php $mainImage = $product->getImageUrl(1); @endphp
                
                <div class="main-image">
                    @if($product->image_1)
                        <img id="mainImage" src="{{ $mainImage }}" alt="{{ $product->name }}">
                    @else
                        <i class="fas fa-box-open fa-5x text-muted"></i>
                    @endif
                    
                    @if($product->featured)
                        <span class="badge-featured" style="position:absolute; top:10px; right:10px; background:#f59e0b; color:white; padding:4px 8px; border-radius:4px;">مميز</span>
                    @endif
                </div>

                @if($product->image_2 || $product->image_3 || $product->image_4)
                <div class="thumbnail-images">
                    @foreach(range(2, 4) as $i)
                        @if($product->{'image_'.$i})
                        <img src="{{ $product->getImageUrl($i) }}" class="thumbnail-img" onclick="changeMainImage('{{ $product->getImageUrl($i) }}')">
                        @endif
                    @endforeach
                </div>
                @endif
            </div>

            <!-- تفاصيل المنتج -->
            <div class="product-info-main col-lg-7">
                <h1 class="product-title">{{ $product->name }}</h1>
                
                <!-- منطق عرض المادة الفعالة -->
                @php
                    $ingredients = [];
                    if ($product->active_ingredient) {
                        $raw = $product->active_ingredient;
                        if (is_string($raw)) {
                            // تقسيم النص بناءً على الفاصلة أو الفاصلة العربية أو سطر جديد
                            $ingredients = preg_split('/[,\n،]+/', $raw);
                            $ingredients = array_map('trim', $ingredients);
                            $ingredients = array_filter($ingredients); // إزالة العناصر الفارغة
                        } elseif (is_array($raw)) {
                            $ingredients = $raw;
                        }
                    }
                @endphp

                @if(!empty($ingredients))
                <div class="active-ingredients-section mt-2">
                    <strong>المادة الفعالة:</strong>
                    <ul class="active-ingredients-list">
                        @foreach($ingredients as $ingredient)
                            <li>{{ $ingredient }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <div class="product-meta border-bottom pb-2 mb-3 mt-3">
                    @if($product->supplier)
                    <div class="product-supplier mb-2">
                        <i class="fas fa-building text-primary"></i>
                        <span class="text-muted ms-1">المورد:</span>
                        <a href="{{ route('frontend.companies.show', $product->supplier->id) }}" class="fw-bold">{{ $product->supplier->name }}</a>
                    </div>
                    @endif
                    <div class="product-company">
                        <i class="fas fa-industry text-secondary"></i>
                        <span class="text-muted ms-1">الشركة المصنعة:</span>
                        <strong>{{ $product->company_name ?? 'غير محدد' }}</strong>
                    </div>
                </div>

                <!-- ✅ تم توحيد السلكتور: يظهر للكل الآن -->
                <div class="order-type-selector">
                    <div class="type-option active" id="unitOption" onclick="setOrderType('unit')">
                        <i class="fas fa-pills mb-1 text-muted"></i><br>
                        <strong>قطعة ({{ $product->unit_type ?? 'قطعة' }})</strong>
                        <small>الحد الأدنى: {{ $product->min_order_quantity ?? 1 }}</small>
                    </div>
                    
                    @if($product->units_per_package > 1)
                    <div class="type-option" id="packageOption" onclick="setOrderType('package')">
                        <i class="fas fa-box mb-1 text-muted"></i><br>
                        <strong>باكيدج ({{ $product->package_type ?? 'علبة' }})</strong>
                        <small>يحتوي على {{ $product->units_per_package }} {{ $product->unit_type ?? 'قطعة' }}</small>
                    </div>
                    @endif
                </div>

                <!-- ✅ تم توحيد عرض السعر -->
                <div class="medicine-price-breakdown">
                    <div class="price-row original">
                        <span>سعر الجمهور:</span>
                        <!-- هنا بنعرض السعر قبل الخصم -->
                        <span id="displayOriginalPrice">{{ number_format($product->price, 2) }} ج.م</span>
                    </div>
                    
                    <!-- الخصم يظهر فقط لو موجود -->
                    @if($product->discount > 0)
                    <div class="price-row discount">
                        <span>خصم:</span>
                        <span>{{ $product->discount }}%</span>
                    </div>
                    @endif
                    
                    <div class="price-row final">
                        <span>السعر النهائي:</span>
                        <span id="displayFinalPrice">{{ number_format($product->final_price, 2) }} ج.م</span>
                    </div>
                </div>

                <!-- ✅ صندوق الشراء -->
                <div class="purchase-section bg-white p-3 rounded shadow-sm border">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="fw-bold">الكمية المطلوبة:</label>
                        <span class="badge bg-info text-dark" id="currentUnitDisplay">{{ $product->unit_type ?? 'قطعة' }}</span>
                    </div>

                    <div class="quantity-controls">
                        <button class="qty-btn minus" type="button" onclick="changeQuantity(-1)">−</button>
                        <input type="number" 
                               id="quantity" 
                               class="qty-input" 
                               value="{{ $product->min_order_quantity ?? 1 }}" 
                               min="1" 
                               onchange="updateTotalPrice()">
                        <button class="qty-btn plus" type="button" onclick="changeQuantity(1)">+</button>
                    </div>
                    
                    <div class="total-price-display alert alert-success d-flex justify-content-between align-items-center py-2 mb-3">
                        <strong>الإجمالي التقريبي:</strong>
                        <span id="totalPrice" class="fw-bold fs-5">
                            {{ number_format($product->final_price * ($product->min_order_quantity ?? 1), 2) }} ج.م
                        </span>
                    </div>

                    <div class="action-buttons">
                        <button class="btn-add-to-cart" onclick="addToCart({{ $product->id }})">
                            <i class="fas fa-cart-plus me-2"></i> إضافة للسلة
                        </button>
                    </div>
                </div>
                
                <div class="product-extra-info mt-4 d-flex gap-3 flex-wrap">
                    <div class="info-item bg-light p-2 rounded small"><i class="fas fa-truck text-primary me-1"></i> توصيل سريع</div>
                    <div class="info-item bg-light p-2 rounded small"><i class="fas fa-undo text-primary me-1"></i> إرجاع خلال 14 يوم</div>
                </div>
            </div>
        </div>

        <!-- التبويبات -->
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#desc">تفاصيل المنتج</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#specs">المواصفات</button></li>
                </ul>
                <div class="tab-content p-4 border border-top-0 bg-white">
                    <div class="tab-pane fade show active" id="desc">
                        {!! $product->full_description ?? '<p class="text-muted">لا يوجد وصف.</p>' !!}
                    </div>
                    <div class="tab-pane fade" id="specs">
                        <div class="row">
                            @if($product->dosage_form)
                            <div class="col-md-6 mb-2"><strong>الشكل الدوائي:</strong> {{ $product->dosage_form }}</div>
                            @endif
                            
                            @if(!empty($ingredients))
                            <div class="col-md-6 mb-2"><strong>المادة الفعالة:</strong> {{ implode(', ', $ingredients) }}</div>
                            @endif
                            
                            @if($product->package_type)
                            <div class="col-md-6 mb-2"><strong>نوع التعبئة:</strong> {{ $product->package_type }}</div>
                            @endif
                            @if($product->units_per_package)
                            <div class="col-md-6 mb-2"><strong>محتوى العبوة:</strong> {{ $product->units_per_package }} {{ $product->unit_type ?? 'قطعة' }}</div>
                            @endif
                            @if($product->production_date)
                            <div class="col-md-6 mb-2"><strong>تاريخ الإنتاج:</strong> {{ $product->production_date->format('Y-m-d') }}</div>
                            @endif
                            @if($product->expiry_date)
                            <div class="col-md-6 mb-2"><strong>تاريخ الانتهاء:</strong> {{ $product->expiry_date->format('Y-m-d') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
// تهيئة البيانات للتعامل مع أي نوع منتج
const productData = {
    id: {{ $product->id }},
    unitPrice: {{ $product->final_price ?? 0 }}, // السعر النهائي للقطعة
    packagePrice: {{ ($product->final_price ?? 0) * ($product->units_per_package ?? 1) }}, // سعر الباكيدج
    unitsPerPackage: {{ $product->units_per_package ?? 1 }},
    unitType: '{{ $product->unit_type ?? "قطعة" }}',
    packageType: '{{ $product->package_type ?? "علبة" }}',
    minOrderUnit: {{ $product->min_order_quantity ?? 1 }},
    minOrderPackage: {{ $product->min_order_package ?? 1 }}
};

let currentOrderType = 'unit'; 
let currentPrice = productData.unitPrice;
let currentMinOrder = productData.minOrderUnit;

function setOrderType(type) {
    currentOrderType = type;
    
    // تحديث الشكل
    document.querySelectorAll('.type-option').forEach(el => el.classList.remove('active'));
    document.getElementById(type + 'Option').classList.add('active');
    
    if (type === 'package') {
        currentPrice = productData.packagePrice;
        currentMinOrder = productData.minOrderPackage;
        document.getElementById('currentUnitDisplay').textContent = productData.packageType;
        
        // تحديث عرض الأسعار للباكيدج
        document.getElementById('displayOriginalPrice').textContent = ({{ $product->price ?? 0 }} * productData.unitsPerPackage).toFixed(2) + ' ج.م';
        document.getElementById('displayFinalPrice').textContent = currentPrice.toFixed(2) + ' ج.م';
    } else {
        currentPrice = productData.unitPrice;
        currentMinOrder = productData.minOrderUnit;
        document.getElementById('currentUnitDisplay').textContent = productData.unitType;
        
        // تحديث عرض الأسعار للقطعة
        document.getElementById('displayOriginalPrice').textContent = {{ $product->price ?? 0 }}.toFixed(2) + ' ج.م';
        document.getElementById('displayFinalPrice').textContent = currentPrice.toFixed(2) + ' ج.م';
    }
    
    // ضبط الكمية لو كانت أقل من الحد الأدنى الجديد
    const qtyInput = document.getElementById('quantity');
    if (parseInt(qtyInput.value) < currentMinOrder) {
        qtyInput.value = currentMinOrder;
    }
    updateTotalPrice();
}

function changeQuantity(change) {
    const qtyInput = document.getElementById('quantity');
    let newVal = parseInt(qtyInput.value) + change;
    
    if (newVal < currentMinOrder) newVal = currentMinOrder;
    
    qtyInput.value = newVal;
    updateTotalPrice();
}

function updateTotalPrice() {
    const qtyInput = document.getElementById('quantity');
    let quantity = parseInt(qtyInput.value) || 1;
    const total = currentPrice * quantity;
    document.getElementById('totalPrice').textContent = total.toFixed(2) + ' ج.م';
}

function changeMainImage(src) {
    document.getElementById('mainImage').src = src;
}

function addToCart(productId) {
    const qtyInput = document.getElementById('quantity');
    let quantity = parseInt(qtyInput.value) || 1;
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    
    // حساب الكمية الفعلية (لو باكيدج نضرب في عدد الوحدات)
    let finalQuantity = quantity;
    if (currentOrderType === 'package') {
        finalQuantity = quantity * productData.unitsPerPackage;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';

    fetch('{{ route("frontend.cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: finalQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success' || data.success) {
            const cartCounter = document.querySelector('.cart-count');
            if (cartCounter) cartCounter.textContent = data.cart_count || data.count;
            alert(`✅ تم إضافة ${quantity} ${currentOrderType === 'package' ? productData.packageType : productData.unitType} للسلة!`);
        } else {
            alert('⚠️ ' + (data.message || 'لم يتم الإضافة'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ خطأ في الاتصال');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    updateTotalPrice();
});
</script>
@endpush
