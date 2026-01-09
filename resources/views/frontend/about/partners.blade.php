<section class="partners-section">
    <div class="container">
        <h2 class="section-title">شركاؤنا الاستراتيجيون</h2>
        <p class="section-subtitle">نفخر بالشراكة مع أفضل الشركات في المجال</p>
        <div class="partners-grid">
            @forelse($partners as $partner)
                <div class="partner-logo">
                    <img src="{{ asset($partner->image) }}" alt="شريك {{ $loop->iteration }}">
                </div>
            @empty
                <!-- شركاء افتراضيين ... -->
            @endforelse
        </div>
    </div>
</section>
