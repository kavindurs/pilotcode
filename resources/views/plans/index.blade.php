@extends('layouts.business')

@section('active-pricing', 'bg-blue-700')

@section('title', 'Pricing - Business Dashboard')
@section('page-title', 'Pricing Plans')
@section('page-subtitle', 'Select the plan that best suits your business needs.')

@push('head')
    <!-- Any additional head content specific to this page -->
@endpush

@section('content')
    <!-- Location Access Required Modal -->
    <div id="locationModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 max-w-md mx-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 mb-4">
                    <i class="fas fa-map-marker-alt text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    Location Access Required
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    We need access to your location to show you the most relevant pricing plans for your region. This helps us provide accurate pricing based on your country.
                </p>
                <div class="flex flex-col space-y-3">
                    <button
                        id="enableLocationBtn"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors"
                    >
                        <i class="fas fa-location-arrow mr-2"></i>
                        Enable Location Access
                    </button>
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        Your location data is only used for pricing and is not stored or shared.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content (Hidden until location access is granted) -->
    <div id="mainContent" class="hidden">
        <!-- Plan Comparison Section -->
    @php
        // Map the plan names to suitable Font Awesome icons.
        $planIcons = [
            'Free'    => 'fas fa-gift',
            'Basic'   => 'fas fa-check-circle',
            'Pro'     => 'fas fa-rocket',
            'Premium' => 'fas fa-crown',
        ];
    @endphp

    <!-- Pricing Cards Section -->
    @php
        // Retrieve Payment records for authentication purposes (this can be removed if not used)
        $userEmail = auth()->user()->business_email ?? null;
        $paymentPlanIdsRaw = $userEmail
            ? \App\Models\Payment::where('business_email', $userEmail)->pluck('plan_id')->toArray()
            : [];
        // Cast Payment record plan ids to strings (if needed)
        $paymentPlanIds = array_map('strval', $paymentPlanIdsRaw);
    @endphp

    <!-- Activated Plans (highlighted) output section, if needed -->
    @if(isset($activatedPlanIds) && count($activatedPlanIds) > 0)
        <div class="mb-4 p-4 bg-gray-200 dark:bg-gray-700 rounded">
            <h3 class="text-gray-800 dark:text-gray-200">Activated Plans:</h3>
            <ul class="text-lg font-bold text-gray-800 dark:text-gray-200">
                @foreach($activatedPlanIds as $planId)
                    @php
                        $activatedPlan = $plans->firstWhere('id', $planId);
                    @endphp
                    @if($activatedPlan)
                        <li>Scoreness {{ $activatedPlan->name }} Plan</li>
                    @endif
                @endforeach
            </ul>
        </div>
    @else
        <div class="mb-4 p-4 bg-gray-200 dark:bg-gray-700 rounded">
            <p class="text-lg font-bold text-gray-800 dark:text-gray-200">No activated plans found.</p>
        </div>
    @endif

    <section class="mt-12">
      <div class="w-full px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          @foreach($plans as $plan)
            @php
                // Check if the current plan id is in the activated plan IDs array.
                $isActivated = isset($activatedPlanIds) && in_array($plan->id, $activatedPlanIds);

                // If no activated plans found, set Free plan as active by default
                if (!isset($activatedPlanIds) || count($activatedPlanIds) == 0) {
                    $isActivated = strtolower($plan->name) === 'free';
                }
            @endphp
            <div class="plan-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 p-6 {{ $isActivated ? 'border-red-500 bg-red-50 dark:bg-red-900/20 dark:border-red-400' : '' }}" data-plan-id="{{ $plan->id }}">
              <div class="flex items-center mb-6">
                <i class="{{ $planIcons[$plan->name] ?? 'fas fa-star' }} text-3xl text-blue-500 dark:text-blue-400"></i>
                <h2 class="ml-4 text-2xl font-extrabold text-gray-800 dark:text-gray-200">{{ $plan->name }}</h2>
              </div>
              <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-6">
                ${{ number_format($plan->price, 2) }}
                <span class="text-lg font-normal text-gray-500 dark:text-gray-400">/month</span>
              </p>
              <ul class="space-y-4 text-gray-600 dark:text-gray-300 text-sm">
                <li class="flex items-center">
                  <i class="fas fa-box-open text-blue-500 dark:text-blue-400 mr-3"></i>
                  <span><strong>Product Limit:</strong> {{ $plan->product_limit }}</span>
                </li>
                <li class="flex items-center">
                  <i class="fas fa-th-large text-blue-500 dark:text-blue-400 mr-3"></i>
                  <span><strong>Widget Limit:</strong> {{ $plan->widget_limit }}</span>
                </li>
                <li class="flex items-center">
                  <i class="fas fa-code text-blue-500 dark:text-blue-400 mr-3"></i>
                  <span><strong>HTML Integration:</strong> {{ $plan->html_integration_limit }} characters</span>
                </li>
                <li class="flex items-center">
                  <i class="fas fa-envelope-open text-blue-500 dark:text-blue-400 mr-3"></i>
                  <span><strong>Review Invitation:</strong> {{ $plan->review_invitation_limit }}</span>
                </li>
                <li class="flex items-center">
                  <i class="fas fa-tags text-blue-500 dark:text-blue-400 mr-3"></i>
                  <span><strong>Referral Code:</strong> {{ $plan->referral_code ? 'Yes' : 'No' }}</span>
                </li>
                <!-- New Features -->
                <li class="flex items-center">
                  <i class="fas fa-user-cog text-blue-500 dark:text-blue-400 mr-3"></i>
                  <span><strong>Customize Profile:</strong> Yes</span>
                </li>
                <li class="flex items-center">
                  <i class="fas fa-star text-blue-500 dark:text-blue-400 mr-3"></i>
                  <span><strong>Scoreness Score:</strong> Yes</span>
                </li>
                <li class="flex items-center">
                  <i class="fas fa-chart-line text-blue-500 dark:text-blue-400 mr-3"></i>
                  <span><strong>Performance Overview:</strong> Yes</span>
                </li>
              </ul>
              <form action="{{ route('payment.checkout.show') }}" method="GET" class="mt-6">
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                <input type="hidden" name="amount" value="{{ $plan->price }}">
                <button type="submit" class="w-full py-3 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300">
                  Select Plan
                </button>
              </form>
            </div>
          @endforeach
        </div>
      </div>
    </section>

    <section class="my-10 w-full px-4 mt-12">
      <div class="overflow-x-auto">
        <table id="comparison-table" class="min-w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
          <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
              <th class="py-4 px-6 text-left text-sm font-medium text-gray-600 dark:text-gray-300 uppercase">Feature</th>
              @foreach($plans as $plan)
                <th class="comparison-col py-4 px-6 text-center text-sm font-medium text-gray-600 dark:text-gray-300 uppercase" data-plan-id="{{ $plan->id }}">
                  <div class="flex flex-col items-center space-y-2">
                    <!-- Using inline Font Awesome icons for plan tiers -->
                    <i class="{{ $planIcons[$plan->name] ?? 'fas fa-star' }} text-3xl text-blue-500 dark:text-blue-400"></i>
                    <span>{{ $plan->name }}</span>
                  </div>
                </th>
              @endforeach
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <!-- Price Row (Shown exactly as stored in the database with monthly indication) -->
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="py-4 px-6 text-sm font-medium text-gray-700 dark:text-gray-300">Price</td>
              @foreach($plans as $plan)
                <td class="comparison-col py-4 px-6 text-center text-sm text-gray-700 dark:text-gray-300 price-cell" data-plan-id="{{ $plan->id }}" data-price="{{ $plan->price }}">
                  ${{ number_format($plan->price, 2) }} <span class="text-lg font-normal text-gray-500 dark:text-gray-400">/month</span>
                </td>
              @endforeach
            </tr>
            <!-- Product Limit Row -->
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="py-4 px-6 text-sm font-medium text-gray-700 dark:text-gray-300">Product Limit</td>
              @foreach($plans as $plan)
                <td class="comparison-col py-4 px-6 text-center text-sm text-gray-700 dark:text-gray-300" data-plan-id="{{ $plan->id }}">
                  {{ $plan->product_limit }}
                </td>
              @endforeach
            </tr>
            <!-- Widget Limit Row -->
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="py-4 px-6 text-sm font-medium text-gray-700 dark:text-gray-300">Widget Limit</td>
              @foreach($plans as $plan)
                <td class="comparison-col py-4 px-6 text-center text-sm text-gray-700 dark:text-gray-300" data-plan-id="{{ $plan->id }}">
                  {{ $plan->widget_limit }}
                </td>
              @endforeach
            </tr>
            <!-- HTML Integration Row -->
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="py-4 px-6 text-sm font-medium text-gray-700 dark:text-gray-300">HTML Integration</td>
              @foreach($plans as $plan)
                <td class="comparison-col py-4 px-6 text-center text-sm text-gray-700 dark:text-gray-300" data-plan-id="{{ $plan->id }}">
                  {{ $plan->html_integration_limit }} characters
                </td>
              @endforeach
            </tr>
            <!-- Review Invitation Row -->
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="py-4 px-6 text-sm font-medium text-gray-700 dark:text-gray-300">Review Invitation</td>
              @foreach($plans as $plan)
                <td class="comparison-col py-4 px-6 text-center text-sm text-gray-700 dark:text-gray-300" data-plan-id="{{ $plan->id }}">
                  {{ $plan->review_invitation_limit }}
                </td>
              @endforeach
            </tr>
            <!-- Referral Code Row -->
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="py-4 px-6 text-sm font-medium text-gray-700 dark:text-gray-300">Referral Code</td>
              @foreach($plans as $plan)
                <td class="comparison-col py-4 px-6 text-center text-sm" data-plan-id="{{ $plan->id }}">
                  @if($plan->referral_code)
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-500">
                      <i class="fas fa-check text-white"></i>
                    </span>
                  @else
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-500">
                      <i class="fas fa-times text-white"></i>
                    </span>
                  @endif
                </td>
              @endforeach
            </tr>
            <!-- Smart Analytics Row -->
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="py-4 px-6 text-sm font-medium text-gray-700 dark:text-gray-300">Smart Analytics</td>
              @foreach($plans as $plan)
                <td class="comparison-col py-4 px-6 text-center text-sm" data-plan-id="{{ $plan->id }}">
                  @if($plan->name === 'Pro' || $plan->name === 'Premium')
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-500">
                      <i class="fas fa-check text-white"></i>
                    </span>
                  @else
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-500">
                      <i class="fas fa-times text-white"></i>
                    </span>
                  @endif
                </td>
              @endforeach
            </tr>
            <!-- Customize Profile Row -->
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="py-4 px-6 text-sm font-medium text-gray-700 dark:text-gray-300">Customize Profile</td>
              @foreach($plans as $plan)
                <td class="comparison-col py-4 px-6 text-center text-sm" data-plan-id="{{ $plan->id }}">
                  <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-500">
                    <i class="fas fa-check text-white"></i>
                  </span>
                </td>
              @endforeach
            </tr>
            <!-- Scoreness Score Row -->
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="py-4 px-6 text-sm font-medium text-gray-700 dark:text-gray-300">Scoreness Score</td>
              @foreach($plans as $plan)
                <td class="comparison-col py-4 px-6 text-center text-sm" data-plan-id="{{ $plan->id }}">
                  <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-500">
                    <i class="fas fa-check text-white"></i>
                  </span>
                </td>
              @endforeach
            </tr>
            <!-- Performance Overview Row -->
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="py-4 px-6 text-sm font-medium text-gray-700 dark:text-gray-300">Performance Overview</td>
              @foreach($plans as $plan)
                <td class="comparison-col py-4 px-6 text-center text-sm" data-plan-id="{{ $plan->id }}">
                  <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-500">
                    <i class="fas fa-check text-white"></i>
                  </span>
                </td>
              @endforeach
            </tr>
          </tbody>
        </table>
      </div>
    </section>
    </div> <!-- End of mainContent -->

    <!-- Hidden debugging elements -->
    <p id="user-coordinates" style="display: none;"></p>
    <p id="user-altitude" style="display: none;"></p>
    <p id="user-country" style="display: none;"></p>
