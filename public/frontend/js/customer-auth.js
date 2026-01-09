// Form Switching Functionality
document.addEventListener('DOMContentLoaded', function() {
    const forms = {
        login: document.getElementById('loginForm'),
        register: document.getElementById('registerForm'),
        forgot: document.getElementById('forgotForm')
    };
    
    const switchLinks = document.querySelectorAll('.switch-form, .forgot-link');

    const activateForm = (targetForm) => {
        const fallback = targetForm && forms[targetForm] ? targetForm : 'login';

        Object.entries(forms).forEach(([key, form]) => {
            if (!form) {
                return;
            }

            const isActive = key === fallback;
            form.style.display = isActive ? 'flex' : 'none';
            form.classList.toggle('active', isActive);

            if (isActive) {
                form.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });

        updatePageTitle(fallback);
    };
    
    switchLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            activateForm(this.getAttribute('data-form'));
        });
    });

    const defaultTab = document.body?.dataset?.activeTab || 'login';
    activateForm(defaultTab);
    
    // Form submission handlers
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('emailLogin').value;
            const password = document.getElementById('passwordLogin').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('يرجى إدخال البريد الإلكتروني وكلمة المرور');
                return false;
            }
            
            // Add loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري تسجيل الدخول...';
            submitBtn.disabled = true;
            
            // Reset after 3 seconds if needed
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });
    }
    
    // Forgot password form
    const forgotForm = document.getElementById('forgotForm');
    if (forgotForm) {
        forgotForm.addEventListener('submit', function(e) {
            const email = this.querySelector('input[name="email"]').value;
            
            if (!email) {
                e.preventDefault();
                alert('يرجى إدخال البريد الإلكتروني');
                return false;
            }
            
            // Add loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';
            submitBtn.disabled = true;
        });
    }
    
    // Enter key submission
    document.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const activeForm = document.querySelector('.active');
            if (activeForm && activeForm.tagName === 'FORM') {
                const submitBtn = activeForm.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    submitBtn.click();
                }
            }
        }
    });
    
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
    });
});

function updatePageTitle(formType) {
    const titles = {
        login: 'تسجيل الدخول - عميل',
        register: 'إنشاء حساب - عميل',
        forgot: 'استعادة كلمة المرور - عميل'
    };
    
    document.title = titles[formType] || 'تسجيل الدخول - عميل';
}

// Password visibility toggle (if needed)
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    if (input) {
        if (input.type === 'password') {
            input.type = 'text';
        } else {
            input.type = 'password';
        }
    }
}

// Phone number formatting for register form (if needed)
function formatPhoneNumber(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length > 11) {
        value = value.substring(0, 11);
    }
    if (value.length >= 3 && !value.startsWith('01')) {
        value = '01' + value.substring(1);
    }
    input.value = value;
}
