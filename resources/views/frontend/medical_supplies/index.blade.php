@extends('frontend.layouts.app')

@section('title', 'المستلزمات الطبية - توريد ميد')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/products.css') }}">
@endpush

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="page-title">المستلزمات الطبية</h1>
                <p class="page-subtitle">أجهزة ومعدات طبية عالية الجودة من موردين موثوقين</p>
            </div>
            <div class="col-lg-4 text-end">
                <div class="stats-info">
                    <span class="stat-item">
                        <i class="fas fa-tools"></i> 
                        {{ $totalProducts }} منتج
                    </span>
                </div>
            </div>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item active">المستلزمات الطبية</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Category Tabs -->
<section class="category-links">
    <div class="container">
        <div class="category-tabs">
            <a href="{{ route('frontend.medicines.index') }}" class="category-tab">
                <span class="tab-icon">💊</span>
                <span>الأدوية</span>
            </a>
            <a href="{{ route('frontend.medical-supplies.index') }}" class="category-tab active">
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
        <a href="{{ route('frontend.medical-supplies.quick-order') }}" class="btn btn-lg btn-success pulse-animation shadow-lg px-5 py-3 rounded-pill" style="min-width: 300px;">
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

<!-- Featured Products Section -->
@if(isset($featuredProducts) && $featuredProducts->count() > 0)
<section class="featured-products">
    <div class="container">
        <h2 class="section-title">المنتجات المميزة</h2>
        <div class="products-grid featured-grid">
            @foreach($featuredProducts as $product)
            <div class="product-card small" onclick="window.location.href='{{ route('frontend.medical-supplies.show', $product->slug ?? $product->id) }}'" style="cursor: pointer;">
                <div class="product-image">
                    <a href="{{ route('frontend.medical-supplies.show', $product->slug) }}" onclick="event.stopPropagation()">
                        @if($product->image_1)
                            <img src="{{ $product->getImageUrl(1) }}" alt="{{ $product->name }}" style="object-fit: contain; padding: 5px;">
                        @else
                            <div class="no-image"><i class="fas fa-tools fa-2x"></i></div>
                        @endif
                    </a>
                </div>
                <div class="product-info">
                    <h4>{{ Str::limit($product->name, 30) }}</h4>
                    <div class="product-price-section">
                        {{-- ✅ تعديل السعر في المميز أيضاً --}}
                        @if($product->discount > 0)
                            <div class="price-discounted">{{ number_format($product->final_package_price, 2) }} جنيه</div>
                            <span class="discount-badge">-{{ $product->discount }}%</span>
                        @else
                            <div class="price-main">{{ number_format($product->final_package_price, 2) }} جنيه</div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Main Products Section -->
<section class="products-main">
    <div class="container">
        <div class="products-layout">
            <!-- Sidebar Filters -->
            <aside class="filters-sidebar">
                <div class="filter-header">
                    <h3>الفلاتر</h3>
                    <a href="{{ route('frontend.medical-supplies.index') }}" class="clear-filters">مسح الكل</a>
                </div>

                <form method="GET" action="{{ route('frontend.medical-supplies.index') }}" id="filterForm">
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
                        <form method="GET" action="{{ route('frontend.medical-supplies.index') }}" class="sort-form">
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
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>الأكثر شعبية</option>
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
                            <a href="{{ route('frontend.medical-supplies.index', request()->except('search')) }}">&times;</a>
                        </span>
                    @endif
                    <a href="{{ route('frontend.medical-supplies.index') }}" class="clear-all-filters">مسح الكل</a>
                </div>
                @endif

                <!-- Products Grid -->
                <div class="products-grid">
                    @forelse($products as $product)
                        <div class="product-card" onclick="window.location.href='{{ route('frontend.medical-supplies.show', $product->slug ?? $product->id) }}'">
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
                                
                                {{-- ✅ تعديل السعر: عرض سعر الباكيدج بالكامل --}}
                                <div class="product-price">
                                    <span class="price">{{ number_format($product->final_package_price, 2) }}</span>
                                    <span class="unit">جنيه / {{ $product->package_type ?? 'كرتونة' }}</span>
                                </div>

                                <div class="product-discount" style="{{ $product->discount > 0 ? '' : 'visibility: hidden' }}">
                                    خصم {{ $product->discount }}%
                                </div>
                                
                                {{-- ✅ تعديل الزرار: تحويل لصفحة المنتج --}}
                                <a href="{{ route('frontend.medical-supplies.show', $product->slug ?? $product->id) }}" 
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
                            <a href="{{ route('frontend.medical-supplies.index') }}" class="btn btn-primary">عرض جميع المنتجات</a>
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
</script>
@endpush
