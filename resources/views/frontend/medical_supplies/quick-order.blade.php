@extends('frontend.layouts.app')

@section('title', 'قائمة الطلب السريع للمستلزمات الطبية - توريد ميد')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <h1 class="h2 fw-bold text-primary mb-3">قائمة الطلب السريع للمستلزمات الطبية</h1>
        <div class="d-inline-block bg-white shadow-sm rounded-pill px-4 py-2 border border-danger">
            <p class="text-danger fw-bold fs-5 mb-0 d-flex align-items-center justify-content-center gap-2">
                <i class="fas fa-info-circle"></i>
                <span>حدد الكميات المطلوبة من أصناف الطلب بحد أدنى 2000 جنيه</span>
            </p>
        </div>
    </div>

    <!-- Category Tabs -->
    <div class="mb-4">
        <div class="category-tabs d-flex flex-column flex-md-row justify-content-center gap-2 gap-md-3">
            <a href="{{ route('frontend.medicines.quick-order') }}" class="category-tab btn btn-lg btn-outline-primary bg-white text-dark shadow-sm d-flex align-items-center">
                <span class="fw-bold ms-2">الأدوية</span>
                <span class="fs-4">💊</span>
            </a>
            <a href="{{ route('frontend.medical-supplies.quick-order') }}" class="category-tab btn btn-lg btn-outline-primary bg-white text-dark shadow-sm d-flex align-items-center">
                <span class="fw-bold ms-2">المستلزمات الطبية</span>
                <span class="fs-4">🏥</span>
            </a>
            <a href="{{ route('frontend.cosmetics.quick-order') }}" class="category-tab btn btn-lg btn-outline-primary bg-white text-dark shadow-sm d-flex align-items-center">
                <span class="fw-bold ms-2">مستحضرات التجميل</span>
                <span class="fs-4">💄</span>
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-end" dir="rtl">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="text-center" width="50">#</th>
                            <th scope="col" width="35%" class="text-start">اسم الصنف</th>
                            <th scope="col" class="text-center" width="100">سعر الجملة</th>
                            <th scope="col" class="text-center" width="150">الكمية</th>
                            <th scope="col" class="text-center" width="120">الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                            @php
                                $isAvailable = $product->is_available;
                                $finalPrice = $product->final_price;
                            @endphp
                            <tr id="row-{{ $product->id }}">
                                <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex flex-column align-items-start">
                                        <h6 class="mb-2 fw-bold text-dark" style="font-size: 1.1rem;">{{ $product->name }}</h6>
                                        <div class="d-flex align-items-center">
                                            <div class="position-relative cursor-pointer zoom-trigger" 
                                                 onclick="openImageModal('{{ $product->getImageUrl() }}', '{{ $product->name }}')">
                                                <img src="{{ $product->getImageUrl() }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="rounded border bg-white p-1" 
                                                     style="width: 100px; height: 70px; object-fit: contain; cursor: zoom-in;">
                                            </div>
                                            </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold fs-6">{{ number_format($product->price, 2) }}</span>
                                </td>
                                <td class="text-center">
                                    @if($isAvailable)
                                        <input type="number" 
                                               class="form-control text-center quantity-input fw-bold" 
                                               data-id="{{ $product->id }}" 
                                               data-price="{{ $finalPrice }}"
                                               id="qty-{{ $product->id }}" 
                                               value="" 
                                               placeholder="0"
                                                min="0"
                                                data-min="{{ $product->min_order_quantity ?? 1 }}"
                                                @if($product->total_units > 0) max="{{ $product->total_units }}" @endif
                                               style="width: 80px; margin: 0 auto;"
                                               onfocus="this.placeholder = ''"
                                               onblur="this.placeholder = '0'"
                                               oninput="updateRowTotal(this)">
                                        <small class="text-muted d-block mt-1">المتاح: {{ $product->total_units }}</small>
                                    @else
                                        <span class="badge bg-secondary mb-1">غير متوفر</span>
                                        <small class="text-muted d-block">المتاح: {{ $product->total_units }}</small>
                                        <input type="hidden" class="quantity-input" value="0">
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-primary row-total" id="total-{{ $product->id }}">
                                        0.00
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-box-open fa-3x mb-3"></i>
                                    <p>لا توجد منتجات متاحة حالياً</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

