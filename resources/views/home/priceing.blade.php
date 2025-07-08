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

    /* Location detection overlay styles */
    .location-detecting {
        position: relative;
    }

    .location-detecting::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(4px);
        z-index: 10;
        pointer-events: none;
    }

    .location-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 20;
        text-align: center;
        pointer-events: none;
    }

    .spinner {
        border: 4px solid #f3f4f6;
        border-top: 4px solid #3b82f6;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto 16px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<section id="pricingContent" class="py-10 bg-white sm:py-16 lg:py-24">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="max-w-xl mx-auto text-center">
            <h2 class="text-4xl font-bold text-black lg:text-5xl sm:text-5xl">Pricing &amp; Plans</h2>
            <p class="mt-4 text-lg leading-relaxed text-gray-600">Choose the perfect plan to elevate your business presence and customer engagement.</p>
            <div id="locationInfo" class="mt-4 p-3 bg-blue-50 rounded-lg">
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
        <div id="desktopPlans" class="hidden mt-16 lg:block">
            <div class="location-overlay hidden">
                <div class="spinner"></div>
                <p class="text-gray-600 font-medium">Detecting your location...</p>
                <p class="text-sm text-gray-500 mt-2">Finding the best plans for your area</p>
            </div>
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
    <div id="mobilePlans" class="block mt-12 border-t border-b border-gray-200 divide-y divide-gray-200 lg:hidden">
        <div class="location-overlay hidden">
            <div class="spinner"></div>
            <p class="text-gray-600 font-medium">Detecting your location...</p>
            <p class="text-sm text-gray-500 mt-2">Finding the best plans for your area</p>
        </div>
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

    // Function to filter comparison table columns
    function filterComparisonColumns(allowedIds) {
        console.log('Filtering with allowed IDs:', allowedIds);
        const allColumns = document.querySelectorAll('.comparison-col');
        console.log('Total columns found:', allColumns.length);

        allColumns.forEach(function(col) {
            const planId = col.getAttribute('data-plan-id');
            console.log('Processing column with plan ID:', planId);

            if (planId && !allowedIds.includes(planId)) {
                console.log('Hiding column with plan ID:', planId);
                col.style.display = 'none';
            } else {
                console.log('Showing column with plan ID:', planId);
                col.style.display = '';
            }
        });
    }

    // Function to show location detection overlay
    function showLocationDetecting() {
        const desktopPlans = document.getElementById('desktopPlans');
        const mobilePlans = document.getElementById('mobilePlans');

        // Add blur class and show overlay
        desktopPlans.classList.add('location-detecting');
        mobilePlans.classList.add('location-detecting');

        // Show overlay
        desktopPlans.querySelector('.location-overlay').classList.remove('hidden');
        mobilePlans.querySelector('.location-overlay').classList.remove('hidden');

        updateLocationDisplay('Detecting location...');
    }

    // Function to update location display
    function updateLocationDisplay(locationText) {
        const userLocationDisplay = document.getElementById('userLocationDisplay');
        if (userLocationDisplay) {
            userLocationDisplay.textContent = locationText;
        }
    }    // Function to request location using default browser geolocation
    function requestLocation() {
        if (!navigator.geolocation) {
            console.error("Geolocation is not supported by this browser.");
            filterComparisonColumns(otherPlans);
            updateLocationDisplay('Geolocation not supported - Showing available plans');
            return;
        }

        console.log('Requesting geolocation...');
        showLocationDetecting();

        navigator.geolocation.getCurrentPosition(
            function(position) {
                console.log('Geolocation success');
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                // Log coordinates for debugging
                console.log(`Coordinates: ${latitude}, ${longitude}`);

                // Reverse geocode using Nominatim
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.address && data.address.country) {
                            const country = data.address.country;
                            const city = data.address.city || data.address.town || data.address.village || '';
                            const displayLocation = city ? `${city}, ${country}` : country;

                            console.log("Detected country:", country);

                            // Filter plans based on country
                            if (country === "Sri Lanka" || country === "ශ්‍රී ලංකාව") {
                                console.log('Sri Lanka detected, showing Sri Lanka plans');
                                filterComparisonColumns(sriLankaPlans);
                            } else {
                                console.log('Other country detected, showing other plans');
                                filterComparisonColumns(otherPlans);
                            }

                            hideLocationDetecting();
                            updateLocationDisplay(displayLocation);
                        } else {
                            console.log('Country detection failed, showing other plans');
                            filterComparisonColumns(otherPlans);
                            hideLocationDetecting();
                            updateLocationDisplay('Location detected - Showing available plans');
                        }
                    })
                    .catch(error => {
                        console.error('Reverse geocoding error:', error);
                        filterComparisonColumns(otherPlans);
                        hideLocationDetecting();
                        updateLocationDisplay('Location detected - Showing available plans');
                    });
            },
            function(error) {
                console.error('Geolocation error:', error);
                console.error('Error code:', error.code);
                console.error('Error message:', error.message);

                // Show otherPlans when location access is denied or unavailable
                console.log('Showing otherPlans due to geolocation error');
                filterComparisonColumns(otherPlans);
                hideLocationDetecting();

                if (error.code === error.PERMISSION_DENIED) {
                    updateLocationDisplay('Location access denied - Showing available plans');
                } else if (error.code === error.POSITION_UNAVAILABLE) {
                    updateLocationDisplay('Location unavailable - Showing available plans');
                } else if (error.code === error.TIMEOUT) {
                    updateLocationDisplay('Location timeout - Showing available plans');
                } else {
                    updateLocationDisplay('Location error - Showing available plans');
                }
            },
            {
                enableHighAccuracy: true,
                timeout: 50000, // 10 seconds timeout
                maximumAge: 300000 // 5 minutes cache
            }
        );
    }

    // Auto-request location on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded, requesting location...');
        requestLocation();
    });
</script>

@endsection
