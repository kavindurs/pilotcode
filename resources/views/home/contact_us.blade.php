<!-- filepath: c:\xampp\htdocs\pilot\resources\views\home\contact_us.blade.php -->

@extends('layouts.app')

@section('title', 'Contact Us - Scoreness')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Container wrapper for consistent width -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-0">
        <!-- Modern Header with Pattern Background - Similar to Profile Page -->
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
                                    <i class="fas fa-envelope text-blue-600 text-3xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Page Title and Description -->
                        <div class="text-white">
                            <h1 class="text-3xl font-bold mb-2">Contact Us</h1>
                            <p class="text-blue-100 max-w-2xl">
                                Have questions or need assistance? Our team is here to help. Fill out the form below and we'll get back to you as soon as possible.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Contact Form -->
            <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a message</h2>

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <div class="relative rounded-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" name="name" id="name" placeholder="Your full name"
                                class="block w-full pl-10 pr-4 py-3 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                        <div class="relative rounded-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" name="email" id="email" placeholder="Your email address"
                                class="block w-full pl-10 pr-4 py-3 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <div class="relative rounded-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-tag text-gray-400"></i>
                            </div>
                            <input type="text" name="subject" id="subject" placeholder="What is this regarding?"
                                class="block w-full pl-10 pr-4 py-3 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <div class="relative rounded-md">
                            <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                <i class="fas fa-comment text-gray-400"></i>
                            </div>
                            <textarea name="message" id="message" rows="5" placeholder="How can we help you?"
                                class="block w-full pl-10 pr-4 py-3 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500"
                                required></textarea>
                        </div>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="inline-flex items-center justify-center w-full px-6 py-3 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Send Message
                        </button>
                    </div>
                </form>
            </div>

            <!-- Contact Information -->
            <div class="flex flex-col">
                <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Contact Information</h2>

                    <div class="space-y-6">
                        <!-- Office Address -->
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600">
                                    <i class="fas fa-map-marker-alt text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Our Office</h3>
                                <address class="mt-2 text-base text-gray-600 not-italic">
                                    145/A/1/1  Katuwana road, <br/>Homagama, Sri Lanka
                                </address>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600">
                                    <i class="fas fa-envelope text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Email Us</h3>
                                <p class="mt-2 text-base text-gray-600">
                                    <a href="mailto:info@scoreness.com" class="text-blue-600 hover:underline">info@scoreness.com</a>
                                </p>
                                <p class="mt-1 text-sm text-gray-500">We aim to respond within 24 hours</p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600">
                                    <i class="fas fa-phone-alt text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Call Us</h3>
                                <p class="mt-2 text-base text-gray-600">
                                    <a href="tel:+94123456789" class="text-blue-600 hover:underline">+94 71 303 3030</a>
                                </p>
                                <p class="mt-1 text-sm text-gray-500">Monday-Friday, 9AM to 6PM</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8 mt-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h2>

                    <div class="space-y-4">
                        <div x-data="{ open: false }" class="border-b border-gray-200 pb-4">
                            <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                                <span class="text-lg font-medium text-gray-900">How do I create a business account?</span>
                                <i class="fas" :class="{ 'fa-chevron-down': !open, 'fa-chevron-up': open }"></i>
                            </button>
                            <div x-show="open" class="mt-2 text-gray-600">
                                To create a business account, click on the "For Business" button in the navigation menu and follow the registration process. You'll need to provide your business details and verify your email address.
                            </div>
                        </div>

                        <div x-data="{ open: false }" class="border-b border-gray-200 pb-4">
                            <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                                <span class="text-lg font-medium text-gray-900">How do I claim my business?</span>
                                <i class="fas" :class="{ 'fa-chevron-down': !open, 'fa-chevron-up': open }"></i>
                            </button>
                            <div x-show="open" class="mt-2 text-gray-600">
                                To claim your business, search for your business on our platform, click on "Claim this business" on your business page, and follow the verification steps to prove ownership.
                            </div>
                        </div>

                        <div x-data="{ open: false }" class="border-b border-gray-200 pb-4">
                            <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                                <span class="text-lg font-medium text-gray-900">How can I submit a review?</span>
                                <i class="fas" :class="{ 'fa-chevron-down': !open, 'fa-chevron-up': open }"></i>
                            </button>
                            <div x-show="open" class="mt-2 text-gray-600">
                                To submit a review, first create an account or log in to your existing account. Then, search for the business you want to review, visit its page, and click on the "Write a Review" button. Fill in your rating, write your experience, add photos if desired, and submit. Your honest feedback helps other consumers make informed decisions.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
