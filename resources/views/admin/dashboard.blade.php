@extends('layouts.admin')

@section('active-dashboard', 'menu-item-active')
@section('page-title', 'Admin Dashboard')
@section('page-subtitle', 'Monitor and manage your platform with comprehensive administrative tools and insights.')

@section('content')
<!-- Today's Activity Summary -->
<div class="bg-gradient-to-r from-blue-900 to-purple-900 border border-gray-700 rounded-xl shadow-xl p-6 mb-8">
    <div class="flex flex-col lg:flex-row items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-white mb-2">Welcome back, {{ Auth::guard('admin')->user()->name }}!</h2>
            <p class="text-blue-200">Here's what's happening with your platform today.</p>
        </div>
        <div class="mt-4 lg:mt-0 grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-3xl font-bold text-white">{{ $todayStats['new_users'] }}</div>
                <div class="text-blue-200 text-sm">New Users</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-white">{{ $todayStats['new_properties'] }}</div>
                <div class="text-blue-200 text-sm">New Properties</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-white">{{ $todayStats['new_reviews'] }}</div>
                <div class="text-blue-200 text-sm">New Reviews</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-white">{{ $todayStats['new_payments'] }}</div>
                <div class="text-blue-200 text-sm">New Payments</div>
            </div>
        </div>
    </div>
</div>

