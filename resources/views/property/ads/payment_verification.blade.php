@extends('layouts.business')

@section('active-ads-manager', 'menu-item-active')
@section('title', 'Payment Processing')

@section('page-title')
    Payment Processing
@endsection

@section('content')
<div class="max-w-2xl mx-auto space-y-8">

    @if(session('success'))
        <!-- Payment Success -->
        <div class="bg-green-900/20 border border-green-700/50 rounded-xl p-6">
            <div class="flex items-start space-x-3">
                <i class="fas fa-check-circle text-green-400 text-2xl"></i>
                <div>
                    <h3 class="text-green-300 font-medium text-lg mb-2">Payment Completed Successfully!</h3>
                    <p class="text-gray-300">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @elseif(session('error'))
        <!-- Payment Error -->
        <div class="bg-red-900/20 border border-red-700/50 rounded-xl p-6">
            <div class="flex items-start space-x-3">
                <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                <div>
                    <h3 class="text-red-300 font-medium text-lg mb-2">Payment Issue</h3>
                    <p class="text-gray-300">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @else
        <!-- Manual Payment Verification -->
        <div class="bg-blue-900/20 border border-blue-700/50 rounded-xl p-6">
            <div class="flex items-start space-x-3">
                <i class="fas fa-credit-card text-blue-400 text-2xl"></i>
                <div>
                    <h3 class="text-blue-300 font-medium text-lg mb-2">Payment Verification</h3>
                    <p class="text-gray-300 mb-4">If you've completed your payment, click the button below to verify and update your ad status.</p>

                    <div class="bg-gray-800/50 p-4 rounded-lg mb-4">
                        <div class="space-y-2 text-sm">
                            <div><strong>Ad ID:</strong> {{ $ad->id }}</div>
                            <div><strong>Payment ID:</strong> {{ $ad->payment_intent_id ?: 'Not available' }}</div>
                            <div><strong>Amount:</strong> USD {{ number_format($ad->total_amount, 2) }}</div>
                            <div><strong>Current Status:</strong>
                                <span class="px-2 py-1 rounded text-xs
                                    @if($ad->payment_status === 'paid') bg-green-900/50 text-green-300
                                    @else bg-yellow-900/50 text-yellow-300 @endif">
                                    {{ ucfirst($ad->payment_status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <button onclick="verifyPayment()" id="verifyBtn"
                                class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-sync mr-2"></i>
                            <span id="verifyText">Verify Payment Status</span>
                        </button>

                        @if($ad->payment_intent_id)
                        <form action="{{ route('property.ads.payment.success', $ad) }}" method="GET" class="inline">
                            <input type="hidden" name="transaction_id" value="{{ $ad->payment_intent_id }}">
                            <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-check mr-2"></i>
                                Complete Payment Verification (Auto)
                            </button>
                        </form>
                        @endif

                        <!-- Manual Transaction ID Verification -->
                        <div class="border-t border-gray-700 pt-4 mt-4">
                            <p class="text-sm text-gray-400 mb-3">If the automatic verification doesn't work, enter your transaction ID manually:</p>
                            <form action="{{ route('property.ads.payment.verify.manual', $ad) }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label for="transaction_id" class="block text-sm text-gray-300 mb-2">Transaction ID</label>
                                    <input type="text"
                                           id="transaction_id"
                                           name="transaction_id"
                                           placeholder="e.g., 686aa364a73596000951a527"
                                           class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <p class="text-xs text-gray-500 mt-1">Find this in your payment confirmation or receipt email</p>
                                </div>
                                <button type="submit" class="w-full px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                    <i class="fas fa-search mr-2"></i>
                                    Verify with Transaction ID
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Payment Instructions -->
    <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
            <i class="fas fa-info-circle text-blue-400 mr-2"></i>
            How to Complete Payment
        </h3>

        <div class="space-y-4 text-gray-300">
            <div class="flex items-start space-x-3">
                <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full mt-1">1</span>
                <div>
                    <p><strong>Make Payment:</strong> Use the Genie Business payment link provided when you created the ad.</p>
                </div>
            </div>

            <div class="flex items-start space-x-3">
                <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full mt-1">2</span>
                <div>
                    <p><strong>Return Here:</strong> After completing payment, return to this page manually.</p>
                </div>
            </div>

            <div class="flex items-start space-x-3">
                <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full mt-1">3</span>
                <div>
                    <p><strong>Verify Payment:</strong> Click "Complete Payment Verification" to update your ad status.</p>
                </div>
            </div>
        </div>

        <div class="mt-6 p-4 bg-yellow-900/20 border border-yellow-700/50 rounded-lg">
            @if(session('payment_url'))
            <p class="text-yellow-300 text-sm mb-3">
                <i class="fas fa-link mr-2"></i>
                <strong>Your Payment URL:</strong>
            </p>
            <div class="bg-gray-800 p-3 rounded-lg">
                <a href="{{ session('payment_url') }}" target="_blank"
                   class="text-blue-400 hover:text-blue-300 break-all text-sm">
                    {{ session('payment_url') }}
                </a>
            </div>
            <p class="text-gray-400 text-xs mt-2">Click the link above to complete your payment in a new window.</p>
            @elseif($ad->payment_intent_id)
            <p class="text-yellow-300 text-sm">
                <i class="fas fa-lightbulb mr-2"></i>
                <strong>Payment URL:</strong>
            </p>
            <div class="bg-gray-800 p-3 rounded-lg mt-2">
                <a href="https://transaction.uat.geniebiz.lk/{{ $ad->payment_intent_id }}" target="_blank"
                   class="text-blue-400 hover:text-blue-300 break-all text-sm">
                    https://transaction.uat.geniebiz.lk/{{ $ad->payment_intent_id }}
                </a>
            </div>
            @else
            <p class="text-yellow-300 text-sm">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>No payment URL available.</strong> Please contact support.
            </p>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-center space-x-4">
        <a href="{{ route('property.ads.index') }}"
           class="px-6 py-3 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Ads Manager
        </a>

        @if($ad->payment_intent_id)
        <a href="https://transaction.uat.geniebiz.lk/{{ $ad->payment_intent_id }}" target="_blank"
           class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
            <i class="fas fa-external-link-alt mr-2"></i>
            Open Payment Page
        </a>
        @endif
    </div>
</div>

<script>
function verifyPayment() {
    const btn = document.getElementById('verifyBtn');
    const text = document.getElementById('verifyText');

    btn.disabled = true;
    text.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Checking...';

    // Reload the page to trigger verification
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}
</script>
@endsection
