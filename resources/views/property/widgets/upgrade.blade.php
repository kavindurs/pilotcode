<!-- filepath: c:\xampp\htdocs\pilot\resources\views\property\widgets\upgrade.blade.php -->
@extends('layouts.business')

@section('active-widgets', 'bg-blue-700')

@section('title', 'Upgrade Plan')
@section('page-title', 'Upgrade Your Plan')
@section('page-subtitle', 'Get access to more widgets and features')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <!-- Current Plan Info -->
    <div class="mb-8 p-4 border-2 border-blue-200 rounded-lg bg-blue-50">
        <h3 class="text-lg font-semibold text-blue-800 mb-2">Your Current Plan: {{ $planInfo['planType'] }}</h3>
        <div class="flex flex-col space-y-2">
            <p><span class="font-medium">Widget Limit:</span> {{ $planInfo['widgetLimit'] }}</p>
            <p><span class="font-medium">Widgets Used:</span> {{ $planInfo['widgetCount'] }} / {{ $planInfo['widgetLimit'] }}</p>
            <p><span class="font-medium">Widgets Remaining:</span> {{ $planInfo['remainingWidgets'] }}</p>
        </div>
    </div>

    <!-- Plans Comparison -->
    <h3 class="text-xl font-semibold text-gray-800 mb-6">Available Plans</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Free Plan -->
        <div class="border rounded-lg overflow-hidden {{ $planInfo['planType'] == 'Free' ? 'ring-2 ring-blue-500' : '' }}">
            <div class="bg-gray-100 p-4">
                <h4 class="text-lg font-semibold">Free</h4>
                <p class="text-2xl font-bold mt-2">$0<span class="text-sm font-normal text-gray-600">/month</span></p>
            </div>
            <div class="p-4">
                <ul class="space-y-2">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Basic Profile</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span><strong>0 Widgets</strong></span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="text-gray-500">No Custom Domain</span>
                    </li>
                </ul>
            </div>
            <div class="p-4 bg-gray-50 flex justify-center">
                @if($planInfo['planType'] == 'Free')
                    <span class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md cursor-default">Current Plan</span>
                @else
                    <a href="{{ route('property.subscription.change', ['plan' => 'free']) }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Downgrade</a>
                @endif
            </div>
        </div>

        <!-- Basic Plan -->
        <div class="border rounded-lg overflow-hidden {{ $planInfo['planType'] == 'Basic' ? 'ring-2 ring-blue-500' : '' }}">
            <div class="bg-blue-100 p-4">
                <h4 class="text-lg font-semibold">Basic</h4>
                <p class="text-2xl font-bold mt-2">$9.99<span class="text-sm font-normal text-gray-600">/month</span></p>
            </div>
            <div class="p-4">
                <ul class="space-y-2">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Enhanced Profile</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span><strong>2 Widgets</strong></span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="text-gray-500">No Custom Domain</span>
                    </li>
                </ul>
            </div>
            <div class="p-4 bg-gray-50 flex justify-center">
                @if($planInfo['planType'] == 'Basic')
                    <span class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md cursor-default">Current Plan</span>
                @else
                    <a href="{{ route('property.subscription.change', ['plan' => 'basic']) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">{{ $planInfo['planType'] == 'Free' ? 'Upgrade' : 'Change Plan' }}</a>
                @endif
            </div>
        </div>

        <!-- Pro Plan -->
        <div class="border rounded-lg overflow-hidden {{ $planInfo['planType'] == 'Pro' ? 'ring-2 ring-blue-500' : '' }}">
            <div class="bg-purple-100 p-4">
                <h4 class="text-lg font-semibold">Pro</h4>
                <p class="text-2xl font-bold mt-2">$19.99<span class="text-sm font-normal text-gray-600">/month</span></p>
            </div>
            <div class="p-4">
                <ul class="space-y-2">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Professional Profile</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span><strong>5 Widgets</strong></span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Custom Domain</span>
                    </li>
                </ul>
            </div>
            <div class="p-4 bg-gray-50 flex justify-center">
                @if($planInfo['planType'] == 'Pro')
                    <span class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md cursor-default">Current Plan</span>
                @else
                    <a href="{{ route('property.subscription.change', ['plan' => 'pro']) }}" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">{{ in_array($planInfo['planType'], ['Free', 'Basic']) ? 'Upgrade' : 'Change Plan' }}</a>
                @endif
            </div>
        </div>

        <!-- Premium Plan -->
        <div class="border rounded-lg overflow-hidden {{ $planInfo['planType'] == 'Premium' ? 'ring-2 ring-blue-500' : '' }}">
            <div class="bg-green-100 p-4">
                <h4 class="text-lg font-semibold">Premium</h4>
                <p class="text-2xl font-bold mt-2">$29.99<span class="text-sm font-normal text-gray-600">/month</span></p>
            </div>
            <div class="p-4">
                <ul class="space-y-2">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Premium Profile</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span><strong>8 Widgets</strong></span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Custom Domain + SEO Tools</span>
                    </li>
                </ul>
            </div>
            <div class="p-4 bg-gray-50 flex justify-center">
                @if($planInfo['planType'] == 'Premium')
                    <span class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md cursor-default">Current Plan</span>
                @else
                    <a href="{{ route('property.subscription.change', ['plan' => 'premium']) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Upgrade</a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="bg-yellow-50 rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-yellow-800 mb-3">Need help choosing a plan?</h3>
    <p class="text-yellow-700 mb-3">Contact our support team for personalized assistance in selecting the best plan for your business needs.</p>
    <a href="{{ route('property.support') }}" class="inline-block px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">Contact Support</a>
</div>
@endsection
