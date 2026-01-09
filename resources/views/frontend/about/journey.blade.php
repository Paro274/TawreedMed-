<!-- Timeline Section -->
<section class="timeline-section">
    <div class="container">
        <h2 class="section-title">رحلة النجاح</h2>
        <p class="section-subtitle">أهم المحطات في مسيرتنا</p>
        <div class="timeline">
            @forelse($journeys as $journey)
                <div class="timeline-item">
                    <div class="timeline-year">{{ $journey->year }}</div>
                    <div class="timeline-content">
                        <h3>{{ $journey->title }}</h3>
                        <p>{{ $journey->description }}</p>
                    </div>
                </div>
            @empty
                <!-- عناصر افتراضية -->
                <div class="timeline-item">
                    <div class="timeline-year">2020</div>
                    <div class="timeline-content">
                        <h3>انطلاق المنصة</h3>
                        <p>إطلاق النسخة الأولى من منصة توريد ميد</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
