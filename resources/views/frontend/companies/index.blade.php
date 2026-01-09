@extends('frontend.layouts.app')

@section('title', 'الشركات الموردة - توريد ميد')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/companies.css') }}">
@endpush

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="page-title">الشركات الموردة</h1>
                <p class="page-subtitle">اكتشف شركائنا الموثوقين ومنتجاتهم المتنوعة</p>
            </div>

            <!-- Search Form -->
            <div class="col-lg-4">
                <form method="GET" action="{{ route('frontend.companies.index') }}" class="search-form">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="ابحث عن شركة..." 
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <nav aria-label="breadcrumb" class="mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item active">الشركات</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Companies Grid -->
<section class="companies-section">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">شركاتنا الموثوقة</h2>
            <p class="section-subtitle">استكشف مجموعة الشركات الموردة ومنتجاتها الطبية عالية الجودة</p>
        </div>

        <div class="companies-grid">
            @forelse($suppliers as $supplier)
            <div class="company-card">
                <!-- Company Logo -->
                <div class="company-logo-wrapper">
                    <img src="{{ $supplier->logo_url }}" 
                         alt="{{ $supplier->name }}" 
                         class="company-logo"
                         onerror="this.onerror=null; this.src='{{ asset('storage/default/default-company-logo.jpg') }}';">
                </div>
                
                <!-- Company Content -->
                <div class="company-content">
                    <h3 class="company-name">{{ $supplier->name }}</h3>
                    
                    @if($supplier->description)
                    <p class="company-description">{{ Str::limit($supplier->description, 80) }}</p>
                    @endif
                    
                    <!-- Company Stats -->
                    <div class="company-stats">
                        @if($supplier->products_count > 0)
                        <div class="stat-item">
                            <i class="fas fa-box"></i>
                            <span>{{ $supplier->products_count }} منتج</span>
                        </div>
                        @endif
                        
                        @if($supplier->city)
                        <div class="stat-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $supplier->city }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Company Actions -->
                <div class="company-actions">
                    <a href="{{ route('frontend.companies.show', $supplier->id) }}" 
                       class="btn btn-primary w-100 view-products-btn">
                        <i class="fas fa-shopping-bag"></i>
                        عرض المنتجات
                        @if($supplier->products_count > 0)
                        <span class="badge bg-success ms-1">{{ $supplier->products_count }}</span>
                        @endif
                    </a>
                </div>
            </div>
            @empty
            <div class="no-companies text-center">
                <i class="fas fa-building fa-5x text-muted mb-4"></i>
                <h3 class="text-muted mb-3">لا توجد شركات</h3>
                <p class="text-muted mb-4">لا يوجد موردين مسجلين في الوقت الحالي</p>
                
                @if(request('search'))
                <p class="text-muted mb-3">جرب بحثاً بكلمات أقل تحديداً</p>
                <a href="{{ route('frontend.companies.index') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-redo"></i> إظهار الكل
                </a>
                @endif
                
                <a href="{{ route('frontend.home') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> العودة للرئيسية
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(method_exists($suppliers, 'hasPages') && $suppliers->hasPages())
        <div class="pagination-wrapper mt-5 text-center">
            {{ $suppliers->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Company card hover effects
    const companyCards = document.querySelectorAll('.company-card');
    companyCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
            this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.15)';
            this.style.transition = 'all 0.3s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.08)';
        });
        
        // Smooth loading for images
        const img = card.querySelector('.company-logo');
        if (img) {
            img.addEventListener('load', function() {
                this.style.opacity = '1';
                this.style.transform = 'scale(1)';
            });
            
            img.addEventListener('error', function() {
                console.log('Logo failed to load for:', this.alt);
            });
        }
    });

    // Search functionality
    const searchInput = document.querySelector('.search-form input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.closest('form').submit();
            }
        });
        
        // Clear search on focus if empty
        searchInput.addEventListener('focus', function() {
            if (!this.value.trim()) {
                this.placeholder = 'اكتب اسم الشركة...';
            }
        });
        
        searchInput.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.placeholder = 'ابحث عن شركة...';
            }
        });
    }

    // Animate cards on scroll
    const cards = document.querySelectorAll('.company-card');
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });

    // Smooth scroll for pagination
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = this.href;
        });
    });
});
</script>
@endpush
