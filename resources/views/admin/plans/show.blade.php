@extends('layouts.admin')

@section('active-plans', 'menu-item-active')
@section('page-title', 'Plan Details')
@section('page-subtitle', 'View subscription plan details and manage associated properties.')

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
                    <a href="{{ route('admin.plans.index') }}" class="text-gray-400 hover:text-white transition-colors">
                        Plans
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">{{ $plan->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl mb-6">
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-green-500 to-blue-600 flex items-center justify-center mr-4">
                        <i class="fas fa-medal text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $plan->name }}</h2>
                        <p class="text-gray-400">Subscription Plan Details</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.plans.edit', $plan->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Plan
                    </a>
                    <a href="{{ route('admin.plans.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Plans
                    </a>
                </div>
            </div>
        </div>

        <!-- Plan Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Plan Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Pricing Information -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-dollar-sign mr-2"></i>
                            Pricing Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="text-center p-4 bg-gray-700 rounded-lg">
                                <p class="text-gray-400 text-sm mb-2">Monthly Price</p>
                                <p class="text-3xl font-bold text-green-400">${{ number_format($plan->price, 2) }}</p>
                                <p class="text-gray-500 text-sm">per month</p>
                            </div>
                            <div class="text-center p-4 bg-gray-700 rounded-lg">
                                <p class="text-gray-400 text-sm mb-2">Annual Price</p>
                                <p class="text-3xl font-bold text-blue-400">${{ number_format($plan->price * 12 * 0.85, 2) }}</p>
                                <p class="text-gray-500 text-sm">per year (15% off)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Plan Limits -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-list mr-2"></i>
                            Plan Limits
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-box text-blue-400 mr-3"></i>
                                    <span class="text-gray-300">Products</span>
                                </div>
                                <span class="text-white font-semibold">{{ $plan->product_limit }}</span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-puzzle-piece text-purple-400 mr-3"></i>
                                    <span class="text-gray-300">Widgets</span>
                                </div>
                                <span class="text-white font-semibold">{{ $plan->widget_limit }}</span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-code text-green-400 mr-3"></i>
                                    <span class="text-gray-300">HTML Integrations</span>
                                </div>
                                <span class="text-white font-semibold">{{ $plan->html_integration_limit }}</span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-400 mr-3"></i>
                                    <span class="text-gray-300">Review Invitations</span>
                                </div>
                                <span class="text-white font-semibold">{{ $plan->review_invitation_limit }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Stats & Features -->
                <div class="space-y-6">
                    <!-- Plan Stats -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Plan Statistics
                        </h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Total Properties</span>
                                <span class="text-white font-semibold">{{ $plan->properties->count() }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Active Properties</span>
                                <span class="text-green-400 font-semibold">{{ $plan->properties->where('status', 'Approved')->count() }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Created</span>
                                <span class="text-white">{{ $plan->created_at->format('M d, Y') }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Last Updated</span>
                                <span class="text-white">{{ $plan->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Plan Features -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-star mr-2"></i>
                            Features
                        </h3>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Referral Codes</span>
                                @if($plan->referral_code)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                        <i class="fas fa-check mr-1"></i>
                                        Enabled
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-300">
                                        <i class="fas fa-times mr-1"></i>
                                        Disabled
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-bolt mr-2"></i>
                            Quick Actions
                        </h3>

                        <div class="space-y-3">
                            @if($plan->properties->count() == 0)
                                <form method="POST" action="{{ route('admin.plans.destroy', $plan->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors text-sm"
                                        onclick="return confirm('Are you sure you want to delete this plan? This action cannot be undone.')"
                                    >
                                        <i class="fas fa-trash mr-2"></i>
                                        Delete Plan
                                    </button>
                                </form>
                            @else
                                <div class="w-full bg-gray-600 text-gray-400 px-4 py-2 rounded-lg text-sm text-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Cannot delete (has properties)
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Associated Properties -->
    @if($plan->properties->count() > 0)
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-building mr-2"></i>
                    Associated Properties ({{ $plan->properties->count() }})
                </h2>
                <a href="{{ route('admin.properties.index', ['plan' => $plan->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors text-sm">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    View All Properties
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-900">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @foreach($plan->properties->take(10) as $property)
                    <tr class="hover:bg-gray-750 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-md bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                        <i class="fas fa-building text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-white">{{ $property->business_name }}</div>
                                    <div class="text-sm text-gray-400">{{ $property->city }}, {{ $property->country }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($property->status === 'Approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Approved
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900 text-yellow-300">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $property->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-300 capitalize">{{ $property->property_type }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-400">
                            {{ $property->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a
                                    href="{{ route('admin.properties.show', $property->id) }}"
                                    class="bg-gray-600 hover:bg-gray-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                    title="View Property"
                                >
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-12 text-center">
        <i class="fas fa-building text-4xl text-gray-600 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-400 mb-2">No Properties</h3>
        <p class="text-gray-500 mb-6">This plan doesn't have any associated properties yet.</p>
    </div>
    @endif
</div>
@endsection
