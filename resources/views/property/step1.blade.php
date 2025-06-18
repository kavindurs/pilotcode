<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>List Your Property - Scoreness</title>
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
                                    Connect with potential customers by listing your business on Scoreness. Complete all steps to create your property profile.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


    <div class="max-w-4xl mx-auto py-10 px-4">
        <div class="mb-10">
            <h2 class="text-2xl font-bold text-gray-900">Step 1: Basic Information</h2>
            <div class="flex items-center mt-4">
                <div class="w-1/3 bg-blue-600 h-1"></div>
                <div class="w-1/3 bg-gray-200 h-1"></div>
                <div class="w-1/3 bg-gray-200 h-1"></div>
            </div>
        </div>

        <form action="{{ route('property.submit.step1') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Property Type</label>
                    <select name="property_type" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600">
                        <option value="">Select Type</option>
                        <option value="Web">Web Business</option>
                        <option value="Physical">Physical Business</option>
                    </select>
                    @error('property_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Business Name</label>
                    <input type="text" name="business_name" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600">
                    @error('business_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">City</label>
                    <input type="text" name="city" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600">
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Country</label>
                    <input type="text" name="country" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600">
                    @error('country')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">ZIP Code</label>
                    <input type="text" name="zip_code" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600">
                    @error('zip_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Annual Revenue</label>
                    <select name="annual_revenue" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600">
                        <option value="">Select Revenue Range</option>
                        <option value="1-9999">$1-9,999</option>
                        <option value="10000-99999">$10,000-99,999</option>
                        <option value="100000-999999">$100,000-999,999</option>
                        <option value="1000000+">More than $1 million</option>
                    </select>
                    @error('annual_revenue')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Number of Employees</label>
                    <select name="employee_count" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600">
                        <option value="">Select Employee Range</option>
                        <option value="1-9">1-9</option>
                        <option value="10-49">10-49</option>
                        <option value="50-99">50-99</option>
                        <option value="100-499">100-499</option>
                        <option value="500-999">500-999</option>
                        <option value="1000-9999">1,000-9,999</option>
                        <option value="10000+">More than 10,000</option>
                    </select>
                    @error('employee_count')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category" id="category" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Subcategory</label>
                    <select name="subcategory" id="subcategory" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600">
                        <option value="">Select Category First</option>
                    </select>
                    @error('subcategory')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Next Step
                </button>
            </div>
        </form>
    </div>

        <!-- Referral Code Notification -->
        @if(isset($referralCode) && $referralCode)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-gift text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-green-800">
                            🎉 You're using a referral code!
                        </h3>
                        <p class="text-green-700 mt-1">
                            Referral code: <span class="font-mono bg-green-100 px-2 py-1 rounded">{{ $referralCode }}</span>
                        </p>
                        <p class="text-sm text-green-600 mt-1">
                            The person who referred you will earn a commission when you purchase a plan. Thank you for using their referral link!
                        </p>
                    </div>
                </div>
            </div>
        @endif

    <div class="max-w-4xl mx-auto py-10 px-4">
        @include('footer.footer')

        @stack('scripts')
        <script>
            const categories = @json($categories);

            document.getElementById('category').addEventListener('change', function() {
                const categoryId = this.value;
                const subcategorySelect = document.getElementById('subcategory');
                subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';

                if(categoryId) {
                    const category = categories.find(c => c.id == categoryId);
                    if(category && category.subcategories) {
                        category.subcategories.forEach(sub => {
                            const option = new Option(sub.name, sub.id);
                            subcategorySelect.add(option);
                        });
                    }
                }
            });
        </script>
  </body>
</html>
