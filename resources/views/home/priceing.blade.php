@extends('layouts.app')

@section('title', 'Pricing & Plans - Scoreness')

@section('content')
<style>
    /* Ensure white background for the entire pricing page */
    body {
        background-color: #ffffff !important;
    }

    .main-content {
        background-color: #ffffff !important;
    }

    /* Override any default app layout backgrounds */
    #app {
        background-color: #ffffff !important;
    }
</style>

<!-- Location Access Required Modal -->
<div id="locationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md mx-4 text-center">
        <div id="locationLoading" class="hidden">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Requesting Location Access</h3>
            <p class="text-gray-600">Please allow location access to view location-based pricing plans.</p>
        </div>

        <div id="locationRequest">
            <div class="mb-4">
                <svg class="w-16 h-16 text-blue-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Location Access Required</h3>
            <p class="text-gray-600 mb-6">We need access to your location to show you the most relevant pricing plans available in your area.</p>
            <button id="allowLocationBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                Allow Location Access
            </button>
        </div>

        <div id="locationError" class="hidden">
            <div class="mb-4">
                <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-red-600 mb-2">Location Access Denied</h3>
            <p class="text-gray-600 mb-6">Location access is required to view our pricing plans. Please enable location access in your browser settings and refresh the page.</p>
            <button onclick="window.location.reload()" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                Refresh Page
            </button>
        </div>
    </div>
</div>

