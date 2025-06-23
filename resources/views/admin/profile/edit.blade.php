@extends('layouts.admin')

@section('active-settings', 'menu-item-active')
@section('page-title', 'Profile Settings')
@section('page-subtitle', 'Manage your admin profile and account settings')

@section('content')
<div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-white mb-1">Profile Settings</h2>
                <p class="text-gray-300 text-sm">Update your personal information and account settings</p>
            </div>
            <div class="mt-3 sm:mt-0">
                <a href="{{ route('admin.dashboard') }}"
                   class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mx-6 mb-6 bg-green-900/50 border border-green-700 text-green-200 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mx-6 mb-6 bg-red-900/50 border border-red-700 text-red-200 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mx-6 mb-6 bg-red-900/50 border border-red-700 text-red-200 px-4 py-3 rounded-lg">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span class="font-medium">Please fix the following errors:</span>
            </div>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Profile Form -->
    <div class="p-6">
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Picture Section -->
                <div class="bg-gray-700 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-camera text-blue-400 mr-2"></i>
                        Profile Picture
                    </h3>

                    <div class="text-center">
                        <div class="mb-4">
                            @if($admin->profile_picture)
                                <img src="{{ Storage::url($admin->profile_picture) }}"
                                     alt="Profile Picture"
                                     class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-gray-600">
                            @else
                                <div class="w-24 h-24 rounded-full mx-auto bg-gradient-to-br from-red-600 to-red-800 flex items-center justify-center border-4 border-gray-600">
                                    <i class="fas fa-user-shield text-white text-2xl"></i>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-3">
                            <input type="file"
                                   id="profile_picture"
                                   name="profile_picture"
                                   accept="image/*"
                                   class="hidden">
                            <label for="profile_picture"
                                   class="inline-block bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg cursor-pointer transition-colors duration-200">
                                <i class="fas fa-upload mr-2"></i>
                                Upload New Picture
                            </label>

                            @if($admin->profile_picture)
                                <button type="button"
                                        onclick="removeProfilePicture()"
                                        class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-trash mr-2"></i>
                                    Remove Picture
                                </button>
                            @endif
                        </div>
                        @error('profile_picture')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="lg:col-span-2 bg-gray-700 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-user text-green-400 mr-2"></i>
                        Personal Information
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Full Name *</label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $admin->name) }}"
                                   required
                                   class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address *</label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $admin->email) }}"
                                   required
                                   class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('email')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="sm:col-span-2">
                            <label for="phone_number" class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                            <input type="text"
                                   id="phone_number"
                                   name="phone_number"
                                   value="{{ old('phone_number', $admin->phone_number) }}"
                                   placeholder="Enter your phone number"
                                   class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Role (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Current Role</label>
                            <div class="px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg">
                                <span class="text-white">{{ ucfirst(str_replace('_', ' ', $admin->role)) }}</span>
                            </div>
                        </div>

                        <!-- Current Status (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Account Status</label>
                            <div class="px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $admin->status === 'active' ? 'bg-green-800 text-green-200' : 'bg-red-800 text-red-200' }}">
                                    {{ ucfirst($admin->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Change Section -->
            <div class="bg-gray-700 rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-lock text-yellow-400 mr-2"></i>
                    Change Password
                </h3>
                <p class="text-sm text-gray-400 mb-4">Leave password fields empty if you don't want to change your password.</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-300 mb-2">Current Password</label>
                        <input type="password"
                               id="current_password"
                               name="current_password"
                               placeholder="Enter current password"
                               class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">New Password</label>
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="Enter new password"
                               class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('password')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Confirm New Password</label>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               placeholder="Confirm new password"
                               class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-info-circle text-purple-400 mr-2"></i>
                    Account Information
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <label class="text-gray-300 font-medium">Account Created</label>
                        <p class="text-white">{{ $admin->created_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                    <div>
                        <label class="text-gray-300 font-medium">Last Updated</label>
                        <p class="text-white">{{ $admin->updated_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                    @if($admin->last_login_at)
                    <div>
                        <label class="text-gray-300 font-medium">Last Login</label>
                        <p class="text-white">{{ $admin->last_login_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="text-gray-300 font-medium">Admin ID</label>
                        <p class="text-white">#{{ $admin->id }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-600">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-lg transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>
                    Update Profile
                </button>
                <a href="{{ route('admin.dashboard') }}"
                   class="bg-gray-600 hover:bg-gray-500 text-white px-6 py-2 rounded-lg transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Handle profile picture removal
    function removeProfilePicture() {
        if (confirm('Are you sure you want to remove your profile picture?')) {
            // Create a form to submit the delete request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.profile.remove-picture") }}';

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add method override for DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            document.body.appendChild(form);
            form.submit();
        }
    }

    // Temporarily disable all validation to test form submission
    console.log('Profile edit page loaded - form validation disabled for testing');

    // Just log when button is clicked
    document.addEventListener('DOMContentLoaded', function() {
        const submitButton = document.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.addEventListener('click', function(e) {
                console.log('Submit button clicked - form should submit');
            });
        }
    });

    // Preview uploaded image
    document.getElementById('profile_picture').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.querySelector('img[alt="Profile Picture"]');
                if (img) {
                    img.src = e.target.result;
                } else {
                    // Replace the icon with an image
                    const iconDiv = document.querySelector('.bg-gradient-to-br');
                    if (iconDiv) {
                        iconDiv.innerHTML = `<img src="${e.target.result}" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover">`;
                        iconDiv.className = 'w-24 h-24 rounded-full mx-auto border-4 border-gray-600 overflow-hidden';
                    }
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
