<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scoreness - Your Score for What's Real</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite('resources/css/app.css')
    <style>
      /* Base styles */
      body {
        background-color: white;
        color: #333333;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        margin: 0;
        padding: 0;
        line-height: 1.5;
      }

      /* Main container */
      .main-container {
        width: 100%;
      }

      .content-wrapper {
        width: 100%;
      }
    </style>
  </head>
  <body class="bg-white text-text-body">

    @include('navigation_bars.unreg_user_nav')

    <div class="main-container">
        <div class="content-wrapper">
            @include('home.user_hero')

            <!-- Property Comparison Tool -->
            <div class="py-12">
                @include('components.property-comparison-simple')
            </div>

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
            @include('home.priceing', ['plans' => $plans])
            @include('home.faq')
        </div>
    </div>

    @include('footer.footer')

    <!-- Script for handling interactive elements -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Initialize any JavaScript functionality here
        // This preserves any interactive functionality from the original page
      });
    </script>
  </body>
</html>
