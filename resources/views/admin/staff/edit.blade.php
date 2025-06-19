@extends('layouts.admin')

@section('active-staff', 'menu-item-active')
@section('page-title', 'Edit Staff Member')
@section('page-subtitle', 'Update staff member information, role and permissions')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <a href="{{ route('admin.staff.index') }}" class="text-gray-400 hover:text-white transition-colors">Staff Management</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">Edit Staff Member</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white mb-1">Edit Staff Member</h2>
                    <p class="text-gray-300 text-sm">Update {{ $staffMember->name }}'s information and permissions</p>
                </div>
                <div class="mt-3 sm:mt-0">
                    <a href="{{ route('admin.staff.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Staff
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if ($errors->any())
                <div class="bg-red-900 border border-red-600 text-red-100 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span class="font-medium">Please correct the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.staff.update', $staffMember->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            Full Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $staffMember->name) }}"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               placeholder="Enter full name"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            Email Address <span class="text-red-400">*</span>
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $staffMember->email) }}"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               placeholder="Enter email address"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-300 mb-2">
                            Phone Number
                        </label>
                        <input type="text"
                               id="phone_number"
                               name="phone_number"
                               value="{{ old('phone_number', $staffMember->phone_number) }}"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               placeholder="Enter phone number">
                        @error('phone_number')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-300 mb-2">
                            Role <span class="text-red-400">*</span>
                        </label>
                        <select id="role"
                                name="role"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                required>
                            <option value="">Select Role</option>
                            <option value="super_admin" {{ old('role', $staffMember->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="admin" {{ old('role', $staffMember->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="editor" {{ old('role', $staffMember->role) == 'editor' ? 'selected' : '' }}>Editor</option>
                            <option value="accountant" {{ old('role', $staffMember->role) == 'accountant' ? 'selected' : '' }}>Accountant</option>
                            <option value="worker" {{ old('role', $staffMember->role) == 'worker' ? 'selected' : '' }}>Worker</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-300 mb-2">
                            Status <span class="text-red-400">*</span>
                        </label>
                        <select id="status"
                                name="status"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                required>
                            <option value="">Select Status</option>
                            <option value="active" {{ old('status', $staffMember->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $staffMember->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status', $staffMember->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="border-t border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-white mb-4">Change Password (Optional)</h3>
                    <p class="text-sm text-gray-400 mb-4">Leave password fields empty if you don't want to change the password.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                                New Password
                            </label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   placeholder="Enter new password">
                            @error('password')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                                Confirm New Password
                            </label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   placeholder="Confirm new password">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center pt-6 border-t border-gray-700">
                    <div class="flex items-center space-x-4">
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                            <i class="fas fa-save mr-2"></i>
                            Update Staff Member
                        </button>
                        <a href="{{ route('admin.staff.index') }}"
                           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-gray-750 px-6 py-4 border-t border-gray-600">
            <div class="flex flex-col sm:flex-row sm:justify-between text-sm text-gray-400">
                <div class="flex items-center mb-2 sm:mb-0">
                    <i class="fas fa-clock mr-2"></i>
                    Created: {{ $staffMember->created_at->format('M d, Y g:i A') }}
                </div>
                <div class="flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    Last Updated: {{ $staffMember->updated_at->format('M d, Y g:i A') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', function() {
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
    });
});
</script>
@endsection
