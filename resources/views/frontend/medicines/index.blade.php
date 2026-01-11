@extends('frontend.layouts.app')

@section('title', 'الأدوية - توريد ميد')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/products.css') }}">
@endpush

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>الأدوية</h1>
        <p>تصفح أفضل المنتجات الدوائية من موردين موثوقين</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item active">الأدوية</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Category Tabs -->
<section class="category-links">
    <div class="container">
        <div class="category-tabs">
            <a href="{{ route('frontend.medicines.index') }}" class="category-tab active">
                <span class="tab-icon">💊</span>
                <span>الأدوية</span>
            </a>
            <a href="{{ route('frontend.medical-supplies.index') }}" class="category-tab ">
                <span class="tab-icon">🏥</span>
                <span>المستلزمات الطبية</span>
            </a>
            <a href="{{ route('frontend.cosmetics.index') }}" class="category-tab ">
                <span class="tab-icon">💄</span>
                <span>مستحضرات التجميل</span>
            </a>
        </div>
    </div>
</section>

<!-- Quick Order CTA Section -->
<section class="quick-order-cta py-4 bg-white">
    <div class="container text-center">
        <a href="{{ route('frontend.medicines.quick-order') }}" class="btn btn-lg btn-success pulse-animation shadow-lg px-5 py-3 rounded-pill" style="min-width: 300px;">
            <i class="fas fa-clipboard-list fa-lg me-2"></i>
            <span class="fs-4 fw-bold">اطلب الآن - قائمة الطلب السريع</span>
        </a>
    </div>
</section>

<style>
.pulse-animation {
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7);
        transform: scale(1);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(25, 135, 84, 0);
        transform: scale(1.05);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(25, 135, 84, 0);
        transform: scale(1);
    }
}
</style>