<!-- Main Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Users Stats -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6 hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 shadow-lg">
                <i class="fas fa-users text-2xl text-white"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-400 text-sm font-medium">Total Users</p>
                <h3 class="text-2xl font-bold text-white">{{ number_format($totalUsers) }}</h3>
                <p class="text-green-400 text-sm mt-1 flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>{{ $newUsersPercentage }}% this month</span>
                </p>
                <p class="text-gray-500 text-xs">{{ number_format($verifiedUsers) }} verified</p>
            </div>
        </div>
    </div>

    <!-- Properties Stats -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6 hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-gradient-to-br from-green-500 to-green-700 shadow-lg">
                <i class="fas fa-building text-2xl text-white"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-400 text-sm font-medium">Properties</p>
                <h3 class="text-2xl font-bold text-white">{{ number_format($totalProperties) }}</h3>
                <p class="text-green-400 text-sm mt-1 flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>{{ $newPropertiesPercentage }}% this month</span>
                </p>
                <p class="text-sm">
                    <span class="text-yellow-400">{{ $pendingProperties }}</span>
                    <span class="text-gray-400">pending</span> •
                    <span class="text-green-400">{{ $approvedProperties }}</span>
                    <span class="text-gray-400">approved</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Reviews Stats -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6 hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-700 shadow-lg">
                <i class="fas fa-star text-2xl text-white"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-400 text-sm font-medium">Reviews</p>
                <h3 class="text-2xl font-bold text-white">{{ number_format($totalReviews) }}</h3>
                <p class="text-green-400 text-sm mt-1 flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>{{ $newReviewsPercentage }}% this month</span>
                </p>
                <p class="text-sm">
                    <span class="text-orange-400">{{ $pendingReviews }}</span>
                    <span class="text-gray-400">pending</span> •
                    <span class="text-green-400">{{ $approvedReviews }}</span>
                    <span class="text-gray-400">approved</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Revenue Stats -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6 hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 shadow-lg">
                <i class="fas fa-dollar-sign text-2xl text-white"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-400 text-sm font-medium">Total Revenue</p>
                <h3 class="text-2xl font-bold text-white">${{ number_format($totalRevenue, 2) }}</h3>
                <p class="text-green-400 text-sm mt-1 flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>{{ $newPaymentsPercentage }}% this month</span>
                </p>
                <p class="text-sm">
                    <span class="text-green-400">{{ $confirmedPayments }}</span>
                    <span class="text-gray-400">confirmed</span> •
                    <span class="text-yellow-400">{{ $pendingPayments }}</span>
                    <span class="text-gray-400">pending</span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Categories -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6 hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 shadow-lg">
                <i class="fas fa-tags text-2xl text-white"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-400 text-sm font-medium">Categories</p>
                <h3 class="text-2xl font-bold text-white">{{ number_format($totalCategories) }}</h3>
                <p class="text-sm mt-1">
                    <span class="text-green-400">{{ $activeCategories }}</span>
                    <span class="text-gray-400">active</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Subcategories -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6 hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-700 shadow-lg">
                <i class="fas fa-tag text-2xl text-white"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-400 text-sm font-medium">Subcategories</p>
                <h3 class="text-2xl font-bold text-white">{{ number_format($totalSubcategories) }}</h3>
                <p class="text-sm mt-1">
                    <span class="text-green-400">{{ $activeSubcategories }}</span>
                    <span class="text-gray-400">active</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Plans -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6 hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-gradient-to-br from-pink-500 to-pink-700 shadow-lg">
                <i class="fas fa-medal text-2xl text-white"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-400 text-sm font-medium">Plans</p>
                <h3 class="text-2xl font-bold text-white">{{ number_format($totalPlans) }}</h3>
                <p class="text-sm text-gray-400 mt-1">Subscription plans</p>
            </div>
        </div>
    </div>

    <!-- Referrals -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6 hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-gradient-to-br from-cyan-500 to-cyan-700 shadow-lg">
                <i class="fas fa-handshake text-2xl text-white"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-400 text-sm font-medium">Referrals</p>
                <h3 class="text-2xl font-bold text-white">{{ number_format($totalReferrals) }}</h3>
                <p class="text-sm mt-1">
                    <span class="text-green-400">{{ $activeReferrals }}</span>
                    <span class="text-gray-400">active</span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Financial Overview -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Financial Stats -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
            <i class="fas fa-chart-line text-green-400 mr-2"></i>
            Financial Overview
        </h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center p-4 bg-gray-700 rounded-lg">
                <div>
                    <p class="text-gray-400 text-sm">Total Revenue</p>
                    <p class="text-white text-xl font-bold">${{ number_format($totalRevenue, 2) }}</p>
                </div>
                <i class="fas fa-dollar-sign text-green-400 text-2xl"></i>
            </div>
            <div class="flex justify-between items-center p-4 bg-gray-700 rounded-lg">
                <div>
                    <p class="text-gray-400 text-sm">Wallet Balance</p>
                    <p class="text-white text-xl font-bold">${{ number_format($totalWalletBalance, 2) }}</p>
                </div>
                <i class="fas fa-wallet text-blue-400 text-2xl"></i>
            </div>
            <div class="flex justify-between items-center p-4 bg-gray-700 rounded-lg">
                <div>
                    <p class="text-gray-400 text-sm">Total Payments</p>
                    <p class="text-white text-xl font-bold">{{ number_format($totalPayments) }}</p>
                </div>
                <i class="fas fa-credit-card text-purple-400 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- System Stats -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
            <i class="fas fa-cogs text-purple-400 mr-2"></i>
            System Overview
        </h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center p-4 bg-gray-700 rounded-lg">
                <div>
                    <p class="text-gray-400 text-sm">Total Wallets</p>
                    <p class="text-white text-xl font-bold">{{ number_format($totalWallets) }}</p>
                </div>
                <i class="fas fa-wallet text-indigo-400 text-2xl"></i>
            </div>
            <div class="flex justify-between items-center p-4 bg-gray-700 rounded-lg">
                <div>
                    <p class="text-gray-400 text-sm">Staff Members</p>
                    <p class="text-white text-xl font-bold">{{ number_format($totalStaff) }}</p>
                    <p class="text-green-400 text-xs">{{ $activeStaff }} active</p>
                </div>
                <i class="fas fa-user-tie text-yellow-400 text-2xl"></i>
            </div>
            <div class="flex justify-between items-center p-4 bg-gray-700 rounded-lg">
                <div>
                    <p class="text-gray-400 text-sm">Server Status</p>
                    <p class="text-green-400 text-xl font-bold">Online</p>
                </div>
                <i class="fas fa-server text-green-400 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
            <i class="fas fa-bolt text-yellow-400 mr-2"></i>
            Quick Actions
        </h3>
        <div class="space-y-3">
            <a href="{{ route('admin.properties.index') }}"
               class="block w-full bg-blue-600 hover:bg-blue-500 text-white p-3 rounded-lg transition-colors duration-200 text-center">
                <i class="fas fa-building mr-2"></i>
                Manage Properties
            </a>
            <a href="{{ route('admin.reviews.index') }}"
               class="block w-full bg-green-600 hover:bg-green-500 text-white p-3 rounded-lg transition-colors duration-200 text-center">
                <i class="fas fa-star mr-2"></i>
                Review Queue
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="block w-full bg-purple-600 hover:bg-purple-500 text-white p-3 rounded-lg transition-colors duration-200 text-center">
                <i class="fas fa-users mr-2"></i>
                User Management
            </a>
            <a href="{{ route('admin.payments.index') }}"
               class="block w-full bg-emerald-600 hover:bg-emerald-500 text-white p-3 rounded-lg transition-colors duration-200 text-center">
                <i class="fas fa-credit-card mr-2"></i>
                Payment Overview
            </a>
        </div>
    </div>
</div>

