<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'توريد ميد - منصة ربط الموردين')</title>
    
    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/images/logo.png') }}">
    
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    {{-- CSS الأساسي --}}
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    
    @stack('styles')
</head>
<body>
    {{-- Standardized Header Component --}}
    <header class="standard-header">
        <div class="container-fluid">
            <div class="header-wrapper">
                {{-- Logo Section --}}
                <div class="header-logo">
                    <a href="{{ route('frontend.home') }}">
                        <img src="{{ asset('frontend/images/logo.png') }}" 
                             alt="توريد ميد" 
                             class="logo-image"
                             onerror="this.src='https://via.placeholder.com/150x50/667eea/ffffff?text=توريد+ميد'">
                    </a>
                </div>
                
                {{-- Navigation Menu --}}
                <nav class="header-nav">
                    <ul class="nav-menu-list">
                        <li class="nav-item">
                            <a href="{{ route('frontend.home') }}" 
                               class="nav-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}">
                                <i class="fas fa-home"></i>
                                <span>الرئيسية</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('frontend.about') }}" 
                               class="nav-link {{ request()->routeIs('frontend.about') ? 'active' : '' }}">
                                <i class="fas fa-info-circle"></i>
                                <span>من نحن</span>
                            </a>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle">
                                <i class="fas fa-boxes"></i>
                                <span>المنتجات</span>
                                <i class="fas fa-chevron-down ms-1"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('frontend.medicines.index') }}"><i class="fas fa-capsules me-2"></i>الأدوية</a></li>
                                <li><a href="{{ route('frontend.medical-supplies.index') }}"><i class="fas fa-medkit me-2"></i>المستلزمات الطبية</a></li>
                                <li><a href="{{ route('frontend.cosmetics.index') }}"><i class="fas fa-magic me-2"></i>مستحضرات التجميل</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ route('frontend.products.index') }}"><i class="fas fa-list me-2"></i>جميع المنتجات</a></li>
                            </ul>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('frontend.companies.index') }}" 
                               class="nav-link {{ request()->routeIs('frontend.companies*') ? 'active' : '' }}">
                                <i class="fas fa-building"></i>
                                <span>الشركات</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('frontend.contact.index') }}" 
                               class="nav-link {{ request()->routeIs('frontend.contact*') ? 'active' : '' }}">
                                <i class="fas fa-phone"></i>
                                <span>إتصل بنا</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                
                {{-- User Actions --}}
                <div class="header-actions">
                    @php
                        $customerGuard = Auth::guard('customer');
                        $activeCustomer = $customerGuard->check() ? $customerGuard->user() : null;
                        $cartCount = count(session('cart', []));
                    @endphp
                    
                    @if($activeCustomer)
                        {{-- Customer Logged In --}}
                        <a href="{{ route('frontend.cart') }}" class="action-btn cart-btn">
                            <i class="fas fa-shopping-cart"></i>
                            @if($cartCount > 0)
                                <span class="badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                        
                        <div class="user-menu">
                            <a href="{{ route('frontend.customer.profile') }}" class="action-btn user-btn">
                                <i class="fas fa-user"></i>
                                <span>{{ Str::limit($activeCustomer->name ?? 'عميل', 15) }}</span>
                            </a>
                            <form method="POST" action="{{ route('frontend.customer.logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="action-btn logout-btn" title="تسجيل الخروج">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>
                    @elseif(session()->has('supplier'))
                        {{-- Supplier Logged In --}}
                        <a href="{{ route('supplier.dashboard') }}" class="action-btn supplier-btn">
                            <i class="fas fa-store"></i>
                            <span>لوحة المورد</span>
                        </a>
                        <form method="POST" action="{{ route('supplier.logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="action-btn logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    @elseif(session()->has('admin'))
                        {{-- Admin Logged In --}}
                        <a href="/admin/dashboard" class="action-btn admin-btn">
                            <i class="fas fa-cog"></i>
                            <span>لوحة الإدارة</span>
                        </a>
                    @else
                        {{-- Guest User --}}
                        <a href="{{ route('frontend.cart') }}" class="action-btn cart-btn">
                            <i class="fas fa-shopping-cart"></i>
                            @if($cartCount > 0)
                                <span class="badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                        
                        <div class="auth-buttons">
                            <a href="{{ route('frontend.customer.login') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-sign-in-alt me-1"></i>تسجيل الدخول
                            </a>
                            <a href="{{ route('supplier.login') }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-store me-1"></i>سجل كمورد
                            </a>
                        </div>
                    @endif
                    
                    {{-- Mobile Menu Toggle --}}
                    <button class="mobile-menu-toggle" id="mobileMenuToggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </header>
    
    @push('styles')
    <style>
        .standard-header {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }
        
        .header-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .header-logo .logo-image {
            height: 50px;
            width: auto;
        }
        
        .header-nav .nav-menu-list {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 10px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .nav-link:hover,
        .nav-link.active {
            background: #f0f0f0;
            color: #667eea;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .action-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 15px;
            border: none;
            background: transparent;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s;
            position: relative;
        }
        
        .action-btn:hover {
            background: #f0f0f0;
        }
        
        .action-btn .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
        }
        
        @media (max-width: 768px) {
            .header-nav {
                display: none;
            }
        }
    </style>
    @endpush





