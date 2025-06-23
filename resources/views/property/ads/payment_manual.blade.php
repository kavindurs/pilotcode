@extends('layouts.business')

@section('active-ads-manager', 'menu-item-active')
@section('title', 'Payment Required')

@section('page-title')
    Payment Required
@endsection

@section('page-subtitle', 'Complete your payment to submit the promotion request.')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <!-- Payment Details -->
    <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
            <i class="fas fa-credit-card text-blue-400 mr-2"></i>
            Payment Details
        </h3>

        <div class="space-y-4">
            <div class="flex justify-between items-center py-2 border-b border-gray-700">
                <span class="text-gray-300">Promotion Period:</span>
                <span class="text-white">{{ $ad->start_date->format('M j, Y') }} - {{ $ad->end_date->format('M j, Y') }}</span>
            </div>

            <div class="flex justify-between items-center py-2 border-b border-gray-700">
                <span class="text-gray-300">Total Days:</span>
                <span class="text-white">{{ $ad->total_days }} days</span>
            </div>            <div class="flex justify-between items-center py-2 border-b border-gray-700">
                <span class="text-gray-300">Rate:</span>
                <span class="text-white">$1.00 USD per day</span>
            </div>

            <div class="flex justify-between items-center py-3 bg-green-900/20 px-4 rounded-lg">
                <span class="text-green-300 font-medium text-lg">Total Amount:</span>
                <span class="text-green-300 font-bold text-2xl">${{ number_format($ad->total_amount, 2) }} USD</span>
            </div>
        </div>
    </div>

    <!-- Payment Instructions -->
    <div class="bg-yellow-900/20 border border-yellow-700/50 rounded-xl p-6">
        <div class="flex items-start space-x-3">
            <i class="fas fa-info-circle text-yellow-400 mt-1"></i>
            <div>
                <h4 class="text-yellow-300 font-medium mb-2">Payment Instructions</h4>
                <div class="text-gray-300 text-sm space-y-2">
                    <p>To complete your payment, please use the following details:</p>
                    <div class="bg-gray-800/50 p-4 rounded-lg mt-4">
                        <div class="space-y-2 text-sm">
                            <div><strong>Payment ID:</strong> {{ $ad->payment_intent_id ?: 'Not available' }}</div>
                            <div><strong>Amount:</strong> ${{ number_format($ad->total_amount, 2) }} USD</div>
                            <div><strong>Property:</strong> {{ $ad->property->business_name }}</div>
                        </div>
                    </div>
                    <p class="mt-4">Contact our support team if you need assistance with the payment process.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-center space-x-4">
        <a href="{{ route('property.ads.index') }}"
           class="px-6 py-3 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Ads Manager
        </a>

        <button onclick="checkPaymentStatus()"
                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-sync mr-2"></i>
            Check Payment Status
        </button>
    </div>
</div>

<script>
function checkPaymentStatus() {
    // Refresh the page to check if payment has been completed
    window.location.reload();
}
</script>
@endsection
