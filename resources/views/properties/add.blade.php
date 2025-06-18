@extends('layouts.app')

@section('title', 'Add Business - Scoreness')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Container wrapper for consistent width -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Modern Header with Pattern Background -->
        <div class="bg-white rounded-xl overflow-hidden shadow-sm mb-6">
            <div class="relative bg-gradient-to-br from-blue-500 to-indigo-600">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                        <defs>
                            <pattern id="pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M0 20 L40 20" stroke="#fff" stroke-width="1" fill="none" />
                                <path d="M20 0 L20 40" stroke="#fff" stroke-width="1" fill="none" />
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#pattern)" />
                    </svg>
                </div>

                <div class="relative px-8 py-16 sm:px-10 sm:py-14">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <!-- Left: Icon -->
                        <div class="flex-shrink-0 mb-6 md:mb-0 md:mr-8">
                            <div class="w-20 h-20 rounded-full bg-white p-1 shadow-lg flex items-center justify-center">
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 rounded-full">
                                    <i class="fas fa-building text-blue-600 text-3xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Page Title and Description -->
                        <div class="text-white">
                            <h1 class="text-3xl font-bold mb-2">Add a Business</h1>
                            <p class="text-blue-100 max-w-2xl">
                                Help the community discover great businesses by adding them to our platform. Business owners can claim their listing later.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Add a Business</h2>
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    Business owners can claim their listing later
                </span>
            </div>

            @guest
                <!-- Guest user message about login requirement -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-yellow-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Login Required</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>You need to be logged in to add a business. Please <a href="{{ route('login') }}" class="font-medium underline">log in</a> or <a href="{{ route('register') }}" class="font-medium underline">create an account</a> to continue.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Multi-step form -->
                <form id="addPropertyForm" action="{{ route('properties.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Step Indicator -->
                    <div class="relative mb-8">
                        <!-- Desktop Version -->
                        <div class="hidden md:flex items-center justify-between mb-2">
                            <div id="step1Indicator" class="flex items-center">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                                <span class="ml-2 font-medium text-blue-600">Property Type</span>
                            </div>
                            <div id="step2Indicator" class="flex items-center opacity-50">
                                <div class="w-8 h-8 bg-gray-300 text-white rounded-full flex items-center justify-center font-bold">2</div>
                                <span class="ml-2 font-medium text-gray-500">Business Details</span>
                            </div>
                            <div id="step3Indicator" class="flex items-center opacity-50">
                                <div class="w-8 h-8 bg-gray-300 text-white rounded-full flex items-center justify-center font-bold">3</div>
                                <span class="ml-2 font-medium text-gray-500">Category</span>
                            </div>
                            <div id="step4Indicator" class="flex items-center opacity-50">
                                <div class="w-8 h-8 bg-gray-300 text-white rounded-full flex items-center justify-center font-bold">4</div>
                                <span class="ml-2 font-medium text-gray-500">Subcategory</span>
                            </div>
                        </div>

                        <!-- Mobile Version -->
                        <div class="md:hidden mb-4">
                            <div class="flex items-center justify-center space-x-2 sm:space-x-4 mb-2">
                                <div id="step1IndicatorMobile" class="flex flex-col items-center">
                                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">1</div>
                                    <span class="text-xs font-medium text-blue-600 mt-1 text-center">Property</span>
                                </div>
                                <div class="flex-1 h-0.5 bg-gray-300 mx-1"></div>
                                <div id="step2IndicatorMobile" class="flex flex-col items-center opacity-50">
                                    <div class="w-8 h-8 bg-gray-300 text-white rounded-full flex items-center justify-center font-bold text-sm">2</div>
                                    <span class="text-xs font-medium text-gray-500 mt-1 text-center">Details</span>
                                </div>
                                <div class="flex-1 h-0.5 bg-gray-300 mx-1"></div>
                                <div id="step3IndicatorMobile" class="flex flex-col items-center opacity-50">
                                    <div class="w-8 h-8 bg-gray-300 text-white rounded-full flex items-center justify-center font-bold text-sm">3</div>
                                    <span class="text-xs font-medium text-gray-500 mt-1 text-center">Category</span>
                                </div>
                                <div class="flex-1 h-0.5 bg-gray-300 mx-1"></div>
                                <div id="step4IndicatorMobile" class="flex flex-col items-center opacity-50">
                                    <div class="w-8 h-8 bg-gray-300 text-white rounded-full flex items-center justify-center font-bold text-sm">4</div>
                                    <span class="text-xs font-medium text-gray-500 mt-1 text-center">Subcategory</span>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="overflow-hidden h-2 mb-4 flex rounded bg-gray-200">
                            <div id="progressBar" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-600 w-1/4 transition-all duration-300"></div>
                        </div>
                    </div>

                    <!-- Step 1: Property Type -->
                    <div id="step1" class="transition-opacity duration-300">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Select Property Type</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-blue-500 transition-colors cursor-pointer property-type-option">
                                <input type="radio" name="property_type" id="physical" value="Physical" class="hidden" required>
                                <label for="physical" class="flex flex-col h-full cursor-pointer">
                                    <div class="flex items-center mb-3">
                                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-store text-lg"></i>
                                        </div>
                                        <span class="text-lg font-medium text-gray-900">Physical Business</span>
                                    </div>
                                    <p class="text-gray-600 text-sm flex-grow">
                                        A business with a physical location that customers can visit, such as a store, restaurant, or office.
                                    </p>
                                    <div class="mt-4 flex items-center">
                                        <span class="inline-flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full border-2 border-gray-400 property-radio-indicator"></span>
                                        <span class="ml-2 text-sm font-medium text-gray-900">Select this option</span>
                                    </div>
                                </label>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-blue-500 transition-colors cursor-pointer property-type-option">
                                <input type="radio" name="property_type" id="web" value="Web" class="hidden" required>
                                <label for="web" class="flex flex-col h-full cursor-pointer">
                                    <div class="flex items-center mb-3">
                                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-globe-americas text-lg"></i>
                                        </div>
                                        <span class="text-lg font-medium text-gray-900">Web Business</span>
                                    </div>
                                    <p class="text-gray-600 text-sm flex-grow">
                                        An online business without physical locations, such as an e-commerce store, digital service, or web application.
                                    </p>
                                    <div class="mt-4 flex items-center">
                                        <span class="inline-flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full border-2 border-gray-400 property-radio-indicator"></span>
                                        <span class="ml-2 text-sm font-medium text-gray-900">Select this option</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" id="step1Next" class="px-4 py-2 bg-blue-300 text-white rounded hover:bg-blue-700 disabled:cursor-not-allowed" disabled>Continue</button>
                        </div>
                    </div>

                    <!-- Step 2: Business Details -->
                    <div id="step2" class="hidden transition-opacity duration-300">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Business Details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label for="business_name" class="block text-sm font-medium text-gray-700">Business Name <span class="text-red-500">*</span></label>
                                <input type="text" id="business_name" name="business_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <div class="text-red-500 text-xs mt-1 hidden"></div>
                            </div>

                            <div class="col-span-2">
                                <label for="business_email" class="block text-sm font-medium text-gray-700">Business Email <span class="text-red-500">*</span></label>
                                <input type="email" id="business_email" name="business_email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <div class="text-red-500 text-xs mt-1 hidden"></div>
                            </div>

                            <div class="col-span-2" id="domain_field" style="display: none;">
                                <label for="domain" class="block text-sm font-medium text-gray-700">Website Domain <span class="text-red-500">*</span></label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">https://</span>
                                    <input type="text" name="domain" id="domain" class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300 px-3 py-2 border" placeholder="example.com">
                                </div>
                                <div class="text-red-500 text-xs mt-1 hidden"></div>
                            </div>

                            <div id="city_field">
                                <label for="city" class="block text-sm font-medium text-gray-700">City <span class="text-red-500">*</span></label>
                                <input type="text" id="city" name="city" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <div class="text-red-500 text-xs mt-1 hidden"></div>
                            </div>

                            <div id="zip_code_field">
                                <label for="zip_code" class="block text-sm font-medium text-gray-700">Zip Code</label>
                                <input type="text" id="zip_code" name="zip_code" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <div class="text-red-500 text-xs mt-1 hidden"></div>
                            </div>

                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700">Country <span class="text-red-500">*</span></label>
                                <select id="country" name="country" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">Select Country</option>
                                    <option value="United States">United States</option>
                                    <option value="Canada">Canada</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="France">France</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="text-red-500 text-xs mt-1 hidden"></div>
                            </div>

                            <div id="other_country_field" class="hidden">
                                <label for="other_country" class="block text-sm font-medium text-gray-700">Specify Country <span class="text-red-500">*</span></label>
                                <input type="text" id="other_country" name="other_country" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button" id="step2Prev" class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">Back</button>
                            <button type="button" id="step2Next" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Continue</button>
                        </div>
                    </div>

                    <!-- Step 3: Category -->
                    <div id="step3" class="hidden transition-opacity duration-300">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Business Category</h3>

                        <div class="mb-6">
                            <label for="category-select" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>

                            <div class="flex space-x-4 mb-4">
                                <div class="flex items-center">
                                    <input type="radio" id="existing-category" name="category_type" value="existing" class="mr-2" checked>
                                    <label for="existing-category">Select existing category</label>
                                </div>

                                <div class="flex items-center">
                                    <input type="radio" id="new-category" name="category_type" value="new" class="mr-2">
                                    <label for="new-category">Add new category</label>
                                </div>
                            </div>

                            <div id="existing-category-container">
                                <label for="existing_category" class="block text-sm font-medium text-gray-700 mb-1">Select Category <span class="text-red-500">*</span></label>
                                <select id="existing_category" name="existing_category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select from existing categories</option>
                                    @php
                                        $categories = \App\Models\Category::where('is_active', 1)->orderBy('name')->get();
                                    @endphp

                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="new-category-container" class="hidden">
                                <label for="new_category" class="block text-sm font-medium text-gray-700 mb-1">New Category <span class="text-red-500">*</span></label>
                                <input type="text" id="new_category" name="new_category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter new category name">
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button" id="step3Prev" class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">Back</button>
                            <button type="button" id="step3Next" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Continue</button>
                        </div>
                    </div>

                    <!-- Step 4: Subcategory -->
                    <div id="step4" class="hidden transition-opacity duration-300">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Select Subcategory</h3>

                        <div id="subcategory-content" class="mb-6">
                            <!-- Dynamic content for subcategory selection -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Subcategory <span class="text-red-500">*</span>
                                </label>

                                <div class="flex space-x-4 mb-4">
                                    <div class="flex items-center">
                                        <input type="radio" id="existing_subcategory_option" name="subcategory_option" value="existing" class="mr-2" checked>
                                        <label for="existing_subcategory_option">Select existing subcategory</label>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="radio" id="new_subcategory_option" name="subcategory_option" value="new" class="mr-2">
                                        <label for="new_subcategory_option">Add new subcategory</label>
                                    </div>
                                </div>

                                <div id="existing_subcategory_container" class="mb-4">
                                    <select id="existing_subcategory" name="existing_subcategory" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Select a subcategory</option>
                                    </select>
                                </div>

                                <div id="new_subcategory_container" class="mb-4 hidden">
                                    <input type="text" id="new_subcategory" name="new_subcategory" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter new subcategory name...">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button" id="step4Prev" class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">Back</button>
                            <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Business</button>
                        </div>
                    </div>
                </form>

                <!-- Form Success Message -->
                <div id="formSuccess" class="hidden">
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    Business successfully added! Thank you for contributing to the Scoreness community.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <a id="addReviewLink" href="#" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 mr-4">
                            <i class="fas fa-star mr-2"></i>Add Review
                        </a>
                        <a href="{{ route('home') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">
                            Return to Homepage
                        </a>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// SINGLE COMPLETE FORM SCRIPT
