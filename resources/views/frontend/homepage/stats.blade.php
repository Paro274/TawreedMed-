<!-- Stats Section -->
<section class="stats">
    <div class="container">
        <div class="stats-grid">
            @forelse($statistics as $stat)
                <div class="stat-card">
                    <div class="stat-number">{{ $stat['number'] }}</div>
                    <div class="stat-label">{{ $stat['title'] }}</div>
                </div>
            @empty
                <!-- إحصائيات افتراضية لو مفيش بيانات -->
                <div class="stat-card">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">مورد نشط</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">تاجر جملة</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">50000+</div>
                    <div class="stat-label">صفقة ناجحة</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">رضا العملاء</div>
                </div>
            @endforelse
        </div>
    </div>
</section>
