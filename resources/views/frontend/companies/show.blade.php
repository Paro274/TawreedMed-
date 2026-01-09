@extends('frontend.layouts.app')

@section('title', $supplier->name . ' - توريد ميد')

@push('styles')
<style>
    .company-cover {
        background: linear-gradient(135deg, #16a34a 0%, #0d9488 100%);
        padding: 60px 0;
        color: white;
        margin-bottom: 40px;
    }
    .company-logo-large {
        width: 150px;
        height: 150px;
        border-radius: 15px;
        border: 4px solid white;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        object-fit: cover;
        background: white;
    }
    .product-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
        background: white;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        border-color: #16a34a;
    }
    .product-img-wrapper {
        height: 200px;
        overflow: hidden;
        position: relative;
        background: #f9fafb;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
    }
    .product-img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    .product-card:hover .product-img {
        transform: scale(1.05);
    }
    .filters-sidebar {
        background: white;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        position: sticky;
        top: 20px;
    }
    .price-tag {
        font-weight: 700;
        color: #16a34a;
        font-size: 1.1rem;
    }
    .old-price {
        text-decoration: line-through;
        color: #9ca3af;
        font-size: 0.9rem;
        margin-left: 5px;
    }
</style>
@endpush

@section('content')

<!-- Company Header -->
<div class="company-cover">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-auto text-center text-md-start mb-3 mb-md-0">
                <img src="{{ $supplier->logo_url }}" alt="{{ $supplier->name }}" class="company-logo-large">
            </div>
            <div class="col-md">
                <div class="text-center text-md-start">
                    <h1 class="fw-bold mb-2">{{ $supplier->name }}</h1>
                    @if($supplier->description)
                        <p class="opacity-75 mb-3">{{ $supplier->description }}</p>
                    @endif
                    <div class="d-flex gap-3 justify-content-center justify-content-md-start flex-wrap">
                        @if($supplier->city)
                            <span class="badge bg-white bg-opacity-25"><i class="fas fa-map-marker-alt me-1"></i> {{ $supplier->city }}</span>
                        @endif
                        <span class="badge bg-white bg-opacity-25"><i class="fas fa-box me-1"></i> {{ $totalProducts }} منتج</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="filters-sidebar">
                <h5 class="fw-bold mb-3"><i class="fas fa-filter me-2"></i> تصفية المنتجات</h5>
                <form action="{{ route('frontend.companies.show', $supplier->id) }}" method="GET">
                    
                    <!-- Search -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold">بحث</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="اسم المنتج...">
                    </div>

                    <!-- Product Type -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold">النوع</label>
                        @foreach($productTypes as $type)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" value="{{ $type->product_type }}" id="type_{{ $loop->index }}" {{ request('type') == $type->product_type ? 'checked' : '' }}>
                            <label class="form-check-label" for="type_{{ $loop->index }}">
                                {{ $type->product_type }} <span class="text-muted small">({{ $type->count }})</span>
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <!-- Categories -->
                    @if($availableCategories->count() > 0)
                    <div class="mb-3">
                        <label class="form-label small fw-bold">التصنيف</label>
                        <div style="max-height: 200px; overflow-y: auto;">
                            @foreach($availableCategories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="category[]" value="{{ $category->id }}" id="cat_{{ $category->id }}" {{ in_array($category->id, (array)request('category')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat_{{ $category->id }}">
                                    {{ $category->name }} <span class="text-muted small">({{ $category->products_count }})</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-100">تطبيق</button>
                    @if(request()->anyFilled(['search', 'type', 'category']))
                        <a href="{{ route('frontend.companies.show', $supplier->id) }}" class="btn btn-outline-secondary w-100 mt-2">إلغاء الفلتر</a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            @if($products->count() > 0)
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                    @foreach($products as $product)
                    <div class="col">
                        <div class="product-card h-100 d-flex flex-column">
                            <div class="product-img-wrapper">
                                {{-- ✅ استخدام الدالة الصحيحة للصور --}}
                                <img src="{{ $product->getImageUrl(1) }}" alt="{{ $product->name }}" class="product-img">
                                @if($product->has_discount)
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-danger">-{{ $product->discount }}%</span>
                                @endif
                            </div>
                            <div class="p-3 d-flex flex-column flex-grow-1">
                                <div class="small text-muted mb-1">{{ $product->category->name ?? 'عام' }}</div>
                                <h5 class="fw-bold mb-2 text-truncate">
<a href="{{ route('frontend.products.show', $product->slug) }}" 
   class="text-decoration-none text-dark stretched-link">
    {{ $product->name }}
</a>

                                <div class="mt-auto d-flex align-items-center justify-content-between">
                                    <div>
                                        @if($product->has_discount)
                                            <div class="old-price">{{ $product->formatted_price }}</div>
                                            <div class="price-tag">{{ $product->formatted_discounted_price }}</div>
                                        @else
                                            <div class="price-tag">{{ $product->formatted_price }}</div>
                                        @endif
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary rounded-circle" style="width: 32px; height: 32px; padding: 0; position: relative; z-index: 2;">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-5 d-flex justify-content-center">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5 bg-white rounded border">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>لا توجد منتجات مطابقة</h4>
                    <p class="text-muted">حاول تغيير خيارات البحث أو الفلترة</p>
                    <a href="{{ route('frontend.companies.show', $supplier->id) }}" class="btn btn-primary mt-2">عرض كل منتجات الشركة</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
