<!-- Awards Section -->
<section class="awards-section">
    <div class="container">
        <h2 class="section-title">الجوائز والشهادات</h2>
        <div class="awards-grid">
            @forelse($certificates as $certificate)
                <div class="award-card">
                    <div class="award-icon">
                        @php
                            // إضافة fa- تلقائياً لو مش موجودة
                            $iconClass = $certificate->icon;
                            if (!str_starts_with($iconClass, 'fa-')) {
                                $iconClass = 'fa-' . $iconClass;
                            }
                        @endphp
                        <i class="fas {{ $iconClass }}"></i>
                    </div>
                    <h3>{{ $certificate->title }}</h3>
                    <p>{{ $certificate->description }}</p>
                </div>
            @empty
                <!-- جوائز افتراضية -->
                <div class="award-card">
                    <div class="award-icon"><i class="fas fa-trophy"></i></div>
                    <h3>أفضل منصة تجارة إلكترونية</h3>
                    <p>جائزة الابتكار التكنولوجي 2024</p>
                </div>
                <div class="award-card">
                    <div class="award-icon"><i class="fas fa-medal"></i></div>
                    <h3>الشركة الناشئة الأكثر نمواً</h3>
                    <p>قمة الشركات الناشئة 2023</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
