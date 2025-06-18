<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>List Your Property - Step 2 | Scoreness</title>
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
                                            <i class="fas fa-building text-blue-600 text-3xl"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right: Page Title and Description -->
                                <div class="text-white">
                                    <h1 class="text-3xl font-bold mb-2">List Your Property</h1>
                                    <p class="text-blue-100 max-w-2xl">
                                        Step 2: User account and business verification details
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    <div class="max-w-4xl mx-auto py-10 px-4">
        <div class="mb-10">
            <h2 class="text-2xl font-bold text-gray-900">Step 2: Account Information</h2>
            <div class="flex items-center mt-4">
                <div class="w-1/3 bg-blue-600 h-1"></div>
                <div class="w-1/3 bg-blue-600 h-1"></div>
                <div class="w-1/3 bg-gray-200 h-1"></div>
            </div>
        </div>

        <form action="{{ route('property.submit.step2') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if(isset($property_type))
                    @if($property_type === 'Web')
                        <div class="col-span-2">
                            <!-- Domain input -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700">Domain</label>
                                <input
                                    type="url"
                                    name="domain"
                                    id="domain"
                                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600"
                                    placeholder="https://example.com"
                                >
                                @error('domain')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Business Email input -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Business Email</label>
                                <div class="mt-1 flex w-full rounded-md shadow-sm">
                                    <input
                                        type="text"
                                        name="email_prefix"
                                        id="email_prefix"
                                        class="flex-1 rounded-l-md px-3 py-2 bg-white border border-r-0 border-gray-300 focus:outline-none focus:ring-blue-600 focus:border-blue-600"
                                        placeholder="username"
                                    >
                                    <span
                                        id="email_domain"
                                        class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-50 text-gray-500 rounded-r-md"
                                    >
                                        @domain.com
                                    </span>
                                    <input type="hidden" name="business_email" id="full_email">
                                </div>
                                @error('business_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @else
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Business Registration Document</label>
                            <input type="file"
                                name="document"
                                class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600">
                            @error('document')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Business Email</label>
                            <input type="email"
                                name="business_email"
                                class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600">
                            @error('business_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                @else
                    <div class="col-span-2 text-red-600 p-4 bg-red-50 rounded-md border border-red-200">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-3 text-red-500"></i>
                            <div>
                                <p class="font-medium">Please complete step 1 first.</p>
                                <a href="{{ route('property.create.step1') }}" class="text-blue-600 hover:underline flex items-center mt-2">
                                    <i class="fas fa-arrow-left mr-2"></i> Go back to step 1
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" name="first_name" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600">
                    @error('first_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="last_name" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600">
                    @error('last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600">
                </div>
            </div>

            <div class="flex justify-between mt-10">
                <a href="{{ route('property.create.step1') }}" class="inline-flex items-center bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Previous Step
                </a>
                <button type="submit" class="inline-flex items-center bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                    Next Step <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </form>
    </div>

    @include('footer.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const domainInput = document.getElementById('domain');
            const emailDomainSpan = document.getElementById('email_domain');
            const emailPrefixInput = document.getElementById('email_prefix');
            const fullEmailInput = document.getElementById('full_email');

            // Only run if these elements exist (for the Web business type)
            if (domainInput && emailDomainSpan && emailPrefixInput && fullEmailInput) {
                function updateEmailDomain() {
                    let domain = domainInput.value;
                    try {
                        // Extract domain from URL
                        domain = new URL(domain).hostname;
                        // Remove 'www.' if present
                        domain = domain.replace(/^www\./, '');
                        emailDomainSpan.textContent = `@${domain}`;
                        updateFullEmail();
                    } catch (e) {
                        emailDomainSpan.textContent = '@domain.com';
                    }
                }

                function updateFullEmail() {
                    const prefix = emailPrefixInput.value;
                    const domain = emailDomainSpan.textContent.substring(1); // Remove @ symbol
                    fullEmailInput.value = `${prefix}@${domain}`;
                }

                // Event listeners
                domainInput.addEventListener('input', updateEmailDomain);
                emailPrefixInput.addEventListener('input', updateFullEmail);

                // Initial update if domain is pre-filled
                if (domainInput.value) {
                    updateEmailDomain();
                }
            }
        });
    </script>
  </body>
</html>
