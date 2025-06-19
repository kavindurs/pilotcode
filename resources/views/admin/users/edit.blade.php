@extends('layouts.admin')

@section('active-users', 'menu-item-active')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Modify user account details and settings.')

@section('content')
<div class="max-w-4xl mx-auto">
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
                    <span class="text-gray-300">Edit {{ $user->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">Edit User</h2>
                    <p class="text-gray-400 text-sm">Update user information and settings</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Users
                </a>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column - Basic Info -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-user text-blue-400 mr-2"></i>
                        Basic Information
                    </h3>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            Full Name *
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        >
                        @error('name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            Email Address *
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                        >
                        @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-300 mb-2">
                            Country
                        </label>
                        <input
                            type="text"
                            id="country"
                            name="country"
                            value="{{ old('country', $user->country) }}"
                            class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('country') border-red-500 @enderror"
                        >
                        @error('country')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="user_type" class="block text-sm font-medium text-gray-300 mb-2">
                            User Type *
                        </label>
                        <select
                            id="user_type"
                            name="user_type"
                            required
                            class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('user_type') border-red-500 @enderror"
                        >
                            <option value="regular user" {{ old('user_type', $user->user_type) === 'regular user' ? 'selected' : '' }}>Regular User</option>
                            <option value="business owner" {{ old('user_type', $user->user_type) === 'business owner' ? 'selected' : '' }}>Business Owner</option>
                            <option value="admin" {{ old('user_type', $user->user_type) === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('user_type')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="profile_picture" class="block text-sm font-medium text-gray-300 mb-2">
                            Profile Picture
                        </label>
                        <input
                            type="file"
                            id="profile_picture"
                            name="profile_picture"
                            accept="image/*"
                            class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('profile_picture') border-red-500 @enderror"
                        >
                        @if($user->profile_picture)
                            <div class="mt-2">
                                <img src="{{ Storage::url($user->profile_picture) }}" alt="Current profile picture" class="w-16 h-16 rounded-full object-cover">
                                <p class="text-sm text-gray-400 mt-1">Current profile picture</p>
                            </div>
                        @endif
                        @error('profile_picture')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column - Security & Settings -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-shield-alt text-green-400 mr-2"></i>
                        Security & Settings
                    </h3>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                            New Password
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                        >
                        <p class="text-sm text-gray-400 mt-1">Leave blank to keep current password</p>
                        @error('password')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                            Confirm New Password
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                id="is_verified"
                                name="is_verified"
                                value="1"
                                {{ old('is_verified', $user->is_verified) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500"
                            >
                            <label for="is_verified" class="ml-2 text-sm text-gray-300">
                                Email Verified
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                id="two_factor_enabled"
                                name="two_factor_enabled"
                                value="1"
                                {{ old('two_factor_enabled', $user->two_factor_enabled) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500"
                            >
                            <label for="two_factor_enabled" class="ml-2 text-sm text-gray-300">
                                Two-Factor Authentication Enabled
                            </label>
                        </div>
                    </div>

                    <!-- Account Info Display -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-4">
                        <h4 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                            Account Information
                        </h4>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">User ID</span>
                                <span class="text-white">#{{ $user->id }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Created</span>
                                <span class="text-white">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Last Updated</span>
                                <span class="text-white">{{ $user->updated_at->format('M d, Y') }}</span>
                            </div>
                            @if($user->google_id)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Google Account</span>
                                <span class="text-blue-400 flex items-center">
                                    <i class="fab fa-google mr-1"></i>
                                    Connected
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-700">
                <a
                    href="{{ route('admin.users.index') }}"
                    class="px-6 py-2 text-gray-400 hover:text-white transition-colors"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-lg transition-colors font-medium"
                >
                    <i class="fas fa-save mr-2"></i>
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
