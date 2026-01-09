/**
 * Main Script for Tawreed Med
 * Includes: Slider, Cart, Navigation (Mobile/Desktop), and Forms
 */

document.addEventListener('DOMContentLoaded', function () {
    console.log('🚀 Script Loaded Successfully');

    // ==================================================
    // 1. Header Navigation & Hamburger Menu (Mobile/Desktop)
    // ==================================================
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');
    const overlay = document.getElementById('mobileMenuOverlay');
    const isMobile = () => window.innerWidth <= 991;

    // Toggle Menu Function
    function toggleMenu() {
        const isActive = hamburger.classList.contains('active');
        
        if (!isActive) {
            hamburger.classList.add('active');
            navMenu.classList.add('active');
            if (overlay) overlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        } else {
            closeMenu();
        }
    }

    // Close Menu Function
    function closeMenu() {
        if (hamburger) hamburger.classList.remove('active');
        if (navMenu) navMenu.classList.remove('active');
        if (overlay) overlay.classList.remove('active');
        document.body.style.overflow = ''; // Restore scrolling
        
        // Close all active dropdowns
        document.querySelectorAll('.dropdown.active').forEach(d => d.classList.remove('active'));
    }

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleMenu();
        });

        // Close when clicking overlay
        if (overlay) {
            overlay.addEventListener('click', closeMenu);
        }

        // Close when clicking outside (Fallback)
        document.addEventListener('click', (e) => {
            if (navMenu.classList.contains('active') && 
                !navMenu.contains(e.target) && 
                !hamburger.contains(e.target)) {
                closeMenu();
            }
        });

        // Close on Resize if switching to Desktop
        window.addEventListener('resize', () => {
            if (!isMobile() && navMenu.classList.contains('active')) {
                closeMenu();
            }
        });
    }

    // ==================================================
    // 2. Dropdown Logic (Mobile Click / Desktop Hover)
    // ==================================================
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');

        if (toggle) {
            // Mobile: Click to toggle
            toggle.addEventListener('click', (e) => {
                if (isMobile()) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Close other dropdowns
                    dropdowns.forEach(d => {
                        if (d !== dropdown) d.classList.remove('active');
                    });

                    dropdown.classList.toggle('active');
                }
            });

            // Desktop: Hover effects
            dropdown.addEventListener('mouseenter', () => {
                if (!isMobile()) dropdown.classList.add('hover');
            });

            dropdown.addEventListener('mouseleave', () => {
                if (!isMobile()) dropdown.classList.remove('hover');
            });
        }
    });

    // Close menu when clicking links inside
    document.querySelectorAll('.nav-link:not(.dropdown-toggle), .dropdown-link').forEach(link => {
        link.addEventListener('click', () => {
            // Allow navigation, then close menu
            if (isMobile()) closeMenu();
        });
    });


    // ==================================================
    // 3. Hero Slider Logic
    // ==================================================
    const slides = document.querySelectorAll('.slide');
    let currentSlideIndex = 1;
    let slideInterval;

    function showSlides(n) {
        if (!slides.length) return;
        const dots = document.querySelectorAll('.dot');

        if (n > slides.length) currentSlideIndex = 1;
        if (n < 1) currentSlideIndex = slides.length;

        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));

        slides[currentSlideIndex - 1].classList.add('active');
        if (dots[currentSlideIndex - 1]) {
            dots[currentSlideIndex - 1].classList.add('active');
        }
    }

    function changeSlide(n) {
        clearInterval(slideInterval);
        showSlides(currentSlideIndex += n);
        startAutoSlide();
    }

    function currentSlide(n) {
        clearInterval(slideInterval);
        showSlides(currentSlideIndex = n);
        startAutoSlide();
    }

    function startAutoSlide() {
        if (slides.length > 0) {
            clearInterval(slideInterval);
            slideInterval = setInterval(() => {
                currentSlideIndex++;
                showSlides(currentSlideIndex);
            }, 5000);
        }
    }

    if (slides.length > 0) {
        showSlides(currentSlideIndex);
        startAutoSlide();

        const prevBtn = document.querySelector('.slider-btn.prev');
        const nextBtn = document.querySelector('.slider-btn.next');
        const dots = document.querySelectorAll('.dot');

        if (prevBtn) prevBtn.addEventListener('click', () => changeSlide(-1));
        if (nextBtn) nextBtn.addEventListener('click', () => changeSlide(1));
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => currentSlide(index + 1));
        });
    }


    // ==================================================
    // 4. Products Swiper Initialization
    // ==================================================
    const swipers = document.querySelectorAll('.products-swiper');
    swipers.forEach(swiperEl => {
        if (typeof Swiper !== 'undefined') {
            new Swiper(swiperEl, {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                speed: 500,
                navigation: {
                    nextEl: swiperEl.querySelector('.swiper-button-next'),
                    prevEl: swiperEl.querySelector('.swiper-button-prev'),
                },
                breakpoints: {
                    640: { slidesPerView: 2 },
                    768: { slidesPerView: 3 },
                    1024: { slidesPerView: 4 },
                },
            });
        }
    });


    // ==================================================
    // 5. Utility Functions (Scroll, Forms, etc.)
    // ==================================================
    
    // Smooth Scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Scroll to Top Button
    const scrollToTopBtn = document.getElementById('scrollToTop');
    if (scrollToTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('show');
            } else {
                scrollToTopBtn.classList.remove('show');
            }
        });

        scrollToTopBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Contact Form Handler
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            // e.preventDefault(); // Uncomment if using AJAX
            // alert('شكراً لتواصلك معنا!');
        });
    }

    // Newsletter Form Handler
    const newsletterForm = document.getElementById('newsletterForm');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function (e) {
            // e.preventDefault(); // Uncomment if using AJAX
            const email = this.querySelector('input[type="email"]').value;
            if (email) {
                // alert('تم التسجيل بنجاح!');
            }
        });
    }
    
    // Category Columns Click
    document.querySelectorAll('.category-column').forEach(column => {
        column.addEventListener('click', () => {
            const category = column.dataset.category;
            if (category) {
                // window.location.href = '/products?category=' + category;
                console.log('Category clicked:', category);
            }
        });
    });
});


// ==================================================
// 6. Global Cart Functions (Outside DOMContentLoaded)
// ==================================================

function updateCartCounter(count) {
    const cartBadges = document.querySelectorAll('.cart-count');
    cartBadges.forEach(badge => {
        badge.textContent = count;
        if (count > 0) {
            badge.classList.remove('bg-secondary');
            badge.classList.add('bg-danger');
        } else {
            badge.classList.remove('bg-danger');
            badge.classList.add('bg-secondary');
        }
    });
}

function addToCart(productId, btnElement) {
    // Determine quantity and button element
    let quantity = 1;
    let btn = null;

    if (typeof btnElement === 'number') {
        quantity = btnElement;
    } else if (btnElement && btnElement.nodeType) {
        btn = btnElement;
    }

    // UI Loading State
    let originalHtml = '';
    if (btn) {
        originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    }

    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfTokenMeta) {
        console.error('CSRF Token not found');
        return;
    }

    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    formData.append('_token', csrfTokenMeta.getAttribute('content'));

    return fetch('/cart/add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCounter(data.count);
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'تم بنجاح!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ!',
                    text: data.message
                });
            } else {
                alert(data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'خطأ!',
                text: 'حدث خطأ أثناء الاتصال بالسيرفر'
            });
        }
    })
    .finally(() => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    });
}
