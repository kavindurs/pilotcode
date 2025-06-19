<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Property Comparison - Scoreness</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite('resources/css/app.css')
    <style>
      body {
        background-color: white;
        color: #333333;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        margin: 0;
        padding: 0;
        line-height: 1.5;
      }
    </style>
  </head>
  <body>

    @include('navigation_bars.unreg_user_nav')

    <div class="min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl font-bold leading-tight text-gray-900 sm:text-4xl xl:text-5xl font-pj">Compare Properties</h1>
                <p class="mt-4 text-base leading-7 text-gray-600 sm:mt-8 font-pj max-w-3xl mx-auto">
                    Make informed decisions by comparing two properties side by side. See ratings, reviews, and business details all in one place.
                </p>
            </div>

            <!-- Property Comparison Tool -->
            @include('components.property-comparison')

            <!-- How it Works Section -->
            <div class="mt-16 p-8">
                <h2 class="text-2xl font-bold text-center mb-8">How It Works</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-filter text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">1. Select Category & Subcategory</h3>
                        <p class="text-gray-600">Choose a category and then select a specific subcategory to find businesses in the same niche.</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-balance-scale text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">2. Choose Two Properties</h3>
                        <p class="text-gray-600">Select exactly two properties from the same subcategory to ensure fair and relevant comparisons.</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">3. Analyze & Compare</h3>
                        <p class="text-gray-600">Review detailed comparisons including ratings, products, review sentiment, and business details.</p>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg p-6 shadow">
                    <div class="text-center">
                        <i class="fas fa-star text-yellow-500 text-2xl mb-3"></i>
                        <h3 class="font-semibold mb-2">Rating Analysis</h3>
                        <p class="text-sm text-gray-600">Compare average ratings and detailed rating distributions</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-6 shadow">
                    <div class="text-center">
                        <i class="fas fa-box text-purple-500 text-2xl mb-3"></i>
                        <h3 class="font-semibold mb-2">Products & Services</h3>
                        <p class="text-sm text-gray-600">View and compare products, pricing, and inventory</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-6 shadow">
                    <div class="text-center">
                        <i class="fas fa-chart-pie text-green-500 text-2xl mb-3"></i>
                        <h3 class="font-semibold mb-2">Review Sentiment</h3>
                        <p class="text-sm text-gray-600">Analyze positive, negative, and neutral review patterns</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-6 shadow">
                    <div class="text-center">
                        <i class="fas fa-trophy text-orange-500 text-2xl mb-3"></i>
                        <h3 class="font-semibold mb-2">Winner Analysis</h3>
                        <p class="text-sm text-gray-600">See which property performs better across all metrics</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('footer.footer')

  </body>
</html>
