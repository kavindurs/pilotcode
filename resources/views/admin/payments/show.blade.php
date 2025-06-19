@extends('layouts.admin')

@section('active-payments', 'menu-item-active')
@section('page-title', 'Payment Details')
@section('page-subtitle', 'View detailed payment information and transaction history')

@section('content')
<div class="max-w-6xl mx-auto">
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
                    <span class="text-gray-300">Payment Details</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white mb-1">Payment Details</h2>
                    <p class="text-gray-300 text-sm">Transaction ID: {{ $payment->transaction_id ?? $payment->order_id ?? 'N/A' }}</p>
                </div>
                <div class="mt-3 sm:mt-0 flex items-center space-x-3">
                    <a href="{{ route('admin.payments.edit', $payment->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Payment
                    </a>
                    <a href="{{ route('admin.payments.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Payments
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Payment Information -->
                <div class="bg-gray-750 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-white mb-4">
                        <i class="fas fa-credit-card mr-2"></i>
                        Payment Information
                    </h3>

                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @switch(strtolower($payment->status))
                                    @case('confirmed')
                                    @case('success')
                                        bg-green-600 text-white
                                        @break
                                    @case('pending')
                                        bg-yellow-600 text-white
                                        @break
                                    @case('failed')
                                        bg-red-600 text-white
                                        @break
                                    @case('cancelled')
                                        bg-gray-600 text-white
                                        @break
                                    @case('refunded')
                                        bg-purple-600 text-white
                                        @break
                                    @default
                                        bg-gray-600 text-white
                                @endswitch
                            ">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-400">Amount:</span>
                            <span class="text-white font-medium">{{ strtoupper($payment->currency) }} {{ number_format($payment->amount, 2) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-400">Payment Method:</span>
                            <span class="text-gray-300">{{ $payment->payment_method ?? 'N/A' }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-400">Transaction ID:</span>
                            <span class="text-gray-300 font-mono">{{ $payment->transaction_id ?? 'N/A' }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-400">Order ID:</span>
                            <span class="text-gray-300 font-mono">{{ $payment->order_id ?? 'N/A' }}</span>
                        </div>

                        @if($payment->genie_transaction_id)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Genie Transaction ID:</span>
                                <span class="text-gray-300 font-mono">{{ $payment->genie_transaction_id }}</span>
                            </div>
                        @endif

                        @if($payment->payment_url)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Payment URL:</span>
                                <a href="{{ $payment->payment_url }}" target="_blank" class="text-blue-400 hover:text-blue-300 transition-colors">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <span class="text-gray-400">Created:</span>
                            <span class="text-gray-300">{{ $payment->created_at->format('M d, Y g:i A') }}</span>
                        </div>

                        @if($payment->completed_at)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Completed:</span>
                                <span class="text-green-400">{{ $payment->completed_at->format('M d, Y g:i A') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-gray-750 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-white mb-4">
                        <i class="fas fa-user mr-2"></i>
                        Customer Information
                    </h3>

                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Name:</span>
                            <span class="text-white">{{ $payment->customer_name ?? 'N/A' }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-400">Customer Email:</span>
                            <span class="text-gray-300">{{ $payment->customer_email ?? 'N/A' }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-400">Business Email:</span>
                            <span class="text-gray-300">{{ $payment->business_email ?? 'N/A' }}</span>
                        </div>

                        @if($payment->user)
                            <div class="flex justify-between">
                                <span class="text-gray-400">User Account:</span>
                                <span class="text-blue-400">
                                    <a href="{{ route('admin.users.show', $payment->user->id) }}" class="hover:text-blue-300 transition-colors">
                                        {{ $payment->user->name }}
                                    </a>
                                </span>
                            </div>
                        @endif

                        @if($payment->genie_customer_id)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Genie Customer ID:</span>
                                <span class="text-gray-300 font-mono">{{ $payment->genie_customer_id }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Plan Information -->
                @if($payment->plan)
                <div class="bg-gray-750 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-white mb-4">
                        <i class="fas fa-medal mr-2"></i>
                        Plan Information
                    </h3>

                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Plan Name:</span>
                            <span class="text-white font-medium">{{ $payment->plan->name }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-400">Plan Price:</span>
                            <span class="text-gray-300">{{ $payment->plan->currency }} {{ number_format($payment->plan->price, 2) }}</span>
                        </div>

                        @if($payment->plan->description)
                            <div>
                                <span class="text-gray-400 block mb-2">Description:</span>
                                <span class="text-gray-300">{{ $payment->plan->description }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Property Information -->
                @if($payment->property)
                <div class="bg-gray-750 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-white mb-4">
                        <i class="fas fa-building mr-2"></i>
                        Property Information
                    </h3>

                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Business Name:</span>
                            <span class="text-white">{{ $payment->property->business_name }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-400">Property ID:</span>
                            <span class="text-gray-300">
                                <a href="{{ route('admin.properties.show', $payment->property->id) }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                                    #{{ $payment->property->id }}
                                </a>
                            </span>
                        </div>

                        @if($payment->property->address)
                            <div>
                                <span class="text-gray-400 block mb-2">Address:</span>
                                <span class="text-gray-300">{{ $payment->property->address }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 bg-gray-750 rounded-lg p-6">
                <h3 class="text-lg font-medium text-white mb-4">
                    <i class="fas fa-tools mr-2"></i>
                    Quick Actions
                </h3>

                <div class="flex flex-wrap gap-3">
                    @if(strtolower($payment->status) === 'pending')
                        <form method="POST" action="{{ route('admin.payments.confirm', $payment->id) }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors"
                                    onclick="return confirm('Are you sure you want to confirm this payment?')">
                                <i class="fas fa-check mr-2"></i>
                                Confirm Payment
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.payments.cancel', $payment->id) }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors"
                                    onclick="return confirm('Are you sure you want to cancel this payment?')">
                                <i class="fas fa-ban mr-2"></i>
                                Cancel Payment
                            </button>
                        </form>
                    @endif

                    @if(strtolower($payment->status) === 'confirmed')
                        <form method="POST" action="{{ route('admin.payments.refund', $payment->id) }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors"
                                    onclick="return confirm('Are you sure you want to refund this payment?')">
                                <i class="fas fa-undo mr-2"></i>
                                Refund Payment
                            </button>
                        </form>
                    @endif

                    @if(in_array(strtolower($payment->status), ['pending', 'failed', 'cancelled']))
                        <form method="POST" action="{{ route('admin.payments.destroy', $payment->id) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors"
                                    onclick="return confirm('Are you sure you want to delete this payment? This action cannot be undone.')">
                                <i class="fas fa-trash mr-2"></i>
                                Delete Payment
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
