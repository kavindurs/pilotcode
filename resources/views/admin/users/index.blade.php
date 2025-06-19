@extends('layouts.admin')

@section('active-users', 'menu-item-active')
@section('page-title', 'User Management')
@section('page-subtitle', 'Manage all users, their profiles, and account settings.')

@section('content')
<div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-white">Users</h2>
                <p class="text-gray-400 text-sm">Total: {{ $users->total() }} users</p>
            </div>

            <!-- Filters and Search -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row items-center gap-2">
                    <!-- Search Input -->
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search users..."
                            class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg pl-10 pr-4 py-2 w-64 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>

                    <!-- User Type Filter -->
                    <select name="user_type" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">All Types</option>
                        <option value="regular user" {{ $userType === 'regular user' ? 'selected' : '' }}>Regular User</option>
                        <option value="business owner" {{ $userType === 'business owner' ? 'selected' : '' }}>Business Owner</option>
                        <option value="admin" {{ $userType === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>

                    <!-- Status Filter -->
                    <select name="status" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="verified" {{ $status === 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="unverified" {{ $status === 'unverified' ? 'selected' : '' }}>Unverified</option>
                    </select>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-search"></i>
                    </button>

                    @if($search || $userType || $status)
                        <a href="{{ route('admin.users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-900">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Country</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">2FA</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-4 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                @forelse($users as $user)
                <tr class="hover:bg-gray-750 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($user->profile_picture)
                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-600"
                                         src="{{ Storage::url($user->profile_picture) }}"
                                         alt="{{ $user->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-white font-medium">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-white">{{ $user->name }}</div>
                                <div class="text-sm text-gray-400">ID: {{ $user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-300">{{ $user->email }}</div>
                        @if($user->google_id)
                            <div class="text-xs text-blue-400 flex items-center mt-1">
                                <i class="fab fa-google mr-1"></i>
                                Google Account
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
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
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-300">{{ $user->country ?: 'Not specified' }}</div>
                    </td>
                    <td class="px-6 py-4">
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
                    </td>
                    <td class="px-6 py-4">
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
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-400">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <!-- View Button -->
                            <a
                                href="{{ route('admin.users.show', $user->id) }}"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                title="View User"
                            >
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- Edit Button -->
                            <a
                                href="{{ route('admin.users.edit', $user->id) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                title="Edit User"
                            >
                                <i class="fas fa-edit"></i>
                            </a>

                            @if($user->is_verified)
                                <!-- Unverify Button -->
                                <form method="POST" action="{{ route('admin.users.unverify', $user->id) }}" class="inline">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="bg-yellow-600 hover:bg-yellow-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                        title="Unverify User"
                                        onclick="return confirm('Are you sure you want to unverify this user?')"
                                    >
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                </form>
                            @else
                                <!-- Verify Button -->
                                <form method="POST" action="{{ route('admin.users.verify', $user->id) }}" class="inline">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                        title="Verify User"
                                        onclick="return confirm('Are you sure you want to verify this user?')"
                                    >
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                            @endif

                            <!-- Delete Button -->
                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                    title="Delete User"
                                    onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-users text-4xl text-gray-600 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-400 mb-2">No users found</h3>
                            <p class="text-gray-500">
                                @if($search || $userType || $status)
                                    No users match your search criteria.
                                @else
                                    No users have registered yet.
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="bg-gray-900 px-6 py-4 border-t border-gray-700">
        {{ $users->appends(request()->query())->links('custom.pagination') }}
    </div>
    @endif
</div>
@endsection
