@extends('layouts.app')

@section('title', 'Wallet')

@section('content')
<!-- Custom Header Section -->
<div class="bg-white rounded-xl overflow-hidden shadow-sm mb-6">
    <div class="relative bg-gradient-to-br from-emerald-500 to-teal-600">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                <defs>
                    <pattern id="wallet-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M0 20 L40 20" stroke="#fff" stroke-width="1" fill="none" />
                        <path d="M20 0 L20 40" stroke="#fff" stroke-width="1" fill="none" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#wallet-pattern)" />
            </svg>
        </div>

        <!-- Header Content -->
        <div class="relative px-8 py-12">
            <h1 class="text-4xl font-bold text-white mb-3">My Wallet</h1>
            <p class="text-emerald-100 text-lg">Manage your earnings and withdrawals</p>
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
    }
    .balance-card {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
    }
</style>

<!-- Balance Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="balance-card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Available Balance</h3>
            <i class="fas fa-wallet text-2xl opacity-80"></i>
        </div>
        <div class="text-3xl font-bold mb-2">${{ number_format($wallet->balance, 2) }}</div>
        <div class="text-sm opacity-80">Ready for withdrawal</div>
    </div>

    <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Pending Balance</h3>
            <i class="fas fa-clock text-2xl text-yellow-500"></i>
        </div>
        <div class="text-3xl font-bold mb-2 text-yellow-600">${{ number_format($wallet->pending_balance, 2) }}</div>
        <div class="text-sm text-gray-600">Processing earnings</div>
    </div>
</div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Total Earned</h3>
                        <i class="fas fa-chart-line text-2xl text-green-500"></i>
                    </div>
                    <div class="text-2xl font-bold text-green-600">${{ number_format($wallet->total_earned, 2) }}</div>
                    <div class="text-sm text-gray-600">Lifetime earnings</div>
                </div>

                <div class="card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Total Withdrawn</h3>
                        <i class="fas fa-arrow-down text-2xl text-blue-500"></i>
                    </div>
                    <div class="text-2xl font-bold text-blue-600">${{ number_format($wallet->total_withdrawn, 2) }}</div>
                    <div class="text-sm text-gray-600">Total withdrawals</div>
                </div>
            </div>

            <!-- Withdrawal Form -->
            <div class="card p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6 flex items-center">
                    <i class="fas fa-money-bill-wave text-green-600 mr-3"></i>
                    Request Withdrawal
                </h2>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('wallet.withdrawal') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount (USD)</label>
                            <input type="number"
                                   id="amount"
                                   name="amount"
                                   step="0.01"
                                   min="1"
                                   max="{{ $wallet->balance }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Enter amount"
                                   required>
                            <div class="text-sm text-gray-600 mt-1">Available: ${{ number_format($wallet->balance, 2) }}</div>
                        </div>

                        <div>
                            <label for="method" class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Method</label>
                            <select id="method"
                                    name="method"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="">Select method</option>
                                <option value="binance">Binance ID</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="details" class="block text-sm font-medium text-gray-700 mb-2">Payment Details</label>
                        <textarea id="details"
                                  name="details"
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Enter your Binance ID or email address"
                                  required></textarea>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Withdrawals are processed within 3-5 business days
                        </div>
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors {{ $wallet->balance <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $wallet->balance <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-paper-plane mr-2"></i>
                            Request Withdrawal
                        </button>
                    </div>
                </form>
            </div>

            <!-- Quick Actions -->
            <div class="card p-6">
                <h2 class="text-2xl font-bold mb-6">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('referrals.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <i class="fas fa-users text-blue-600 text-xl mr-3"></i>
                        <div>
                            <div class="font-semibold">Referral Dashboard</div>
                            <div class="text-sm text-gray-600">Manage your referrals</div>
                        </div>
                    </a>

                    <button onclick="refreshBalance()" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <i class="fas fa-sync-alt text-green-600 text-xl mr-3"></i>
                        <div>
                            <div class="font-semibold">Refresh Balance</div>
                            <div class="text-sm text-gray-600">Update your balance</div>
                        </div>
                    </button>

                    <a href="#" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <i class="fas fa-history text-purple-600 text-xl mr-3"></i>
                        <div>
                            <div class="font-semibold">Transaction History</div>
                            <div class="text-sm text-gray-600">View all transactions</div>
                        </div>
                    </a>
                </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function refreshBalance() {
        fetch('/wallet/balance', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update balance displays
            location.reload(); // Simple refresh for now
        })
        .catch(error => {
            console.error('Error refreshing balance:', error);
        });
    }
</script>
@endpush
