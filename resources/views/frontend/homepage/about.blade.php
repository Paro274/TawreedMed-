<!-- About -->
<section id="about" class="about">
    <div class="container">
        <div class="about-content">
            <div class="about-text">
                <h2>{{ $about->title ?? 'من نحن؟' }}</h2>
                @if($about && $about->description)
                    {!! nl2br(e($about->description)) !!}
                @else
                    <p class="lead">منصة توريد ميد هي المنصة الرائدة في مصر والشرق الأوسط التي تربط بين الموردين وتجار الجملة في القطاع الطبي والتجميلي.</p>
                    <p>تأسست منصتنا على رؤية واضحة: تسهيل عملية التجارة وجعلها أكثر شفافية وكفاءة. نحن نؤمن بأن التكنولوجيا يمكن أن تحدث ثورة في طريقة عمل تجار الجملة والموردين.</p>
                    <p>من خلال منصتنا، نوفر بيئة آمنة وموثوقة لإجراء الصفقات التجارية، مع ضمان جودة المنتجات وسرعة التسليم وأفضل الأسعار في السوق.</p>
                @endif
            </div>
            <div class="about-image">
                @if($about && $about->image)
                    <img src="{{ asset($about->image) }}" alt="{{ $about->title }}">
                @else
                    <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?w=600&h=500&fit=crop" alt="عن توريد ميد">
                @endif
            </div>
        </div>
    </div>
</section>
