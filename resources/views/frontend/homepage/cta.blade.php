<!-- CTA -->
<section class="cta">
    <div class="container">
        <div class="cta-content">
            <h2>{{ $cta->title ?? 'ابدأ رحلتك التجارية معنا اليوم' }}</h2>
            <p>{{ $cta->description ?? 'انضم لآلاف التجار والموردين الذين يثقون بمنصة توريد ميد' }}</p>
            <div class="cta-buttons">
                @if($cta && $cta->button1_text && $cta->button1_link)
                    <a href="{{ $cta->button1_link }}" class="btn btn-large btn-white">{{ $cta->button1_text }}</a>
                @else
                    <a href="{{ route('frontend.customer.register') }}" class="btn btn-large btn-white">سجل كتاجر</a>
                @endif
                
                @if($cta && $cta->button2_text && $cta->button2_link)
                    <a href="{{ $cta->button2_link }}" class="btn btn-large btn-outline">{{ $cta->button2_text }}</a>
                @else
                    <a href="{{ route('frontend.supplier.register') }}" class="btn btn-large btn-outline">سجل كمورد</a>
                @endif
            </div>
        </div>
    </div>
</section>
