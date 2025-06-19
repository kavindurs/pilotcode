@extends('layouts.admin')

@section('active-staff', 'menu-item-active')
@section('page-title', 'Staff Management')
@section('page-subtitle', 'Manage all staff members, add new members, edit roles and permissions.')

@section('content')
<div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-white">Staff Members</h2>
                <p class="text-gray-400 text-sm">Total: {{ $staff->total() }} staff members</p>
            </div>

            <!-- Search & Filter Form -->
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.staff.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Staff
                </a>

                <form method="GET" action="{{ route('admin.staff.index') }}" class="flex items-center gap-2">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search staff..."
                            class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg pl-10 pr-4 py-2 w-64 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>

                    <select name="role" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Roles</option>
                        <option value="super_admin" {{ $role == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="editor" {{ $role == 'editor' ? 'selected' : '' }}>Editor</option>
                        <option value="accountant" {{ $role == 'accountant' ? 'selected' : '' }}>Accountant</option>
                        <option value="worker" {{ $role == 'worker' ? 'selected' : '' }}>Worker</option>
                    </select>

                    <select name="status" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ $status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                    @if($search || $role || $status)
                        <a href="{{ route('admin.staff.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-600 text-white px-6 py-3 border-b border-gray-700">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-600 text-white px-6 py-3 border-b border-gray-700">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($staff->count() > 0)
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-750 border-b border-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Staff Member</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($staff as $member)
                        <tr class="bg-gray-800 hover:bg-gray-750 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-3">
                                        @if($member->profile_picture)
                                            <img src="{{ Storage::url($member->profile_picture) }}" alt="{{ $member->name }}" class="h-10 w-10 rounded-full object-cover">
                                        @else
                                            <i class="fas fa-user text-white text-sm"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-white">{{ $member->name }}</div>
                                        <div class="text-sm text-gray-400">ID: {{ $member->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($member->role)
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
                                        @switch($member->role)
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
                                    {{ ucfirst(str_replace('_', ' ', $member->role)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <div class="text-white">{{ $member->email }}</div>
                                    @if($member->phone_number)
                                        <div class="text-gray-400">{{ $member->phone_number }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($member->status)
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
                                        @switch($member->status)
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
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                @if($member->last_login_at)
                                    {{ $member->last_login_at->format('M d, Y g:i A') }}
                                @else
                                    <span class="text-gray-500">Never</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                {{ $member->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.staff.show', $member->id) }}"
                                       class="text-blue-400 hover:text-blue-300 transition-colors p-1"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.staff.edit', $member->id) }}"
                                       class="text-yellow-400 hover:text-yellow-300 transition-colors p-1"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($member->status === 'active')
                                        <form method="POST" action="{{ route('admin.staff.deactivate', $member->id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-orange-400 hover:text-orange-300 transition-colors p-1"
                                                    title="Deactivate"
                                                    onclick="return confirm('Are you sure you want to deactivate this staff member?')">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.staff.activate', $member->id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-green-400 hover:text-green-300 transition-colors p-1"
                                                    title="Activate">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($member->status !== 'suspended')
                                        <form method="POST" action="{{ route('admin.staff.suspend', $member->id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-red-400 hover:text-red-300 transition-colors p-1"
                                                    title="Suspend"
                                                    onclick="return confirm('Are you sure you want to suspend this staff member?')">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($member->id !== auth('admin')->id())
                                        <form method="POST" action="{{ route('admin.staff.destroy', $member->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-400 hover:text-red-300 transition-colors p-1"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this staff member? This action cannot be undone.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-700">
            {{ $staff->appends(request()->query())->links('admin.partials.pagination') }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-700 mb-4">
                <i class="fas fa-users text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-white mb-2">No Staff Members Found</h3>
            <p class="text-gray-400 mb-6">
                @if($search || $role || $status)
                    No staff members match your current search criteria.
                @else
                    Start by adding your first staff member.
                @endif
            </p>
            @if(!$search && !$role && !$status)
                <a href="{{ route('admin.staff.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add First Staff Member
                </a>
            @else
                <a href="{{ route('admin.staff.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-list mr-2"></i>
                    View All Staff
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
