@extends('layouts.admin')

@section('active-staff', 'menu-item-active')
@section('page-title', 'Add New Staff Member')
@section('page-subtitle', 'Create a new staff member account with specific role and permissions.')

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
                    <a href="{{ route('admin.staff.index') }}" class="text-gray-400 hover:text-white transition-colors">
                        Staff
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">Add New</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">Add New Staff Member</h2>
                    <p class="text-gray-400 text-sm">Create a new staff account with role and permissions</p>
                </div>
                <a href="{{ route('admin.staff.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Staff
                </a>
            </div>
        </div>

        @if($errors->any())
            <div class="bg-red-600 text-white px-6 py-3 border-b border-gray-700">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <div>
                        <div class="font-medium">Please fix the following errors:</div>
                        <ul class="mt-1 text-sm list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('admin.staff.store') }}" class="p-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column - Basic Info -->
                <div class="space-y-6">
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-user mr-2"></i>
                            Personal Information
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                    Full Name *
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                    placeholder="Enter full name"
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
                                    value="{{ old('email') }}"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                    placeholder="Enter email address"
                                >
                                @error('email')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-300 mb-2">
                                    Phone Number
                                </label>
                                <input
                                    type="text"
                                    id="phone_number"
                                    name="phone_number"
                                    value="{{ old('phone_number') }}"
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone_number') border-red-500 @enderror"
                                    placeholder="Enter phone number (optional)"
                                >
                                @error('phone_number')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-lock mr-2"></i>
                            Security
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                                    Password *
                                </label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                                    placeholder="Enter password"
                                >
                                @error('password')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                                    Confirm Password *
                                </label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Confirm password"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Role & Status -->
                <div class="space-y-6">
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-shield-alt mr-2"></i>
                            Role & Permissions
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-300 mb-2">
                                    Staff Role *
                                </label>
                                <select
                                    id="role"
                                    name="role"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role') border-red-500 @enderror"
                                >
                                    <option value="">Select a role</option>
                                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="editor" {{ old('role') == 'editor' ? 'selected' : '' }}>Editor</option>
                                    <option value="accountant" {{ old('role') == 'accountant' ? 'selected' : '' }}>Accountant</option>
                                    <option value="worker" {{ old('role') == 'worker' ? 'selected' : '' }}>Worker</option>
                                </select>
                                @error('role')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-300 mb-2">
                                    Account Status *
                                </label>
                                <select
                                    id="status"
                                    name="status"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror"
                                >
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Role Descriptions -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Role Descriptions
                        </h3>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-start">
                                <i class="fas fa-crown text-red-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-white font-medium">Super Admin</p>
                                    <p class="text-gray-400">Full system access and control</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="fas fa-shield-alt text-purple-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-white font-medium">Admin</p>
                                    <p class="text-gray-400">Manage users, properties, and content</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="fas fa-edit text-blue-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-white font-medium">Editor</p>
                                    <p class="text-gray-400">Content management and moderation</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="fas fa-calculator text-green-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-white font-medium">Accountant</p>
                                    <p class="text-gray-400">Financial data and payment management</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="fas fa-hard-hat text-yellow-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-white font-medium">Worker</p>
                                    <p class="text-gray-400">Basic operational tasks and support</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-700">
                <a
                    href="{{ route('admin.staff.index') }}"
                    class="px-6 py-2 text-gray-400 hover:text-white transition-colors"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-2 rounded-lg transition-colors font-medium"
                >
                    <i class="fas fa-plus mr-2"></i>
                    Create Staff Member
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
