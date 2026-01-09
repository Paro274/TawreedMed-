@php
    use App\Models\Admin;

    $admin = null;
    if (session()->has('admin')) {
        $admin = Admin::find(session('admin'));
    }
@endphp

<!-- زرار القائمة للموبايل -->
<button class="sidebar-toggle" id="sidebarToggle">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M3 12H21M3 6H21M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</button>

<!-- Overlay للموبايل -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2>لوحة التحكم</h2>
        <p>إدارة النظام</p>
        
        <!-- زرار إغلاق للموبايل -->
        <button class="sidebar-close" id="sidebarClose">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
    
    <ul class="sidebar-menu">
        <!-- الرئيسية -->
        <li>
            <a href="/admin/dashboard" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 9L12 2L21 9V20C21 21.1 20.1 22 19 22H5C3.9 22 3 21.1 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>الرئيسية</span>
            </a>
        </li>
        
        {{-- التصنيفات --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('categories')))
        <li>
            <a href="{{ route('admin.categories.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6H20M4 12H20M4 18H20M4 6V18M20 6V18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span>التصنيفات</span>
            </a>
        </li>
        @endif
        
        {{-- الموردين --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('suppliers')))
        <li>
            <a href="{{ route('admin.suppliers.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 7.5C16 9.98528 13.9853 12 11.5 12C9.01472 12 7 9.98528 7 7.5C7 5.01472 9.01472 3 11.5 3C13.9853 3 16 5.01472 16 7.5Z" stroke="currentColor" stroke-width="2"/>
                    <path d="M21 14C21 16.4853 19.0147 18.5 16.5 18.5C14.1671 18.5 12.2218 17.3546 11.125 15.625C10.0273 17.3546 8.08205 18.5 5.749 18.5C3.23579 18.5 1 16.4853 1 14C1 12.6861 1.50878 11.4617 2.42593 10.5486C3.34308 9.63544 4.5674 9.12676 5.881 9.12676H16.119C17.4326 9.12676 18.6569 9.63544 19.5741 10.5486C20.4912 11.4617 21 12.6861 21 14Z" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>الموردين</span>
            </a>
        </li>
        @endif
        
        {{-- إدارة الطلبات --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('orders')))
        <li>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>إدارة الطلبات</span>
            </a>
        </li>
        @endif
        
        {{-- المنتجات --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('products')))
        <li>
            <a href="{{ route('admin.products.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12L11 14L15 10M21 21H3V19C3 17.8954 3.89543 17 5 17H19C20.1046 17 21 17.8954 21 19V21Z" stroke="currentColor" stroke-width="2"/>
                    <path d="M7 6C7 4.89543 7.89543 4 9 4H15C16.1046 4 17 4.89543 17 6V15C17 16.1046 16.1046 17 15 17H9C7.89543 17 7 16.1046 7 15V6Z" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>المنتجات</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.products.create') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>إضافة منتج</span>
            </a>
        </li>
        @endif
        
        {{-- البانرات --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('sliders')))
        <li>
            <a href="{{ route('admin.sliders.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <rect x="2" y="2" width="20" height="8" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
                    <path d="M2 14h20v8a2 2 0 0 1 -2 2H4a2 2 0 0 1 -2 -2v-8z" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>البانرات</span>
            </a>
        </li>
        @endif

        {{-- من نحن --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('about')))
        <li>
            <a href="{{ route('admin.about.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 21V19C19 17.9 18.1 17 17 17H7C5.9 17 5 17.9 5 19V21M12 2C13.1 2 14 2.9 14 4V7C14 8.1 13.1 9 12 9C10.9 9 10 8.1 10 7V4C10 2.9 10.9 2 12 2Z" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>من نحن</span>
            </a>
        </li>
        @endif

        {{-- التقييمات --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('testimonials')))
        <li>
            <a href="{{ route('admin.testimonials.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.48 3.499a.562.562 0 0 1 .981 0l2.532 5.192a.562.562 0 0 0 .504.307l5.655.001c.295 0 .54.223.562.518a.562.562 0 0 1-.998.45l-5.14-2.669a.562.562 0 0 0-.66 0l-5.14 2.669a.562.562 0 0 1-.998-.45c.022-.295.267-.518.562-.518l5.655-.001a.562.562 0 0 0 .504-.307l2.532-5.192z" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>التقييمات</span>
            </a>
        </li>
        @endif

        {{-- الإحصائيات --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('statistics')))
        <li>
            <a href="{{ route('admin.statistics.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 19V16H5V19H8V21H3V19ZM9 19V12H11V19H14V21H9V19ZM15 19V14H17V19H20V21H15V19Z" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>الإحصائيات</span>
            </a>
        </li>
        @endif

        {{-- المميزات --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('features')))
        <li>
            <a href="{{ route('admin.features.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L13.09 8.26L22 9L15.827 13.74L16.665 21.76L12 17.91L7.335 21.76L8.173 13.74L2 9L10.91 8.26L12 2Z" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>المميزات</span>
            </a>
        </li>
        @endif

        {{-- قسم CTA --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('cta')))
        <li>
            <a href="{{ route('admin.cta.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2"/>
                    <path d="M2 17L12 22L22 17M2 12L12 17L22 12" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>قسم CTA</span>
            </a>
        </li>
        @endif

        {{-- بيانات التواصل --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('contact')))
        <li>
            <a href="{{ route('admin.contact.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 5C3 3.89543 3.89543 3 5 3H8.27924C8.70967 3 9.09181 3.27543 9.22792 3.68377L10.7257 8.17721C10.8831 8.64932 10.6694 9.16531 10.2243 9.38787L7.96701 10.5165C9.06925 12.9612 11.0388 14.9308 13.4835 16.033L14.6121 13.7757C14.8347 13.3306 15.3507 13.1169 15.8228 13.2743L20.3162 14.7721C20.7246 14.9082 21 15.2903 21 15.7208V19C21 20.1046 20.1046 21 19 21H18C9.71573 21 3 14.2843 3 6V5Z" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>بيانات التواصل</span>
            </a>
        </li>
        @endif

        {{-- قصتنا --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('story')))
        <li>
            <a href="{{ route('admin.story.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>قصتنا</span>
            </a>
        </li>
        @endif

        {{-- رسالتنا ورؤيتنا --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('mvv')))
        <li>
            <a href="{{ route('admin.mvv.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>رسالتنا ورؤيتنا</span>
            </a>
        </li>
        @endif

        {{-- فريق العمل --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('team')))
        <li>
            <a href="{{ route('admin.team.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2"/>
                    <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>فريق العمل</span>
            </a>
        </li>
        @endif

        {{-- رحلتنا --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('journey')))
        <li>
            <a href="{{ route('admin.journey.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" stroke="currentColor" stroke-width="2"/>
                    <polyline points="9 22 9 12 15 12 15 22" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>رحلتنا</span>
            </a>
        </li>
        @endif

        {{-- شركاءنا --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('partners')))
        <li>
            <a href="{{ route('admin.partners.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2"/>
                    <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>شركاءنا</span>
            </a>
        </li>
        @endif

        {{-- الشهادات والجوائز --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('certificates')))
        <li>
            <a href="{{ route('admin.certificates.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 15l-3 3m0 0l-3-3m3 3V8m-6 10h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>الشهادات والجوائز</span>
            </a>
        </li>
        @endif

        {{-- بيانات الاتصال 2 --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('contact2')))
        <li>
            <a href="{{ route('admin.contact2.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>بيانات الاتصال 2</span>
            </a>
        </li>
        @endif

        {{-- الأسئلة الشائعة --}}
        @if($admin && ($admin->is_super_admin || $admin->hasPermission('faqs')))
        <li>
            <a href="{{ route('admin.faqs.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" stroke="currentColor" stroke-width="2"/>
                    <line x1="12" y1="17" x2="12.01" y2="17" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>الأسئلة الشائعة</span>
            </a>
        </li>
        @endif

        {{-- الصلاحيات --}}
        @if($admin && $admin->is_super_admin)
        <li>
            <a href="{{ route('admin.admins.index') }}" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7v10c0 2.97 2.16 5.37 5 5.82V17l2-1.5V17l2-1.5V17l2-1.5V17l2-1.5V17l2-1.5V17.82c2.84-.45 5-2.85 5-5.82V7l-10-5z" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>الصلاحيات</span>
            </a>
        </li>
        @endif

        {{-- تسجيل الخروج --}}
        <li class="sidebar-logout">
            <a href="/admin/logout" class="sidebar-link sidebar-link-logout">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 21H5C3.89543 21 3 20.1046 3 19V5C3 3.89543 3.89543 3 5 3H9M16 8L20 12M20 12L16 16M20 12H9" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>تسجيل الخروج</span>
            </a>
        </li>
    </ul>
