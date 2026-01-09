<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h4>عن توريد ميد</h4>
                <p>منصة رائدة في ربط الموردين بتجار الجملة في المجالات الطبية والتجميلية.</p>
            </div>
            <div class="footer-section">
                <h4>روابط سريعة</h4>
                <ul>
                    <li><a href="{{ route('frontend.home') }}">الرئيسية</a></li>
                    <li><a href="{{ route('frontend.products') }}">المنتجات</a></li>
                    <li><a href="{{ route('frontend.companies.index') }}">الشركات</a></li>
                    <li><a href="{{ route('frontend.about') }}">من نحن</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>الدعم</h4>
                <ul>
                    <li><a href="{{ route('frontend.faq') }}">الأسئلة الشائعة</a></li>
                    <li><a href="{{ route('frontend.privacy') }}">سياسة الخصوصية</a></li>
                    <li><a href="{{ route('frontend.terms') }}">الشروط والأحكام</a></li>
                    <li><a href="{{ route('frontend.contact') }}">تواصل معنا</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>النشرة البريدية</h4>
                <p>اشترك ليصلك كل جديد</p>
                <form class="newsletter-form" action="{{ route('frontend.newsletter.subscribe') }}" method="POST">
                    @csrf
                    <input type="email" name="email" placeholder="بريدك الإلكتروني" required>
                    <button type="submit" class="btn btn-primary">اشترك</button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} توريد ميد. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</footer>
