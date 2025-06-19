@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Dashboard Overview</h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Stats Cards -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Total Users</p>
                <h3 class="text-2xl font-bold text-gray-700">{{ $totalUsers }}</h3>
                <p class="text-green-500 text-sm mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>{{ $newUsersPercentage }}% increase</span>
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-building text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Properties</p>
                <h3 class="text-2xl font-bold text-gray-700">{{ $totalProperties }}</h3>
                <p class="text-sm mt-1">
                    <span class="text-yellow-500">{{ $pendingProperties }}</span> pending approval
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-star text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Reviews</p>
                <h3 class="text-2xl font-bold text-gray-700">{{ $totalReviews }}</h3>
                <p class="text-sm mt-1">
                    <span class="text-blue-500">{{ $avgRating }}/5</span> avg rating
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Revenue</p>
                <h3 class="text-2xl font-bold text-gray-700">${{ number_format($totalRevenue) }}</h3>
                <p class="text-green-500 text-sm mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>{{ $revenueGrowth }}% growth</span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Recent Properties</h2>
        </div>
        <div class="p-6">
            <div class="divide-y divide-gray-200">
                @foreach($recentProperties as $property)
                <div class="py-3 flex items-center justify-between">
                    <div class="flex items-center">
                        <img src="{{ Storage::url($property->image) }}"
                             alt="{{ $property->name }}"
                             class="h-10 w-10 rounded-lg object-cover">
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">{{ $property->business_name }}</h4>
                            <p class="text-sm text-gray-500">{{ $property->city }}, {{ $property->country }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full {{ $property->status === 'Approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $property->status }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Recent Reviews</h2>
        </div>
        <div class="p-6">
            <div class="divide-y divide-gray-200">
                @foreach($recentReviews as $review)
                <div class="py-3">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center">
                            <img src="{{ $review->user->profile_picture ? Storage::url($review->user->profile_picture) : asset('images/default-avatar.png') }}"
                                 class="h-8 w-8 rounded-full object-cover"
                                 alt="{{ $review->user->name }}">
                            <span class="ml-2 text-sm font-medium text-gray-900">{{ $review->user->name }}</span>
                        </div>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-{{ $i <= $review->rate ? 'yellow' : 'gray' }}-400 text-xs"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">{{ Str::limit($review->comment, 100) }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
