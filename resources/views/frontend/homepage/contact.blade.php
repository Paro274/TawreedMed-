<!-- Contact -->
<section id="contact" class="contact" style="background: url('{{ asset('frontend/images/contact-bg.png') }}') center/cover no-repeat; position: relative;">
    <!-- طبقة شفافة للتحكم في الوضوح -->
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.95);"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <h2 class="section-title">تواصل معنا</h2>
        <p class="section-subtitle">نحن هنا لمساعدتك في أي وقت</p>
        
        @if(session('success'))
            <div class="alert alert-success" style="background: #10b981; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                {{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger" style="background: #ef4444; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-right: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="contact-grid">
            <div class="contact-info">
                <h3>معلومات الاتصال</h3>
                <div class="contact-item">
                    <span class="contact-icon">📞</span>
                    <div>
                        <strong>الهاتف</strong>
                        @if(isset($contact) && $contact->phone)
                            <p dir="ltr" style="text-align:right">{{ $contact->phone }}</p>
                        @else
                            <p>01004629908</p>
                            <p>01111313610</p>
                        @endif
                    </div>
                </div>
                <div class="contact-item">
                    <span class="contact-icon">✉️</span>
                    <div>
                        <strong>البريد الإلكتروني</strong>
                        <p>{{ $contact->email ?? 'tawreed.med@gmail.com' }}</p>
                    </div>
                </div>
                <div class="contact-item">
                    <span class="contact-icon">📍</span>
                    <div>
                        <strong>العنوان</strong>
                        <p>{{ $contact->address ?? 'القاهرة، مصر' }}</p>
                    </div>
                </div>
                <div class="social-links">
                    <a href="https://facebook.com" target="_blank" class="social-link">
                        <i class="fab fa-facebook-f"></i>
                        Facebook
                    </a>
                    <a href="https://twitter.com" target="_blank" class="social-link">
                        <i class="fab fa-twitter"></i>
                        Twitter
                    </a>
                    <a href="https://linkedin.com" target="_blank" class="social-link">
                        <i class="fab fa-linkedin-in"></i>
                        LinkedIn
                    </a>
                    <a href="https://instagram.com" target="_blank" class="social-link">
                        <i class="fab fa-instagram"></i>
                        Instagram
                    </a>
                </div>
            </div>
            <div class="contact-form">
                <form action="{{ route('frontend.contact.send') }}" method="POST">
                    @csrf
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="الاسم الكامل" required>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="البريد الإلكتروني" required>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="رقم الهاتف" required>
                    <input type="text" name="subject" value="{{ old('subject') }}" placeholder="عنوان الرسالة" required>
                    <textarea name="message" placeholder="رسالتك" rows="5" required>{{ old('message') }}</textarea>
                    <button type="submit" class="btn btn-primary btn-full">إرسال الرسالة</button>
                </form>
            </div>
        </div>
    </div>
</section>
