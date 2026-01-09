<!-- Team Section -->
<section class="team-section">
    <div class="container">
        <h2 class="section-title">فريق العمل</h2>
        <p class="section-subtitle">تعرف على الفريق المميز الذي يعمل على نجاح منصة توريد ميد</p>
        <div class="team-grid">
            @forelse($team as $member)
                <div class="team-card">
                    <div class="team-image">
                        @if($member->image)
                            <img src="{{ asset('storage/' . $member->image) }}" alt="{{ $member->name }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&h=400&fit=crop" alt="{{ $member->name }}">
                        @endif
                    </div>
                    <h3>{{ $member->name }}</h3>
                    <p class="team-position">{{ $member->job_title }}</p>
                    @if($member->description)
                        <p class="team-bio">{{ $member->description }}</p>
                    @endif

                    
                </div>
            @empty
                <!-- أعضاء افتراضيين لو مفيش بيانات -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&h=400&fit=crop" alt="عضو الفريق">
                    </div>
                    <h3>أحمد محمد</h3>
                    <p class="team-position">الرئيس التنفيذي</p>
                    <p class="team-bio">خبرة 15 عاماً في مجال التجارة الإلكترونية</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
