@if($medicines->count() > 0)
<section id="products" class="products-section medicines-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">منتجات الأدوية</h2>
            <p class="section-subtitle">أحدث وأفضل المنتجات الدوائية من موردين موثوقين</p>
        </div>
        <div class="products-with-category">
            <div class="category-column" onclick="location.href='{{ route('frontend.medicines.index') }}'">
                <div class="category-icon">💊</div>
                <h3>الأدوية</h3>
                <p>تصفح جميع منتجات الأدوية</p>
                <span class="category-arrow">←</span>
            </div>
            <div class="swiper products-swiper">
                <div class="swiper-wrapper">
                    @foreach($medicines as $product)
                        <div class="swiper-slide">
                            <div class="product-card" onclick="window.location.href='{{ route('frontend.medicines.show', $product->slug ?? $product->id) }}'">
                                <div class="product-image">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    @if($product->created_at->diffInDays(now()) < 7)
                                        <span class="product-badge">جديد</span>
                                    @elseif($product->discount > 0)
                                        <span class="product-badge sale">خصم {{ $product->discount }}%</span>
                                    @endif
                                </div>
                                <div class="product-info">
                                    <h3>{{ $product->name }}</h3>
                                    <p class="product-category">{{ $product->short_description ?? ($product->category->name ?? 'غير محدد') }}</p>
                                    
                                    @php
                                        $packagePrice = $product->price * $product->units_per_package;
                                        $hasDiscount = $product->discount > 0;
                                        $finalPrice = $hasDiscount ? $packagePrice - ($packagePrice * $product->discount / 100) : $packagePrice;
                                    @endphp
                                    
                                    {{-- ✅ نفس التصميم الأصلي بس مع المعلومات المطلوبة --}}
                                    @if($hasDiscount)
                                        {{-- لو في خصم: نعرض السعر قبل - الخصم - بعد --}}
                                        <div class="product-price">
                                            <span class="price-before" style="text-decoration: line-through; color: #999; font-size: 14px;">{{ number_format($packagePrice, 0) }}</span>
                                            <span class="price">{{ number_format($finalPrice, 0) }}</span>
                                            <span class="unit">جنيه / {{ $product->package_type ?? 'كرتونة' }}</span>
                                        </div>
                                        <div class="product-discount">
                                            خصم {{ $product->discount }}%
                                        </div>
                                    @else
                                        {{-- لو مافيش خصم: نعرض السعر عادي --}}
                                        <div class="product-price">
                                            <span class="price">{{ number_format($packagePrice, 0) }}</span>
                                            <span class="unit">جنيه / {{ $product->package_type ?? 'كرتونة' }}</span>
                                        </div>
                                        <div class="product-discount" style="visibility: hidden">
                                            خصم 0%
                                        </div>
                                    @endif
                                    
                                    <a href="{{ route('frontend.medicines.show', $product->slug ?? $product->id) }}" 
                                       class="btn btn-product btn-add-cart" 
                                       onclick="event.stopPropagation();">
                                        اطلب الآن
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </div>
</section>
@else
<section id="products" class="products-section medicines-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">منتجات الأدوية</h2>
            <p class="section-subtitle">سيتم إضافة المنتجات قريباً</p>
        </div>
    </div>
</section>
@endif