document.addEventListener('DOMContentLoaded', function() {
    console.log('Form script loaded');

    // Multi-step form elements
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    const step4 = document.getElementById('step4');

    const step1Next = document.getElementById('step1Next');
    const step2Prev = document.getElementById('step2Prev');
    const step2Next = document.getElementById('step2Next');
    const step3Prev = document.getElementById('step3Prev');
    const step3Next = document.getElementById('step3Next');
    const step4Prev = document.getElementById('step4Prev');

    const step1Indicator = document.getElementById('step1Indicator');
    const step2Indicator = document.getElementById('step2Indicator');
    const step3Indicator = document.getElementById('step3Indicator');
    const step4Indicator = document.getElementById('step4Indicator');

    const progressBar = document.getElementById('progressBar');

    // Property type handling
    document.querySelectorAll('.property-type-option').forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;

            // Update UI
            document.querySelectorAll('.property-type-option').forEach(opt => {
                if (opt.querySelector('input[type="radio"]').checked) {
                    opt.classList.add('border-blue-500', 'ring-2', 'ring-blue-200');
                    opt.querySelector('.property-radio-indicator').classList.remove('border-gray-400');
                    opt.querySelector('.property-radio-indicator').classList.add('border-blue-600', 'bg-blue-600');
                    opt.querySelector('.property-radio-indicator').innerHTML = '<span class="inline-block h-3 w-3 rounded-full bg-white"></span>';
                } else {
                    opt.classList.remove('border-blue-500', 'ring-2', 'ring-blue-200');
                    opt.querySelector('.property-radio-indicator').classList.add('border-gray-400');
                    opt.querySelector('.property-radio-indicator').classList.remove('border-blue-600', 'bg-blue-600');
                    opt.querySelector('.property-radio-indicator').innerHTML = '';
                }
            });

            // Enable continue button
            step1Next.disabled = false;
            step1Next.classList.remove('bg-blue-300');
            step1Next.classList.add('bg-blue-600');

            // Toggle fields
            if (radio.value === 'Web') {
                document.getElementById('domain_field').style.display = 'block';
                document.getElementById('city_field').style.display = 'none';
                document.getElementById('zip_code_field').style.display = 'none';
            } else {
                document.getElementById('domain_field').style.display = 'none';
                document.getElementById('city_field').style.display = 'block';
                document.getElementById('zip_code_field').style.display = 'block';
            }
        });
    });

    // Step 1 to 2
    step1Next.addEventListener('click', function() {
        step1.classList.add('hidden');
        step2.classList.remove('hidden');
        updateStepIndicators(2);
    });

    // Step 2 back to 1
    step2Prev.addEventListener('click', function() {
        step2.classList.add('hidden');
        step1.classList.remove('hidden');
        updateStepIndicators(1);
    });

    // Step 2 to 3
    step2Next.addEventListener('click', function() {
        step2.classList.add('hidden');
        step3.classList.remove('hidden');
        updateStepIndicators(3);
    });

    // Step 3 back to 2
    step3Prev.addEventListener('click', function() {
        step3.classList.add('hidden');
        step2.classList.remove('hidden');
        updateStepIndicators(2);
    });

    // Step 3 to 4 with subcategory loading
    step3Next.addEventListener('click', function() {
        step3.classList.add('hidden');
        step4.classList.remove('hidden');
        updateStepIndicators(4);

        // Load subcategories
        setTimeout(loadSubcategories, 200);
    });

    // Step 4 back to 3
    step4Prev.addEventListener('click', function() {
        step4.classList.add('hidden');
        step3.classList.remove('hidden');
        updateStepIndicators(3);
    });

    // Update step indicators
    function updateStepIndicators(currentStep) {
        // Get both desktop and mobile indicators
        const desktopIndicators = [step1Indicator, step2Indicator, step3Indicator, step4Indicator];
        const mobileIndicators = [
            document.getElementById('step1IndicatorMobile'),
            document.getElementById('step2IndicatorMobile'),
            document.getElementById('step3IndicatorMobile'),
            document.getElementById('step4IndicatorMobile')
        ];

        // Update both desktop and mobile indicators
        [desktopIndicators, mobileIndicators].forEach(indicators => {
            indicators.forEach((indicator, index) => {
                if (!indicator) return;

                const stepNum = index + 1;
                const circle = indicator.querySelector('div');
                const text = indicator.querySelector('span');

                if (stepNum < currentStep) {
                    // Completed step
                    indicator.classList.remove('opacity-50');
                    circle.classList.remove('bg-gray-300', 'bg-blue-600');
                    circle.classList.add('bg-green-600');
                    text.classList.remove('text-gray-500', 'text-blue-600');
                    text.classList.add('text-green-600');
                } else if (stepNum === currentStep) {
                    // Current step
                    indicator.classList.remove('opacity-50');
                    circle.classList.remove('bg-gray-300', 'bg-green-600');
                    circle.classList.add('bg-blue-600');
                    text.classList.remove('text-gray-500', 'text-green-600');
                    text.classList.add('text-blue-600');
                } else {
                    // Future step
                    indicator.classList.add('opacity-50');
                    circle.classList.remove('bg-blue-600', 'bg-green-600');
                    circle.classList.add('bg-gray-300');
                    text.classList.remove('text-blue-600', 'text-green-600');
                    text.classList.add('text-gray-500');
                }
            });
        });

        // Update progress bar
        const progressWidths = ['w-1/4', 'w-2/4', 'w-3/4', 'w-full'];
        progressBar.className = progressBar.className.replace(/w-\d+\/\d+|w-full/g, '');
        progressBar.classList.add(progressWidths[currentStep - 1]);
    }

    // Category type toggle
    document.querySelectorAll('input[name="category_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const existingContainer = document.getElementById('existing-category-container');
            const newContainer = document.getElementById('new-category-container');

            if (this.value === 'existing') {
                existingContainer.classList.remove('hidden');
                newContainer.classList.add('hidden');
            } else {
                existingContainer.classList.add('hidden');
                newContainer.classList.remove('hidden');
            }
        });
    });

    // Subcategory option toggle
    document.querySelectorAll('input[name="subcategory_option"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const existingContainer = document.getElementById('existing_subcategory_container');
            const newContainer = document.getElementById('new_subcategory_container');

            if (this.value === 'existing') {
                existingContainer.classList.remove('hidden');
                newContainer.classList.add('hidden');
            } else {
                existingContainer.classList.add('hidden');
                newContainer.classList.remove('hidden');
            }
        });
    });

    // Country field toggle
    document.getElementById('country').addEventListener('change', function() {
        const otherField = document.getElementById('other_country_field');
        if (this.value === 'Other') {
            otherField.classList.remove('hidden');
        } else {
            otherField.classList.add('hidden');
        }
    });

    // Load subcategories function
    function loadSubcategories() {
        console.log('Loading subcategories...');

        const categoryType = document.querySelector('input[name="category_type"]:checked')?.value;
        if (categoryType !== 'existing') {
            console.log('New category selected');
            return;
        }

        const categorySelect = document.getElementById('existing_category');
        if (!categorySelect || !categorySelect.value) {
            console.log('No category selected');
            return;
        }

        const categoryId = categorySelect.value;
        const subcategorySelect = document.getElementById('existing_subcategory');

        if (!subcategorySelect) {
            console.error('Subcategory select not found');
            return;
        }

        subcategorySelect.innerHTML = '<option value="">Loading...</option>';

        fetch(`/test-subcategories/${categoryId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Subcategories loaded:', data);

                subcategorySelect.innerHTML = '<option value="">Select a subcategory</option>';

                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub.id;
                        option.textContent = sub.name;
                        subcategorySelect.appendChild(option);
                    });
                } else {
                    subcategorySelect.innerHTML = '<option value="">No subcategories available</option>';
                }
            })
            .catch(error => {
                console.error('Error loading subcategories:', error);
                subcategorySelect.innerHTML = '<option value="">Error loading</option>';
            });
    }

    // FORM SUBMISSION (update the success handling part)
    document.getElementById('addPropertyForm').addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Form submitted');

        const formData = new FormData(this);

        // Add subcategory type
        const subcategoryType = document.querySelector('input[name="subcategory_option"]:checked')?.value || 'existing';
        formData.set('subcategory_type', subcategoryType);

        // Debug: Log form data
        console.log('Form data:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);

            if (data.success) {
                document.getElementById('addPropertyForm').style.display = 'none';
                document.getElementById('formSuccess').classList.remove('hidden');

                // Set the review link to use your existing rate route
                const addReviewLink = document.getElementById('addReviewLink');
                if (addReviewLink && data.property_id) {
                    // Use your existing rate.create route
                    addReviewLink.href = `/rate/${data.property_id}`;
                }

                if (data.category_created || data.subcategory_created) {
                    const approvalMessage = document.createElement('div');
                    approvalMessage.className = 'bg-yellow-50 border border-yellow-200 rounded-md p-4 mt-4';
                    approvalMessage.innerHTML = `
                        <div class="flex">
                            <i class="fas fa-clock text-yellow-500 mr-2"></i>
                            <p class="text-sm text-yellow-700">
                                ${data.category_created ? 'New category' : ''}
                                ${data.category_created && data.subcategory_created ? ' and ' : ''}
                                ${data.subcategory_created ? 'subcategory' : ''}
                                will be reviewed by administrators.
                            </p>
                        </div>
                    `;
                    document.getElementById('formSuccess').appendChild(approvalMessage);
                }
            } else {
                alert('Error: ' + data.message);
                console.error('Server error:', data);
            }
        })
        .catch(error => {
            console.error('Network error:', error);
            alert('Network error occurred');
        });
    });

    // Test function
    window.testFormSubmission = function() {
        const form = document.getElementById('addPropertyForm');
        const formData = new FormData(form);

        console.log('Testing form submission with data:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
    };
});
</script>
@endpush