<section id="pricingContent" class="py-10 bg-white sm:py-16 lg:py-24" style="display: none;">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="max-w-xl mx-auto text-center">
            <h2 class="text-4xl font-bold text-black lg:text-5xl sm:text-5xl">Pricing &amp; Plans</h2>
            <p class="mt-4 text-lg leading-relaxed text-gray-600">Choose the perfect plan to elevate your business presence and customer engagement.</p>
            <div id="locationInfo" class="mt-4 p-3 bg-blue-50 rounded-lg" style="display: none;">
                <p class="text-sm text-blue-700">
                    <span class="font-medium">📍 Location:</span> <span id="userLocationDisplay">Detecting...</span>
                </p>
            </div>
        </div>

        @php
            // Map the plan names to suitable Font Awesome icons
            $planIcons = [
                'Free'    => 'fas fa-gift',
                'Basic'   => 'fas fa-check-circle',
                'Pro'     => 'fas fa-rocket',
                'Premium' => 'fas fa-crown',
            ];
        @endphp

        <!-- lg+ -->
        <div class="hidden mt-16 lg:block">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="py-8 pr-4"></th>

                        @foreach($plans as $plan)
                            <th class="comparison-col px-4 py-8 text-center {{ $plan->name == 'Pro' ? 'bg-gray-900 rounded-t-xl' : '' }}" data-plan-id="{{ $plan->id }}">
                                @if($plan->name == 'Pro')
                                    <span class="px-4 py-2 text-base font-medium text-white bg-blue-600 rounded-full"> Popular </span>
                                @else
                                    <span class="text-base font-medium text-blue-600"> {{ $plan->name }} </span>
                                @endif
                                <p class="mt-6 text-6xl font-bold {{ $plan->name == 'Pro' ? 'text-white' : '' }}">${{ number_format($plan->price, 2) }}</p>
                                <p class="mt-2 text-base font-normal {{ $plan->name == 'Pro' ? 'text-gray-300' : 'text-gray-500' }}">Per month</p>
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    <!-- Product Limit Row -->
                    <tr>
                        <td class="py-4 pr-4 font-medium border-b border-gray-200">Product Limit</td>

                        @foreach($plans as $plan)
                            <td class="comparison-col px-4 py-4 text-center text-gray-700 border-b border-gray-200 {{ $plan->name == 'Pro' ? 'bg-gray-900 text-white' : '' }}" data-plan-id="{{ $plan->id }}">
                                {{ $plan->product_limit == 0 ? '-' : $plan->product_limit }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- Widget Limit Row -->
                    <tr>
                        <td class="py-4 pr-4 font-medium border-b border-gray-200">Widget Limit</td>

                        @foreach($plans as $plan)
                            <td class="comparison-col px-4 py-4 text-center text-gray-700 border-b border-gray-200 {{ $plan->name == 'Pro' ? 'bg-gray-900 text-white' : '' }}" data-plan-id="{{ $plan->id }}">
                                {{ $plan->widget_limit == 0 ? '-' : $plan->widget_limit }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- HTML Integration Row -->
                    <tr>
                        <td class="py-4 pr-4 font-medium border-b border-gray-200">HTML Integration</td>

                        @foreach($plans as $plan)
                            <td class="comparison-col px-4 py-4 text-center text-gray-700 border-b border-gray-200 {{ $plan->name == 'Pro' ? 'bg-gray-900 text-white' : '' }}" data-plan-id="{{ $plan->id }}">
                                {{ $plan->html_integration_limit }} characters
                            </td>
                        @endforeach
                    </tr>

                    <!-- Review Invitation Row -->
                    <tr>
                        <td class="py-4 pr-4 font-medium border-b border-gray-200">Review Invitation</td>

                        @foreach($plans as $plan)
                            <td class="comparison-col px-4 py-4 text-center text-gray-700 border-b border-gray-200 {{ $plan->name == 'Pro' ? 'bg-gray-900 text-white' : '' }}" data-plan-id="{{ $plan->id }}">
                                {{ $plan->review_invitation_limit == 0 ? '-' : $plan->review_invitation_limit }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- Referral Code Row -->
                    <tr>
                        <td class="py-4 pr-4 font-medium border-b border-gray-200">Referral Code</td>

                        @foreach($plans as $plan)
                            <td class="comparison-col px-4 py-4 text-center border-b border-gray-200 {{ $plan->name == 'Pro' ? 'bg-gray-900' : '' }}" data-plan-id="{{ $plan->id }}">
                                @if($plan->referral_code)
                                    <svg class="w-5 h-5 mx-auto {{ $plan->name == 'Pro' ? 'text-green-300' : 'text-green-500' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 mx-auto {{ $plan->name == 'Pro' ? 'text-red-300' : 'text-red-500' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <!-- Smart Analytics Row -->
                    <tr>
                        <td class="py-4 pr-4 font-medium border-b border-gray-200">Smart Analytics</td>

                        @foreach($plans as $plan)
                            <td class="comparison-col px-4 py-4 text-center border-b border-gray-200 {{ $plan->name == 'Pro' ? 'bg-gray-900' : '' }}" data-plan-id="{{ $plan->id }}">
                                @if($plan->name == 'Pro' || $plan->name == 'Premium')
                                    <svg class="w-5 h-5 mx-auto {{ $plan->name == 'Pro' ? 'text-green-300' : 'text-green-500' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 mx-auto {{ $plan->name == 'Pro' ? 'text-red-300' : 'text-red-500' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <!-- Customize Profile Row -->
                    <tr>
                        <td class="py-4 pr-4 font-medium border-b border-gray-200">Customize Profile</td>

                        @foreach($plans as $plan)
                            <td class="comparison-col px-4 py-4 text-center border-b border-gray-200 {{ $plan->name == 'Pro' ? 'bg-gray-900' : '' }}" data-plan-id="{{ $plan->id }}">
                                <svg class="w-5 h-5 mx-auto {{ $plan->name == 'Pro' ? 'text-green-300' : 'text-green-500' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </td>
                        @endforeach
                    </tr>

                    <!-- Scoreness Score Row -->
                    <tr>
                        <td class="py-4 pr-4 font-medium border-b border-gray-200">Scoreness Score</td>

                        @foreach($plans as $plan)
                            <td class="comparison-col px-4 py-4 text-center border-b border-gray-200 {{ $plan->name == 'Pro' ? 'bg-gray-900' : '' }}" data-plan-id="{{ $plan->id }}">
                                <svg class="w-5 h-5 mx-auto {{ $plan->name == 'Pro' ? 'text-green-300' : 'text-green-500' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </td>
                        @endforeach
                    </tr>

                    <!-- Performance Overview Row -->
                    <tr>
                        <td class="py-4 pr-4 font-medium border-b border-gray-200">Performance Overview</td>

                        @foreach($plans as $plan)
                            <td class="comparison-col px-4 py-4 text-center border-b border-gray-200 {{ $plan->name == 'Pro' ? 'bg-gray-900' : '' }}" data-plan-id="{{ $plan->id }}">
                                <svg class="w-5 h-5 mx-auto {{ $plan->name == 'Pro' ? 'text-green-300' : 'text-green-500' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </td>
                        @endforeach
                    </tr>

                    <!-- Get Started Row -->
                    <tr>
                        <td class="py-6 pr-4"></td>

                        @foreach($plans as $plan)
                            <td class="comparison-col px-4 py-6 text-center {{ $plan->name == 'Pro' ? 'bg-blue-700 rounded-b-xl' : '' }}" data-plan-id="{{ $plan->id }}">
                                <a href="{{ route('plans.index') }}" title="" class="inline-flex items-center font-semibold {{ $plan->name == 'Pro' ? 'text-white' : 'text-blue-600 hover:text-blue-700' }}">
                                    Get Started
                                    <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- xs to lg -->
    <div class="block mt-12 border-t border-b border-gray-200 divide-y divide-gray-200 lg:hidden">
        <div class="grid grid-cols-4 text-center divide-x divide-gray-200">
            @foreach($plans as $index => $plan)
                <div class="comparison-col px-2 py-2" data-plan-id="{{ $plan->id }}">
                    <span class="text-sm font-medium text-blue-600"> {{ $plan->name }} </span>
                    <p class="mt-2 text-xl font-bold">${{ number_format($plan->price, 2) }}</p>
                    <span class="mt-1 text-sm font-normal text-gray-500"> Per month </span>
                </div>
            @endforeach
        </div>

        <div class="px-2 py-4 sm:px-4">
            <p class="font-semibold">Product Limit</p>
        </div>

        <div class="grid grid-cols-4 text-center divide-x divide-gray-200">
            @foreach($plans as $plan)
                <div class="comparison-col px-2 py-2" data-plan-id="{{ $plan->id }}">
                    {{ $plan->product_limit == 0 ? '-' : $plan->product_limit }}
                </div>
            @endforeach
        </div>

        <div class="px-2 py-4 sm:px-4">
            <p class="font-semibold">Widget Limit</p>
        </div>

        <div class="grid grid-cols-4 text-center divide-x divide-gray-200">
            @foreach($plans as $plan)
                <div class="comparison-col px-2 py-2" data-plan-id="{{ $plan->id }}">
                    {{ $plan->widget_limit == 0 ? '-' : $plan->widget_limit }}
                </div>
            @endforeach
        </div>

        <div class="px-2 py-4 sm:px-4">
            <p class="font-semibold">HTML Integration</p>
        </div>

        <div class="grid grid-cols-4 text-center divide-x divide-gray-200">
            @foreach($plans as $plan)
                <div class="comparison-col px-2 py-2" data-plan-id="{{ $plan->id }}">
                    {{ $plan->html_integration_limit }}
                </div>
            @endforeach
        </div>

        <div class="px-2 py-4 sm:px-4">
            <p class="font-semibold">Review Invitation</p>
        </div>

        <div class="grid grid-cols-4 text-center divide-x divide-gray-200">
            @foreach($plans as $plan)
                <div class="comparison-col px-2 py-2" data-plan-id="{{ $plan->id }}">
                    {{ $plan->review_invitation_limit == 0 ? '-' : $plan->review_invitation_limit }}
                </div>
            @endforeach
        </div>

        <div class="px-2 py-4 sm:px-4">
            <p class="font-semibold">Referral Code</p>
        </div>

        <div class="grid grid-cols-4 text-center divide-x divide-gray-200">
            @foreach($plans as $plan)
                <div class="comparison-col px-2 py-2" data-plan-id="{{ $plan->id }}">
                    @if($plan->referral_code)
                        <svg class="w-5 h-5 mx-auto text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg class="w-5 h-5 mx-auto text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-4 text-center divide-x divide-gray-200">
            @foreach($plans as $plan)
                <div class="comparison-col px-1 py-2 sm:px-4" data-plan-id="{{ $plan->id }}">
                    <span class="text-sm font-medium text-blue-600"> {{ $plan->name }} </span>
                    <p class="mt-2 text-xl font-bold">${{ number_format($plan->price, 2) }}</p>
                    <span class="mt-1 text-sm font-normal text-gray-500"> Per month </span>
                    <a href="{{ route('plans.index') }}" title="" class="flex items-center justify-center w-full px-1 py-2 mt-5 text-sm text-white transition-all duration-200 bg-blue-600 border border-transparent rounded-md hover:bg-blue-700" role="button"> Get Started </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Hidden debugging elements -->
    <p id="user-coordinates" style="display: none;"></p>
    <p id="user-altitude" style="display: none;"></p>
    <p id="user-country" style="display: none;"></p>
</section>



<!-- JavaScript to require location access and filter plan cards by country -->
<script>
    // Allowed plan IDs based on country
    const sriLankaPlans = ['1', '5', '6', '7'];
    const otherPlans = ['1', '2', '3', '4'];

    // DOM elements
    const locationModal = document.getElementById('locationModal');
    const locationLoading = document.getElementById('locationLoading');
    const locationRequest = document.getElementById('locationRequest');
    const locationError = document.getElementById('locationError');
    const pricingContent = document.getElementById('pricingContent');
    const allowLocationBtn = document.getElementById('allowLocationBtn');
    const userLocationDisplay = document.getElementById('userLocationDisplay');

    // Function to filter comparison table columns
    function filterComparisonColumns(allowedIds) {
        document.querySelectorAll('.comparison-col').forEach(function(col) {
            const planId = col.getAttribute('data-plan-id');
            if (planId && !allowedIds.includes(planId)) {
                col.style.display = 'none';
            }
        });
    }

    // Function to show location loading
    function showLocationLoading() {
        locationRequest.classList.add('hidden');
        locationError.classList.add('hidden');
        locationLoading.classList.remove('hidden');
    }

    // Function to show location error
    function showLocationError() {
        locationLoading.classList.add('hidden');
        locationRequest.classList.add('hidden');
        locationError.classList.remove('hidden');
    }

    // Function to hide modal and show content
    function showPricingContent(locationText = 'Location detected') {
        locationModal.style.display = 'none';
        pricingContent.style.display = 'block';
        userLocationDisplay.textContent = locationText;
    }

    // Function to request location
    function requestLocation() {
        if (!navigator.geolocation) {
            console.error("Geolocation is not supported by this browser.");
            showLocationError();
            return;
        }

        showLocationLoading();

        navigator.geolocation.getCurrentPosition(function(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            const altitude = position.coords.altitude;

            // Log coordinates and altitude for debugging
            document.getElementById('user-coordinates').innerText = `Latitude: ${latitude}, Longitude: ${longitude}`;
            document.getElementById('user-altitude').innerText = altitude !== null ? `Altitude: ${altitude} meters` : "Altitude not available";

            // Reverse geocode using Nominatim
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.address && data.address.country) {
                        const country = data.address.country;
                        const city = data.address.city || data.address.town || data.address.village || '';
                        const displayLocation = city ? `${city}, ${country}` : country;

                        document.getElementById('user-country').innerText = country;
                        console.log("Country:", country);

                        // Filter plans based on country
                        if (country === "Sri Lanka" || country === "ශ්‍රී ලංකාව") {
                            filterComparisonColumns(sriLankaPlans);
                        } else {
                            filterComparisonColumns(otherPlans);
                        }

                        // Show pricing content
                        showPricingContent(displayLocation);
                    } else {
                        filterComparisonColumns(otherPlans);
                        showPricingContent('Location detected');
                    }
                })
                .catch(error => {
                    console.error('Reverse geocoding error:', error);
                    filterComparisonColumns(otherPlans);
                    showPricingContent('Location detected');
                });
        }, function(error) {
            console.error('Geolocation error:', error);
            showLocationError();
        }, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 300000 // 5 minutes
        });
    }

    // Event listener for allow location button
    allowLocationBtn.addEventListener('click', requestLocation);

    // Auto-request location on page load if previously granted
    document.addEventListener('DOMContentLoaded', function() {
        // Check if geolocation permission was previously granted
        if (navigator.permissions) {
            navigator.permissions.query({name: 'geolocation'}).then(function(result) {
                if (result.state === 'granted') {
                    requestLocation();
                } else {
                    // Show modal to request permission
                    locationModal.style.display = 'flex';
                }
            });
        } else {
            // Fallback for browsers that don't support permissions API
            locationModal.style.display = 'flex';
        }
    });
</script>

@endsection
