@extends('layouts.admin')

@section('active-plans', 'menu-item-active')
@section('page-title', 'Edit Plan')
@section('page-subtitle', 'Modify subscription plan details, pricing, and feature limits.')

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
                    <a href="{{ route('admin.plans.index') }}" class="text-gray-400 hover:text-white transition-colors">
                        Plans
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">Edit {{ $plan->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">Edit Subscription Plan</h2>
                    <p class="text-gray-400 text-sm">Update plan pricing, features, and limits</p>
                </div>
                <a href="{{ route('admin.plans.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Plans
                </a>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.plans.update', $plan->id) }}" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column - Basic Info -->
                <div class="space-y-6">
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Basic Information
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                    Plan Name *
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $plan->name) }}"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                >
                                @error('name')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-300 mb-2">
                                    Monthly Price ($) *
                                </label>
                                <input
                                    type="number"
                                    id="price"
                                    name="price"
                                    value="{{ old('price', $plan->price) }}"
                                    step="0.01"
                                    min="0"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror"
                                >
                                @error('price')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-star mr-2"></i>
                            Features
                        </h3>

                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                id="referral_code"
                                name="referral_code"
                                value="1"
                                {{ old('referral_code', $plan->referral_code) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500"
                            >
                            <label for="referral_code" class="ml-2 text-sm text-gray-300">
                                Enable Referral Codes
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Limits -->
                <div class="space-y-6">
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-list mr-2"></i>
                            Feature Limits
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label for="product_limit" class="block text-sm font-medium text-gray-300 mb-2">
                                    Product Limit *
                                </label>
                                <input
                                    type="number"
                                    id="product_limit"
                                    name="product_limit"
                                    value="{{ old('product_limit', $plan->product_limit) }}"
                                    min="0"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('product_limit') border-red-500 @enderror"
                                >
                                @error('product_limit')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="widget_limit" class="block text-sm font-medium text-gray-300 mb-2">
                                    Widget Limit *
                                </label>
                                <input
                                    type="number"
                                    id="widget_limit"
                                    name="widget_limit"
                                    value="{{ old('widget_limit', $plan->widget_limit) }}"
                                    min="0"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('widget_limit') border-red-500 @enderror"
                                >
                                @error('widget_limit')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="html_integration_limit" class="block text-sm font-medium text-gray-300 mb-2">
                                    HTML Integration Limit *
                                </label>
                                <input
                                    type="number"
                                    id="html_integration_limit"
                                    name="html_integration_limit"
                                    value="{{ old('html_integration_limit', $plan->html_integration_limit) }}"
                                    min="0"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('html_integration_limit') border-red-500 @enderror"
                                >
                                @error('html_integration_limit')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="review_invitation_limit" class="block text-sm font-medium text-gray-300 mb-2">
                                    Review Invitation Limit *
                                </label>
                                <input
                                    type="number"
                                    id="review_invitation_limit"
                                    name="review_invitation_limit"
                                    value="{{ old('review_invitation_limit', $plan->review_invitation_limit) }}"
                                    min="0"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('review_invitation_limit') border-red-500 @enderror"
                                >
                                @error('review_invitation_limit')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Plan Stats -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Current Usage
                        </h3>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Associated Properties</span>
                                <span class="text-white font-semibold">{{ $plan->properties()->count() }}</span>
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
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-700">
                <a
                    href="{{ route('admin.plans.index') }}"
                    class="px-6 py-2 text-gray-400 hover:text-white transition-colors"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-lg transition-colors font-medium"
                >
                    <i class="fas fa-save mr-2"></i>
                    Update Plan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
