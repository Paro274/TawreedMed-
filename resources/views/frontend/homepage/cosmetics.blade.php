@if($cosmetics->count() > 0)
<section class="products-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">مستحضرات التجميل</h2>
            <p class="section-subtitle">أفضل منتجات العناية بالبشرة والجمال</p>
        </div>
        <div class="products-with-category">
            <div class="category-column" onclick="location.href='{{ route('frontend.cosmetics.index') }}'">
                <div class="category-icon">💄</div>
                <h3>مستحضرات التجميل</h3>
                <p>تصفح جميع مستحضرات التجميل</p>
                <span class="category-arrow">←</span>
            </div>
            <div class="swiper products-swiper">
                <div class="swiper-wrapper">
                    @foreach($cosmetics as $product)
                        <div class="swiper-slide">
                            <div class="product-card" onclick="window.location.href='{{ route('frontend.cosmetics.show', $product->slug ?? $product->id) }}'">
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
                                    
                                    {{-- ✅ تعديل السعر --}}
                                    <div class="product-price">
                                        <span class="price">{{ number_format($product->final_package_price, 0) }}</span>
                                        <span class="unit">جنيه / {{ $product->package_type ?? 'كرتونة' }}</span>
                                    </div>

                                    <div class="product-discount" style="{{ $product->discount > 0 ? '' : 'visibility: hidden' }}">
                                        خصم {{ $product->discount }}%
                                    </div>

                                    {{-- ✅ تعديل الزرار --}}
                                    <a href="{{ route('frontend.cosmetics.show', $product->slug ?? $product->id) }}" 
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
<section class="products-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">مستحضرات التجميل</h2>
            <p class="section-subtitle">سيتم إضافة المنتجات قريباً</p>
        </div>
    </div>
</section>
@endif
