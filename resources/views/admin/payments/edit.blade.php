@extends('layouts.admin')

@section('active-payments', 'menu-item-active')
@section('page-title', 'Edit Payment')
@section('page-subtitle', 'Update payment information and status')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <a href="{{ route('admin.payments.index') }}" class="text-gray-400 hover:text-white transition-colors">Payments</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">Edit Payment</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white mb-1">Edit Payment</h2>
                    <p class="text-gray-300 text-sm">Update payment information and status</p>
                </div>
                <div class="mt-3 sm:mt-0">
                    <a href="{{ route('admin.payments.show', $payment->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        View Details
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if ($errors->any())
                <div class="bg-red-900 border border-red-600 text-red-100 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span class="font-medium">Please correct the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer Information -->
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-gray-300 mb-2">
                            Customer Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text"
                               id="customer_name"
                               name="customer_name"
                               value="{{ old('customer_name', $payment->customer_name) }}"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('customer_name') border-red-500 @enderror"
                               placeholder="Enter customer name"
                               required>
                        @error('customer_name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="customer_email" class="block text-sm font-medium text-gray-300 mb-2">
                            Customer Email <span class="text-red-400">*</span>
                        </label>
                        <input type="email"
                               id="customer_email"
                               name="customer_email"
                               value="{{ old('customer_email', $payment->customer_email) }}"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('customer_email') border-red-500 @enderror"
                               placeholder="Enter customer email"
                               required>
                        @error('customer_email')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="business_email" class="block text-sm font-medium text-gray-300 mb-2">
                            Business Email <span class="text-red-400">*</span>
                        </label>
                        <input type="email"
                               id="business_email"
                               name="business_email"
                               value="{{ old('business_email', $payment->business_email) }}"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('business_email') border-red-500 @enderror"
                               placeholder="Enter business email"
                               required>
                        @error('business_email')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-300 mb-2">
                            Payment Status <span class="text-red-400">*</span>
                        </label>
                        <select id="status"
                                name="status"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('status') border-red-500 @enderror"
                                required>
                            @foreach($statuses as $statusOption)
                                <option value="{{ $statusOption }}" {{ old('status', $payment->status) == $statusOption ? 'selected' : '' }}>
                                    {{ ucfirst($statusOption) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-300 mb-2">
                            Amount <span class="text-red-400">*</span>
                        </label>
                        <input type="number"
                               id="amount"
                               name="amount"
                               value="{{ old('amount', $payment->amount) }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('amount') border-red-500 @enderror"
                               placeholder="Enter amount"
                               required>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-300 mb-2">
                            Currency <span class="text-red-400">*</span>
                        </label>
                        <select id="currency"
                                name="currency"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('currency') border-red-500 @enderror"
                                required>
                            <option value="USD" {{ old('currency', $payment->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="EUR" {{ old('currency', $payment->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="GBP" {{ old('currency', $payment->currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                            <option value="LKR" {{ old('currency', $payment->currency) == 'LKR' ? 'selected' : '' }}>LKR</option>
                        </select>
                        @error('currency')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-300 mb-2">
                            Payment Method
                        </label>
                        <input type="text"
                               id="payment_method"
                               name="payment_method"
                               value="{{ old('payment_method', $payment->payment_method) }}"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('payment_method') border-red-500 @enderror"
                               placeholder="e.g., Credit Card, PayPal, Bank Transfer">
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="transaction_id" class="block text-sm font-medium text-gray-300 mb-2">
                            Transaction ID
                        </label>
                        <input type="text"
                               id="transaction_id"
                               name="transaction_id"
                               value="{{ old('transaction_id', $payment->transaction_id) }}"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('transaction_id') border-red-500 @enderror"
                               placeholder="Enter transaction ID">
                        @error('transaction_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="plan_id" class="block text-sm font-medium text-gray-300 mb-2">
                            Plan
                        </label>
                        <select id="plan_id"
                                name="plan_id"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('plan_id') border-red-500 @enderror">
                            <option value="">No Plan</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ old('plan_id', $payment->plan_id) == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} ({{ $plan->currency }} {{ number_format($plan->price, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('plan_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Read-only Information -->
                <div class="border-t border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-white mb-4">Additional Information (Read-only)</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Order ID</label>
                            <div class="px-4 py-3 bg-gray-600 border border-gray-500 rounded-lg text-gray-300 font-mono">
                                {{ $payment->order_id ?? 'N/A' }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Created Date</label>
                            <div class="px-4 py-3 bg-gray-600 border border-gray-500 rounded-lg text-gray-300">
                                {{ $payment->created_at->format('M d, Y g:i A') }}
                            </div>
                        </div>

                        @if($payment->genie_transaction_id)
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Genie Transaction ID</label>
                            <div class="px-4 py-3 bg-gray-600 border border-gray-500 rounded-lg text-gray-300 font-mono">
                                {{ $payment->genie_transaction_id }}
                            </div>
                        </div>
                        @endif

                        @if($payment->completed_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Completed Date</label>
                            <div class="px-4 py-3 bg-gray-600 border border-gray-500 rounded-lg text-gray-300">
                                {{ $payment->completed_at->format('M d, Y g:i A') }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center pt-6 border-t border-gray-700">
                    <div class="flex items-center space-x-4">
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                            <i class="fas fa-save mr-2"></i>
                            Update Payment
                        </button>
                        <a href="{{ route('admin.payments.show', $payment->id) }}"
                           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation feedback
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', function() {
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
    });
});
</script>
@endsection
