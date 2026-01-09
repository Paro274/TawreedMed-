<!-- Contact Info Cards -->
<section class="contact-info-section">
    <div class="container">
        <div class="contact-cards-grid">
            
            <!-- 📞 كارت الهواتف -->
            <div class="contact-info-card">
                <div class="card-icon">📞</div>
                <h3>اتصل بنا</h3>
                
                @if($contact)
                    {{-- الهاتف الأول --}}
                    @if($contact->phone1)
                        <p class="contact-label">{{ $contact->phone1_title ?? 'الرئيسي' }}</p>
                        <a href="tel:{{ $contact->phone1 }}" dir="ltr">{{ $contact->phone1 }}</a>
                    @endif

                    {{-- الهاتف الثاني --}}
                    @if($contact->phone2)
                        <p class="contact-label mt-3">{{ $contact->phone2_title ?? 'المبيعات' }}</p>
                        <a href="tel:{{ $contact->phone2 }}" dir="ltr">{{ $contact->phone2 }}</a>
                    @endif

                    {{-- الهاتف الثالث --}}
                    @if($contact->phone3)
                        <p class="contact-label mt-3">{{ $contact->phone3_title ?? 'الدعم الفني' }}</p>
                        <a href="tel:{{ $contact->phone3 }}" dir="ltr">{{ $contact->phone3 }}</a>
                    @endif
                @else
                    <p class="text-muted">لا توجد بيانات متاحة حالياً</p>
                @endif
            </div>

            <!-- ✉️ كارت الإيميلات -->
            <div class="contact-info-card">
                <div class="card-icon">✉️</div>
                <h3>راسلنا</h3>
                
                @if($contact)
                    @if($contact->email1)
                        <p class="contact-label">{{ $contact->email1_title ?? 'الرئيسي' }}</p>
                        <a href="mailto:{{ $contact->email1 }}">{{ $contact->email1 }}</a>
                    @endif

                    @if($contact->email2)
                        <p class="contact-label mt-3">{{ $contact->email2_title ?? 'المبيعات' }}</p>
                        <a href="mailto:{{ $contact->email2 }}">{{ $contact->email2 }}</a>
                    @endif

                    @if($contact->email3)
                        <p class="contact-label mt-3">{{ $contact->email3_title ?? 'الدعم' }}</p>
                        <a href="mailto:{{ $contact->email3 }}">{{ $contact->email3 }}</a>
                    @endif
                @else
                    <p class="text-muted">لا توجد بيانات متاحة حالياً</p>
                @endif
            </div>

            <!-- 📍 كارت العنوان -->
            <div class="contact-info-card">
                <div class="card-icon">📍</div>
                <h3>{{ $contact && $contact->address_title ? $contact->address_title : 'العنوان' }}</h3>
                
                @if($contact && $contact->address_text)
                    <div class="address-text">{!! $contact->address_text !!}</div>
                @else
                    <p class="text-muted">لا توجد بيانات متاحة حالياً</p>
                @endif
            </div>

            <!-- 🌐 كارت السوشيال ميديا -->
            <div class="contact-info-card">
                <div class="card-icon">🌐</div>
                <h3>تابعنا على</h3>
                <div class="social-links-grid">
                    <a href="https://facebook.com" target="_blank" class="social-link">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://twitter.com" target="_blank" class="social-link">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                    <a href="https://linkedin.com" target="_blank" class="social-link">
                        <i class="fab fa-linkedin-in"></i> LinkedIn
                    </a>
                    <a href="https://instagram.com" target="_blank" class="social-link">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
