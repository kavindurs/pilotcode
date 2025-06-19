@extends('layouts.admin')

@section('active-payments', 'menu-item-active')
@section('page-title', 'Payment Management')
@section('page-subtitle', 'Manage all payments, transactions, and billing information')

@section('content')
<div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-white mb-1">Payment Management</h2>
                <p class="text-gray-300 text-sm">View and manage all payment transactions</p>
            </div>
            <div class="mt-3 sm:mt-0 flex items-center space-x-3">
                <div class="text-sm text-gray-300">
                    Total: <span class="font-semibold text-white">{{ $payments->total() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-gray-750 px-6 py-4 border-b border-gray-600">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Search payments..."
                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <div>
                <select name="status" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $statusOption)
                        <option value="{{ $statusOption }}" {{ $status == $statusOption ? 'selected' : '' }}>
                            {{ ucfirst($statusOption) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="plan" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">All Plans</option>
                    @foreach($plans as $planOption)
                        <option value="{{ $planOption->id }}" {{ $plan == $planOption->id ? 'selected' : '' }}>
                            {{ $planOption->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <input type="date"
                       name="date_from"
                       value="{{ $dateFrom }}"
                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <div>
                <input type="date"
                       name="date_to"
                       value="{{ $dateTo }}"
                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-search"></i>
                </button>
                @if($search || $status || $plan || $dateFrom || $dateTo)
                    <a href="{{ route('admin.payments.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-600 text-white px-6 py-3 border-b border-gray-700">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-600 text-white px-6 py-3 border-b border-gray-700">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($payments->count() > 0)
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-750 border-b border-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Transaction</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($payments as $payment)
                        <tr class="hover:bg-gray-750 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <div class="text-white font-medium">{{ $payment->customer_name ?? 'N/A' }}</div>
                                    <div class="text-gray-400">{{ $payment->customer_email ?? $payment->business_email }}</div>
                                    @if($payment->property)
                                        <div class="text-gray-500 text-xs">Property: {{ $payment->property->business_name }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($payment->plan)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-600 text-white">
                                        {{ $payment->plan->name }}
                                    </span>
                                @else
                                    <span class="text-gray-500">No Plan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-white font-medium">
                                    {{ strtoupper($payment->currency) }} {{ number_format($payment->amount, 2) }}
                                </div>
                                @if($payment->payment_method)
                                    <div class="text-gray-400 text-xs">{{ $payment->payment_method }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
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
                                    <i class="fas
                                        @switch(strtolower($payment->status))
                                            @case('confirmed')
                                            @case('success')
                                                fa-check-circle
                                                @break
                                            @case('pending')
                                                fa-clock
                                                @break
                                            @case('failed')
                                                fa-times-circle
                                                @break
                                            @case('cancelled')
                                                fa-ban
                                                @break
                                            @case('refunded')
                                                fa-undo
                                                @break
                                            @default
                                                fa-question-circle
                                        @endswitch
                                        mr-1"></i>
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    @if($payment->transaction_id)
                                        <div class="text-gray-300 font-mono">{{ $payment->transaction_id }}</div>
                                    @endif
                                    @if($payment->order_id)
                                        <div class="text-gray-500 text-xs">Order: {{ $payment->order_id }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                <div>{{ $payment->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $payment->created_at->format('g:i A') }}</div>
                                @if($payment->completed_at)
                                    <div class="text-xs text-green-400">Completed: {{ $payment->completed_at->format('M d, Y') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.payments.show', $payment->id) }}"
                                       class="text-blue-400 hover:text-blue-300 transition-colors p-1"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.payments.edit', $payment->id) }}"
                                       class="text-yellow-400 hover:text-yellow-300 transition-colors p-1"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if(strtolower($payment->status) === 'pending')
                                        <form method="POST" action="{{ route('admin.payments.confirm', $payment->id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-green-400 hover:text-green-300 transition-colors p-1"
                                                    title="Confirm Payment"
                                                    onclick="return confirm('Are you sure you want to confirm this payment?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.payments.cancel', $payment->id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-orange-400 hover:text-orange-300 transition-colors p-1"
                                                    title="Cancel Payment"
                                                    onclick="return confirm('Are you sure you want to cancel this payment?')">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if(strtolower($payment->status) === 'confirmed')
                                        <form method="POST" action="{{ route('admin.payments.refund', $payment->id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-purple-400 hover:text-purple-300 transition-colors p-1"
                                                    title="Refund Payment"
                                                    onclick="return confirm('Are you sure you want to refund this payment?')">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if(in_array(strtolower($payment->status), ['pending', 'failed', 'cancelled']))
                                        <form method="POST" action="{{ route('admin.payments.destroy', $payment->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-400 hover:text-red-300 transition-colors p-1"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this payment? This action cannot be undone.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-750 px-6 py-4 border-t border-gray-600">
            {{ $payments->withQueryString()->links('admin.partials.pagination') }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-gray-400 text-6xl mb-4">
                <i class="fas fa-credit-card"></i>
            </div>
            <h3 class="text-lg font-medium text-white mb-2">No Payments Found</h3>
            <p class="text-gray-400">
                @if($search || $status || $plan || $dateFrom || $dateTo)
                    No payments match your current filters.
                @else
                    No payments have been recorded yet.
                @endif
            </p>
            @if($search || $status || $plan || $dateFrom || $dateTo)
                <a href="{{ route('admin.payments.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Clear Filters
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