</div>

<style>
/* الأساسيات */
.sidebar {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: white;
    width: 280px;
    height: 100vh;
    position: fixed;
    top: 0;
    right: 0;
    padding: 20px 0;
    box-shadow: -4px 0 20px rgba(0,0,0,0.15);
    z-index: 1000;
    overflow-y: auto;
    overflow-x: hidden;
    transition: transform 0.3s ease-in-out;
}

.sidebar-header {
    text-align: center;
    padding: 0 20px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
    position: relative;
}

.sidebar-header h2 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
}

.sidebar-header p {
    margin: 8px 0 0 0;
    opacity: 0.9;
    font-size: 14px;
}

.sidebar-close {
    display: none;
    position: absolute;
    left: 15px;
    top: 10px;
    background: rgba(255,255,255,0.2);
    border: none;
    border-radius: 8px;
    width: 36px;
    height: 36px;
    cursor: pointer;
    color: white;
    transition: 0.3s;
}

.sidebar-close:hover {
    background: rgba(255,255,255,0.3);
}

.sidebar-menu {
    list-style: none;
    padding: 20px 10px;
    margin: 0;
}

.sidebar-menu li {
    margin: 8px 0;
}

.sidebar-link {
    color: white;
    text-decoration: none;
    background: rgba(255,255,255,0.1);
    padding: 12px 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.3s ease;
    font-weight: 500;
    font-size: 15px;
}

