<!-- filepath: c:\xampp\htdocs\pilot\resources\views\home\latest_reviews.blade.php -->
<section class="py-8 sm:py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section heading with subtitle -->
        <div class="text-center">
            <h2 class="text-3xl font-bold leading-tight text-gray-900 sm:text-4xl xl:text-5xl font-pj">Latest Reviews</h2>
            <p class="mt-4 text-base leading-7 text-gray-600 sm:mt-8 font-pj">See what customers are saying about businesses on Scoreness</p>
        </div>

        <!-- Reviews grid layout -->
        <div class="mt-10 sm:mt-16">
            <!-- Reviews container -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @forelse ($latestReviews->take(8) as $review)
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden h-full flex flex-col hover:shadow-md transition-shadow">
                        <!-- Review header with user info -->
                        <div class="p-4 border-b border-gray-100 flex items-center">
                            <!-- User avatar -->
                            <div class="flex-shrink-0">
                                @if($review->user && $review->user->profile_picture)
                                    <img src="{{ Storage::url($review->user->profile_picture) }}"
                                        alt="{{ $review->user->name }}"
                                        class="h-10 w-10 rounded-full object-cover">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                        <span>{{ substr($review->user->name ?? 'A', 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- User name and review date -->
                            <div class="ml-3 flex-grow min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $review->user->name ?? 'Anonymous' }}</h4>
                                <p class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y') }}</p>
                            </div>

                            <!-- Star rating -->
                            <div class="flex items-center ml-2">
                                <div class="flex">
                                    @for ($i = 0; $i < 5; $i++)
                                        @if ($i < $review->rate)
                                            <svg class="w-4 h-4 text-blue-500 fill-current" viewBox="0 0 24 24">
                                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 24 24">
                                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="ml-1 text-xs text-blue-600 font-semibold">{{ number_format($review->rate, 1) }}</span>
                            </div>
                        </div>

                        <!-- Review content -->
                        <div class="p-4 flex-grow">
                            <!-- Business name -->
                            <div class="mb-2">
                                <a href="{{ route('property.show', $review->property->id) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                                    {{ $review->property->business_name }}
                                </a>
                            </div>

                            <!-- Review text -->
                            <p class="text-gray-700 text-sm">
                                {{ \Illuminate\Support\Str::limit($review->review, 100) }}
                            </p>
                        </div>

                        <!-- Review footer -->
                        <div class="p-4 pt-0 mt-auto">
                            <div class="pt-3 border-t border-gray-100 flex justify-between items-center">
                                <div class="text-xs text-gray-500">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ \Carbon\Carbon::parse($review->experienced_date)->format('M d, Y') }}
                                </div>
                                <a href="{{ route('property.show', $review->property->id) }}" class="text-xs text-blue-600 hover:text-blue-800">
                                    Read more
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10">
                        <p class="text-gray-500">No reviews found yet.</p>
                    </div>
                @endforelse
            </div>


        </div>
    </div>
</section>
