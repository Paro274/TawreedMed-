<!-- Features -->
<section id="features" class="full-features">
    <div class="container">
        <h2 class="section-title">مميزات المنصة</h2>
        <p class="section-subtitle">نوفر لك جميع الأدوات والخدمات التي تحتاجها لإدارة أعمالك التجارية بكفاءة</p>
        <div class="features-grid">
            @forelse($features as $feature)
                <div class="feature-item">
                    <div class="feature-icon-box">
                        @php
                            // إضافة fa- تلقائياً لو مش موجودة
                            $iconClass = $feature->icon;
                            if (!str_starts_with($iconClass, 'fa-')) {
                                $iconClass = 'fa-' . $iconClass;
                            }
                        @endphp
                        <i class="fas {{ $iconClass }}"></i>
                    </div>
                    <h3>{{ $feature->title }}</h3>
                    <p>{{ $feature->description }}</p>
                </div>
            @empty
                <!-- مميزات افتراضية -->
                <div class="feature-item">
                    <div class="feature-icon-box"><i class="fas fa-search"></i></div>
                    <h3>بحث متقدم</h3>
                    <p>محرك بحث قوي يساعدك في العثور على المنتجات والموردين المناسبين بسرعة</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon-box"><i class="fas fa-chart-line"></i></div>
                    <h3>لوحة تحكم شاملة</h3>
                    <p>متابعة جميع عملياتك التجارية من مكان واحد مع تقارير تفصيلية</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon-box"><i class="fas fa-credit-card"></i></div>
                    <h3>نظام دفع آمن</h3>
                    <p>خيارات دفع متعددة ومرنة مع ضمان أمان المعاملات المالية</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon-box"><i class="fas fa-mobile-alt"></i></div>
                    <h3>تطبيق موبايل</h3>
                    <p>أدر أعمالك من أي مكان عبر تطبيقنا المتاح على iOS و Android</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon-box"><i class="fas fa-bell"></i></div>
                    <h3>تنبيهات فورية</h3>
                    <p>احصل على إشعارات فورية لجميع العروض والصفقات الجديدة</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon-box"><i class="fas fa-truck"></i></div>
                    <h3>تتبع الشحنات</h3>
                    <p>تتبع شحناتك لحظياً من لحظة الطلب حتى التسليم</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
