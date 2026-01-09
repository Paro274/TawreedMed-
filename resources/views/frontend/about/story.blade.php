<section class="about-story">
    <div class="container">
        <div class="story-content">
            <div class="story-text">
                <h2>{{ $story->title }}</h2>
                <div class="story-description">
                    {!! $story->description !!}
                </div>
            </div>
            <div class="story-image">
                @if($story->image)
                    <img src="{{ asset($story->image) }}" alt="{{ $story->title }}">
                @else
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=600&h=500&fit=crop" alt="قصتنا">
                @endif
            </div>
        </div>
    </div>
</section>
