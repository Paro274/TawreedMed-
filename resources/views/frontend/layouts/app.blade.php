<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'توريد ميد - منصة ربط الموردين')</title>
    
    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    {{-- Swiper CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    {{-- Bootstrap CSS (للـ forms والـ responsive) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    {{-- Select2 CSS (للـ dropdowns) --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    {{-- CSS الأساسي --}}
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    
    {{-- CSS خاص بكل صفحة --}}
    @stack('styles')
</head>
<body>
    {{-- Loading Spinner --}}
    <div id="loadingSpinner" class="loading-spinner d-none">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">جاري التحميل...</span>
        </div>
        <p class="mt-2">جاري التحميل...</p>
    </div>
    
    {{-- Flash Messages --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'نجح العمل!',
                    text: '{{ session("success") }}',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#28a745'
                });
            });
        </script>
    @endif
    
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ!',
                    text: '{{ session("error") }}',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#dc3545'
                });
            });
        </script>
    @endif
    
    @if (session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'معلومات',
                    text: '{{ session("info") }}',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#17a2b8'
                });
            });
        </script>
    @endif
    
    {{-- Header/Navbar --}}
    @include('frontend.partials.navbar')
    
    {{-- Main Content --}}
    <main class="main-content py-4">
        @yield('content')
    </main>
    
    {{-- Footer --}}
    @include('frontend.partials.footer')
    
    {{-- Back to Top Button --}}
    <button id="backToTop" class="back-to-top btn btn-primary rounded-circle shadow" style="position: fixed; bottom: 20px; left: 20px; display: none; z-index: 1000;" title="العودة للأعلى">
        <i class="fas fa-chevron-up"></i>
    </button>
    
    {{-- Scripts --}}
    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Swiper JS --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- Select2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    {{-- تفعيل السلايدر --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Swiper sliders
            const swipers = document.querySelectorAll('.products-swiper');
            swipers.forEach(function(swiperEl) {
                new Swiper(swiperEl, {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    navigation: {
                        nextEl: swiperEl.querySelector('.swiper-button-next'),
                        prevEl: swiperEl.querySelector('.swiper-button-prev'),
                    },
                    breakpoints: {
                        640: { slidesPerView: 2 },
                        768: { slidesPerView: 3 },
                        1024: { slidesPerView: 4 }
                    }
                });
            });
            
            // Back to Top functionality
            const backToTop = document.getElementById('backToTop');
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTop.style.display = 'block';
                } else {
                    backToTop.style.display = 'none';
                }
            });
            
            backToTop.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            
            // CSRF Token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
    
    {{-- JS الأساسي --}}
    <script src="{{ asset('frontend/js/script.js') }}"></script>
    
    {{-- JS خاص بكل صفحة --}}
    @stack('scripts')
</body>
</html>
