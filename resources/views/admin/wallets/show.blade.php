@extends('layouts.admin')

@section('active-wallets', 'menu-item-active')
@section('page-title', 'Wallet Details')
@section('page-subtitle', 'View and manage wallet information')

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
                    <a href="{{ route('admin.wallets.index') }}" class="text-gray-400 hover:text-white transition-colors">Wallets</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">Wallet Details</span>
                </div>
            </li>
        </ol>
    </nav>

    @if(session('success'))
        <div class="bg-green-600 text-white px-6 py-4 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-600 text-white px-6 py-4 rounded-lg mb-6">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span class="font-medium">Please correct the following errors:</span>
            </div>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Wallet Info -->
        <div class="lg:col-span-2">
            <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg mr-4">
                                {{ strtoupper(substr($wallet->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">{{ $wallet->user->name ?? 'Unknown User' }}</h2>
                                <p class="text-gray-300 text-sm">{{ $wallet->user->email ?? 'No email' }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.wallets.edit', $wallet->id) }}"
                               class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-edit mr-2"></i>Edit
                            </a>
                            <a href="{{ route('admin.wallets.index') }}"
                               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Back
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Balance Overview -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-green-900 bg-opacity-50 border border-green-600 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-300 text-sm font-medium">Current Balance</p>
                                    <p class="text-2xl font-bold text-white">{{ $wallet->currency }} {{ number_format($wallet->balance, 2) }}</p>
                                </div>
                                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-wallet text-white text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-orange-900 bg-opacity-50 border border-orange-600 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-orange-300 text-sm font-medium">Pending Balance</p>
                                    <p class="text-2xl font-bold text-white">{{ $wallet->currency }} {{ number_format($wallet->pending_balance, 2) }}</p>
                                </div>
                                <div class="w-12 h-12 bg-orange-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-900 bg-opacity-50 border border-blue-600 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-300 text-sm font-medium">Total Earned</p>
                                    <p class="text-2xl font-bold text-white">{{ $wallet->currency }} {{ number_format($wallet->total_earned, 2) }}</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-arrow-up text-white text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-red-900 bg-opacity-50 border border-red-600 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-red-300 text-sm font-medium">Total Withdrawn</p>
                                    <p class="text-2xl font-bold text-white">{{ $wallet->currency }} {{ number_format($wallet->total_withdrawn, 2) }}</p>
                                </div>
                                <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-arrow-down text-white text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Wallet Details -->
                    <div class="border-t border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-white mb-4">Wallet Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Wallet ID</label>
                                <p class="text-white">#{{ $wallet->id }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Currency</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-600 text-white">
                                    {{ $wallet->currency }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Created Date</label>
                                <p class="text-white">{{ $wallet->created_at->format('M d, Y g:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Last Updated</label>
                                <p class="text-white">{{ $wallet->updated_at->format('M d, Y g:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Net Worth</label>
                                <p class="text-white font-medium">{{ $wallet->currency }} {{ number_format($wallet->total_earned - $wallet->total_withdrawn, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance Adjustment Panel -->
        <div class="lg:col-span-1">
            <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-bold text-white">Balance Adjustment</h3>
                    <p class="text-gray-300 text-sm">Add or deduct funds from this wallet</p>
                </div>

                <div class="p-6">
                    <form action="{{ route('admin.wallets.adjust', $wallet->id) }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="adjustment_type" class="block text-sm font-medium text-gray-300 mb-2">
                                Adjustment Type <span class="text-red-400">*</span>
                            </label>
                            <select id="adjustment_type"
                                    name="adjustment_type"
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="">Select Type</option>
                                <option value="add">Add Funds</option>
                                <option value="subtract">Deduct Funds</option>
                            </select>
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-300 mb-2">
                                Amount ({{ $wallet->currency }}) <span class="text-red-400">*</span>
                            </label>
                            <input type="number"
                                   id="amount"
                                   name="amount"
                                   step="0.01"
                                   min="0.01"
                                   max="999999999.99"
                                   class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="0.00"
                                   required>
                        </div>

                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-300 mb-2">
                                Reason <span class="text-red-400">*</span>
                            </label>
                            <textarea id="reason"
                                      name="reason"
                                      rows="3"
                                      class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Reason for adjustment..."
                                      required></textarea>
                        </div>

                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-plus-minus mr-2"></i>
                            Apply Adjustment
                        </button>
                    </form>

                    <div class="mt-6 p-4 bg-yellow-900 bg-opacity-30 border border-yellow-600 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mr-3 mt-1"></i>
                            <div>
                                <h4 class="text-yellow-300 font-medium text-sm">Important Notice</h4>
                                <p class="text-yellow-200 text-xs mt-1">
                                    Balance adjustments will be reflected immediately and cannot be undone. Please ensure the amount and reason are correct.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden mt-6">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-bold text-white">Quick Actions</h3>
                </div>

                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.wallets.edit', $wallet->id) }}"
                       class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-3 rounded-lg text-center transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Wallet
                    </a>

                    <form method="POST" action="{{ route('admin.wallets.destroy', $wallet->id) }}" class="block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg transition-colors"
                                onclick="return confirm('Are you sure you want to delete this wallet? This action cannot be undone.')">
                            <i class="fas fa-trash mr-2"></i>Delete Wallet
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
