<!-- Hero Slider Section -->
<section id="home" class="hero-slider">
    <div class="slider-container">
        @forelse($sliders as $index => $slider)
            <div class="slide {{ $index === 0 ? 'active' : '' }}" 
                 style="background-image: url('{{ asset($slider->image) }}');">
                 <!-- Content removed to show full image only -->
            </div>
        @empty
            <div class="slide active default-slide">
                 <!-- Content removed -->
            </div>
        @endforelse
    </div>
    
    @if($sliders->count() > 1)
        {{-- Arrows Removed as per request --}}
        
        <div class="slider-dots">
            @foreach($sliders as $index => $slider)
                <span class="dot {{ $index === 0 ? 'active' : '' }}" onclick="currentSlide({{ $index + 1 }})"></span>
            @endforeach
        </div>
    @endif
</section>

<style>
/* Elliptical Hero Slider Styles */
.hero-slider {
    position: relative;
    width: 85%; /* Reduced from 95% to make it smaller width-wise */
    height: 500px; /* Reduced from 75vh to fixed 500px for a more compact height */
    margin: 30px auto; /* Slightly more margin */
    overflow: hidden;
    background: #f3f4f6;
    border-radius: 40px; /* Adjusted radius for smaller size relative to width */
    box-shadow: 0 20px 50px rgba(0,0,0,0.15);
    border: 1px solid rgba(255,255,255,0.5);
}

/* Slide Content Container */
.slide-content {
    position: relative;
    z-index: 10;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    padding: 0 50px;
}

.slide-text {
    max-width: 500px; /* Slightly confined width for text */
    color: white;
    text-align: right;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease-out 0.3s;
}

.slide.active .slide-text {
    opacity: 1;
    transform: translateY(0);
}

.slide-subtitle {
    display: inline-block;
    background: rgba(37, 99, 235, 0.9); /* Primary color background */
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.slide-title {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 20px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.slide-desc {
    font-size: 1.25rem;
    line-height: 1.6;
    margin-bottom: 30px;
    opacity: 0.95;
    text-shadow: 0 1px 4px rgba(0,0,0,0.3);
}

.custom-btn {
    padding: 12px 35px;
    border-radius: 30px;
    font-weight: 800; /* Bolder for clarity */
    color: #ffffff !important; /* Ensure generic white is forced */
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    transition: all 0.3s;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border: 2px solid rgba(255,255,255,0.2); /* Add border to pop */
    box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
}

.custom-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(37, 99, 235, 0.4);
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.slider-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    background-size: 100% 100% !important; /* Force image to stretch and fit exactly 100% of container width and height */
    background-position: center center;
    background-repeat: no-repeat;
    background-color: #f3f4f6;
}

/* Ken Burns Effect Animation */
@keyframes kenburns {
    0% { transform: scale(1); }
    100% { transform: scale(1.1); }
}

.slide.active {
    opacity: 1;
    z-index: 1;
    animation: kenburns 10s infinite alternate; /* Subtle zoom */
}

/* Subtle Gradient Overlay - Adjusted for better text visibility */
/* Subtle Gradient Overlay - Adjusted for better text visibility */
.slide::after {
    display: none;
}

/* Default Slide */
.default-slide {
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.default-slide h2 {
    color: white;
    font-size: 3.5rem;
    font-weight: 800;
    text-align: center;
    padding: 0 20px;
    z-index: 2;
    text-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

/* Navigation Buttons - Glassmorphism */
.slider-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 20;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.slider-btn:hover {
    background: white;
    color: #2563eb;
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.slider-btn.prev {
    right: 40px;
    left: auto;
}

.slider-btn.next {
    left: 40px;
    right: auto;
}

/* Pagination Dots */
.slider-dots {
    position: absolute;
    bottom: 40px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    z-index: 20;
    background: rgba(255,255,255,0.2);
    padding: 8px 16px;
    border-radius: 30px;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255,255,255,0.1);
    direction: ltr; /* Force Left-to-Right for dots */
}

.dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.6);
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.dot:hover {
    background: white;
}

.dot.active {
    background: #2563eb;
    width: 30px;
    border-radius: 10px;
}

/* Responsive Adjustments */
@media (max-width: 1024px) {
    .hero-slider {
        height: 400px; /* Tablet height */
        width: 90%;
        border-radius: 30px;
    }
}

@media (max-width: 768px) {
    .hero-slider {
        height: 300px; /* Mobile height */
        width: 92%;
        border-radius: 20px;
        margin: 20px auto;
    }
    
    .slider-btn {
        display: none; /* Hide arrows on mobile */
    }
    
    .slider-dots {
        bottom: 15px;
    }
}
</style>

<script>
let slideIndex = 1;
showSlides(slideIndex);

function changeSlide(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    let slides = document.getElementsByClassName("slide");
    let dots = document.getElementsByClassName("dot");
    
    if (slides.length === 0) return;
    
    if (n > slides.length) { slideIndex = 1 }
    if (n < 1) { slideIndex = slides.length }
    
    for (let i = 0; i < slides.length; i++) {
        slides[i].classList.remove("active");
    }
    
    for (let i = 0; i < dots.length; i++) {
        dots[i].classList.remove("active");
    }
    
    slides[slideIndex - 1].classList.add("active");
    if (dots.length > 0) {
        dots[slideIndex - 1].classList.add("active");
    }
}

// Auto slide كل 5 ثواني
setInterval(function() {
    let slides = document.getElementsByClassName("slide");
    if (slides.length > 1) {
        changeSlide(1);
    }
}, 5000);
</script>
