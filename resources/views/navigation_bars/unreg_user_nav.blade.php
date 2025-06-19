<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<header class="pb-6 bg-white lg:pb-0 relative" x-data="{ isMenuOpen: false, isCategoryMenuOpen: false }">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Top Bar -->
        <div class="hidden lg:flex items-center justify-end space-x-6 py-2 border-b border-gray-100">
            <a href="{{ route('privacy.policy') }}" class="text-sm text-gray-600 hover:text-blue-600">Privacy & Policy</a>
            <a href="{{ route('terms.conditions') }}" class="text-sm text-gray-600 hover:text-blue-600">Terms & Conditions</a>
            <a href="{{ route('contact.us') }}" class="text-sm text-gray-600 hover:text-blue-600">Contact</a>
        </div>

        <nav class="flex items-center justify-between h-16 lg:h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="/" class="flex items-center space-x-2">
                    <img class="w-auto h-8 lg:h-10" src="https://cimacleaners.com.au/wp-content/uploads/2025/05/10-scaled.png" alt="Logo" />
                </a>
            </div>


            <!-- Desktop Navigation -->
            <div class="hidden lg:flex lg:items-center lg:ml-auto lg:space-x-6">
                <a href="{{ route('review') }}" class="flex items-center space-x-2 text-base font-medium text-black hover:text-blue-600">
                    <i class="fas fa-edit"></i>
                    <span>Write a Review</span>
                </a>
                <!-- Categories Dropdown -->
                <div class="relative group">
                    <a href="{{ route('categories.index') }}" class="flex items-center space-x-2 text-base font-medium text-black transition-all duration-200 hover:text-blue-600">
                        <i class="fas fa-th-large"></i>
                        <span>Categories</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </a>

                    <!-- Enhanced Mega Menu with improved transitions -->
                    <div class="absolute left-0 z-50 w-[500px] p-6 mt-4 bg-white border border-gray-100 rounded-xl shadow-lg transform opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 ease-in-out">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">Popular Subcategories</h3>
                                <ul class="space-y-3">
                                    <li>
                                        <a href="{{ route('properties.subcategory', 20) }}" class="flex items-center text-gray-600 hover:text-blue-600">
                                            <i class="fas fa-taxi w-5 h-5 mr-3 text-blue-600"></i>
                                            Taxis & Public Transport
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('properties.subcategory', 17) }}" class="flex items-center text-gray-600 hover:text-blue-600">
                                            <i class="fas fa-car w-5 h-5 mr-3 text-blue-600"></i>
                                            Cars & Trucks
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('properties.subcategory', 5) }}" class="flex items-center text-gray-600 hover:text-blue-600">
                                            <i class="fas fa-paw w-5 h-5 mr-3 text-blue-600"></i>
                                            Pet Services
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">More Categories</h3>
                                <ul class="space-y-3">
                                    <li>
                                        <a href="{{ route('properties.subcategory', 10) }}" class="flex items-center text-gray-600 hover:text-blue-600">
                                            <i class="fas fa-shield-alt w-5 h-5 mr-3 text-blue-600"></i>
                                            Insurance
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('properties.subcategory', 4) }}" class="flex items-center text-gray-600 hover:text-blue-600">
                                            <i class="fas fa-horse w-5 h-5 mr-3 text-blue-600"></i>
                                            Horses & Riding
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('properties.subcategory', 3) }}" class="flex items-center text-gray-600 hover:text-blue-600">
                                            <i class="fas fa-dog w-5 h-5 mr-3 text-blue-600"></i>
                                            Cats & Dogs
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('login') }}" class="flex items-center space-x-2 text-base font-medium text-black hover:text-blue-600">
                    <i class="fas fa-user"></i>
                    <span>Sign in</span>
                </a>

                <!-- Business Owner Button -->
                <a href="{{ route('register.show') }}" class="inline-flex items-center px-6 py-3 text-base font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-store mr-2"></i>
                    For Business
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button
                type="button"
                class="inline-flex p-2 text-black transition-all duration-200 rounded-md lg:hidden focus:bg-gray-100 hover:bg-gray-100"
                @click="isMenuOpen = !isMenuOpen"
            >
                <svg class="w-6 h-6" :class="{'hidden': isMenuOpen, 'block': !isMenuOpen}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                </svg>
                <svg class="w-6 h-6" :class="{'block': isMenuOpen, 'hidden': !isMenuOpen}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </nav>

        <!-- Mobile Menu -->
        <div x-show="isMenuOpen" class="lg:hidden">
            <nav class="pt-4 pb-6 bg-white border border-gray-200 rounded-md shadow-md">
                <div class="flow-root">
                    <div class="flex flex-col px-6 -my-2 space-y-1">
                        <!-- Mobile Categories -->
                        <div class="py-2" x-data="{ isOpen: false }">
                            <button @click="isOpen = !isOpen" class="flex items-center justify-between w-full text-base font-medium text-black">
                                <span>Categories</span>
                                <svg class="w-4 h-4" :class="{'rotate-180': isOpen}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="isOpen" class="mt-2 space-y-2 pl-4">
                                <!-- Mobile Category Items -->
                                <a href="{{ route('properties.subcategory', 20) }}" class="flex items-center py-2 text-sm text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-taxi w-4 h-4 mr-3 text-blue-600"></i>
                                    Taxis & Public Transport
                                </a>
                                <a href="{{ route('properties.subcategory', 17) }}" class="flex items-center py-2 text-sm text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-car w-4 h-4 mr-3 text-blue-600"></i>
                                    Cars & Trucks
                                </a>
                                <a href="{{ route('properties.subcategory', 5) }}" class="flex items-center py-2 text-sm text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-paw w-4 h-4 mr-3 text-blue-600"></i>
                                    Pet Services
                                </a>
                                <a href="{{ route('properties.subcategory', 10) }}" class="flex items-center py-2 text-sm text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-shield-alt w-4 h-4 mr-3 text-blue-600"></i>
                                    Insurance
                                </a>
                                <a href="{{ route('properties.subcategory', 4) }}" class="flex items-center py-2 text-sm text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-horse w-4 h-4 mr-3 text-blue-600"></i>
                                    Horses & Riding
                                </a>
                                <a href="{{ route('properties.subcategory', 3) }}" class="flex items-center py-2 text-sm text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-dog w-4 h-4 mr-3 text-blue-600"></i>
                                    Cats & Dogs
                                </a>
                            </div>
                        </div>

                        <a href="{{ route('review') }}" class="py-2 text-base font-medium text-black hover:text-blue-600">Write a Review</a>
                        <a href="{{ route('login') }}" class="py-2 text-base font-medium text-black hover:text-blue-600">Sign in</a>
                    </div>
                </div>

                <div class="px-6 mt-6">
                    <a href="{{ route('register.show') }}" class="inline-flex justify-center w-full px-4 py-3 text-base font-semibold text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                        For Business
                    </a>
                </div>
            </nav>
        </div>
    </div>
</header>
