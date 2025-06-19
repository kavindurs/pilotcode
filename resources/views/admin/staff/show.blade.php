@extends('layouts.admin')

@section('active-staff', 'menu-item-active')
@section('page-title', 'Staff Details')
@section('page-subtitle', 'View detailed information about this staff member.')

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
                    <a href="{{ route('admin.staff.index') }}" class="text-gray-400 hover:text-white transition-colors">
                        Staff
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">{{ $staffMember->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-4">
                        @if($staffMember->profile_picture)
                            <img src="{{ Storage::url($staffMember->profile_picture) }}" alt="{{ $staffMember->name }}" class="h-16 w-16 rounded-full object-cover">
                        @else
                            <i class="fas fa-user text-white text-2xl"></i>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $staffMember->name }}</h2>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @switch($staffMember->role)
                                    @case('super_admin')
                                        bg-red-600 text-white
                                        @break
                                    @case('admin')
                                        bg-purple-600 text-white
                                        @break
                                    @case('editor')
                                        bg-blue-600 text-white
                                        @break
                                    @case('accountant')
                                        bg-green-600 text-white
                                        @break
                                    @case('worker')
                                        bg-yellow-600 text-white
                                        @break
                                    @default
                                        bg-gray-600 text-white
                                @endswitch
                            ">
                                <i class="fas
                                    @switch($staffMember->role)
                                        @case('super_admin')
                                            fa-crown
                                            @break
                                        @case('admin')
                                            fa-shield-alt
                                            @break
                                        @case('editor')
                                            fa-edit
                                            @break
                                        @case('accountant')
                                            fa-calculator
                                            @break
                                        @case('worker')
                                            fa-hard-hat
                                            @break
                                        @default
                                            fa-user
                                    @endswitch
                                    mr-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $staffMember->role)) }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @switch($staffMember->status)
                                    @case('active')
                                        bg-green-600 text-white
                                        @break
                                    @case('inactive')
                                        bg-gray-600 text-white
                                        @break
                                    @case('suspended')
                                        bg-red-600 text-white
                                        @break
                                    @default
                                        bg-gray-600 text-white
                                @endswitch
                            ">
                                <i class="fas
                                    @switch($staffMember->status)
                                        @case('active')
                                            fa-check-circle
                                            @break
                                        @case('inactive')
                                            fa-pause-circle
                                            @break
                                        @case('suspended')
                                            fa-ban
                                            @break
                                        @default
                                            fa-question-circle
                                    @endswitch
                                    mr-1"></i>
                                {{ ucfirst($staffMember->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.staff.edit', $staffMember->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    <a href="{{ route('admin.staff.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Personal Information -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-user mr-2"></i>
                    Personal Information
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Full Name</span>
                    <span class="text-white font-medium">{{ $staffMember->name }}</span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Email Address</span>
                    <span class="text-white">{{ $staffMember->email }}</span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Phone Number</span>
                    <span class="text-white">{{ $staffMember->phone_number ?: 'Not provided' }}</span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Staff ID</span>
                    <span class="text-white font-mono">{{ $staffMember->id }}</span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Account Created</span>
                    <span class="text-white">{{ $staffMember->created_at->format('M d, Y g:i A') }}</span>
                </div>

                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-400">Last Updated</span>
                    <span class="text-white">{{ $staffMember->updated_at->format('M d, Y g:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Account Status & Activity -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-chart-line mr-2"></i>
                    Account Status & Activity
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Current Role</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @switch($staffMember->role)
                            @case('super_admin')
                                bg-red-600 text-white
                                @break
                            @case('admin')
                                bg-purple-600 text-white
                                @break
                            @case('editor')
                                bg-blue-600 text-white
                                @break
                            @case('accountant')
                                bg-green-600 text-white
                                @break
                            @case('worker')
                                bg-yellow-600 text-white
                                @break
                            @default
                                bg-gray-600 text-white
                        @endswitch
                    ">
                        <i class="fas
                            @switch($staffMember->role)
                                @case('super_admin')
                                    fa-crown
                                    @break
                                @case('admin')
                                    fa-shield-alt
                                    @break
                                @case('editor')
                                    fa-edit
                                    @break
                                @case('accountant')
                                    fa-calculator
                                    @break
                                @case('worker')
                                    fa-hard-hat
                                    @break
                                @default
                                    fa-user
                            @endswitch
                            mr-1"></i>
                        {{ ucfirst(str_replace('_', ' ', $staffMember->role)) }}
                    </span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Account Status</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @switch($staffMember->status)
                            @case('active')
                                bg-green-600 text-white
                                @break
                            @case('inactive')
                                bg-gray-600 text-white
                                @break
                            @case('suspended')
                                bg-red-600 text-white
                                @break
                            @default
                                bg-gray-600 text-white
                        @endswitch
                    ">
                        <i class="fas
                            @switch($staffMember->status)
                                @case('active')
                                    fa-check-circle
                                    @break
                                @case('inactive')
                                    fa-pause-circle
                                    @break
                                @case('suspended')
                                    fa-ban
                                    @break
                                @default
                                    fa-question-circle
                            @endswitch
                            mr-1"></i>
                        {{ ucfirst($staffMember->status) }}
                    </span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Last Login</span>
                    <span class="text-white">
                        @if($staffMember->last_login_at)
                            {{ $staffMember->last_login_at->format('M d, Y g:i A') }}
                        @else
                            <span class="text-gray-500">Never logged in</span>
                        @endif
                    </span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Email Verified</span>
                    <span class="text-white">
                        @if($staffMember->email_verified_at)
                            <span class="text-green-400">
                                <i class="fas fa-check-circle mr-1"></i>
                                Verified
                            </span>
                        @else
                            <span class="text-yellow-400">
                                <i class="fas fa-clock mr-1"></i>
                                Pending
                            </span>
                        @endif
                    </span>
                </div>

                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-400">Remember Token</span>
                    <span class="text-white">{{ $staffMember->remember_token ? 'Active' : 'None' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden mt-6">
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-bolt mr-2"></i>
                Quick Actions
            </h3>
        </div>
        <div class="p-6">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('admin.staff.edit', $staffMember->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Profile
                </a>

                @if($staffMember->status === 'active')
                    <form method="POST" action="{{ route('admin.staff.deactivate', $staffMember->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors" onclick="return confirm('Are you sure you want to deactivate this staff member?')">
                            <i class="fas fa-pause mr-2"></i>
                            Deactivate Account
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.staff.activate', $staffMember->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-play mr-2"></i>
                            Activate Account
                        </button>
                    </form>
                @endif

                @if($staffMember->status !== 'suspended')
                    <form method="POST" action="{{ route('admin.staff.suspend', $staffMember->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors" onclick="return confirm('Are you sure you want to suspend this staff member?')">
                            <i class="fas fa-ban mr-2"></i>
                            Suspend Account
                        </button>
                    </form>
                @endif

                @if($staffMember->id !== auth('admin')->id())
                    <form method="POST" action="{{ route('admin.staff.destroy', $staffMember->id) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors" onclick="return confirm('Are you sure you want to delete this staff member? This action cannot be undone.')">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Account
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
