<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<header class="pb-6 bg-white lg:pb-0 relative" x-data="{ isMenuOpen: false, isCategoryMenuOpen: false, isProfileOpen: false }">
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
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
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
                                <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">Popular Categories</h3>
                                <ul class="space-y-3">
                                    <li>
                                        <a href="/category/banks" class="flex items-center text-gray-600 hover:text-blue-600">
                                            <i class="fas fa-university w-5 h-5 mr-3 text-blue-600"></i>
                                            Banks & Finance
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/category/restaurants" class="flex items-center text-gray-600 hover:text-blue-600">
                                            <i class="fas fa-utensils w-5 h-5 mr-3 text-blue-600"></i>
                                            Restaurants
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/category/shopping" class="flex items-center text-gray-600 hover:text-blue-600">
                                            <i class="fas fa-shopping-bag w-5 h-5 mr-3 text-blue-600"></i>
                                            Shopping
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">Browse By</h3>
                                <ul class="space-y-3">
                                    <li>
                                        <a href="/categories/trending" class="flex items-center text-gray-600 hover:text-blue-600">
                                            <i class="fas fa-chart-line w-5 h-5 mr-3 text-blue-600"></i>
                                            Trending Now
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/categories/new" class="flex items-center text-gray-600 hover:text-blue-600">
                                            <i class="fas fa-star w-5 h-5 mr-3 text-blue-600"></i>
                                            New Additions
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
                        <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-gray-200">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ Storage::url(Auth::user()->profile_picture) }}"
                                     alt="Profile Picture"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-blue-600 text-white text-xl font-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <span class="text-base font-medium text-gray-700">{{ Str::before(Auth::user()->name, ' ') }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Desktop Profile Dropdown Menu -->
                    <div x-show="open"
                         @click.away="open = false"
                         class="absolute right-0 w-48 mt-2 py-2 bg-white border border-gray-100 rounded-lg shadow-lg z-50">
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user-circle mr-2"></i> Profile
                        </a>
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </a>
                        <hr class="my-2 border-gray-200">
                        <a href="{{ route('referrals.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-users mr-2"></i> Referrals
                        </a>
                        <a href="{{ route('wallet.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-wallet mr-2"></i> Wallet
                        </a>
                        <hr class="my-2 border-gray-200">
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> Sign out
                            </button>
                        </form>
                    </div>
                </div>

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
                        <!-- Mobile User Profile -->
                        <div class="py-4 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-gray-200">
                                    @if(Auth::user()->profile_picture)
                                        <img src="{{ Storage::url(Auth::user()->profile_picture) }}"
                                             alt="Profile Picture"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-blue-600 text-white text-xl font-bold">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ Str::before(Auth::user()->name, ' ') }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Categories -->
                        <div class="py-2" x-data="{ isOpen: false }">

                            <button @click="isOpen = !isOpen" class="flex items-center justify-between w-full text-base font-medium text-black">
                                <a href="{{ route('categories.index') }}" class="flex items-center justify-between w-full text-base font-medium text-black hover:text-blue-600">
                                <span>Categories</span>
                                </a>
                                <svg class="w-4 h-4" :class="{'rotate-180': isOpen}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </a>

                            <div x-show="isOpen" class="mt-2 space-y-2 pl-4">
                                <!-- Mobile Category Items -->
                                <a href="/category/banks" class="flex items-center py-2 text-sm text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-university w-4 h-4 mr-3 text-blue-600"></i>
                                    Banks & Finance
                                </a>
                                <!-- Add more mobile category items -->
                            </div>
                        </div>

                        <a href="#" class="py-2 text-base font-medium text-black hover:text-blue-600">Write a Review</a>
                        <a href="#" class="py-2 text-base font-medium text-black hover:text-blue-600">Sign in</a>

                        <!-- Mobile Profile Menu Items -->
                        <a href="{{ route('profile.show') }}" class="py-2 text-base font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-user-circle mr-2"></i> Profile
                        </a>
                        <a href="{{ route('profile.show') }}" class="py-2 text-base font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </a>
                        <a href="{{ route('referrals.index') }}" class="py-2 text-base font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-users mr-2"></i> Referrals
                        </a>
                        <a href="{{ route('wallet.index') }}" class="py-2 text-base font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-wallet mr-2"></i> Wallet
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left py-2 text-base font-medium text-red-600 hover:text-red-700">
                                <i class="fas fa-sign-out-alt mr-2"></i> Sign out
                            </button>
                        </form>
                    </div>
                </div>

                <div class="px-6 mt-6">
                    <a href="#" class="inline-flex justify-center w-full px-4 py-3 text-base font-semibold text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                        Business owner
                    </a>
                </div>
            </nav>
        </div>
    </div>
</header>
