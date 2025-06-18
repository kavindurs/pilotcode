<!-- filepath: c:\xampp\htdocs\pilot\resources\views\home\privacy_policy.blade.php -->
@extends('layouts.app')

@section('title', 'Privacy Policy - Scoreness')

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
                            <pattern id="privacy-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M0 20 L40 20" stroke="#fff" stroke-width="1" fill="none" />
                                <path d="M20 0 L20 40" stroke="#fff" stroke-width="1" fill="none" />
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#privacy-pattern)" />
                    </svg>
                </div>

                <div class="relative px-8 py-16 sm:px-10 sm:py-14">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <!-- Left: Icon -->
                        <div class="flex-shrink-0 mb-6 md:mb-0 md:mr-8">
                            <div class="w-20 h-20 rounded-full bg-white p-1 shadow-lg flex items-center justify-center">
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 rounded-full">
                                    <i class="fas fa-shield-alt text-blue-600 text-3xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Page Title and Description -->
                        <div class="text-white">
                            <h1 class="text-3xl font-bold mb-2">Privacy Policy</h1>
                            <p class="text-blue-100 max-w-2xl">
                                Last updated: April 30, 2025
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8">
            <div class="prose prose-blue max-w-none">
                <!-- Keep your existing content here -->
                <h2 class="text-2xl font-bold text-gray-900">We value your privacy</h2>
                <p class="text-gray-700">With this policy, we set out your privacy rights and how we collect, use, disclose, transfer and store your personal data.</p>

                <h3 class="text-xl font-bold text-gray-900 mt-8">Terms we use in this policy</h3>
                <p class="text-gray-700">When we say "Scoreness", "we", "our", or "us", we mean scoreness.com, which is the entity responsible for processing your personal data.</p>
                <p class="text-gray-700">When we say "website", "platform" or "service", we mean all of Scoreness's websites, applications and other services.</p>
                <p class="text-gray-700">When we say "public personal data", we mean personal data published on our website which anyone on the internet and allowed on our site can access, and when we say "private personal data" we mean personal data we don't publish on our website.</p>

                <h3 class="text-xl font-bold text-gray-900 mt-8">We're an open platform</h3>
                <p class="text-gray-700">When you write a review, the review and your Scoreness profile will be visible to anyone who visits our platform. When someone clicks on your profile, they can see your country location and all the reviews you've written, including the locations and products you've reviewed, and any photos or videos submitted with your reviews. Similarly, if you're a business user and reply to a review about your company, this will also be visible on our platform. We consider all of this information to be public personal data. You can share as much or as little personal information as you want when creating your Scoreness profile and username.</p>

                <h3 class="text-xl font-bold text-gray-900 mt-8">Personal data we collect</h3>
                <p class="text-gray-700">Personal data is any information that relates to an identifiable individual. When you create a Scoreness user account, write a review, create a business account on behalf of your company, or otherwise use our platform or services, we may collect and process personal data about you.</p>

                <p class="text-gray-700">We ask you not to share any sensitive personal documents or personal data with us, such as information revealing ethnic origin, religious beliefs, or health information, of yourself or others.</p>

                <div class="bg-blue-50 p-4 rounded-lg mt-6">
                    <h4 class="font-bold text-gray-900">Public personal data we collect:</h4>
                    <ul class="list-disc pl-5 mt-2 text-gray-700">
                        <li>User account information: Your username, photo, country location</li>
                        <li>Information about reviews and ratings you've submitted</li>
                        <li>Information about businesses you've reviewed</li>
                        <li>Review content and star ratings</li>
                        <li>Product photos or videos you've submitted</li>
                        <li>Information about finding reviews "useful"</li>
                    </ul>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg mt-6">
                    <h4 class="font-bold text-gray-900">Private personal data we collect:</h4>
                    <ul class="list-disc pl-5 mt-2 text-gray-700">
                        <li>User account information: Your password and your preferred language</li>
                        <li>Contact information: Your name and email address</li>
                        <li>Device and location information: Your IP address, browser settings, and location</li>
                        <li>Usage and profiling information: Your search history and interactions with our platform</li>
                        <li>Preferences information: Selected preferences associated with your account</li>
                        <li>Communication information: Communications we receive from you</li>
                    </ul>
                </div>

                <h3 class="text-xl font-bold text-gray-900 mt-8">How we collect personal data</h3>
                <p class="text-gray-700">The personal data that we process is primarily collected directly from you when you provide your details to create an account with us, or your usage and profiling information, meaning your interaction with our platform, services or marketing emails, such as by leaving reviews or posting replies to reviews.</p>

                <p class="text-gray-700">Sometimes we're given information about you from third parties. For example, when you sign up for a Scoreness account via social media, your account is automatically pre-filled with the information we receive from them. Similarly, when a business asks us to send a review invitation to you on their behalf, they give us your name, email address and a reference number, such as an order ID or similar.</p>

                <h3 class="text-xl font-bold text-gray-900 mt-8">Why and how we use your personal data</h3>
                <p class="text-gray-700">We may use your personal data for the following purposes:</p>
                <ul class="list-disc pl-5 mt-2 text-gray-700">
                    <li>Provide our services to you</li>
                    <li>Identify you as a registered user</li>
                    <li>Improve our platform and services</li>
                    <li>Respond to your questions and provide customer service</li>
                    <li>Send you our newsletters</li>
                    <li>Personalize your experience on our platform</li>
                    <li>Engage in various internal business purposes</li>
                    <li>Train our staff and for quality control purposes</li>
                </ul>

                <h3 class="text-xl font-bold text-gray-900 mt-8">Your rights</h3>
                <p class="text-gray-700">If we hold personal data about you, you have rights in relation to this data, including:</p>
                <ul class="list-disc pl-5 mt-2 text-gray-700">
                    <li>The right to access and download your personal data</li>
                    <li>The right to correct or delete your personal data</li>
                    <li>The right to object to the processing of your personal data</li>
                    <li>The right to have the processing of your personal data restricted</li>
                </ul>

                <h3 class="text-xl font-bold text-gray-900 mt-8">Changes to this policy</h3>
                <p class="text-gray-700">We may change this policy from time to time. Laws, regulations, and industry standards evolve, which may make those changes necessary, or we may make changes to our services or business. We will post the changes to this page and encourage you to review our Privacy Policy to stay informed.</p>

                <h3 class="text-xl font-bold text-gray-900 mt-8">Contact us</h3>
                <p class="text-gray-700">We aim to make this information as clear and transparent as possible. But if you still have questions about how we process your personal data, or would like to exercise your rights under our policy, you're welcome to contact our Data Protection Officer at info@scoreness.com.</p>

                <p class="text-gray-700 mt-4">You can reach us by mail at:</p>
                <address class="not-italic text-gray-700 mt-2">
                    Scoreness,<br>
                    Colombo,
                    Sri Lanka

                </address>
            </div>
        </div>
    </div>
</div>
@endsection
