@extends('layouts.app')

@section('title', 'Referral Dashboard')



@section('content')
<!-- Custom Header Section -->
<div class="bg-white rounded-xl overflow-hidden shadow-sm mb-6">
    <div class="relative bg-gradient-to-br from-blue-500 to-indigo-600">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                <defs>
                    <pattern id="privacy-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M0 20 L40 20" stroke="#fff" stroke-width="1" fill="none" />
                        <path d="M20 0 L20 40" stroke="#fff" stroke-width="1" fill="none" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#privacy-pattern)" />
            </svg>
        </div>

        <!-- Header Content -->
        <div class="relative px-8 py-12">
            <h1 class="text-4xl font-bold text-white mb-3">Referral Dashboard</h1>
            <p class="text-blue-100 text-lg">Earn money by referring new users to our platform</p>
        </div>
    </div>
</div>

<style>
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
    }
    .stat-card {
        @apply bg-white rounded-xl shadow-lg p-6 text-center;
    }
    .copy-button {
        @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors;
    }
</style>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card">
        <div class="text-3xl font-bold text-blue-600">${{ number_format($stats['wallet_balance'], 2) }}</div>
        <div class="text-gray-600 mt-1">Available Balance</div>
    </div>
    <div class="stat-card">
        <div class="text-3xl font-bold text-yellow-600">${{ number_format($stats['pending_balance'], 2) }}</div>
        <div class="text-gray-600 mt-1">Pending Balance</div>
    </div>
    <div class="stat-card">
        <div class="text-3xl font-bold text-green-600">{{ $stats['total_referrals'] }}</div>
        <div class="text-gray-600 mt-1">Total Referrals</div>
    </div>
    <div class="stat-card">
        <div class="text-3xl font-bold text-purple-600">${{ number_format($stats['total_earnings'], 2) }}</div>
        <div class="text-gray-600 mt-1">Total Earnings</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Referral Links -->
    <div class="card p-6">
        <h2 class="text-2xl font-bold mb-6 flex items-center">
            <i class="fas fa-link text-blue-600 mr-3"></i>
            Your Referral Links
        </h2>

        <!-- Referral Code -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Referral Code</label>
            <div class="flex gap-2">
                <input type="text"
                       id="referralCode"
                       value="{{ $referral->referral_code }}"
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       readonly>
                <button onclick="copyToClipboard('referralCode')" class="copy-button">
                    <i class="fas fa-copy"></i>
                </button>
                <button onclick="generateNewCode()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-refresh"></i>
                </button>
            </div>
        </div>

        <!-- Referral Links -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-3">Referral Links</label>

            <!-- User Registration Link -->
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-600 mb-1">User Registration Link</label>
                <div class="flex gap-2">
                    <input type="text"
                           id="userReferralLink"
                           value="{{ $userLink }}"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                           readonly>
                    <button onclick="copyToClipboard('userReferralLink')" class="copy-button">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Send this link to invite people to register as users</p>
            </div>

            <!-- Property Registration Link -->
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-600 mb-1">Property Registration Link</label>
                <div class="flex gap-2">
                    <input type="text"
                           id="propertyReferralLink"
                           value="{{ $propertyLink }}"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                           readonly>
                    <button onclick="copyToClipboard('propertyReferralLink')" class="copy-button">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Send this link to invite people to register their business property</p>
            </div>
        </div>

        <!-- Commission Rate (Read-only) -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Commission Rate</label>
            <div class="flex items-center gap-2">
                <input type="text"
                       value="{{ $stats['commission_rate'] }}%"
                       class="w-24 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                       readonly>
                <span class="text-sm text-gray-500">System-wide rate (cannot be changed)</span>
            </div>
        </div>

        <!-- Status Toggle -->
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-gray-700">Referral Status</span>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox"
                       id="referralStatus"
                       {{ $referral->is_active ? 'checked' : '' }}
                       onchange="toggleReferralStatus()"
                       class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
            </label>
        </div>
    </div>

    <!-- Recent Earnings -->
    <div class="card p-6">
        <h2 class="text-2xl font-bold mb-6 flex items-center">
            <i class="fas fa-coins text-green-600 mr-3"></i>
            Recent Earnings
        </h2>

        <div class="space-y-4 max-h-96 overflow-y-auto">
            @forelse($earnings as $earning)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <div class="font-medium">
                            @if($earning->referredUser)
                                {{ $earning->referredUser->name }}
                            @else
                                Property Referral
                            @endif
                        </div>
                        <div class="text-sm text-gray-600">{{ $earning->property->business_name }}</div>
                        <div class="text-xs text-gray-500">{{ $earning->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-green-600">${{ number_format($earning->commission_amount, 2) }}</div>
                        <div class="text-xs px-2 py-1 rounded-full
                            {{ $earning->status === 'paid' ? 'bg-green-100 text-green-800' :
                               ($earning->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($earning->status) }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-4"></i>
                    <p>No earnings yet. Start referring users to earn commissions!</p>
                </div>
            @endforelse
        </div>

        @if($earnings->hasPages())
            <div class="mt-6">
                {{ $earnings->links() }}
            </div>
        @endif
    </div>
</div>

<!-- How It Works -->
<div class="card p-8 mt-8">
    <h2 class="text-2xl font-bold mb-6 text-center">How It Works</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="text-center">
            <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-share-alt text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold mb-2">1. Share Your Links</h3>
            <p class="text-gray-600">Share your user or property registration links with friends and business contacts.</p>
        </div>
        <div class="text-center">
            <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-plus text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold mb-2">2. They Register & Purchase</h3>
            <p class="text-gray-600">When someone registers a property and purchases a plan through your link, you earn a commission.</p>
        </div>
        <div class="text-center">
            <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-wallet text-purple-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold mb-2">3. Get Paid</h3>
            <p class="text-gray-600">Earn {{ $stats['commission_rate'] }}% commission on every successful referral.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        element.select();
        element.setSelectionRange(0, 99999);
        document.execCommand('copy');

        // Show success message
        showNotification('Copied to clipboard!', 'success');
    }

    function generateNewCode() {
        fetch('/referrals/generate-new-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('referralCode').value = data.referral_code;
                document.getElementById('userReferralLink').value = data.user_link;
                document.getElementById('propertyReferralLink').value = data.property_link;
                showNotification('New referral code generated!', 'success');
            }
        })
        .catch(error => {
            showNotification('Error generating new code', 'error');
        });
    }

    function toggleReferralStatus() {
        fetch('/referrals/toggle-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Referral status updated!', 'success');
            }
        })
        .catch(error => {
            showNotification('Error updating status', 'error');
        });
    }

    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white font-medium z-50 ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>
@endpush
