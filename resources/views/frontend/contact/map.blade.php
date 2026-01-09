{{-- الخريطة --}}
<section class="map-section" id="map">
    <div class="container">
        <h2 class="section-title">{{ $map->address ?? 'موقعنا على الخريطة' }}</h2>
        <p class="section-subtitle">تعرف على موقع مقرنا الرئيسي</p>
    </div>
    <div class="map-container">
        @if($map && $map->map_link)
            <iframe 
                src="{{ $map->map_link }}" 
                width="100%" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        @else
            <div class="default-map">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d55251.37709871288!2d31.208244899999997!3d30.0444196!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14583fa60b21beeb%3A0x79dfb296e8423bba!2sCairo%2C%20Egypt!5e0!3m2!1sen!2s!4v1234567890123!5m2!1sen!2s" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        @endif
    </div>
</section>
