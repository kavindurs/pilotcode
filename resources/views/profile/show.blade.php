<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - {{ Auth::user()->name }}</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    @include('navigation_bars.reg_user_nav')

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Profile Settings</h2>
                <p class="mt-2 text-gray-600">Manage your account settings and preferences</p>
            </div>

            <div x-data="{ activeTab: window.location.hash ? window.location.hash.substring(1) : 'profile' }">
                <!-- Profile Header with Stats -->
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

                        <div class="relative px-8 py-16 sm:px-10 sm:py-14 flex flex-col sm:flex-row items-center">
                            <!-- Profile Picture -->
                            <div class="relative mb-6 sm:mb-0 sm:mr-8">
                                <div class="w-28 h-28 rounded-full bg-white p-1 shadow-lg">
                                    @if(Auth::user()->profile_picture)
                                        <img src="{{ Storage::url(Auth::user()->profile_picture) }}"
                                             alt="{{ Auth::user()->name }}"
                                             class="w-full h-full object-cover rounded-full">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-200 to-blue-300 rounded-full text-4xl font-bold text-white">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <label for="profile_picture" class="absolute -bottom-1 -right-1 cursor-pointer">
                                    <div class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50 transition-colors border">
                                        <i class="fas fa-camera text-blue-600 text-sm"></i>
                                    </div>
                                </label>
                            </div>

                            <!-- User Info -->
                            <div class="text-center sm:text-left text-white">
                                <h1 class="text-3xl font-bold mb-2">{{ Auth::user()->name }}</h1>
                                <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-2 sm:space-y-0 sm:space-x-4">
                                    <p class="flex items-center text-blue-100 text-sm">
                                        <i class="fas fa-envelope mr-2"></i>
                                        {{ Auth::user()->email }}
                                    </p>
                                    <p class="flex items-center text-blue-100 text-sm">
                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                        <span id="user-country">{{ Auth::user()->country ?? 'Set your location' }}</span>
                                    </p>
                                    <p class="flex items-center text-blue-100 text-sm">
                                        <i class="fas fa-user-circle mr-2"></i>
                                        {{ ucfirst(Auth::user()->user_type) }}
                                    </p>
                                </div>

                                @if(Auth::user()->email_verified_at)
                                    <div class="mt-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Verified Account
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div class="bg-white rounded-xl overflow-hidden shadow-sm mb-6">
                    <div class="px-2 sm:px-0">
                        <nav class="flex overflow-x-auto" aria-label="Tabs">
                            <button @click="activeTab = 'profile'; window.location.hash = 'profile'"
                                    :class="{ 'text-blue-600 border-blue-600': activeTab === 'profile', 'border-transparent hover:text-gray-700 hover:border-gray-300': activeTab !== 'profile' }"
                                    class="text-sm font-medium px-1 py-4 border-b-2 transition-colors flex items-center justify-center flex-1">
                                <i class="fas fa-user mr-2"></i>
                                Basic Information
                            </button>
                            <button @click="activeTab = 'account'; window.location.hash = 'account'"
                                    :class="{ 'text-blue-600 border-blue-600': activeTab === 'account', 'border-transparent hover:text-gray-700 hover:border-gray-300': activeTab !== 'account' }"
                                    class="text-sm font-medium px-1 py-4 border-b-2 transition-colors flex items-center justify-center flex-1">
                                <i class="fas fa-cog mr-2"></i>
                                Account Settings
                            </button>
                            <button @click="activeTab = 'security'; window.location.hash = 'security'"
                                    :class="{ 'text-blue-600 border-blue-600': activeTab === 'security', 'border-transparent hover:text-gray-700 hover:border-gray-300': activeTab !== 'security' }"
                                    class="text-sm font-medium px-1 py-4 border-b-2 transition-colors flex items-center justify-center flex-1">
                                <i class="fas fa-shield-alt mr-2"></i>
                                Security
                            </button>
                            <button @click="activeTab = 'privacy'; window.location.hash = 'privacy'"
                                    :class="{ 'text-blue-600 border-blue-600': activeTab === 'privacy', 'border-transparent hover:text-gray-700 hover:border-gray-300': activeTab !== 'privacy' }"
                                    class="text-sm font-medium px-1 py-4 border-b-2 transition-colors flex items-center justify-center flex-1">
                                <i class="fas fa-lock mr-2"></i>
                                Privacy
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="space-y-6">
                    <!-- Profile Tab - Basic Information -->
                    <div x-show="activeTab === 'profile'" class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-6 sm:p-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-6">Basic Information</h3>

                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <input type="file" id="profile_picture" name="profile_picture" class="hidden" accept="image/*">

                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Name Input -->
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                            <div class="relative rounded-md">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-user text-gray-400"></i>
                                                </div>
                                                <input type="text"
                                                       name="name"
                                                       id="name"
                                                       value="{{ old('name', Auth::user()->name) }}"
                                                       required
                                                       class="block w-full pl-10 pr-4 py-3 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 transition-colors">
                                            </div>
                                            @error('name')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Email Input -->
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                            <div class="relative rounded-md">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-envelope text-gray-400"></i>
                                                </div>
                                                <input type="email"
                                                       name="email"
                                                       id="email"
                                                       value="{{ old('email', Auth::user()->email) }}"
                                                       required
                                                       class="block w-full pl-10 pr-4 py-3 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 transition-colors">
                                            </div>
                                            @error('email')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Country Input -->
                                        <div>
                                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                            <div class="relative rounded-md">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-globe text-gray-400"></i>
                                                </div>
                                                <input type="text"
                                                       name="country"
                                                       id="country"
                                                       value="{{ old('country', Auth::user()->country) }}"
                                                       class="block w-full pl-10 pr-4 py-3 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 transition-colors">
                                            </div>
                                            @error('country')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- User's current coordinates (view only) -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Location</label>
                                            <div class="relative rounded-md">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-map-marker-alt text-gray-400"></i>
                                                </div>
                                                <div class="block w-full pl-10 pr-4 py-3 bg-gray-100 rounded-lg">
                                                    <p id="user-coordinates" class="text-gray-600 text-sm truncate">Detecting location...</p>
                                                </div>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500">This information is only displayed to you and is not stored in your profile.</p>
                                        </div>
                                    </div>

                                    <!-- Profile Picture Section -->
                                    <div class="bg-gray-50 p-5 rounded-lg">
                                        <h3 class="text-md font-medium text-gray-700 mb-4">Profile Picture</h3>

                                        <div class="flex flex-col sm:flex-row items-start gap-6">
                                            <div class="flex-shrink-0">
                                                <!-- Current profile picture preview -->
                                                <div class="relative overflow-hidden w-32 h-32 rounded-full bg-white shadow-md">
                                                    <div id="profile-preview" class="w-full h-full flex items-center justify-center">
                                                        @if(Auth::user()->profile_picture)
                                                            <img src="{{ Storage::url(Auth::user()->profile_picture) }}" alt="Profile Preview" class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-400 to-blue-600 text-5xl font-bold text-white">
                                                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="absolute inset-0 bg-black bg-opacity-25 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-200 cursor-pointer">
                                                        <label for="profile_picture_upload" class="w-full h-full flex items-center justify-center cursor-pointer">
                                                            <i class="fas fa-camera text-white text-xl"></i>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex-grow">
                                                <div class="relative">
                                                    <label for="profile_picture_upload" class="block text-sm font-medium text-gray-700 mb-1">Upload New Picture</label>
                                                    <input type="file"
                                                           id="profile_picture_upload"
                                                           name="profile_picture"
                                                           class="block w-full text-sm text-gray-500
                                                                  file:mr-4 file:py-2.5 file:px-4 file:rounded-md
                                                                  file:border-0 file:text-sm file:font-medium
                                                                  file:bg-blue-50 file:text-blue-700
                                                                  hover:file:bg-blue-100
                                                                  cursor-pointer focus:outline-none"
                                                           accept="image/*">
                                                </div>
                                                <p class="mt-2 text-sm text-gray-500">Recommended: Square image, at least 200x200px. Maximum file size: 2MB.</p>

                                                @if(Auth::user()->profile_picture)
                                                <div class="mt-4">
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox" class="rounded-sm border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" name="remove_picture" value="1">
                                                        <span class="ml-2 text-sm text-gray-600">Remove current profile picture</span>
                                                    </label>
                                                </div>
                                                @endif

                                                @error('profile_picture')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Save Button -->
                                    <div class="flex justify-end">
                                        <button type="submit"
                                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                            <i class="fas fa-save mr-2"></i>
                                            Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Account Settings Tab -->
                    <div x-show="activeTab === 'account'" class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-6 sm:p-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-6">Account Settings</h3>

                            <form action="{{ route('profile.update.preferences') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="space-y-8">
                                    <!-- Account Type Selection -->
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-700 mb-4">Account Type</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <label class="relative block cursor-pointer">
                                                <input type="radio" name="user_type" value="regular user" class="sr-only peer" @if(Auth::user()->user_type == 'regular user') checked @endif>
                                                <div class="p-5 rounded-lg border bg-white peer-checked:border-blue-500 peer-checked:ring-1 peer-checked:ring-blue-500 transition-all hover:bg-gray-50">
                                                    <div class="flex items-center mb-2">
                                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                        <h5 class="font-medium">Regular User</h5>
                                                    </div>
                                                    <p class="text-sm text-gray-600">Browse businesses, leave reviews, and interact with the community.</p>
                                                </div>
                                                <div class="absolute top-4 right-4 h-5 w-5 rounded-full bg-white border border-gray-300 flex items-center justify-center peer-checked:bg-blue-500 peer-checked:border-blue-500">
                                                    <svg class="h-3 w-3 text-white opacity-0 peer-checked:opacity-100" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </label>

                                            <label class="relative block cursor-pointer">
                                                <input type="radio" name="user_type" value="business owner" class="sr-only peer" @if(Auth::user()->user_type == 'business owner') checked @endif>
                                                <div class="p-5 rounded-lg border bg-white peer-checked:border-blue-500 peer-checked:ring-1 peer-checked:ring-blue-500 transition-all hover:bg-gray-50">
                                                    <div class="flex items-center mb-2">
                                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                                            <i class="fas fa-store"></i>
                                                        </div>
                                                        <h5 class="font-medium">Business Owner</h5>
                                                    </div>
                                                    <p class="text-sm text-gray-600">List your business, respond to reviews, and manage your business profile.</p>
                                                </div>
                                                <div class="absolute top-4 right-4 h-5 w-5 rounded-full bg-white border border-gray-300 flex items-center justify-center peer-checked:bg-blue-500 peer-checked:border-blue-500">
                                                    <svg class="h-3 w-3 text-white opacity-0 peer-checked:opacity-100" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Notification Preferences -->
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-700 mb-4">Notification Preferences</h4>
                                        <div class="bg-gray-50 rounded-lg p-5 space-y-4">
                                            <label class="flex items-center">
                                                <input type="checkbox" class="rounded-sm border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 h-5 w-5" name="notifications[]" value="email_notifications" checked>
                                                <div class="ml-3">
                                                    <span class="font-medium text-gray-800">Email notifications</span>
                                                    <p class="text-xs text-gray-500 mt-0.5">Receive notifications about account activity via email</p>
                                                </div>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" class="rounded-sm border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 h-5 w-5" name="notifications[]" value="marketing_emails" checked>
                                                <div class="ml-3">
                                                    <span class="font-medium text-gray-800">Marketing emails and newsletters</span>
                                                    <p class="text-xs text-gray-500 mt-0.5">Receive promotional content and news updates</p>
                                                </div>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" class="rounded-sm border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 h-5 w-5" name="notifications[]" value="browser_notifications" checked>
                                                <div class="ml-3">
                                                    <span class="font-medium text-gray-800">Browser notifications</span>
                                                    <p class="text-xs text-gray-500 mt-0.5">Receive real-time notifications in your browser</p>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Language and Region -->
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-700 mb-4">Language and Region</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                                                <select name="language" id="language" class="block w-full pl-3 pr-10 py-3 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 transition-colors">
                                                    <option value="en" selected>English</option>
                                                    <option value="es">Español</option>
                                                    <option value="fr">Français</option>
                                                    <option value="de">Deutsch</option>
                                                    <option value="pt">Português</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                                                <select name="timezone" id="timezone" class="block w-full pl-3 pr-10 py-3 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 transition-colors">
                                                    <option value="UTC" selected>UTC (Coordinated Universal Time)</option>
                                                    <option value="America/New_York">Eastern Time (US & Canada)</option>
                                                    <option value="America/Chicago">Central Time (US & Canada)</option>
                                                    <option value="America/Denver">Mountain Time (US & Canada)</option>
                                                    <option value="America/Los_Angeles">Pacific Time (US & Canada)</option>
                                                    <option value="Europe/London">London</option>
                                                    <option value="Europe/Paris">Paris</option>
                                                    <option value="Asia/Tokyo">Tokyo</option>
                                                    <option value="Australia/Sydney">Sydney</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Save Button -->
                                    <div class="flex justify-end">
                                        <button type="submit"
                                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                            <i class="fas fa-save mr-2"></i>
                                            Save Settings
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Security Tab -->
                    <div x-show="activeTab === 'security'" class="space-y-6">
                        <!-- Change Password Section -->
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            <div class="p-6 sm:p-8">
                                <h3 class="text-xl font-semibold text-gray-900 mb-6">Change Password</h3>

                                <form action="{{ route('profile.update.password') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="space-y-5 max-w-xl">
                                        <!-- Current Password -->
                                        <div>
                                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                            <div class="relative rounded-md">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-lock text-gray-400"></i>
                                                </div>
                                                <input type="password"
                                                       name="current_password"
                                                       id="current_password"
                                                       required
                                                       class="block w-full pl-10 pr-4 py-3 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 transition-colors">
                                            </div>
                                            @error('current_password')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- New Password -->
                                        <div>
                                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                            <div class="relative rounded-md">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-key text-gray-400"></i>
                                                </div>
                                                <input type="password"
                                                       name="password"
                                                       id="password"
                                                       required
                                                       class="block w-full pl-10 pr-4 py-3 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 transition-colors">
                                            </div>
                                            @error('password')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Confirm New Password -->
                                        <div>
                                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                            <div class="relative rounded-md">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-check-double text-gray-400"></i>
                                                </div>
                                                <input type="password"
                                                       name="password_confirmation"
                                                       id="password_confirmation"
                                                       required
                                                       class="block w-full pl-10 pr-4 py-3 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 transition-colors">
                                            </div>
                                        </div>

                                        <!-- Change Password Button -->
                                        <div>
                                            <button type="submit"
                                                    class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                                <i class="fas fa-key mr-2"></i>
                                                Change Password
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Two-Factor Authentication -->
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            <div class="p-6 sm:p-8">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Two-Factor Authentication</h3>

                                <p class="text-gray-600 mb-6">Add an extra layer of security to your account by enabling two-factor authentication.</p>

                                <!-- 2FA Status -->
                                <div class="bg-gray-50 rounded-lg p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div>
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 mr-3">
                                                <i class="fas fa-shield-alt"></i>
                                            </div>
                                            <p class="font-medium text-gray-900">Not Enabled</p>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1.5 ml-11">Two-factor authentication is not currently enabled for your account.</p>
                                    </div>
                                    <button type="button" class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                        <i class="fas fa-shield-alt mr-2"></i>
                                        Enable 2FA
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Account Deletion -->
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            <div class="p-6 sm:p-8">
                                <div class="flex items-center mb-4 text-red-600">
                                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold">Delete Account</h3>
                                </div>

                                <div class="bg-red-50 border border-red-100 rounded-lg p-5 mb-6">
                                    <p class="text-gray-700">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                                </div>

                                <form action="{{ route('profile.destroy') }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone and all your data will be permanently erased.')">
                                    @csrf
                                    @method('DELETE')

                                    <div class="space-y-5 max-w-xl">
                                        <div>
                                            <label for="delete_password" class="block text-sm font-medium text-gray-700 mb-1">Enter Your Password to Confirm</label>
                                            <div class="relative rounded-md">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-lock text-gray-400"></i>
                                                </div>
                                                <input type="password"
                                                       name="password"
                                                       id="delete_password"
                                                       required
                                                       class="block w-full pl-10 pr-4 py-3 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-red-500 transition-colors">
                                            </div>
                                            @error('password')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <button type="submit"
                                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                                            <i class="fas fa-trash-alt mr-2"></i>
                                            Permanently Delete Account
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Privacy Tab -->
                    <div x-show="activeTab === 'privacy'" class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-6 sm:p-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-6">Privacy Settings</h3>

                            <form action="{{ route('profile.update.privacy') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="space-y-8">
                                    <!-- Profile Visibility -->
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-700 mb-4">Profile Visibility</h4>
                                        <div class="bg-gray-50 rounded-lg p-5 space-y-4">
                                            <label class="flex items-start">
                                                <input type="radio" class="mt-1 h-5 w-5 text-blue-600 focus:ring-blue-500" name="profile_visibility" value="public" checked>
                                                <div class="ml-3">
                                                    <span class="font-medium text-gray-800">Public</span>
                                                    <p class="text-xs text-gray-500 mt-0.5">Anyone can see your profile information and activity</p>
                                                </div>
                                            </label>
                                            <label class="flex items-start">
                                                <input type="radio" class="mt-1 h-5 w-5 text-blue-600 focus:ring-blue-500" name="profile_visibility" value="registered">
                                                <div class="ml-3">
                                                    <span class="font-medium text-gray-800">Registered users only</span>
                                                    <p class="text-xs text-gray-500 mt-0.5">Only users with an account can see your profile information</p>
                                                </div>
                                            </label>
                                            <label class="flex items-start">
                                                <input type="radio" class="mt-1 h-5 w-5 text-blue-600 focus:ring-blue-500" name="profile_visibility" value="private">
                                                <div class="ml-3">
                                                    <span class="font-medium text-gray-800">Private</span>
                                                    <p class="text-xs text-gray-500 mt-0.5">Only you can see your profile information</p>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Data Collection -->
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-700 mb-4">Data Collection and Usage</h4>
                                        <div class="bg-gray-50 rounded-lg p-5 space-y-4">
                                            <label class="flex items-start">
                                                <input type="checkbox" class="rounded-sm border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mt-1 h-5 w-5" name="data_collection[]" value="location_tracking" checked>
                                                <div class="ml-3">
                                                    <span class="font-medium text-gray-800">Location Tracking</span>
                                                    <p class="text-xs text-gray-500 mt-0.5">Allow us to track your location to provide personalized recommendations.</p>
                                                </div>
                                            </label>
                                            <label class="flex items-start">
                                                <input type="checkbox" class="rounded-sm border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mt-1 h-5 w-5" name="data_collection[]" value="usage_data" checked>
                                                <div class="ml-3">
                                                    <span class="font-medium text-gray-800">Usage Data</span>
                                                    <p class="text-xs text-gray-500 mt-0.5">Allow us to collect data about how you use our platform to improve our services.</p>
                                                </div>
                                            </label>
                                            <label class="flex items-start">
                                                <input type="checkbox" class="rounded-sm border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mt-1 h-5 w-5" name="data_collection[]" value="personalized_ads" checked>
                                                <div class="ml-3">
                                                    <span class="font-medium text-gray-800">Personalized Advertising</span>
                                                    <p class="text-xs text-gray-500 mt-0.5">Allow us to use your data to show you personalized advertisements.</p>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Connected Services -->
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-700 mb-4">Connected Services</h4>

                                        <div class="space-y-4">
                                            @if(Auth::user()->google_id)
                                                <div class="flex items-center justify-between p-5 bg-gray-50 rounded-lg">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-sm mr-4">
                                                            <i class="fab fa-google text-red-500 text-xl"></i>
                                                        </div>
                                                        <div>
                                                            <p class="font-medium text-gray-900">Google</p>
                                                            <p class="text-sm text-gray-600">Connected to your Google account</p>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-red-600 hover:bg-red-50 transition-colors text-sm font-medium">
                                                        Disconnect
                                                    </button>
                                                </div>
                                            @else
                                                <div class="flex items-center justify-between p-5 bg-gray-50 rounded-lg">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-sm mr-4">
                                                            <i class="fab fa-google text-gray-400 text-xl"></i>
                                                        </div>
                                                        <div>
                                                            <p class="font-medium text-gray-900">Google</p>
                                                            <p class="text-sm text-gray-600">Connect your Google account for easier login</p>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors text-sm font-medium">
                                                        Connect
                                                    </button>
                                                </div>
                                            @endif

                                            <div class="flex items-center justify-between p-5 bg-gray-50 rounded-lg">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-sm mr-4">
                                                        <i class="fab fa-facebook text-blue-500 text-xl"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-900">Facebook</p>
                                                        <p class="text-sm text-gray-600">Connect your Facebook account for easier login</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors text-sm font-medium">
                                                    Connect
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <div class="mt-8 flex justify-end">
                                    <button type="submit"
                                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                        <i class="fas fa-save mr-2"></i>
                                        Save Privacy Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('footer.footer')

    <!-- Success Toast -->
    @if(session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-8"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-8"
             class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-4 rounded-xl shadow-xl flex items-center z-50">
            <i class="fas fa-check-circle mr-2 text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Error Toast -->
    @if(session('error'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-8"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-8"
             class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-4 rounded-xl shadow-xl flex items-center z-50">
            <i class="fas fa-exclamation-circle mr-2 text-xl"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <script>
        // Profile picture preview
        document.getElementById('profile_picture_upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewContainer = document.getElementById('profile-preview');
                    previewContainer.innerHTML = '';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover';
                    img.alt = 'Profile Preview';

                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        });

        // Geolocation
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                const altitude = position.coords.altitude; // may be null on many devices

                // Display coordinates
                document.getElementById('user-coordinates').innerText =
                    `Latitude: ${latitude.toFixed(6)}, Longitude: ${longitude.toFixed(6)}`;

                // Auto-fill country field if empty
                const countryField = document.getElementById('country');
                if (!countryField.value) {
                    // Reverse geocode using Nominatim (free, no API key required for low-volume requests)
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
                        .then(response => response.json())
                        .then(data => {
                            if(data && data.address && data.address.country) {
                                countryField.value = data.address.country;
                                document.getElementById('user-country').innerText = data.address.country;
                            }
                        })
                        .catch(error => console.error('Reverse geocoding error:', error));
                }
            }, function(error) {
                console.error('Geolocation error:', error);
                document.getElementById('user-coordinates').innerText = 'Location access denied or unavailable.';
            });
        } else {
            console.error("Geolocation is not supported by this browser.");
            document.getElementById('user-coordinates').innerText = 'Geolocation not supported by your browser.';
        }
    </script>
</body>
</html>
