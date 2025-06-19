<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Scoreness - Your Score for What's Real</title>
    @vite('resources/css/app.css')
  </head>
  <body class="bg-white text-text-body">

    @include('navigation_bars.business_home_nav')

    <div>
        <div>
            @include('home.user_hero')
            @php
            $topRatedBusinesses = App\Models\Property::select(
                    'properties.id',
                    'properties.business_name',
                    'properties.domain',
                    'properties.profile_picture',
                    'properties.category',
                    DB::raw('AVG(rates.rate) as average_rating'),
                    DB::raw('COUNT(rates.id) as review_count')
                )
                ->join('rates', 'properties.id', '=', 'rates.property_id')
                ->where('rates.status', 'Approved')
                ->groupBy('properties.id', 'properties.business_name', 'properties.domain', 'properties.profile_picture', 'properties.category')
                ->orderBy('average_rating', 'desc')
                ->limit(4)
                ->get();

                $latestReviews = App\Models\Rate::with(['user', 'property'])
                ->where('status', 'Approved')
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();
        @endphp

        @include('home.top_rated_businesses', ['topRatedBusinesses' => $topRatedBusinesses])
        @include('home.feature')
        @include('home.scorness')
        @include('home.latest_reviews', ['latestReviews' => $latestReviews])
        @php
        $plans = \App\Models\Plan::all();
    @endphp

        @include('home.faq')
    </div>

    @include('footer.footer')
  </body>
</html>
