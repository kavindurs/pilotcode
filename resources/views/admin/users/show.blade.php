@extends('layouts.admin')

@section('active-users', 'menu-item-active')
@section('page-title', 'User Details')
@section('page-subtitle', 'View detailed information about {{ $user->name }}')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-white transition-colors">
                        Users
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">{{ $user->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
                <!-- Profile Header -->
                <div class="bg-gradient-to-br from-blue-600 to-purple-700 p-6 text-center">
                    @if($user->profile_picture)
                        <img class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-white object-cover"
                             src="{{ Storage::url($user->profile_picture) }}"
                             alt="{{ $user->name }}">
                    @else
                        <div class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-white bg-white flex items-center justify-center">
                            <span class="text-gray-600 text-2xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <h3 class="text-xl font-bold text-white">{{ $user->name }}</h3>
                    <p class="text-blue-100">{{ $user->email }}</p>
                </div>

                <!-- Profile Details -->
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">User ID</span>
                        <span class="text-white">#{{ $user->id }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">User Type</span>
                        @if($user->user_type === 'admin')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-300">
                                <i class="fas fa-shield-alt mr-1"></i>
                                Admin
                            </span>
                        @elseif($user->user_type === 'business owner')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-900 text-purple-300">
                                <i class="fas fa-briefcase mr-1"></i>
                                Business
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900 text-blue-300">
                                <i class="fas fa-user mr-1"></i>
                                Regular
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Country</span>
                        <span class="text-white">{{ $user->country ?: 'Not specified' }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Verification Status</span>
                        @if($user->is_verified)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                <i class="fas fa-check-circle mr-1"></i>
                                Verified
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900 text-yellow-300">
                                <i class="fas fa-clock mr-1"></i>
                                Unverified
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Two-Factor Auth</span>
                        @if($user->two_factor_enabled)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                <i class="fas fa-lock mr-1"></i>
                                Enabled
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-900 text-gray-400">
                                <i class="fas fa-unlock mr-1"></i>
                                Disabled
                            </span>
                        @endif
                    </div>

                    @if($user->google_id)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Google Account</span>
                        <span class="text-blue-400 flex items-center">
                            <i class="fab fa-google mr-1"></i>
                            Connected
                        </span>
                    </div>
                    @endif

                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Joined Date</span>
                        <span class="text-white">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Last Updated</span>
                        <span class="text-white">{{ $user->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="p-6 border-t border-gray-700 flex gap-3">
                    <a href="{{ route('admin.users.edit', $user->id) }}"
                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors text-center">
                        <i class="fas fa-edit mr-2"></i>
                        Edit User
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                       class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors text-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back
                    </a>
                </div>
            </div>
        </div>

        <!-- User Activity and Details -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Account Information -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-user-cog text-blue-400 mr-2"></i>
                        Account Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Full Name</label>
                            <p class="text-white bg-gray-700 p-3 rounded-lg">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Email Address</label>
                            <p class="text-white bg-gray-700 p-3 rounded-lg">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Country</label>
                            <p class="text-white bg-gray-700 p-3 rounded-lg">{{ $user->country ?: 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">User Type</label>
                            <p class="text-white bg-gray-700 p-3 rounded-lg">{{ ucwords($user->user_type) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Information -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-shield-alt text-green-400 mr-2"></i>
                        Security Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Email Verification</label>
                            <div class="flex items-center">
                                @if($user->is_verified)
                                    <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                    <span class="text-green-400">Verified</span>
                                    @if($user->email_verified_at)
                                        <span class="text-gray-400 ml-2 text-sm">({{ $user->email_verified_at->format('M d, Y') }})</span>
                                    @endif
                                @else
                                    <i class="fas fa-times-circle text-red-400 mr-2"></i>
                                    <span class="text-red-400">Not Verified</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Two-Factor Authentication</label>
                            <div class="flex items-center">
                                @if($user->two_factor_enabled)
                                    <i class="fas fa-lock text-green-400 mr-2"></i>
                                    <span class="text-green-400">Enabled</span>
                                    @if($user->two_factor_confirmed_at)
                                        <span class="text-gray-400 ml-2 text-sm">({{ $user->two_factor_confirmed_at->format('M d, Y') }})</span>
                                    @endif
                                @else
                                    <i class="fas fa-unlock text-gray-400 mr-2"></i>
                                    <span class="text-gray-400">Disabled</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Stats -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-chart-bar text-yellow-400 mr-2"></i>
                        Activity Statistics
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="bg-blue-900 p-4 rounded-lg">
                                <i class="fas fa-star text-2xl text-blue-400 mb-2"></i>
                                <div class="text-2xl font-bold text-white">{{ $reviewsCount }}</div>
                                <div class="text-sm text-gray-400">Reviews Given</div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="bg-green-900 p-4 rounded-lg">
                                <i class="fas fa-calendar text-2xl text-green-400 mb-2"></i>
                                <div class="text-2xl font-bold text-white">{{ $user->created_at->diffInDays(now()) }}</div>
                                <div class="text-sm text-gray-400">Days Since Joined</div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="bg-purple-900 p-4 rounded-lg">
                                <i class="fas fa-clock text-2xl text-purple-400 mb-2"></i>
                                <div class="text-2xl font-bold text-white">{{ $user->updated_at->diffInDays(now()) }}</div>
                                <div class="text-sm text-gray-400">Days Since Last Update</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
