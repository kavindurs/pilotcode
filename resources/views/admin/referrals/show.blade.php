@extends('layouts.admin')

@section('active-referrals', 'menu-item-active')
@section('page-title', 'Referral Details')
@section('page-subtitle', 'View detailed information about this referral program.')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <a href="{{ route('admin.referrals.index') }}" class="text-gray-400 hover:text-white transition-colors">
                        Referrals
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">{{ $referral->referral_code }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-green-500 to-blue-600 flex items-center justify-center mr-4">
                        <i class="fas fa-handshake text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">{{ $referral->user->name }}'s Referral Program</h2>
                        <p class="text-gray-400 text-sm">Code: {{ $referral->referral_code }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.referrals.edit', $referral->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    <a href="{{ route('admin.referrals.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Status Cards -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center mr-4">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Total Referrals</p>
                    <p class="text-2xl font-bold text-white">{{ $referral->total_referrals }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mr-4">
                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Total Earnings</p>
                    <p class="text-2xl font-bold text-white">${{ number_format($referral->total_earnings, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center mr-4">
                    <i class="fas fa-percentage text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Commission Rate</p>
                    <p class="text-2xl font-bold text-white">{{ $referral->commission_rate }}%</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Referral Details -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Referral Information
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">User</span>
                    <div class="text-right">
                        <div class="text-white font-medium">{{ $referral->user->name }}</div>
                        <div class="text-gray-400 text-sm">{{ $referral->user->email }}</div>
                    </div>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Referral Code</span>
                    <div class="flex items-center">
                        <span class="bg-blue-600 text-white px-2 py-1 rounded text-sm font-mono mr-2">{{ $referral->referral_code }}</span>
                        <button onclick="copyToClipboard('{{ $referral->referral_code }}')" class="text-gray-400 hover:text-white">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Status</span>
                    <span>
                        @if($referral->is_active)
                            @if($referral->expires_at && $referral->expires_at->isPast())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-600 text-white">
                                    <i class="fas fa-clock mr-1"></i>
                                    Expired
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-600 text-white">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Active
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-600 text-white">
                                <i class="fas fa-pause-circle mr-1"></i>
                                Inactive
                            </span>
                        @endif
                    </span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Created</span>
                    <span class="text-white">{{ $referral->created_at->format('M d, Y g:i A') }}</span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Expires</span>
                    <span class="text-white">
                        @if($referral->expires_at)
                            {{ $referral->expires_at->format('M d, Y g:i A') }}
                        @else
                            <span class="text-green-400">Never</span>
                        @endif
                    </span>
                </div>

                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-400">Last Updated</span>
                    <span class="text-white">{{ $referral->updated_at->format('M d, Y g:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Referral Links -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-link mr-2"></i>
                    Referral Links
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-300 mb-2 block">User Registration Link</label>
                    <div class="flex items-center">
                        <input type="text" value="{{ $referral->getUserReferralLink() }}" readonly
                               class="flex-1 bg-gray-700 border border-gray-600 text-white text-sm rounded-l-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <button onclick="copyToClipboard('{{ $referral->getUserReferralLink() }}')"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-r-lg border border-blue-600">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-300 mb-2 block">Property Registration Link</label>
                    <div class="flex items-center">
                        <input type="text" value="{{ $referral->getPropertyReferralLink() }}" readonly
                               class="flex-1 bg-gray-700 border border-gray-600 text-white text-sm rounded-l-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <button onclick="copyToClipboard('{{ $referral->getPropertyReferralLink() }}')"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-r-lg border border-blue-600">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Earnings -->
    @if($referral->earnings->count() > 0)
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden mt-6">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-chart-line mr-2"></i>
                    Recent Earnings
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-750 border-b border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Description</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($referral->earnings->take(10) as $earning)
                            <tr class="bg-gray-800 hover:bg-gray-750 transition-colors">
                                <td class="px-6 py-4 text-white">{{ $earning->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-green-400 font-medium">${{ number_format($earning->commission_amount, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $earning->status === 'paid' ? 'bg-green-600 text-white' : 'bg-yellow-600 text-white' }}">
                                        {{ ucfirst($earning->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-300">{{ $earning->description ?? 'Referral commission' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show a temporary notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        notification.textContent = 'Copied to clipboard!';
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 2000);
    });
}
</script>
@endsection