<!-- Floating Bottom Bar -->
<div class="fixed-bottom bg-white border-top shadow-lg py-3">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center" id="total-container">
                <span class="fs-4 fw-bold me-2">الإجمالي الكلي:</span>
                <span class="fs-4 fw-bold" id="grand-total">0.00</span>
                <span class="fs-5 ms-1">ج.م</span>
            </div>
            
            <div class="d-flex gap-3">
                 <!-- Previous Button -->
                <a href="{{ route('frontend.medical-supplies.index') }}" class="btn btn-outline-primary btn-lg px-4 rounded-3 d-flex align-items-center justify-content-center bg-white text-primary border-primary hover-effect" style="width: 140px;">
                    <i class="fas fa-chevron-right me-2"></i> السابق
                </a>

                <!-- Next Button -->
                <button id="nextBtn" onclick="processNextStep()" class="btn btn-outline-primary btn-lg px-4 rounded-3 d-flex align-items-center justify-content-center bg-white text-primary border-primary hover-effect" style="width: 140px;">
                    التالي <i class="fas fa-chevron-left ms-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<style>
    /* Add padding to body to prevent bottom bar from covering content */
    body { padding-bottom: 80px; }
    
    .hover-effect {
        transition: all 0.3s ease;
    }
    .hover-effect:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .btn-outline-primary.hover-effect:hover {
        background-color: #0d6efd !important;
        color: white !important;
    }
    /* Disabled state styling */
    #nextBtn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background-color: #6c757d;
        border-color: #6c757d;
    }

    /* Category Tab Hover Effects */
    .category-tab {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-width: 2px;
    }
    .category-tab:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .category-tab.btn-outline-primary:hover {
        background-color: #0d6efd !important;
        color: white !important;
        border-color: #0d6efd !important;
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .category-tab {
            width: 100%;
            justify-content: center;
        }
        
        .table th, .table td {
            font-size: 0.8rem;
            padding: 0.5rem 0.2rem !important;
        }
        
        /* Ensure table header text wraps properly or stays readable */
        .table thead th {
             white-space: nowrap;
        }

        .quantity-input {
            width: 60px !important;
            padding: 2px !important;
        }

        .zoom-trigger img {
            width: 50px !important;
            height: 35px !important;
        }
        
        /* Compact rows */
        .table tbody tr td {
            vertical-align: middle;
        }
        
        /* Bottom bar responsiveness */
        .fixed-bottom {
            padding: 15px 0;
            padding-bottom: calc(15px + env(safe-area-inset-bottom));
            z-index: 1040;
        }
        
        #total-container {
            width: 100%;
            justify-content: center;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        
        .fixed-bottom .d-flex.gap-3 {
            width: 100%;
            gap: 10px !important;
        }
        
        .fixed-bottom .btn {
            flex: 1; /* Equal width buttons */
            font-size: 0.9rem;
            padding: 8px;
        }
        
        body { padding-bottom: 180px; } /* Increase bottom padding for taller footer */
    }
</style>

<!-- Image Modal -->
<div id="imageModal" class="modal-overlay" style="display: none;" onclick="closeImageModal()">
    <div class="modal-content-zoom" onclick="event.stopPropagation()">
        <span class="close-btn" onclick="closeImageModal()">&times;</span>
        <img id="modalImage" src="" alt="Full Image">
        <h4 id="modalTitle" class="text-center mt-3 text-white"></h4>
    </div>
</div>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .modal-overlay.show {
        opacity: 1;
    }
    .modal-content-zoom {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        animation: zoomIn 0.3s ease;
    }
    .modal-content-zoom img {
        max-width: 100%;
        max-height: 80vh;
        border-radius: 8px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.5);
        background: white;
    }
    .close-btn {
        position: absolute;
        top: -40px;
        right: 0;
        color: white;
        font-size: 35px;
        cursor: pointer;
        font-weight: bold;
    }
    @keyframes zoomIn {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>

@push('scripts')
<script>
    const isLoggedIn = @json(Auth::guard('customer')->check());
    const checkoutRoute = "{{ route('frontend.cart.checkout') }}";
    const loginRoute = "{{ route('frontend.customer.login') }}";

function openImageModal(src, title) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const titleEl = document.getElementById('modalTitle');
    
    modalImg.src = src;
    titleEl.textContent = title;
    
    modal.style.display = 'flex';
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.remove('show');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
        closeImageModal();
    }
});

function updateRowTotal(input) {
    const qty = parseFloat(input.value) || 0;
    const maxAttr = input.getAttribute('max');
    const max = maxAttr ? parseFloat(maxAttr) : Infinity;
    const min = parseFloat(input.dataset.min) || 1;
    
    // Enforce max stock only if it exists and is positive
    if (maxAttr && qty > max) {
        alert('عفواً، الكمية المطلوبة غير متوفرة. المتاح: ' + max);
        input.value = max;
        updateRowTotal(input); // Recalculate with max
        return;
    }
    
    // Save to localStorage
    const productId = input.dataset.id;
    if(qty > 0) {
        localStorage.setItem('quick_order_qty_' + productId, qty);
    } else {
        localStorage.removeItem('quick_order_qty_' + productId);
    }
    
    const price = parseFloat(input.dataset.price);
    const totalElement = document.getElementById('total-' + input.dataset.id);
    const total = qty * price;
    
    totalElement.dataset.rawTotal = total; // Store raw value for grand total calc
    totalElement.textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
    updateGrandTotal();
}

