{{-- الأسئلة الشائعة --}}
<section class="faq-section">
    <div class="container">
        <h2 class="section-title">الأسئلة الشائعة</h2>
        <p class="section-subtitle">ابحث عن إجابة سريعة لسؤالك</p>

        <div class="faq-grid">
            @forelse($faqs as $faq)
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>{{ $faq->question }}</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>{{ $faq->answer }}</p>
                    </div>
                </div>
            @empty
                <p style="text-align:center;">لا توجد أسئلة مضافة حالياً.</p>
            @endforelse
        </div>
    </div>
</section>
