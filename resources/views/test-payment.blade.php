@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-center mb-8">Payment Gateway Test</h1>

            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <h2 class="text-xl font-semibold text-blue-800 mb-2">Genie Business Integration Status</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium">Environment:</span>
                        <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ config('genie_business.environment') }}</span>
                    </div>
                    <div>
                        <span class="font-medium">API URL:</span>
                        <span class="font-mono bg-gray-100 px-2 py-1 rounded text-xs">{{ config('genie_business.api_url') }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Currency:</span>
                        <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ config('genie_business.currency') }}</span>
                    </div>
                    <div>
                        <span class="font-medium">App ID:</span>
                        <span class="font-mono bg-gray-100 px-2 py-1 rounded text-xs">{{ Str::mask(config('genie_business.app_id'), '*', 8) }}</span>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                @foreach($plans as $plan)
                <div class="border rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="text-center mb-4">
                        <h3 class="text-2xl font-bold text-gray-800">{{ $plan->name }} Plan</h3>
                        <div class="text-3xl font-bold text-green-600 my-2">
                            ${{ number_format($plan->price, 2) }}
                            <span class="text-lg font-normal text-gray-500">/month</span>
                        </div>
                        <div class="text-sm text-gray-600">
                            ≈ {{ number_format($plan->price * 300, 0) }} LKR
                        </div>
                    </div>

                    <ul class="space-y-2 mb-6 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            {{ $plan->product_limit }} Products
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            {{ $plan->widget_limit }} Widgets
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            {{ $plan->review_invitation_limit }} Review Invitations
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            {{ number_format($plan->html_integration_limit) }} HTML Integrations
                        </li>
                    </ul>

                    <form action="{{ route('payment.checkout.show') }}" method="GET">
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <input type="hidden" name="amount" value="{{ $plan->price }}">
                        <button type="submit"
                                class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            <i class="fas fa-credit-card mr-2"></i>
                            Test {{ $plan->name }} Payment
                        </button>
                    </form>
                </div>
                @endforeach
            </div>

            <div class="mt-8 p-4 bg-yellow-50 rounded-lg">
                <h3 class="font-semibold text-yellow-800 mb-2">Test Instructions:</h3>
                <ol class="text-sm text-yellow-700 space-y-1">
                    <li>1. Click "Test Payment" for any plan above</li>
                    <li>2. Fill in your details on the checkout form</li>
                    <li>3. Submit to create a payment transaction with Genie Business</li>
                    <li>4. Complete the payment process</li>
                    <li>5. Verify the payment status and plan activation</li>
                </ol>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('plans.index') }}"
                   class="inline-block bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Plans
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
@endpush
