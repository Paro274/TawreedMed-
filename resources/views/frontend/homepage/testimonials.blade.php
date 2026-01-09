<!-- Testimonials -->
<section id="testimonials" class="testimonials">
    <div class="container">
        <h2 class="section-title">ماذا يقول عملاؤنا</h2>
        <p class="section-subtitle">آراء حقيقية من تجار وموردين يستخدمون منصة توريد ميد</p>
        <div class="testimonials-grid">
            @forelse($testimonials as $testimonial)
                <div class="testimonial-card">
                    <div class="testimonial-stars">
                        @for($i = 0; $i < $testimonial->rating; $i++)
                            ⭐
                        @endfor
                    </div>
                    <p class="testimonial-text">"{{ $testimonial->review }}"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            @if($testimonial->image)
                                <img src="{{ asset($testimonial->image) }}" alt="{{ $testimonial->name }}">
                            @else
                                {{ mb_substr($testimonial->name, 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <strong>{{ $testimonial->name }}</strong>
                            <span>{{ $testimonial->job_title }} - {{ $testimonial->governorate }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <!-- تقييمات افتراضية -->
                <div class="testimonial-card">
                    <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">"منصة توريد ميد غيرت طريقة عملي تماماً. أصبح العثور على الموردين أسهل بكثير والأسعار ممتازة."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">أ</div>
                        <div>
                            <strong>أحمد محمود</strong>
                            <span>صاحب صيدلية - القاهرة</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">"كمورد، ساعدتني المنصة في الوصول لعملاء جدد وزيادة مبيعاتي بشكل كبير. خدمة احترافية."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">س</div>
                        <div>
                            <strong>سارة علي</strong>
                            <span>مورد مستحضرات تجميل - الإسكندرية</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">"الدعم الفني ممتاز والمنصة سهلة الاستخدام. أنصح بها جميع تجار الجملة في المجال الطبي."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">م</div>
                        <div>
                            <strong>محمد حسن</strong>
                            <span>تاجر مستلزمات طبية - الجيزة</span>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
