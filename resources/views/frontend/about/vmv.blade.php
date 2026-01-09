<!-- Vision Mission Values -->
<section class="vmv-section">
    <div class="container">
        <div class="vmv-grid">
            <div class="vmv-card">
                <div class="vmv-icon">🎯</div>
                <h3>{{ $mvv->vision_title ?? 'رؤيتنا' }}</h3>
                @if($mvv && $mvv->vision_description)
                    <div>{!! $mvv->vision_description !!}</div>
                @else
                    <p>أن نصبح المنصة الأولى والأكثر ثقة للتجارة بالجملة في الشرق الأوسط وشمال أفريقيا.</p>
                @endif
            </div>
            
            <div class="vmv-card">
                <div class="vmv-icon">🚀</div>
                <h3>{{ $mvv->mission_title ?? 'رسالتنا' }}</h3>
                @if($mvv && $mvv->mission_description)
                    <div>{!! $mvv->mission_description !!}</div>
                @else
                    <p>تسهيل عملية التجارة بالجملة من خلال توفير منصة تكنولوجية متقدمة.</p>
                @endif
            </div>
            
            <div class="vmv-card">
                <div class="vmv-icon">⭐</div>
                <h3>{{ $mvv->values_title ?? 'قيمنا' }}</h3>
                @if($mvv && $mvv->values_description)
                    <div>{!! $mvv->values_description !!}</div>
                @else
                    <ul>
                        <li>الثقة والشفافية في كل تعامل</li>
                        <li>الابتكار المستمر في الحلول</li>
                        <li>التركيز على رضا العملاء</li>
                    </ul>
                @endif
            </div>
        </div>
    </div>
</section>
