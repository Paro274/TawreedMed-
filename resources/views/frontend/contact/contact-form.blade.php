<!-- Contact Form Section -->
<section class="contact-form-section">
    <div class="container">
        <div class="form-header">
            <h2>أرسل لنا رسالة</h2>
            <p>املأ النموذج وسنتواصل معك في أقرب وقت ممكن</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="color:green;">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger" style="color:red;">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin:0; padding-right:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="contact-form-wrapper">
            <form action="{{ route('frontend.contact.send') }}" method="POST" class="contact-form">
                @csrf
                <div class="form-group">
                    <label for="name">الاسم الكامل *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="email">البريد الإلكتروني *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="phone">رقم الهاتف *</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required>
                </div>

                <div class="form-group">
                    <label for="subject">عنوان الرسالة *</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required>
                </div>

                <div class="form-group">
                    <label for="accountType">نوع الحساب *</label>
                    <select id="accountType" name="accountType" required>
                        <option value="">اختر النوع</option>
                        <option value="supplier">مورد</option>
                        <option value="customer">عميل</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">نص الرسالة *</label>
                    <textarea id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-large">إرسال</button>
            </form>
        </div>
    </div>
</section>
