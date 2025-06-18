<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>List Your Property - Step 3 | Scoreness</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  </head>
  <body class="bg-gray-50 text-text-body">

    @include('navigation_bars.business_home_nav')
            <!-- Modern Header with Pattern Background -->

            <div class="bg-gray-50 min-h-screen mt-8">
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
                                                <i class="fas fa-check-circle text-blue-600 text-3xl"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right: Page Title and Description -->
                                    <div class="text-white">
                                        <h1 class="text-3xl font-bold mb-2">List Your Property</h1>
                                        <p class="text-blue-100 max-w-2xl">
                                            Final Step: Verify your email address to complete your registration
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


    <div class="max-w-4xl mx-auto py-10 px-4">
        <div class="mb-10">
            <h2 class="text-2xl font-bold text-gray-900">Step 3: Email Verification</h2>
            <div class="flex items-center mt-4">
                <div class="w-1/3 bg-blue-600 h-1"></div>
                <div class="w-1/3 bg-blue-600 h-1"></div>
                <div class="w-1/3 bg-blue-600 h-1"></div>
            </div>
        </div>

        <div class="max-w-md mx-auto">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="text-center mb-6">
                    <div class="inline-block p-3 bg-blue-50 rounded-full mb-4">
                        <i class="fas fa-envelope text-blue-600 text-3xl"></i>
                    </div>
                    <h2 class="text-xl font-semibold mb-2">Enter Verification Code</h2>
                    <p class="text-gray-600">
                        We've sent a verification code to your email address. Please enter it below to complete your registration.
                    </p>
                </div>

                <form action="{{ route('property.submit.step3') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">OTP Code</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input
                                type="text"
                                name="otp"
                                class="pl-10 block w-full px-3 py-3 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600"
                                placeholder="Enter 6-digit code"
                                maxlength="6"
                                autocomplete="one-time-code"
                            >
                        </div>
                        @error('otp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <p class="text-sm text-gray-500 text-center mb-6">
                            Didn't receive the code?
                            <a href="#" class="text-blue-600 hover:underline">Resend code</a>
                        </p>
                    </div>

                    <div class="flex justify-between items-center">
                        <a href="{{ route('property.create.step2') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 px-4 py-2 rounded-md hover:bg-gray-100 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Previous Step
                        </a>
                        <button type="submit" class="inline-flex items-center bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                            Complete Registration <i class="fas fa-check ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('footer.footer')
  </body>
</html>
