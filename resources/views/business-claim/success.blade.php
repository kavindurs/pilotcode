<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Claim Submitted Successfully | Scoreness</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-white text-text-body">

    @include('navigation_bars.business_home_nav')

    <!-- Success Content -->
    <div class="bg-gradient-to-br from-green-50 via-blue-50 to-purple-50 min-h-screen">
        <!-- Hero Section -->
        <div class="relative bg-gradient-to-r from-green-500 to-blue-600 py-20">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <!-- Success Animation Container -->
                <div class="relative inline-flex items-center justify-center w-24 h-24 mb-8">
                    <div class="absolute inset-0 bg-white/20 rounded-full animate-ping"></div>
                    <div class="relative w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-2xl">
                        <i class="fas fa-check text-green-500 text-3xl"></i>
                    </div>
                </div>

                <h1 class="text-5xl font-bold text-white mb-6 leading-tight">
                    Claim Submitted
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-green-300">Successfully!</span>
                </h1>
                <p class="text-xl text-blue-100 max-w-2xl mx-auto leading-relaxed">
                    Thank you for submitting your business claim. We'll review your request and get back to you soon.
                </p>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 pb-20">

            <!-- Progress Timeline -->
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-8 mb-8 relative">
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    <div class="bg-gradient-to-r from-green-400 to-blue-500 text-white px-6 py-2 rounded-full text-sm font-semibold shadow-lg">
                        <i class="fas fa-clock mr-2"></i>What's Next?
                    </div>
                </div>

                <div class="mt-8 space-y-8">
                    <div class="flex items-start space-x-6">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-lg">1</span>
                            </div>
                        </div>
                        <div class="flex-grow">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-xl font-bold text-gray-900">Review Process</h3>
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">2-3 Business Days</span>
                            </div>
                            <p class="text-gray-600 leading-relaxed">Our expert team will carefully review your claim and verify all business information you've provided.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-6">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-lg">2</span>
                            </div>
                        </div>
                        <div class="flex-grow">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-xl font-bold text-gray-900">Verification</h3>
                                <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">If Needed</span>
                            </div>
                            <p class="text-gray-600 leading-relaxed">We may contact you via email for additional verification or documentation if required.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-6">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-lg">3</span>
                            </div>
                        </div>
                        <div class="flex-grow">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-xl font-bold text-gray-900">Approval & Access</h3>
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">Final Step</span>
                            </div>
                            <p class="text-gray-600 leading-relaxed">Once approved, you'll receive login credentials to manage your business profile and respond to customer reviews.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Card -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl p-8 mb-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full transform translate-x-16 -translate-y-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full transform -translate-x-12 translate-y-12"></div>

                <div class="relative flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-headset text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex-grow">
                        <h3 class="text-2xl font-bold mb-2">Need Help?</h3>
                        <p class="text-blue-100 mb-4 leading-relaxed">
                            Our support team is here to help you through the claiming process. Don't hesitate to reach out!
                        </p>
                        <a href="mailto:support@scoreness.com"
                           class="inline-flex items-center space-x-2 bg-white text-blue-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-envelope"></i>
                            <span>support@scoreness.com</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <a href="{{ url('/business') }}"
                   class="flex items-center justify-center space-x-3 bg-white text-gray-700 px-8 py-4 rounded-xl font-semibold border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-home text-xl"></i>
                    <span>Back to Home</span>
                </a>
                <a href="{{ route('business-claim.search') }}"
                   class="flex items-center justify-center space-x-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-search text-xl"></i>
                    <span>Claim Another Business</span>
                </a>
            </div>

        </div>
    </div>

    @include('footer.footer')

</body>
</html>
