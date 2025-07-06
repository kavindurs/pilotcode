@extends('layouts.business')

@section('active-ads-manager', 'menu-item-active')
@section('title', 'Ads Manager')

@section('page-title')
    Ads Manager
@endsection

@section('page-subtitle', 'Manage your property promotion requests and track their status.')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total Requests</p>
                    <p class="text-2xl font-bold text-white">{{ $ads->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bullhorn text-blue-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Active</p>
                    <p class="text-2xl font-bold text-green-400">{{ $ads->where('status', 'active')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-play text-green-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Pending</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ $ads->where('status', 'pending')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Approved</p>
                    <p class="text-2xl font-bold text-blue-400">{{ $ads->where('status', 'approved')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check text-blue-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Ads Table -->
    <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl overflow-hidden">
        <div class="p-6 border-b border-gray-800">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-list text-blue-400 mr-2"></i>
                    Promotion Requests
                </h3>
                <a href="{{ route('property.ads.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Request Promotion
                </a>
            </div>
        </div>

        @if($ads->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Property
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Period
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Submitted
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($ads as $ad)
                            <tr class="hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-building text-gray-400 text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-white font-medium">{{ $ad->property->business_name }}</p>
                                            <p class="text-gray-400 text-sm">{{ $ad->property->city }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        <p class="text-white">{{ $ad->start_date->format('M j, Y') }}</p>
                                        <p class="text-gray-400">to {{ $ad->end_date->format('M j, Y') }}</p>
                                        <p class="text-gray-500 text-xs">
                                            {{ $ad->start_date->diffInDays($ad->end_date) + 1 }} days
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $ad->getStatusBadgeClass() }}">
                                            @switch($ad->status)
                                                @case('payment_pending')
                                                    <i class="fas fa-credit-card mr-1"></i>
                                                    Payment Required
                                                    @break
                                                @case('pending')
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Pending Review
                                                    @break
                                                @case('approved')
                                                    <i class="fas fa-check mr-1"></i>
                                                    Approved
                                                    @break
                                                @case('active')
                                                    <i class="fas fa-play mr-1"></i>
                                                    Active
                                                    @break
                                                @case('rejected')
                                                    <i class="fas fa-times mr-1"></i>
                                                    Rejected
                                                    @break
                                                @case('expired')
                                                    <i class="fas fa-calendar-times mr-1"></i>
                                                    Expired
                                                    @break
                                            @endswitch
                                        </span>
                                        @if($ad->total_amount)
                                            <div class="text-xs text-gray-400">
                                                Cost: ${{ number_format($ad->total_amount/300, 2) }} USD
                                            </div>
                                        @endif
                                        @if($ad->payment_status)
                                            <div class="text-xs">
                                                @switch($ad->payment_status)
                                                    @case('pending')
                                                        <span class="text-yellow-400">Payment Pending</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="text-green-400">Payment Completed</span>
                                                        @break
                                                    @case('failed')
                                                        <span class="text-red-400">Payment Failed</span>
                                                        @break
                                                    @case('refunded')
                                                        <span class="text-blue-400">Payment Refunded</span>
                                                        @break
                                                @endswitch
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-400 text-sm">
                                    {{ $ad->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('property.ads.show', $ad) }}"
                                           class="text-blue-400 hover:text-blue-300 transition-colors"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($ad->status === 'payment_pending')
                                            <a href="{{ route('property.ads.payment.retry', $ad) }}"
                                               class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 transition-colors"
                                               title="Complete Payment">
                                                <i class="fas fa-credit-card mr-1"></i>
                                                Pay Now
                                            </a>
                                        @endif

                                        @if($ad->status === 'pending')
                                            <a href="{{ route('property.ads.edit', $ad) }}"
                                               class="text-yellow-400 hover:text-yellow-300 transition-colors"
                                               title="Edit Request">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('property.ads.destroy', $ad) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Are you sure you want to cancel this request?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-400 hover:text-red-300 transition-colors"
                                                        title="Cancel Request">
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
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bullhorn text-gray-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-white mb-2">No promotion requests yet</h3>
                <p class="text-gray-400 mb-6">Start promoting your property by creating your first ad request.</p>
                <a href="{{ route('property.ads.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Request Promotion
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
