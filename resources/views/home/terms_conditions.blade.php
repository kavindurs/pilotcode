<!-- filepath: c:\xampp\htdocs\pilot\resources\views\home\terms_conditions.blade.php -->
@extends('layouts.app')

@section('title', 'Terms and Conditions - Scoreness')

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
                            <pattern id="terms-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M0 20 L40 20" stroke="#fff" stroke-width="1" fill="none" />
                                <path d="M20 0 L20 40" stroke="#fff" stroke-width="1" fill="none" />
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#terms-pattern)" />
                    </svg>
                </div>

                <div class="relative px-8 py-16 sm:px-10 sm:py-14">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <!-- Left: Icon -->
                        <div class="flex-shrink-0 mb-6 md:mb-0 md:mr-8">
                            <div class="w-20 h-20 rounded-full bg-white p-1 shadow-lg flex items-center justify-center">
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 rounded-full">
                                    <i class="fas fa-gavel text-blue-600 text-3xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Page Title and Description -->
                        <div class="text-white">
                            <h1 class="text-3xl font-bold mb-2">Terms and Conditions</h1>
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
                <h2 class="text-2xl font-bold text-gray-900">Introduction</h2>
                <p class="text-gray-700">These terms and conditions ("Terms") govern your use of Scoreness's website, platform, and services. By accessing or using our services, you agree to be bound by these Terms. If you disagree with any part of the Terms, you may not access our services.</p>

                <h3 class="text-xl font-bold text-gray-900 mt-8">Definitions</h3>
                <ul class="list-disc pl-5 mt-2 text-gray-700">
                    <li><strong>Platform:</strong> The Scoreness website, applications, and all services provided by Scoreness.</li>
                    <li><strong>Content:</strong> Reviews, ratings, texts, images, videos, and other material posted by users or businesses on our platform.</li>
                    <li><strong>User:</strong> Any individual who accesses or uses our platform, whether registered or not.</li>
                    <li><strong>Business User:</strong> A business or organization with a Scoreness business account.</li>
                    <li><strong>Services:</strong> All features, functions, and services provided by Scoreness.</li>
                </ul>

                <h3 class="text-xl font-bold text-gray-900 mt-8">User Accounts</h3>
                <p class="text-gray-700">When you create an account with us, you must provide accurate, complete, and current information. You are responsible for safeguarding your account and password. You agree to accept responsibility for all activities that occur under your account.</p>

                <p class="text-gray-700">You must be at least 16 years old to create an account and use our services. If you are under 18, you must have your parent or guardian's permission to use our services.</p>

                <div class="bg-yellow-50 p-4 rounded-lg mt-6 border-l-4 border-yellow-500">
                    <h4 class="font-bold text-gray-900">Important notice about account termination</h4>
                    <p class="text-gray-700 mt-2">We reserve the right to terminate or suspend your account immediately, without prior notice or liability, for any reason, including but not limited to a breach of the Terms.</p>
                </div>

                <h3 class="text-xl font-bold text-gray-900 mt-8">Content Guidelines</h3>
                <p class="text-gray-700">As a Scoreness user, you may post content on our platform, including reviews, ratings, comments, images, and videos. You are solely responsible for any content you post and its accuracy.</p>

                <p class="text-gray-700">All content you post must:</p>
                <ul class="list-disc pl-5 mt-2 text-gray-700">
                    <li>Be based on genuine experiences (for reviews)</li>
                    <li>Be accurate and truthful</li>
                    <li>Not infringe on any third party's intellectual property rights</li>
                    <li>Not contain unlawful, defamatory, harmful, abusive, harassing, or offensive material</li>
                    <li>Not contain personal data about others without their explicit consent</li>
                    <li>Not promote any illegal activities</li>
                </ul>

                <p class="text-gray-700 mt-4">We reserve the right to remove any content that, in our sole judgment, violates these guidelines or is otherwise objectionable.</p>

                <h3 class="text-xl font-bold text-gray-900 mt-8">Intellectual Property</h3>
                <p class="text-gray-700">The platform and its original content, features, and functionality are owned by Scoreness and are protected by international copyright, trademark, patent, trade secret, and other intellectual property laws.</p>

                <p class="text-gray-700">By posting content on our platform, you grant Scoreness a non-exclusive, royalty-free, perpetual, irrevocable, and fully sublicensable right to use, reproduce, modify, adapt, publish, translate, create derivative works from, distribute, and display such content throughout the world in any media.</p>

                <h3 class="text-xl font-bold text-gray-900 mt-8">Limitation of Liability</h3>
                <p class="text-gray-700">In no event shall Scoreness, its officers, directors, employees, or agents, be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from:</p>
                <ul class="list-disc pl-5 mt-2 text-gray-700">
                    <li>Your access to or use of or inability to access or use the platform</li>
                    <li>Any conduct or content of any third party on the platform</li>
                    <li>Any content obtained from the platform</li>
                    <li>Unauthorized access, use, or alteration of your transmissions or content</li>
                </ul>

                <h3 class="text-xl font-bold text-gray-900 mt-8">Disclaimer</h3>
                <p class="text-gray-700">Your use of the platform is at your sole risk. The platform is provided on an "AS IS" and "AS AVAILABLE" basis. The platform is provided without warranties of any kind, whether express or implied, including, but not limited to, implied warranties of merchantability, fitness for a particular purpose, non-infringement, or course of performance.</p>

                <p class="text-gray-700">Scoreness does not warrant that the platform will function uninterrupted, secure, or available at any particular time or location; that any errors or defects will be corrected; or that the platform is free of viruses or other harmful components.</p>

                <h3 class="text-xl font-bold text-gray-900 mt-8">Governing Law</h3>
                <p class="text-gray-700">These Terms shall be governed and construed in accordance with the laws of Sri Lanka, without regard to its conflict of law provisions.</p>

                <p class="text-gray-700">Our failure to enforce any right or provision of these Terms will not be considered a waiver of those rights. If any provision of these Terms is held to be invalid or unenforceable by a court, the remaining provisions of these Terms will remain in effect.</p>

                <h3 class="text-xl font-bold text-gray-900 mt-8">Changes to Terms</h3>
                <p class="text-gray-700">We reserve the right to modify or replace these Terms at any time. If a revision is material, we will try to provide at least 30 days' notice prior to any new terms taking effect. What constitutes a material change will be determined at our sole discretion.</p>

                <p class="text-gray-700">By continuing to access or use our platform after those revisions become effective, you agree to be bound by the revised terms. If you do not agree to the new terms, please stop using the platform.</p>

                <h3 class="text-xl font-bold text-gray-900 mt-8">Contact Us</h3>
                <p class="text-gray-700">If you have any questions about these Terms, please contact us at:</p>

                <address class="not-italic text-gray-700 mt-2">
                    145/A/1/1  Katuwana road, <br/>Homagama, Sri Lanka<br/>
                    Email: info@scoreness.com
                </address>
            </div>
        </div>
    </div>
</div>
@endsection
