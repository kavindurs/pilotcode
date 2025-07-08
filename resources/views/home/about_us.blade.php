@php
    // Determine which layout to use based on the current route
    $layout = request()->route()->getName() === 'business.about.us' ? 'layouts.business' : 'layouts.app';
@endphp

@extends($layout)

@section('title', 'About Us - Scoreness')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Container wrapper for consistent width -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-0">
        <!-- Modern Header with Pattern Background -->
        <div class="bg-white rounded-xl overflow-hidden shadow-sm mb-6">
            <div class="relative bg-gradient-to-br from-blue-500 to-indigo-600">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                        <defs>
                            <pattern id="about-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M0 20 L40 20" stroke="#fff" stroke-width="1" fill="none" />
                                <path d="M20 0 L20 40" stroke="#fff" stroke-width="1" fill="none" />
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#about-pattern)" />
                    </svg>
                </div>

                <div class="relative px-8 py-16 sm:px-10 sm:py-14">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <!-- Left: Icon -->
                        <div class="flex-shrink-0 mb-6 md:mb-0 md:mr-8">
                            <div class="w-20 h-20 rounded-full bg-white p-1 shadow-lg flex items-center justify-center">
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 rounded-full">
                                    <i class="fas fa-users text-2xl text-blue-600"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Page Title and Description -->
                        <div class="text-white">
                            <h1 class="text-3xl font-bold mb-2">About Scoreness</h1>
                            <p class="text-blue-100 max-w-2xl">
                                Your trusted partner in business ratings and reviews
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8">
            <div class="prose prose-blue max-w-none">
                <!-- Mission Section -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900">Our Mission</h2>
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-lg">
                        <p class="text-gray-700 leading-relaxed mb-0">
                            At Scoreness, we're committed to creating a transparent and trustworthy platform where businesses and customers can connect through authentic reviews and ratings. Our mission is to empower consumers with reliable information while helping businesses build credibility and improve their services.
                        </p>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mt-8">What We Do</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 p-4 rounded-lg mt-6">
                        <h3 class="text-xl font-bold text-gray-900">For Businesses</h3>
                        <ul class="list-disc pl-5 mt-2 text-gray-700">
                            <li>Manage your online reputation</li>
                            <li>Respond to customer feedback</li>
                            <li>Showcase your services and achievements</li>
                            <li>Build trust with potential customers</li>
                        </ul>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg mt-6">
                        <h3 class="text-xl font-bold text-gray-900">For Customers</h3>
                        <ul class="list-disc pl-5 mt-2 text-gray-700">
                            <li>Find trusted businesses and services</li>
                            <li>Read authentic reviews and ratings</li>
                            <li>Share your experiences with others</li>
                            <li>Make informed purchasing decisions</li>
                        </ul>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mt-8">Our Values</h2>
                <p class="text-gray-700">We are guided by core principles that shape every aspect of our platform:</p>
                <div class="bg-blue-50 p-4 rounded-lg mt-6">
                    <h3 class="text-xl font-bold text-gray-900">Transparency</h3>
                    <p class="text-gray-700 mt-2">We believe in open, honest communication and clear policies that protect both businesses and consumers.</p>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg mt-6">
                    <h3 class="text-xl font-bold text-gray-900">Authenticity</h3>
                    <p class="text-gray-700 mt-2">Every review on our platform comes from real customers with genuine experiences.</p>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg mt-6">
                    <h3 class="text-xl font-bold text-gray-900">Fairness</h3>
                    <p class="text-gray-700 mt-2">We provide equal opportunities for all businesses to showcase their services and respond to feedback.</p>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mt-8">Platform Features</h2>
                <p class="text-gray-700">Our platform offers comprehensive tools and features for both businesses and customers:</p>
                <div class="bg-blue-50 p-4 rounded-lg mt-6">
                    <h3 class="text-xl font-bold text-gray-900">Review System Features:</h3>
                    <ul class="list-disc pl-5 mt-2 text-gray-700">
                        <li>5-star rating system</li>
                        <li>Detailed written reviews</li>
                        <li>Photo and video uploads</li>
                        <li>Review helpfulness voting</li>
                    </ul>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg mt-6">
                    <h3 class="text-xl font-bold text-gray-900">Business Tools:</h3>
                    <ul class="list-disc pl-5 mt-2 text-gray-700">
                        <li>Analytics dashboard</li>
                        <li>Response management</li>
                        <li>Premium business profiles</li>
                        <li>Reputation monitoring</li>
                    </ul>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mt-8">Get in Touch</h2>
                <p class="text-gray-700">Have questions about our platform? Want to partner with us? We'd love to hear from you.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center mt-4">
                    <a href="{{ route('contact.us') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-envelope mr-2"></i>
                        Contact Us
                    </a>
                    <a href="/business" class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-store mr-2"></i>
                        Join as Business
                    </a>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
