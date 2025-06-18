{{-- filepath: c:\xampp\htdocs\pilot\resources\views\payments\checkout.blade.php --}}

@extends('layouts.business')

@section('title', 'Checkout - Business Dashboard')
@section('page-title', 'Checkout')
@section('page-subtitle', 'Complete your payment')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Payment Error</h3>
                            <ul class="mt-2 text-sm text-red-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Plan Information --}}
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                <h3 class="text-lg font-medium text-blue-800">{{ $plan->name }} Plan</h3>
                <p class="text-blue-600">Price: ${{ number_format($plan->price, 2) }}/month</p>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="space-y-1">
                        <li>• Product Limit: {{ $plan->product_limit }}</li>
                        <li>• Widget Limit: {{ $plan->widget_limit }}</li>
                        <li>• Review Invitations: {{ $plan->review_invitation_limit }}</li>
                    </ul>
                </div>
            </div>

            <form action="{{ route('payment.checkout') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Hidden fields --}}
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                <input type="hidden" name="amount" value="{{ $amount }}">

                {{-- Customer Information --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name *
                        </label>
                        <input type="text"
                               id="customer_name"
                               name="customer_name"
                               value="{{ old('customer_name', $customerData['name'] ?? '') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address *
                        </label>
                        <input type="email"
                               id="customer_email"
                               name="customer_email"
                               value="{{ old('customer_email', $customerData['email'] ?? '') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                {{-- Payment Summary --}}
                <div class="bg-gray-50 rounded-md p-4">
                    <h4 class="text-lg font-medium text-gray-900 mb-3">Payment Summary</h4>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ $plan->name }} Plan (Monthly)</span>
                        <span class="text-xl font-bold text-gray-900">${{ number_format($amount, 2) }}</span>
                    </div>
                    <div class="border-t border-gray-200 mt-3 pt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-medium text-gray-900">Total</span>
                            <span class="text-xl font-bold text-blue-600">${{ number_format($amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-between items-center pt-6">
                    <a href="{{ route('plans.index') }}"
                       class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Back to Plans
                    </a>

                    <button type="submit"
                            class="px-8 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-credit-card mr-2"></i>
                        Pay ${{ number_format($amount, 2) }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