<!-- Main Products Section -->
<section class="products-main">
    <div class="container">
        <div class="products-layout">
            <!-- Sidebar Filters -->
            <aside class="filters-sidebar">
                <div class="filter-header">
                    <h3>الفلاتر</h3>
                    <a href="{{ route('frontend.medicines.index') }}" class="clear-filters">مسح الكل</a>
                </div>

                <form method="GET" action="{{ route('frontend.medicines.index') }}" id="filterForm">
                    <!-- Search Filter -->
                    <div class="filter-group">
                        <label>البحث</label>
                        <input type="text" 
                               name="search" 
                               class="search-input" 
                               placeholder="ابحث عن منتج..."
                               value="{{ request('search') }}">
                    </div>

                    <!-- Category Filter -->
                    @if($categories->count() > 0)
                    <div class="filter-group">
                        <div class="filter-title" onclick="toggleFilter(this)">
                            <label>التصنيف</label>
                            <span class="toggle-icon">−</span>
                        </div>
                        <div class="filter-content active">
                            @foreach($categories as $category)
                                <div class="filter-option">
                                    <input type="checkbox" 
                                           id="cat-{{ $category->id }}" 
                                           name="category[]"
                                           value="{{ $category->id }}"
                                           {{ in_array($category->id, (array)request('category', [])) ? 'checked' : '' }}>
                                    <label for="cat-{{ $category->id }}">
                                        {{ $category->name }}
                                        <span class="count">({{ $category->products_count }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Company Filter -->
                    @if(count($companies) > 0)
                    <div class="filter-group">
                        <div class="filter-title" onclick="toggleFilter(this)">
                            <label>الشركة المصنعة</label>
                            <span class="toggle-icon">−</span>
                        </div>
                        <div class="filter-content active">
                            @foreach($companies as $company)
                                <div class="filter-option">
                                    <input type="checkbox" 
                                           id="company-{{ Str::slug($company) }}" 
                                           name="company[]"
                                           value="{{ $company }}"
                                           {{ in_array($company, (array)request('company', [])) ? 'checked' : '' }}>
                                    <label for="company-{{ Str::slug($company) }}">{{ $company }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Price Range Filter -->
                    <div class="filter-group">
                        <div class="filter-title" onclick="toggleFilter(this)">
                            <label>نطاق السعر</label>
                            <span class="toggle-icon">−</span>
                        </div>
                        <div class="filter-content active">
                            <div class="price-inputs">
                                <input type="number" 
                                       name="price_min" 
                                       placeholder="من" 
                                       value="{{ request('price_min') }}"
                                       class="price-input">
                                <span>-</span>
                                <input type="number" 
                                       name="price_max" 
                                       placeholder="إلى" 
                                       value="{{ request('price_max') }}"
                                       class="price-input">
                            </div>
                            <small class="text-muted">السعر بالجنيه المصري</small>
                        </div>
                    </div>

                    <!-- Availability Filter -->
                    <div class="filter-group">
                        <div class="filter-option">
                            <input type="checkbox" 
                                   id="available" 
                                   name="available"
                                   value="1"
                                   {{ request('available') ? 'checked' : '' }}>
                            <label for="available">متوفر فقط</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">تطبيق الفلاتر</button>
                </form>
            </aside>

            <!-- Products Content -->
            <div class="products-content">
                <!-- Toolbar -->
                <div class="products-toolbar">
                    <div class="results-count">
                        عرض {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} من {{ $products->total() }} منتج
                    </div>
                    <div class="toolbar-actions">
                        <form method="GET" action="{{ route('frontend.medicines.index') }}" class="sort-form">
                            <!-- Preserve filters -->
                            @foreach(request()->except('sort') as $key => $value)
                                @if(is_array($value))
                                    @foreach($value as $v)
                                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            
                            <select name="sort" class="sort-select" onchange="this.form.submit()">
                                <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>الترتيب الافتراضي</option>
                                <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                                <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                                <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>الاسم: أ - ي</option>
                                <option value="name-desc" {{ request('sort') == 'name-desc' ? 'selected' : '' }}>الاسم: ي - أ</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Active Filters Tags -->
                @if(request()->hasAny(['search', 'category', 'company', 'price_min', 'price_max', 'available']))
                <div class="active-filters">
                    @if(request('search'))
                        <span class="filter-tag">
                            البحث: {{ request('search') }}
                            <a href="{{ route('frontend.medicines.index', request()->except('search')) }}">&times;</a>
                        </span>
                    @endif
                    <a href="{{ route('frontend.medicines.index') }}" class="clear-all-filters">مسح الكل</a>
                </div>
                @endif

                <!-- Products Grid -->
                <div class="products-grid">
                    @forelse($products as $product)
                        <div class="product-card" onclick="window.location.href='{{ route('frontend.medicines.show', $product->slug ?? $product->id) }}'">
                            <div class="product-image">
                                @if($product->image_1)
                                    <img src="{{ $product->getImageUrl(1) }}" 
                                         alt="{{ $product->name }}" 
                                         style="object-fit: contain; padding: 10px;">
                                @else
                                    <img src="https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=400&h=280&fit=crop" alt="{{ $product->name }}">
                                @endif

                                @if($product->created_at->diffInDays(now()) < 7)
                                    <span class="product-badge">جديد</span>
                                @elseif($product->discount > 0)
                                    <span class="product-badge sale">خصم {{ $product->discount }}%</span>
                                @endif
                            </div>
                            <div class="product-info">
                                <h3>{{ $product->name }}</h3>
                                <p class="product-category">{{ $product->short_description ?? ($product->category->name ?? 'غير محدد') }}</p>
                                
                                {{-- ✅ عرض السعر: السعر قبل الخصم + السعر بعد الخصم --}}
                                {{-- ✅ عرض السعر: السعر قبل الخصم + السعر بعد الخصم --}}
                                <div class="product-price-details mb-3 text-center">
                                    {{-- السعر قبل الخصم --}}
                                    @php
                                        $publicPrice = $product->price * ($product->units_per_package > 0 ? $product->units_per_package : 1);
                                    @endphp
                                    <div class="public-price mb-2">
                                        <span class="fw-bold" style="font-size: 0.95rem; color: #dc3545;">سعر الجمهور:</span>
                                        <span class="text-decoration-line-through fw-bold" style="font-size: 1.3rem; font-family: 'Arial', sans-serif; color: #dc3545;">{{ number_format($publicPrice, 2) }} جنيه</span>
                                    </div>

                                    {{-- الخصم (في المنتصف) --}}
                                    @if($product->discount > 0)
                                        <div class="mb-2">
                                            <span class="badge rounded-pill custom-discount-box px-3 py-2" style="font-size: 0.9rem;">
                                                <i class="fas fa-arrow-down me-1"></i> خصم {{ $product->discount }}%
                                            </span>
                                        </div>
                                    @else
                                        <div style="height: 38px;"></div>
                                    @endif

                                    {{-- السعر بعد الخصم --}}
                                    <div class="product-price d-flex justify-content-center align-items-baseline gap-2 mt-2">
                                        <span class="fw-bold text-dark small">بعد الخصم:</span>
                                        <span class="fw-bolder text-primary" style="font-size: 1.5rem;">{{ number_format($product->final_package_price, 2) }}</span>
                                        <span class="badge bg-dark text-white">{{ $product->package_type ?? 'كرتونة' }}</span>
                                    </div>
                                </div>
                                
                                {{-- ✅ تعديل الزرار: تحويل لصفحة المنتج --}}
                                <a href="{{ route('frontend.medicines.show', $product->slug ?? $product->id) }}" 
                                   class="btn btn-product btn-add-cart" 
                                   onclick="event.stopPropagation();">
                                    اطلب الآن
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="no-products">
                            <i class="fas fa-search fa-4x"></i>
                            <h3>لا توجد منتجات</h3>
                            <p>لم يتم العثور على منتجات تطابق معايير البحث</p>
                            <a href="{{ route('frontend.medicines.index') }}" class="btn btn-primary">عرض جميع المنتجات</a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="pagination-wrapper">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function toggleFilter(element) {
    element.classList.toggle('active');
    const content = element.nextElementSibling;
    const icon = element.querySelector('.toggle-icon');
    
    if (content.classList.contains('active')) {
        content.classList.remove('active');
        icon.textContent = '+';
    } else {
        content.classList.add('active');
        icon.textContent = '−';
    }
}

document.querySelectorAll('#filterForm input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        // document.getElementById('filterForm').submit();
    });
});
</script>
@endpush
