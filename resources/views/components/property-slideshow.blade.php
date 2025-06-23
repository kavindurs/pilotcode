{{--
    Property Slideshow Component

    A reusable component for displaying properties in a clean, modern slideshow format.
    Shows 4 properties at a time with navigation arrows and auto-play functionality.

    Props:
    - title: The main heading (default: "Featured Properties")
    - subtitle: The subtitle text (default: "Top-rated properties currently being promoted")
    - properties: Collection of properties to display (falls back to $promotedProperties if not provided)

    Usage Examples:
    <x-property-slideshow />

    <x-property-slideshow
        title="Top Rated Businesses"
        subtitle="Discover the highest rated properties in your area"
        :properties="$topRatedProperties"
    />

    <x-property-slideshow
        title="Recently Added"
        subtitle="Check out the newest properties on our platform"
        :properties="$recentProperties"
    />
--}}

@props(['title' => 'Featured Properties', 'subtitle' => 'Top-rated properties currently being promoted', 'properties' => null])

@php
    $properties = $properties ?? $promotedProperties ?? collect();
@endphp

@if($properties && $properties->count() > 0)
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center">
            <h2 class="text-3xl font-bold leading-tight text-gray-900 sm:text-4xl xl:text-5xl font-pj">
                {{ $title }}
            </h2>
            <p class="mt-4 text-base leading-7 text-gray-600 sm:mt-8 font-pj max-w-2xl mx-auto">
                {{ $subtitle }}
            </p>
        </div>

        <!-- Slideshow Container -->
        <div class="relative overflow-hidden rounded-xl bg-white mt-10 sm:mt-16">
            <div id="promoted-slideshow" class="flex transition-transform duration-500 ease-in-out">                @php
                    // Calculate how many properties we need for seamless infinite scroll
                    $propertiesPerSlide = 4;
                    $totalProperties = $properties->count();

                    // If we have fewer than 4 properties, duplicate them to fill at least 2 full slides
                    if ($totalProperties < $propertiesPerSlide) {
                        $neededProperties = $propertiesPerSlide * 2;
                        $extendedProperties = collect();
                        for ($i = 0; $i < $neededProperties; $i++) {
                            $extendedProperties->push($properties[$i % $totalProperties]);
                        }
                        $properties = $extendedProperties;
                        $totalProperties = $properties->count();
                    }

                    // Calculate slides needed and fill gaps
                    $slidesNeeded = ceil($totalProperties / $propertiesPerSlide);
                    $totalSlotsNeeded = $slidesNeeded * $propertiesPerSlide;

                    // If we have gaps, fill them by repeating properties from the beginning
                    if ($totalProperties < $totalSlotsNeeded) {
                        $extendedProperties = collect();
                        // Add all existing properties first
                        foreach ($properties as $property) {
                            $extendedProperties->push($property);
                        }

                        $slotsToFill = $totalSlotsNeeded - $totalProperties;

                        for ($i = 0; $i < $slotsToFill; $i++) {
                            $extendedProperties->push($properties[$i % $totalProperties]);
                        }
                        $properties = $extendedProperties;
                    }

                    // Add one more complete slide at the end with the first properties for seamless loop
                    $seamlessProperties = collect();
                    // Add all existing properties first
                    foreach ($properties as $property) {
                        $seamlessProperties->push($property);
                    }

                    for ($i = 0; $i < $propertiesPerSlide; $i++) {
                        $seamlessProperties->push($properties[$i % $properties->count()]);
                    }

                    $chunks = $seamlessProperties->chunk($propertiesPerSlide);
                    $originalChunksCount = ceil($properties->count() / $propertiesPerSlide);
                @endphp

                @foreach($chunks as $chunkIndex => $chunk)
                <div class="w-full flex-shrink-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 p-6">
                        @foreach($chunk as $property)
                        @php
                            // Convert array to object if needed for compatibility
                            if (is_array($property)) {
                                $property = (object) $property;
                            }
                        @endphp
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden transition-transform hover:shadow-lg hover:-translate-y-1">
                            <a href="{{ route('property.show', $property->id ?? '#') }}" class="block">
                                <div class="p-4">
                                    <!-- Restructured layout with image on left -->
                                    <div class="flex items-start space-x-4 mb-3">
                                        <!-- Larger profile picture with rounded corners -->
                                        <div class="flex-shrink-0">
                                            @if($property->profile_picture ?? false)
                                                <img
                                                    src="{{ Storage::url($property->profile_picture) }}"
                                                    alt="{{ $property->business_name ?? 'Business' }}"
                                                    class="w-20 h-20 object-cover rounded-md border border-gray-200"
                                                    onerror="this.onerror=null; this.src='{{ asset('images/default-business.png') }}';"
                                                />
                                            @else
                                                <div class="w-20 h-20 bg-blue-100 rounded-md border border-gray-200 flex items-center justify-center">
                                                    <span class="text-xl font-semibold text-blue-600">{{ substr($property->business_name ?? 'Business', 0, 2) }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Business details -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-1 truncate">{{ $property->business_name ?? 'Business Name' }}</h3>
                                            <p class="text-sm text-gray-500 truncate">
                                                @php
                                                    $location = '';
                                                    if($property->city ?? false) {
                                                        $location .= $property->city;
                                                    }
                                                    if($property->country ?? false) {
                                                        if($location) $location .= ', ';
                                                        $location .= $property->country;
                                                    }
                                                    if(!$location) {
                                                        $location = $property->domain ?? $property->category ?? 'Business';
                                                    }
                                                @endphp
                                                {{ $location }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Rating section -->
                                    @if($property->average_rating ?? false)
                                    <div class="flex items-center mt-3 pt-3 border-t border-gray-100">
                                        <div class="flex">
                                            @php
                                                $rating = round(($property->average_rating ?? 0) * 2) / 2; // Round to nearest 0.5
                                                $fullStars = floor($rating);
                                                $halfStar = $rating - $fullStars >= 0.5;
                                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

                                                // Determine color based on rating
                                                if ($rating >= 4) {
                                                    $starColor = 'text-blue-500';
                                                } elseif ($rating >= 3) {
                                                    $starColor = 'text-yellow-400';
                                                } else {
                                                    $starColor = 'text-red-500';
                                                }
                                            @endphp

                                            @for ($i = 0; $i < $fullStars; $i++)
                                                <i class="fas fa-star {{ $starColor }}"></i>
                                            @endfor

                                            @if ($halfStar)
                                                <i class="fas fa-star-half-alt {{ $starColor }}"></i>
                                            @endif

                                            @for ($i = 0; $i < $emptyStars; $i++)
                                                <i class="far fa-star {{ $starColor }}"></i>
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-sm font-medium">
                                            {{ number_format($property->average_rating ?? 0, 1) }}
                                            <span class="text-gray-500 font-normal">({{ $property->review_count ?? 0 }})</span>
                                        </span>
                                    </div>
                                    @else
                                    <div class="mt-3 pt-3 border-t border-gray-100">
                                        <span class="text-sm text-gray-500">No reviews yet</span>
                                    </div>
                                    @endif
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Navigation Arrows -->
            @if($originalChunksCount > 1)
            <button id="prev-slide"
                    class="absolute left-3 top-1/2 transform -translate-y-1/2 bg-white hover:bg-gray-50 text-gray-700 p-2 rounded-full shadow-md transition-all duration-200 hover:shadow-lg">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button id="next-slide"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-white hover:bg-gray-50 text-gray-700 p-2 rounded-full shadow-md transition-all duration-200 hover:shadow-lg">
                <i class="fas fa-chevron-right"></i>
            </button>
            @endif

            <!-- Slide Indicators (only for original slides) -->
            @if($originalChunksCount > 1)
            <div class="absolute bottom-3 left-1/2 transform -translate-x-1/2 flex space-x-2">
                @for($i = 0; $i < $originalChunksCount; $i++)
                <button class="slide-indicator w-2 h-2 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-blue-600' : 'bg-gray-300' }}"
                        data-slide="{{ $i }}"></button>
                @endfor
            </div>
            @endif
        </div>

        <!-- Slideshow Stats -->
        <div class="mt-6 text-center">
            <div class="inline-flex items-center text-gray-600">
                <i class="fas fa-bullhorn text-blue-500 mr-2"></i>
                <span class="text-sm">Promote Your Business Immediately, Get More Sales!</span>
            </div>
        </div>
    </div>
</section>

<!-- Slideshow JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slideshow = document.getElementById('promoted-slideshow');
    const prevBtn = document.getElementById('prev-slide');
    const nextBtn = document.getElementById('next-slide');
    const indicators = document.querySelectorAll('.slide-indicator');

    let currentSlide = 0;
    const totalSlides = {{ $chunks->count() }};
    const originalSlides = {{ $originalChunksCount }}; // Number of original slides (without duplicates)
    let isAutoPlaying = true;
    let autoPlayInterval;

    if (totalSlides <= 1) return; // No need for slideshow if only one slide

    function updateSlideshow(smooth = true) {
        if (smooth) {
            slideshow.style.transition = 'transform 0.5s ease-in-out';
        } else {
            slideshow.style.transition = 'none';
        }

        const translateX = -currentSlide * 100;
        slideshow.style.transform = `translateX(${translateX}%)`;

        // Update indicators based on original slides only
        const indicatorIndex = currentSlide % originalSlides;
        indicators.forEach((indicator, index) => {
            if (index === indicatorIndex) {
                indicator.classList.remove('bg-gray-300');
                indicator.classList.add('bg-blue-600');
            } else {
                indicator.classList.remove('bg-blue-600');
                indicator.classList.add('bg-gray-300');
            }
        });
    }

    function nextSlide() {
        currentSlide++;
        updateSlideshow();

        // If we've reached the duplicate slide at the end, reset to slide 0 instantly
        if (currentSlide >= originalSlides) {
            setTimeout(() => {
                currentSlide = 0;
                updateSlideshow(false);
            }, 500); // Wait for transition to complete
        }
    }

    function prevSlide() {
        if (currentSlide <= 0) {
            // Jump to the last original slide (before duplicates)
            currentSlide = originalSlides;
            updateSlideshow(false);
            setTimeout(() => {
                currentSlide = originalSlides - 1;
                updateSlideshow();
            }, 50);
        } else {
            currentSlide--;
            updateSlideshow();
        }
    }

    function goToSlide(slideIndex) {
        currentSlide = slideIndex;
        updateSlideshow();
    }

    function startAutoPlay() {
        if (autoPlayInterval) clearInterval(autoPlayInterval);
        autoPlayInterval = setInterval(() => {
            if (isAutoPlaying) {
                nextSlide();
            }
        }, 4000); // Change slide every 4 seconds for continuous feel
    }

    function stopAutoPlay() {
        isAutoPlaying = false;
        if (autoPlayInterval) clearInterval(autoPlayInterval);
    }

    function resumeAutoPlay() {
        isAutoPlaying = true;
        startAutoPlay();
    }

    // Event listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            stopAutoPlay();
            nextSlide();
            setTimeout(resumeAutoPlay, 3000); // Resume after 3 seconds
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            stopAutoPlay();
            prevSlide();
            setTimeout(resumeAutoPlay, 3000); // Resume after 3 seconds
        });
    }

    // Indicator clicks
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            stopAutoPlay();
            goToSlide(index);
            setTimeout(resumeAutoPlay, 3000); // Resume after 3 seconds
        });
    });

    // Pause on hover
    slideshow.addEventListener('mouseenter', stopAutoPlay);
    slideshow.addEventListener('mouseleave', resumeAutoPlay);

    // Start auto-play
    startAutoPlay();
});
</script>

<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endif
