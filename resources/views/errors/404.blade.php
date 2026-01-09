<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصفحة غير موجودة - توريد ميد</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }
        
        .error-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            padding: 60px 40px;
            text-align: center;
            max-width: 600px;
            width: 100%;
            animation: slideInUp 0.8s ease-out;
        }
        
        .error-icon {
            font-size: 8rem;
            color: #ff6b6b;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }
        
        .error-title {
            font-size: 3rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
            line-height: 1.2;
        }
        
        .error-subtitle {
            font-size: 1.25rem;
            color: #7f8c8d;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .search-box {
            position: relative;
            max-width: 400px;
            margin: 0 auto 30px;
        }
        
        .search-box input {
            width: 100%;
            padding: 15px 50px 15px 20px;
            border: 2px solid #e9ecef;
            border-radius: 50px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fff;
        }
        
        .search-box input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .search-btn {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: #667eea;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .search-btn:hover {
            background: #5a67d8;
            transform: translateY(-50%) scale(1.1);
        }
        
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            margin: 10px;
        }
        
        .btn-home:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }
        
        .quick-links {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e9ecef;
        }
        
        .quick-links h5 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .quick-links a {
            display: block;
            color: #667eea;
            text-decoration: none;
            padding: 8px 0;
            transition: color 0.3s ease;
            border-radius: 5px;
            padding: 10px 15px;
        }
        
        .quick-links a:hover {
            color: #5a67d8;
            background: #f8f9ff;
        }
        
        .footer-text {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #7f8c8d;
            font-size: 14px;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .error-container {
                padding: 40px 20px;
                margin: 20px;
            }
            
            .error-icon {
                font-size: 5rem;
            }
            
            .error-title {
                font-size: 2.5rem;
            }
            
            .error-subtitle {
                font-size: 1.1rem;
            }
            
            .btn-home {
                display: block;
                width: 100%;
                justify-content: center;
                margin: 10px 0;
            }
            
            .search-box {
                max-width: 100%;
            }
        }
        
        @media (max-width: 480px) {
            .error-title {
                font-size: 2rem;
            }
            
            .error-icon {
                font-size: 4rem;
            }
            
            .error-container {
                padding: 30px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <!-- Error Icon -->
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        
        <!-- Error Title -->
        <h1 class="error-title">عذرًا! الصفحة غير موجودة</h1>
        
        <!-- Error Subtitle -->
        <p class="error-subtitle">
            يبدو أن الصفحة التي تبحث عنها غير موجودة أو تم نقلها إلى مكان آخر.
            لا تقلق، يمكنك العودة للصفحة الرئيسية أو البحث عما تريد.
        </p>
        
        <!-- Search Box -->
        <div class="search-box">
            <input type="text" placeholder="ابحث عن ما تريد..." id="searchInput">
            <button class="search-btn" onclick="performSearch()">
                <i class="fas fa-search"></i>
            </button>
        </div>
        
        <!-- Action Buttons -->
        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
            <a href="{{ route('frontend.home') }}" class="btn-home">
                <i class="fas fa-home"></i>
                العودة للصفحة الرئيسية
            </a>
            
            <a href="javascript:history.back()" class="btn-home">
                <i class="fas fa-arrow-right"></i>
                العودة للصفحة السابقة
            </a>
        </div>
        
        <!-- Quick Links -->
        <div class="quick-links">
            <h5><i class="fas fa-th-large me-2"></i>روابط سريعة</h5>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-3 mb-2">
                    <a href="{{ route('frontend.home') }}">
                        <i class="fas fa-home me-2"></i>الرئيسية
                    </a>
                </div>
                <div class="col-md-6 col-lg-3 mb-2">
                    <a href="{{ route('frontend.products.index') }}">
                        <i class="fas fa-box me-2"></i>المنتجات
                    </a>
                </div>
                <div class="col-md-6 col-lg-3 mb-2">
                    <a href="{{ route('frontend.about') }}">
                        <i class="fas fa-info-circle me-2"></i>من نحن
                    </a>
                </div>
                <div class="col-md-6 col-lg-3 mb-2">
                    <a href="{{ route('frontend.contact.index') }}">
                        <i class="fas fa-envelope me-2"></i>تواصل معنا
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer-text">
            <p class="mb-0">
                إذا كنت تعتقد أن هذا خطأ، يرجى 
                <a href="{{ route('frontend.contact.index') }}" class="text-primary">التواصل معنا</a>
            </p>
            <small class="text-muted">
                © {{ date('Y') }} توريد ميد. جميع الحقوق محفوظة.
            </small>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Search functionality
        function performSearch() {
            const query = document.getElementById('searchInput').value.trim();
            if (query) {
                window.location.href = `/search?q=${encodeURIComponent(query)}`;
            } else {
                // Focus on input and show placeholder
                document.getElementById('searchInput').focus();
            }
        }
        
        // Enter key search
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
        
        // Auto search after typing (debounced)
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length >= 3) {
                searchTimeout = setTimeout(() => {
                    window.location.href = `/search?q=${encodeURIComponent(query)}`;
                }, 800);
            }
        });
        
        // Add loading animation to search button
        document.querySelector('.search-btn').addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-search"></i>';
            }, 1000);
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        // Observe elements for animation
        document.querySelectorAll('.quick-links a').forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            observer.observe(el);
        });
        
        // Add click animation to buttons
        document.querySelectorAll('.btn-home').forEach(btn => {
            btn.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });
        
        // Easter egg: Konami code for fun
        let konamiCode = [];
        const konamiSequence = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'KeyB', 'KeyA'];
        
        document.addEventListener('keydown', function(e) {
            konamiCode.push(e.code);
            if (konamiCode.length > konamiSequence.length) {
                konamiCode.shift();
            }
            
            if (JSON.stringify(konamiCode) === JSON.stringify(konamiSequence)) {
                // Show fun message
                alert('🎉 كود الكونامي! أنت محترف! 🎮\n\nمرحباً بك في عالم توريد ميد! 🚀');
                konamiCode = [];
            }
        });
    </script>
</body>
</html>
