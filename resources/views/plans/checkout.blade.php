@extends('layouts.business')

@section('title', 'Checkout - ' . $plan->name . ' Plan')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Complete Your Purchase</h1>
            <p class="text-gray-400">You're purchasing the {{ $plan->name }} plan</p>
        </div>

        <!-- Plan Summary Card -->
        <div class="bg-gray-800 rounded-lg p-6 mb-6 border border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-white">{{ $plan->name }} Plan</h2>
                    <p class="text-gray-400 text-sm">Monthly subscription</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-yellow-400">
                        ${{ number_format($usdAmount, 2) }} USD
                    </div>
                    <div class="text-sm text-gray-400">
                        (LKR {{ number_format((isset($lkrAmount) ? $lkrAmount : $amount) * 3, 2) }})

                    </div>
                </div>
            </div>

            <!-- Plan Features -->
            <div class="border-t border-gray-700 pt-4">
                <h3 class="text-sm font-medium text-gray-300 mb-3">Plan includes:</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-400">
                    @if($plan->product_limit)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $plan->product_limit }} Products
                        </div>
                    @endif
                    @if($plan->widget_limit)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $plan->widget_limit }} Widgets
                        </div>
                    @endif
                    @if($plan->html_integration_limit)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $plan->html_integration_limit }} HTML Integrations
                        </div>
                    @endif
                    @if($plan->review_invitation_limit)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $plan->review_invitation_limit }} Review Invitations
                        </div>
                    @endif
                    @if($plan->referral_code)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Referral Code System
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="bg-gray-800 rounded-lg p-6 mb-6 border border-gray-700">
            <h3 class="text-lg font-semibold text-white mb-4">Customer Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <label class="block text-gray-400 mb-1">Business Name</label>
                    <div class="text-white">{{ $property->business_name ?: 'N/A' }}</div>
                </div>
                <div>
                    <label class="block text-gray-400 mb-1">Email</label>
                    <div class="text-white">{{ $property->business_email ?: 'noemail@property' . $property->id . '.local' }}</div>
                </div>
                <div>
                    <label class="block text-gray-400 mb-1">Contact Person</label>
                    <div class="text-white">{{ $property->contact_person ?: 'N/A' }}</div>
                </div>
                <div>
                    <label class="block text-gray-400 mb-1">Phone</label>
                    <div class="text-white">{{ $property->contact_phone ?: 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <h3 class="text-lg font-semibold text-white mb-4">Payment Method</h3>

            <form action="{{ route('plans.payment.process') }}" method="POST" id="paymentForm">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                <input type="hidden" name="amount" value="{{ isset($lkrAmount) ? $lkrAmount : $amount }}">

                <!-- Payment Method Selection -->
                <div class="mb-6">
                    <label class="block text-gray-400 mb-3">Payment Method</label>
                    <div class="space-y-3">
                        <div class="flex items-center p-4 border border-yellow-400 rounded-lg bg-gray-700">
                            <input type="hidden" name="payment_method" value="genie_business">
                            <div class="ml-3">
                                <div class="text-white font-medium">Credit/Debit Card</div>
                                <div class="text-gray-400 text-sm">Secure payment via Genie Business Gateway</div>
                            </div>
                            <div class="ml-auto">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="bg-gray-700 rounded-lg p-4 mb-6">
                    <div class="flex justify-between items-center text-white">
                        <span>Total Amount:</span>
                        <span class="text-xl font-bold text-yellow-400">${{ number_format($usdAmount, 2) }} USD</span>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="mb-6">
                    <label class="flex items-start">
                        <input type="checkbox" required class="mt-1 text-yellow-400 focus:ring-yellow-400" name="agree_terms">
                        <span class="ml-2 text-sm text-gray-400">
                            I agree to the <a href="#" class="text-yellow-400 hover:underline">Terms of Service</a>
                            and <a href="#" class="text-yellow-400 hover:underline">Privacy Policy</a>
                        </span>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-4">
                    <button type="submit"
                            class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-black font-semibold py-3 px-6 rounded-lg transition-colors">
                        Complete Payment
                    </button>
                    <a href="{{ route('plans.index') }}"
                       class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Security Notice -->
        <div class="mt-6 text-center">
            <div class="flex items-center justify-center text-sm text-gray-400">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Your payment information is secure and encrypted
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;

    submitBtn.disabled = true;
    submitBtn.textContent = 'Processing...';

    // Re-enable button after 30 seconds in case of timeout
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }, 30000);
});
</script>
@endpush
@endsection