function restoreQuantities() {
    const inputs = document.querySelectorAll('.quantity-input');
    inputs.forEach(input => {
        const productId = input.dataset.id;
        const savedQty = localStorage.getItem('quick_order_qty_' + productId);
        
        // 1. Restore from storage (useful for page refresh)
        if (savedQty && input.type !== 'hidden') {
            input.value = savedQty;
        }
        
        // 2. Calculate row total based on ACTUAL input value (useful for back button)
        const qty = parseFloat(input.value) || 0;
        const price = parseFloat(input.dataset.price);
        const totalElement = document.getElementById('total-' + productId);
        
        if (totalElement) {
            const total = qty * price;
            totalElement.dataset.rawTotal = total;
            totalElement.textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
    });
    updateGrandTotal();
}

document.addEventListener('DOMContentLoaded', restoreQuantities);

// Fix for Back button (resets button state and restores totals)
window.addEventListener('pageshow', function(event) {
    restoreQuantities(); // Recalculate everything on page show
    const btn = document.getElementById('nextBtn');
    if (btn) {
        btn.innerHTML = 'التالي <i class="fas fa-chevron-left ms-2"></i>';
        btn.disabled = false;
    }
});


function updateGrandTotal() {
    let grandTotal = 0;
    const totalElements = document.querySelectorAll('.row-total');
    
    totalElements.forEach(el => {
        // Use the dataset value if available, or parse the text (removing commas)
        const val = parseFloat(el.dataset.rawTotal) || parseFloat(el.textContent.replace(/,/g, '')) || 0;
        grandTotal += val;
    });
    
    const grandTotalElement = document.getElementById('grand-total');
    const totalContainer = document.getElementById('total-container');
    
    if (grandTotalElement) {
        grandTotalElement.textContent = grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    // Check total and update UI colors
    if (grandTotal >= 2000) {
        // Change color to green (success)
        if (totalContainer) {
            totalContainer.classList.remove('text-danger');
            totalContainer.classList.add('text-success');
        }
    } else {
        // Change color to red (danger)
        if (totalContainer) {
            totalContainer.classList.remove('text-success');
            totalContainer.classList.add('text-danger');
        }
    }
}

function processNextStep() {
    // 1. Check Login
    if (!isLoggedIn) {
        alert('يجب تسجيل الدخول أولاً للمتابعة');
        window.location.href = loginRoute;
        return;
    }

    // 2. Check Total > 2000
    // Recalculate total from inputs to be safe
    const inputs = document.querySelectorAll('.quantity-input');
    let calculatedTotal = 0;
    inputs.forEach(input => {
        const qty = parseFloat(input.value) || 0;
        const price = parseFloat(input.dataset.price);
        calculatedTotal += qty * price;
    });

    if (calculatedTotal < 2000) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: 'عفواً، يجب أن يتجاوز إجمالي الطلب 2000 جنيه للمتابعة',
            confirmButtonText: 'حسناً',
            confirmButtonColor: '#e3342f'
        });
        return;
    }

    // 3. Submit Items to Cart then Redirect
    submitBulkOrder(true);
}

function submitBulkOrder(redirect = false) {
    const inputs = document.querySelectorAll('.quantity-input');
    const products = [];

    inputs.forEach(input => {
        const qty = parseInt(input.value);
        if (qty > 0) {
            products.push({
                product_id: input.dataset.id,
                quantity: qty
            });
        }
    });

    if (products.length === 0) {
        alert('الرجاء اختيار منتج واحد على الأقل');
        return;
    }

    // Determine button to show loading state
    let btn;
    if (redirect) {
         // Try to find the Next button
         btn = document.querySelector('button[onclick="processNextStep()"]');
    } else {
         btn = document.querySelector('button[onclick="submitBulkOrder()"]'); // Fallback if old button exists or separate add button
    }
    
    // Safely handle button state if found
    let originalContent = '';
    if (btn) {
        originalContent = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري المعالجة...';
        btn.disabled = true;
    }

    // Clear the cart first to avoid duplicate quantities from previous clicks
    fetch("{{ route('frontend.cart.clear') }}", {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }).then(response => {
        const promises = products.map(p => {
            return fetch("{{ route('frontend.cart.add') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(p)
            }).then(res => res.json());
        });

        return Promise.all(promises);
    })
    .then(results => {
        const successResults = results.filter(r => r.success);
        const failResults = results.filter(r => !r.success);

        if (successResults.length > 0) {
            // Clear localStorage choices for successful items
            products.forEach(p => localStorage.removeItem('quick_order_qty_' + p.product_id));
            
            if (redirect) {
                window.location.href = checkoutRoute;
            } else {
                alert('تم إضافة ' + successResults.length + ' منتجات بنجاح في السلة');
                window.location.reload(); 
            }
        } else {
            // If all failed, show the message from the first failure
            const errorMsg = failResults.length > 0 ? failResults[0].message : 'حدث مشكلة أثناء إضافة المنتجات';
            alert(errorMsg);
             if(btn) {
                btn.innerHTML = originalContent;
                btn.disabled = false;
            }
        }
    })
    .catch(err => {
        console.error(err);
        alert('حدث خطأ غير متوقع');
         if(btn) {
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    });
}
</script>
@endpush
@endsection
