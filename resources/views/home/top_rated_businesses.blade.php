<!-- filepath: c:\xampp\htdocs\pilot\resources\views\business\top_rated_businesses.blade.php -->
<section class="py-8 sm:py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section heading with subtitle in the same style as feature.blade.php -->
        <div class="text-center">
            <h2 class="text-3xl font-bold leading-tight text-gray-900 sm:text-4xl xl:text-5xl font-pj">Highest Rated Businesses</h2>
            <p class="mt-4 text-base leading-7 text-gray-600 sm:mt-8 font-pj">Discover top-rated companies with exceptional customer reviews and satisfaction scores</p>
        </div>

        <div class="grid grid-cols-1 mt-10 sm:mt-16 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @forelse ($topRatedBusinesses as $business)
                <!-- Rest of your business cards code remains unchanged -->
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden transition-transform hover:shadow-lg hover:-translate-y-1">
                    <a href="{{ route('property.show', $business->id) }}" class="block">
                        <div class="p-4">
                            <!-- Restructured layout with image on left -->
                            <div class="flex items-start space-x-4 mb-3">
                                <!-- Larger profile picture with rounded corners -->
                                <div class="flex-shrink-0">
                                    @if($business->profile_picture)
                                        <img
                                            src="{{ Storage::url($business->profile_picture) }}"
                                            alt="{{ $business->business_name }}"
                                            class="w-20 h-20 object-cover rounded-md border border-gray-200"
                                            onerror="this.onerror=null; this.src='{{ asset('images/default-business.png') }}';"
                                        />
                                    @else
                                        <div class="w-20 h-20 bg-blue-100 rounded-md border border-gray-200 flex items-center justify-center">
                                            <span class="text-xl font-semibold text-blue-600">{{ substr($business->business_name, 0, 2) }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Business details -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1 truncate">{{ $business->business_name }}</h3>
                                    <p class="text-sm text-gray-500 truncate">{{ $business->domain ?? $business->category }}</p>
                                </div>
                            </div>

                            <!-- Rating section -->
                            <div class="flex items-center mt-3 pt-3 border-t border-gray-100">
                                <div class="flex">
                                    @php
                                        $rating = round($business->average_rating * 2) / 2; // Round to nearest 0.5
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
                                    {{ number_format($business->average_rating, 1) }}
                                    <span class="text-gray-500 font-normal">({{ $business->review_count }})</span>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-lg text-gray-500">No rated businesses found.</p>
                    <p class="mt-2 text-gray-400">Be the first to rate a business!</p>
                </div>
            @endforelse
        </div>

    </div>
</section>
