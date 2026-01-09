<header class="header">
    @php
        $customerGuard = Auth::guard('customer');
        $activeCustomer = $customerGuard->check() ? $customerGuard->user() : null;
        $cartCount = count(session('cart', []));
    @endphp
    <div class="container">
        <div class="header-content">
            {{-- Logo --}}
            <div class="logo">
                <a href="{{ route('frontend.home') }}">
                    <img src="{{ asset('frontend/images/logo.png') }}" 
                         alt="توريد ميد" 
                         class="logo-img"
                         onerror="this.src='https://via.placeholder.com/150x50/667eea/ffffff?text=توريد+ميد'">
                </a>
            </div>
            
            {{-- Navigation Menu --}}
            <nav class="nav-menu" id="navMenu">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('frontend.home') }}" 
                           class="nav-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}">
                            <i class="fas fa-home me-1"></i>الرئيسية
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('frontend.about') }}" 
                           class="nav-link {{ request()->routeIs('frontend.about') ? 'active' : '' }}">
                            <i class="fas fa-info-circle me-1"></i>من نحن
                        </a>
                    </li>
                    
                    {{-- Dropdown Menu --}}
                    <li class="nav-item dropdown products-dropdown">
                        <a href="javascript:void(0);" 
                           class="nav-link dropdown-toggle {{ request()->routeIs('frontend.medicines*') || request()->routeIs('frontend.medical-supplies*') || request()->routeIs('frontend.cosmetics*') || request()->routeIs('frontend.products*') ? 'active' : '' }}" 
                           role="button">
                            <i class="fas fa-boxes me-1"></i>المنتجات 
                            <span class="arrow">▼</span>
                        </a>
                        
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">الفئات الرئيسية</li>
                            <li><a href="{{ route('frontend.medicines.index') }}" class="dropdown-link"><i class="fas fa-capsules me-2"></i>الأدوية</a></li>
                            <li><a href="{{ route('frontend.medical-supplies.index') }}" class="dropdown-link"><i class="fas fa-medkit me-2"></i>المستلزمات الطبية</a></li>
                            <li><a href="{{ route('frontend.cosmetics.index') }}" class="dropdown-link"><i class="fas fa-magic me-2"></i>مستحضرات التجميل</a></li>
                        </ul>
                    </li>
                    
                    {{-- ❌ تم حذف رابط الشركات من هنا --}}
                    
                    <li class="nav-item">
                        <a href="{{ route('frontend.contact.index') }}" class="nav-link {{ request()->routeIs('frontend.contact*') ? 'active' : '' }}">
                            <i class="fas fa-phone me-1"></i>اتصل بنا
                        </a>
                    </li>

                    {{-- Mobile Auth Buttons (Visible only on mobile) --}}
                    <li class="nav-item d-lg-none mobile-auth-section" style="border-top: 1px solid #eee; margin-top: 10px; padding-top: 10px;">
                        @if($activeCustomer)
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="fw-bold"><i class="fas fa-user me-2"></i>{{ $activeCustomer->name }}</span>
                                <a href="{{ route('frontend.cart') }}" class="position-relative text-dark text-decoration-none">
                                    <i class="fas fa-shopping-cart fa-lg"></i>
                                    <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                                        {{ $cartCount }}
                                    </span>
                                </a>
                            </div>
                            <a href="{{ route('frontend.customer.profile') }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                                <i class="fas fa-user-circle me-1"></i> الملف الشخصي
                            </a>
                            <form method="POST" action="{{ route('frontend.customer.logout') }}" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                    <i class="fas fa-sign-out-alt me-1"></i> تسجيل الخروج
                                </button>
                            </form>
                        @elseif(session()->has('supplier'))
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="fw-bold"><i class="fas fa-store me-2"></i>المورد</span>
                            </div>
                            <a href="{{ route('supplier.dashboard') }}" class="btn btn-primary btn-sm w-100 mb-2">
                                <i class="fas fa-tachometer-alt me-1"></i> لوحة التحكم
                            </a>
                            <form method="POST" action="{{ route('supplier.logout') }}" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                    <i class="fas fa-sign-out-alt me-1"></i> تسجيل الخروج
                                </button>
                            </form>
                        @elseif(session()->has('admin'))
                            <a href="/admin/dashboard" class="btn btn-danger btn-sm w-100 mb-2">
                                <i class="fas fa-cog me-1"></i> لوحة الإدارة
                            </a>
                        @else
                            <div class="d-flex align-items-center justify-content-between mb-3 px-2">
                                <span class="text-muted small">تسوق الآن</span>
                                <a href="{{ route('frontend.cart') }}" class="position-relative text-dark text-decoration-none">
                                    <i class="fas fa-shopping-cart fa-lg"></i>
                                    <span class="badge bg-secondary rounded-pill position-absolute top-0 start-100 translate-middle">
                                        {{ $cartCount }}
                                    </span>
                                </a>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('frontend.customer.login') }}" class="btn btn-outline-primary btn-sm w-50">تسجيل عميل</a>
                                <a href="{{ route('supplier.login') }}" class="btn btn-outline-success btn-sm w-50">تسجيل مورد</a>
                            </div>
                        @endif
                    </li>
                </ul>
            </nav>

            {{-- Header Actions --}}
            <div class="header-actions">
                @if($activeCustomer)
                    <a href="{{ route('frontend.cart') }}" class="cart-icon position-relative">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count badge {{ $cartCount > 0 ? 'bg-danger' : 'bg-secondary' }}">{{ $cartCount }}</span>
                    </a>
                    <div class="user-menu">
                        <a href="{{ route('frontend.customer.profile') }}" class="user-link">
                            <i class="fas fa-user me-1"></i>
                            <span class="d-none d-md-inline">{{ Str::limit($activeCustomer->display_name ?? $activeCustomer->name ?? 'عميل', 10) }}</span>
                        </a>
                        <form method="POST" action="{{ route('frontend.customer.logout') }}" class="d-inline logout-form">
                            @csrf
                            <button type="submit" class="logout-btn" title="تسجيل الخروج"><i class="fas fa-sign-out-alt"></i></button>
                        </form>
                    </div>
                @elseif(session()->has('supplier'))
                    <a href="{{ route('supplier.dashboard') }}" class="btn btn-outline-warning btn-sm"><i class="fas fa-store me-1"></i>لوحة المورد</a>
                    <form method="POST" action="{{ route('supplier.logout') }}" class="d-inline logout-form">
                        @csrf
                        <button type="submit" class="logout-btn" title="تسجيل الخروج"><i class="fas fa-sign-out-alt"></i></button>
                    </form>
                @elseif(session()->has('admin'))
                    <a href="/admin/dashboard" class="btn btn-outline-danger btn-sm"><i class="fas fa-cog me-1"></i>الإدارة</a>
                @else
                    <a href="{{ route('frontend.cart') }}" class="cart-icon position-relative me-2">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count badge {{ $cartCount > 0 ? 'bg-danger' : 'bg-secondary' }}">{{ $cartCount }}</span>
                    </a>
                    <div class="auth-buttons">
                        <a href="{{ route('frontend.customer.login') }}" class="btn btn-outline-primary btn-sm">تسجيل عميل</a>
                        <a href="{{ route('supplier.login') }}" class="btn btn-outline-success btn-sm">تسجيل مورد</a>
                    </div>
                @endif
            </div>

            {{-- Hamburger Menu --}}
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>
<div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
