@extends('layouts.admin')

@section('active-plans', 'menu-item-active')
@section('page-title', 'Plans Management')
@section('page-subtitle', 'Manage subscription plans, pricing, and features for your platform.')

@section('content')
<div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-white">Subscription Plans</h2>
                <p class="text-gray-400 text-sm">Total: {{ $plans->total() }} plans</p>
            </div>

            <!-- Search Form and Add Button -->
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('admin.plans.index') }}" class="flex items-center gap-2">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search plans..."
                            class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg pl-10 pr-4 py-2 w-64 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                    @if($search)
                        <a href="{{ route('admin.plans.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>

                <!-- Add New Plan Button -->
                <a href="{{ route('admin.plans.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Plan
                </a>
            </div>
        </div>
    </div>

    <!-- Plans Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-900">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Plan Details</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Limits</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Features</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Usage</th>
                    <th class="px-6 py-4 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                @forelse($plans as $plan)
                <tr class="hover:bg-gray-750 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-green-500 to-blue-600 flex items-center justify-center">
                                    <i class="fas fa-medal text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-white">{{ $plan->name }}</div>
                                <div class="text-sm text-gray-400">ID: {{ $plan->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-lg font-bold text-green-400">${{ number_format($plan->price, 2) }}</div>
                        <div class="text-sm text-gray-400">per month</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="space-y-1 text-sm">
                            <div class="text-gray-300">Products: <span class="text-white font-semibold">{{ $plan->product_limit }}</span></div>
                            <div class="text-gray-300">Widgets: <span class="text-white font-semibold">{{ $plan->widget_limit }}</span></div>
                            <div class="text-gray-300">HTML: <span class="text-white font-semibold">{{ $plan->html_integration_limit }}</span></div>
                            <div class="text-gray-300">Reviews: <span class="text-white font-semibold">{{ $plan->review_invitation_limit }}</span></div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($plan->referral_code)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                <i class="fas fa-check-circle mr-1"></i>
                                Referral Codes
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-900 text-gray-400">
                                <i class="fas fa-times-circle mr-1"></i>
                                No Referrals
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="bg-blue-900 text-blue-300 px-2 py-1 rounded-full text-xs font-medium">
                                {{ $plan->properties_count }} properties
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <!-- View Button -->
                            <a
                                href="{{ route('admin.plans.show', $plan->id) }}"
                                class="bg-gray-600 hover:bg-gray-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                title="View Plan Details"
                            >
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- Edit Button -->
                            <a
                                href="{{ route('admin.plans.edit', $plan->id) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                title="Edit Plan"
                            >
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-medal text-4xl text-gray-600 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-400 mb-2">No plans found</h3>
                            <p class="text-gray-500">
                                @if($search)
                                    No plans match your search criteria.
                                @else
                                    Get started by creating your first subscription plan.
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
    @if($plans->hasPages())
    <div class="bg-gray-900 px-6 py-4 border-t border-gray-700">
        {{ $plans->links('custom.pagination') }}
    </div>
    @endif
</div>
@endsection