.sidebar-link:hover {
    background: rgba(255,255,255,0.25);
    transform: translateX(-5px);
}

.sidebar-link svg {
    flex-shrink: 0;
}

.sidebar-link span {
    flex: 1;
}

/* Active state */
.sidebar-link[href="{{ request()->url() }}"] {
    background: rgba(255,255,255,0.3) !important;
    box-shadow: 0 4px 15px rgba(255,255,255,0.2);
    font-weight: 600;
}

/* تسجيل الخروج */
.sidebar-logout {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid rgba(255,255,255,0.2);
}

.sidebar-link-logout {
    background: rgba(239,68,68,0.3) !important;
    color: #fee2e2 !important;
}

.sidebar-link-logout:hover {
    background: rgba(239,68,68,0.5) !important;
}

/* Scrollbar */
.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.5);
}

/* زرار القائمة والخلفية - مخفية افتراضياً */
.sidebar-toggle,
.sidebar-overlay {
    display: none;
}

/* تابلت - 1024px */
@media (max-width: 1024px) {
    .sidebar {
        width: 260px;
    }
    
    .sidebar-link {
        font-size: 14px;
        padding: 10px 16px;
    }
}

/* موبايل - 768px */
@media (max-width: 768px) {
    .sidebar-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        position: fixed;
        top: 15px;
        right: 15px;
        z-index: 1001;
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        border: none;
        border-radius: 12px;
        width: 50px;
        height: 50px;
        color: white;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
        transition: 0.3s;
    }
    
    .sidebar-toggle:hover {
        background: linear-gradient(135deg, #4338ca, #4f46e5);
        transform: scale(1.05);
    }
    
    .sidebar-overlay {
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .sidebar {
        transform: translateX(100%);
        width: 280px;
        box-shadow: -8px 0 30px rgba(0,0,0,0.3);
    }
    
    .sidebar.active {
        transform: translateX(0);
    }
    
    .sidebar-close {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .content {
        margin-right: 0 !important;
        padding: 80px 15px 15px !important;
    }
}

/* موبايل صغير - 480px */
@media (max-width: 480px) {
    .sidebar {
        width: 100%;
        max-width: 320px;
    }
    
    .sidebar-toggle {
        width: 45px;
        height: 45px;
        top: 10px;
        right: 10px;
    }
    
    .sidebar-link {
        font-size: 14px;
        padding: 10px 15px;
    }
    
    .sidebar-header h2 {
        font-size: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    // فتح القائمة
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }
    
    // إغلاق القائمة
    function closeSidebar() {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    if (sidebarClose) {
        sidebarClose.addEventListener('click', closeSidebar);
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }
    
    // إغلاق عند الضغط على أي رابط (للموبايل)
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                setTimeout(closeSidebar, 200);
            }
        });
    });
    
    // إغلاق عند الضغط على ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('active')) {
            closeSidebar();
        }
    });
});
</script>