<!-- Recent Activity Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-6">
    <!-- Recent Properties -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-700 bg-gradient-to-r from-gray-800 to-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-building text-blue-400 mr-2"></i>
                    Recent Properties
                </h2>
                <a href="{{ route('admin.properties.index') }}"
                   class="text-blue-400 hover:text-blue-300 text-sm transition-colors">
                    View All
                </a>
            </div>
        </div>
        <div class="p-6">
            @if($recentProperties->count() > 0)
                <div class="space-y-4">
                    @foreach($recentProperties as $property)
                    <div class="flex items-center justify-between p-4 bg-gray-900 rounded-lg border border-gray-600 hover:border-gray-500 transition-all duration-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center">
                                <i class="fas fa-building text-white"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-white">{{ Str::limit($property->business_name, 25) }}</h4>
                                <p class="text-sm text-gray-400">{{ $property->city }}, {{ $property->country }}</p>
                                <p class="text-xs text-gray-500">{{ $property->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs rounded-full font-medium
                            {{ $property->status === 'Approved' ? 'bg-green-900 text-green-400 border border-green-700' : 'bg-yellow-900 text-yellow-400 border border-yellow-700' }}">
                            {{ $property->status }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-building text-4xl text-gray-600 mb-4"></i>
                    <p class="text-gray-400">No recent properties</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Reviews -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-700 bg-gradient-to-r from-gray-800 to-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-star text-yellow-400 mr-2"></i>
                    Recent Reviews
                </h2>
                <a href="{{ route('admin.reviews.index') }}"
                   class="text-yellow-400 hover:text-yellow-300 text-sm transition-colors">
                    View All
                </a>
            </div>
        </div>
        <div class="p-6">
            @if($recentReviews->count() > 0)
                <div class="space-y-4">
                    @foreach($recentReviews as $review)
                    <div class="p-4 bg-gray-900 rounded-lg border border-gray-600 hover:border-gray-500 transition-all duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-yellow-500 to-yellow-700 flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <span class="ml-2 text-sm font-medium text-white">{{ $review->user->name ?? 'Anonymous' }}</span>
                            </div>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-{{ $i <= $review->rating ? 'yellow' : 'gray' }}-400 text-xs"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="text-sm text-gray-300 leading-relaxed">{{ Str::limit($review->review, 80) }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                            <span class="px-2 py-1 text-xs rounded-full font-medium
                                {{ $review->status === 'Approved' ? 'bg-green-900 text-green-400' : 'bg-yellow-900 text-yellow-400' }}">
                                {{ $review->status }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-star text-4xl text-gray-600 mb-4"></i>
                    <p class="text-gray-400">No recent reviews</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bottom Section with Recent Users and Payments -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <!-- Recent Users -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-700 bg-gradient-to-r from-gray-800 to-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-users text-green-400 mr-2"></i>
                    Recent Users
                </h2>
                <a href="{{ route('admin.users.index') }}"
                   class="text-green-400 hover:text-green-300 text-sm transition-colors">
                    View All
                </a>
            </div>
        </div>
        <div class="p-6">
            @if($recentUsers->count() > 0)
                <div class="space-y-4">
                    @foreach($recentUsers as $user)
                    <div class="flex items-center justify-between p-4 bg-gray-900 rounded-lg border border-gray-600 hover:border-gray-500 transition-all duration-200">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-white">{{ $user->name }}</h4>
                                <p class="text-sm text-gray-400">{{ $user->email }}</p>
                                <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs rounded-full font-medium
                            {{ $user->email_verified_at ? 'bg-green-900 text-green-400' : 'bg-yellow-900 text-yellow-400' }}">
                            {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-users text-4xl text-gray-600 mb-4"></i>
                    <p class="text-gray-400">No recent users</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-700 bg-gradient-to-r from-gray-800 to-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-credit-card text-purple-400 mr-2"></i>
                    Recent Payments
                </h2>
                <a href="{{ route('admin.payments.index') }}"
                   class="text-purple-400 hover:text-purple-300 text-sm transition-colors">
                    View All
                </a>
            </div>
        </div>
        <div class="p-6">
            @if($recentPayments->count() > 0)
                <div class="space-y-4">
                    @foreach($recentPayments as $payment)
                    <div class="flex items-center justify-between p-4 bg-gray-900 rounded-lg border border-gray-600 hover:border-gray-500 transition-all duration-200">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center">
                                <i class="fas fa-dollar-sign text-white"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-white">${{ number_format($payment->amount, 2) }}</h4>
                                <p class="text-sm text-gray-400">{{ $payment->user->name ?? 'Guest' }}</p>
                                <p class="text-xs text-gray-500">{{ $payment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs rounded-full font-medium
                            @if(in_array(strtolower($payment->status), ['confirmed', 'success', 'completed'])) bg-green-900 text-green-400
                            @elseif(in_array(strtolower($payment->status), ['pending', 'processing'])) bg-yellow-900 text-yellow-400
                            @else bg-red-900 text-red-400
                            @endif">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-credit-card text-4xl text-gray-600 mb-4"></i>
                    <p class="text-gray-400">No recent payments</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-refresh dashboard every 5 minutes
    setTimeout(function() {
        window.location.reload();
    }, 300000);

    // Add hover effects and animations
    document.addEventListener('DOMContentLoaded', function() {
        // Animate numbers on page load
        const counters = document.querySelectorAll('h3');
        counters.forEach(counter => {
            const text = counter.textContent.replace(/[,$]/g, '');
            if (!isNaN(text) && text !== '') {
                const target = parseInt(text);
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    counter.textContent = Math.floor(current).toLocaleString();
                }, 50);
            }
        });
    });
</script>
@endpush
@endsection
