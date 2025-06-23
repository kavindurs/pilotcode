@extends('layouts.admin')

@section('title', 'System Settings')
@section('active-settings', 'bg-gray-800 text-white')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-white">System Settings</h1>
            <p class="text-gray-400">Configure system-wide settings and parameters</p>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-900/50 border border-green-700 text-green-200 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Ad Settings Card -->
    <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-white">Ad Promotion Settings</h2>
                    <p class="text-gray-400">Configure pricing for ad promotion requests</p>
                </div>
                <div class="w-12 h-12 bg-green-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-400"></i>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.settings.update-ad-cost') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="ad_daily_cost" class="block text-sm font-medium text-gray-300 mb-2">
                            Daily Cost (USD)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400">$</span>
                            </div>
                            <input type="number"
                                   name="ad_daily_cost"
                                   id="ad_daily_cost"
                                   step="0.01"
                                   min="0.01"
                                   max="100"
                                   value="{{ old('ad_daily_cost', $settings['ad_daily_cost']) }}"
                                   class="w-full pl-8 pr-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                   placeholder="1.00">
                        </div>
                        @error('ad_daily_cost')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">
                            Amount charged per day for ad promotion requests
                        </p>
                    </div>

                    <div class="flex flex-col justify-center">
                        <div class="bg-gray-800 border border-gray-700 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-300 mb-2">Current Setting</h3>
                            <p class="text-2xl font-bold text-white">${{ number_format($settings['ad_daily_cost'], 2) }}</p>
                            <p class="text-gray-400 text-sm">per day</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-700">
                    <div class="text-sm text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        Changes will affect new ad requests immediately
                    </div>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Update Setting
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Information Card -->
    <div class="bg-blue-900/20 border border-blue-700 rounded-xl p-6">
        <div class="flex items-start">
            <div class="w-12 h-12 bg-blue-900 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-lightbulb text-blue-400"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white mb-2">How Ad Pricing Works</h3>
                <ul class="text-gray-300 space-y-1">
                    <li>• Business owners pay this amount per day they want their property promoted</li>
                    <li>• Payment is required before ad submission</li>
                    <li>• Admins must approve paid ads before they appear on the homepage</li>
                    <li>• Refunds are processed for rejected ads</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