@endsection

@push('scripts')
    <!-- JavaScript to handle location access and filter plan cards by country -->
    <script>
      // Allowed plan IDs based on country (using numbers for better comparison)
      const sriLankaPlans = [1, 5, 6, 7];
      const otherPlans   = [1, 2, 3, 4];

      // Function to show main content and hide location modal
      function showMainContent() {
        document.getElementById('locationModal').style.display = 'none';
        document.getElementById('mainContent').classList.remove('hidden');
      }

      // Function to show location modal and hide main content
      function showLocationModal() {
        document.getElementById('locationModal').style.display = 'flex';
        document.getElementById('mainContent').classList.add('hidden');
      }

      // Function to filter plan cards by allowed IDs
      function filterPlans(allowedIds) {
        console.log('Filtering plans with IDs:', allowedIds);
        document.querySelectorAll('.plan-card').forEach(function(card) {
          const planId = parseInt(card.getAttribute('data-plan-id'));
          console.log('Checking plan card with ID:', planId, 'against allowed IDs:', allowedIds);
          if (allowedIds.includes(planId)) {
            card.style.display = 'block';
            card.style.visibility = 'visible';
            console.log('Showing plan card:', planId);
          } else {
            card.style.display = 'none';
            card.style.visibility = 'hidden';
            console.log('Hiding plan card:', planId);
          }
        });
      }

      // Function to filter comparison table columns
      function filterComparisonColumns(allowedIds) {
        console.log('Filtering comparison columns with IDs:', allowedIds);
        document.querySelectorAll('.comparison-col').forEach(function(col) {
          const planId = parseInt(col.getAttribute('data-plan-id'));
          console.log('Checking comparison column with ID:', planId, 'against allowed IDs:', allowedIds);
          if (allowedIds.includes(planId)) {
            col.style.display = 'table-cell';
            col.style.visibility = 'visible';
          } else {
            col.style.display = 'none';
            col.style.visibility = 'hidden';
          }
        });

        // Also filter the price cells in the comparison table
        document.querySelectorAll('.price-cell').forEach(function(cell) {
          const planId = parseInt(cell.getAttribute('data-plan-id'));
          if (allowedIds.includes(planId)) {
            cell.style.display = 'table-cell';
            cell.style.visibility = 'visible';
          } else {
            cell.style.display = 'none';
            cell.style.visibility = 'hidden';
          }
        });
      }

      // Function to request location access
      function requestLocationAccess() {
        if (navigator.geolocation) {
          console.log('Geolocation is supported, requesting location...');
          navigator.geolocation.getCurrentPosition(function(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            const altitude = position.coords.altitude; // may be null

            // Log coordinates and altitude for debugging
            console.log('User coordinates:', latitude, longitude);
            document.getElementById('user-coordinates').innerText = `Latitude: ${latitude}, Longitude: ${longitude}`;
            document.getElementById('user-altitude').innerText = altitude !== null ? `Altitude: ${altitude} meters` : "Altitude not available";

            // Show main content after getting location
            showMainContent();

            // Reverse geocode using Nominatim
            console.log('Fetching country information...');
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
              .then(response => response.json())
              .then(data => {
                console.log('Nominatim response:', data);
                if (data && data.address && data.address.country) {
                  const country = data.address.country;
                  document.getElementById('user-country').innerText = country;
                  console.log("Country detected:", country);

                  // Filter plans based on country without altering database prices.
                  if (country === "Sri Lanka" || country === "ශ්‍රී ලංකාව") {
                    console.log('Showing Sri Lanka plans:', sriLankaPlans);
                    filterPlans(sriLankaPlans);
                    filterComparisonColumns(sriLankaPlans);
                  } else {
                    console.log('Showing other country plans:', otherPlans);
                    filterPlans(otherPlans);
                    filterComparisonColumns(otherPlans);
                  }
                } else {
                  console.log('No country found in response, showing other plans');
                  filterPlans(otherPlans);
                  filterComparisonColumns(otherPlans);
                }
              })
              .catch(error => {
                console.error('Reverse geocoding error:', error);
                console.log('Error occurred, showing other plans');
                filterPlans(otherPlans);
                filterComparisonColumns(otherPlans);
              });
          }, function(error) {
            console.error('Geolocation error:', error);
            alert('Location access was denied or failed. Please enable location access and refresh the page to view pricing plans.');
            // Keep showing the location modal if access is denied
            showLocationModal();
          }, {
            timeout: 10000, // 10 second timeout
            enableHighAccuracy: true,
            maximumAge: 300000 // 5 minutes
          });
        } else {
          console.error("Geolocation is not supported by this browser.");
          alert('Geolocation is not supported by your browser. Please use a modern browser to access this page.');
          // Show other plans if geolocation is not supported
          showMainContent();
          filterPlans(otherPlans);
          filterComparisonColumns(otherPlans);
        }
      }

      // Event listener for enable location button
      document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('enableLocationBtn').addEventListener('click', function() {
          requestLocationAccess();
        });
      });
    </script>
@endpush
