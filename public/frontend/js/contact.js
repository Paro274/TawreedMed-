// js/contact.js

// Contact Form Validation and Submission
document.addEventListener('DOMContentLoaded', function() {
    const contactFormElement = document.getElementById('contactForm');
    const formMessage = document.getElementById('formMessage');

    if (contactFormElement) {
        contactFormElement.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                company: document.getElementById('company').value,
                department: document.getElementById('department').value,
                accountType: document.getElementById('accountType').value,
                subject: document.getElementById('subject').value,
                message: document.getElementById('message').value
            };
            
            // Validate phone number (Egyptian format)
            const phoneRegex = /^(010|011|012|015)\d{8}$/;
            if (!phoneRegex.test(formData.phone.replace(/[\s\-\+]/g, ''))) {
                showMessage('error', 'من فضلك أدخل رقم هاتف مصري صحيح');
                return;
            }
            
            // Simulate form submission
            showMessage('success', 'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.');
            contactFormElement.reset();
            
            // Log form data (in production, send to server)
            console.log('Form submitted:', formData);
            
            // Scroll to message
            if (formMessage) {
                formMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }

    function showMessage(type, text) {
        if (formMessage) {
            formMessage.className = `form-message ${type}`;
            formMessage.textContent = text;
            formMessage.style.display = 'block';
            
            setTimeout(() => {
                formMessage.style.display = 'none';
            }, 5000);
        }
    }

    // Newsletter Form
    const newsletterFormElement = document.getElementById('newsletterForm');
    if (newsletterFormElement) {
        newsletterFormElement.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            alert('شكراً لاشتراكك في النشرة البريدية!');
            this.reset();
            console.log('Newsletter subscription:', email);
        });
    }

    // FAQ Accordion
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function(e) {
            e.preventDefault();
            
            const faqItem = this.closest('.faq-item');
            const isActive = faqItem.classList.contains('active');
            
            // Close all FAQ items
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Open clicked item if it wasn't active
            if (!isActive) {
                faqItem.classList.add('active');
            }
        });
    });

    // Animate elements on scroll
    const animateOnScroll = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px'
    });

    // Apply animation to cards
    document.querySelectorAll('.contact-info-card, .social-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        animateOnScroll.observe(card);
    });

    // Form input animation
    const formInputs = document.querySelectorAll('.form-group input, .form-group select, .form-group textarea');
    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
            this.parentElement.style.transition = 'transform 0.3s';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });

    // Smooth scroll to map
    const viewMapBtn = document.querySelector('.view-map');
    if (viewMapBtn) {
        viewMapBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('#map').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    }

    // Phone number formatting
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            e.target.value = value;
        });
    }

    // Email validation on blur
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.style.borderColor = '#ef4444';
            } else {
                this.style.borderColor = '#e2e8f0';
            }
        });
    }

    // Character counter for message textarea
    const messageTextarea = document.getElementById('message');
    if (messageTextarea) {
        const charCountLabel = document.createElement('span');
        charCountLabel.style.cssText = 'font-size: 13px; color: #64748b; margin-top: 5px; display: block;';
        messageTextarea.parentElement.appendChild(charCountLabel);
        
        messageTextarea.addEventListener('input', function() {
            const count = this.value.length;
            charCountLabel.textContent = `${count} حرف`;
            
            if (count > 500) {
                charCountLabel.style.color = '#ef4444';
            } else {
                charCountLabel.style.color = '#64748b';
            }
        });
    }

    // Social media card click tracking
    document.querySelectorAll('.social-card').forEach(card => {
        card.addEventListener('click', function(e) {
            e.preventDefault();
            const platform = this.querySelector('h3').textContent;
            console.log(`Social media clicked: ${platform}`);
            alert(`سيتم فتح صفحتنا على ${platform} قريباً!`);
        });
    });

    // Department selection auto-suggestions
    const departmentSelect = document.getElementById('department');
    const subjectInput = document.getElementById('subject');

    if (departmentSelect && subjectInput) {
        departmentSelect.addEventListener('change', function() {
            const suggestions = {
                'sales': 'استفسار عن الأسعار والعروض',
                'support': 'مشكلة تقنية في المنصة',
                'partnership': 'طلب شراكة تجارية',
                'general': 'استفسار عام',
                'complaint': 'شكوى'
            };
            
            if (suggestions[this.value] && !subjectInput.value) {
                subjectInput.placeholder = suggestions[this.value];
            }
        });
    }

    console.log('صفحة اتصل بنا - تم تحميل جميع السكريبتات بنجاح ✓');
});
