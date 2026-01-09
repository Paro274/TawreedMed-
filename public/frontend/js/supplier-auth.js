document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.switch-form, .forgot-link').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const formType = e.target.dataset.form;
            // أخفي كل النماذج (نموذج الدخول/التسجيل/الاستعادة)
            document.querySelectorAll('form').forEach(f => f.style.display = 'none');
            // أظهر النموذج المناسب
            if (formType === 'login') document.getElementById('loginForm').style.display = 'flex';
            if (formType === 'register') document.getElementById('registerForm').style.display = 'flex';
            if (formType === 'forgot') document.getElementById('forgotForm').style.display = 'flex';
        });
    });
});
