@extends('layouts.admin')

@section('active-wallets', 'menu-item-active')
@section('page-title', 'Edit Wallet')
@section('page-subtitle', 'Update wallet information and balances')

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
                    <a href="{{ route('admin.wallets.index') }}" class="text-gray-400 hover:text-white transition-colors">Wallets</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">Edit Wallet</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white mb-1">Edit Wallet</h2>
                    <p class="text-gray-300 text-sm">Update wallet information for {{ $wallet->user->name ?? 'Unknown User' }}</p>
                </div>
                <div class="mt-3 sm:mt-0">
                    <a href="{{ route('admin.wallets.show', $wallet->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Wallet
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

            <form action="{{ route('admin.wallets.update', $wallet->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Current Wallet Owner Info -->
                <div class="bg-gray-750 border border-gray-600 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-white mb-3">Current Wallet Owner</h3>
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg mr-4">
                            {{ strtoupper(substr($wallet->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-white font-medium">{{ $wallet->user->name ?? 'Unknown User' }}</div>
                            <div class="text-gray-400 text-sm">{{ $wallet->user->email ?? 'No email' }}</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-300 mb-2">
                            Wallet Owner <span class="text-red-400">*</span>
                        </label>
                        <select id="user_id"
                                name="user_id"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('user_id') border-red-500 @enderror"
                                required>
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $wallet->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-300 mb-2">
                            Currency <span class="text-red-400">*</span>
                        </label>
                        <select id="currency"
                                name="currency"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('currency') border-red-500 @enderror"
                                required>
                            <option value="">Select Currency</option>
                            <option value="USD" {{ old('currency', $wallet->currency) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                            <option value="EUR" {{ old('currency', $wallet->currency) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                            <option value="GBP" {{ old('currency', $wallet->currency) == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                            <option value="CAD" {{ old('currency', $wallet->currency) == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                            <option value="AUD" {{ old('currency', $wallet->currency) == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar</option>
                            <option value="INR" {{ old('currency', $wallet->currency) == 'INR' ? 'selected' : '' }}>INR - Indian Rupee</option>
                            <option value="LKR" {{ old('currency', $wallet->currency) == 'LKR' ? 'selected' : '' }}>LKR - Sri Lankan Rupee</option>
                        </select>
                        @error('currency')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="balance" class="block text-sm font-medium text-gray-300 mb-2">
                            Current Balance <span class="text-red-400">*</span>
                        </label>
                        <input type="number"
                               id="balance"
                               name="balance"
                               step="0.01"
                               min="0"
                               max="999999999.99"
                               value="{{ old('balance', $wallet->balance) }}"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('balance') border-red-500 @enderror"
                               placeholder="0.00"
                               required>
                        @error('balance')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Available balance that can be withdrawn</p>
                    </div>

                    <div>
                        <label for="pending_balance" class="block text-sm font-medium text-gray-300 mb-2">
                            Pending Balance <span class="text-red-400">*</span>
                        </label>
                        <input type="number"
                               id="pending_balance"
                               name="pending_balance"
                               step="0.01"
                               min="0"
                               max="999999999.99"
                               value="{{ old('pending_balance', $wallet->pending_balance) }}"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('pending_balance') border-red-500 @enderror"
                               placeholder="0.00"
                               required>
                        @error('pending_balance')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Balance awaiting approval or processing</p>
                    </div>

                    <div>
                        <label for="total_earned" class="block text-sm font-medium text-gray-300 mb-2">
                            Total Earned <span class="text-red-400">*</span>
                        </label>
                        <input type="number"
                               id="total_earned"
                               name="total_earned"
                               step="0.01"
                               min="0"
                               max="999999999.99"
                               value="{{ old('total_earned', $wallet->total_earned) }}"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('total_earned') border-red-500 @enderror"
                               placeholder="0.00"
                               required>
                        @error('total_earned')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Total amount earned by the user</p>
                    </div>

                    <div>
                        <label for="total_withdrawn" class="block text-sm font-medium text-gray-300 mb-2">
                            Total Withdrawn <span class="text-red-400">*</span>
                        </label>
                        <input type="number"
                               id="total_withdrawn"
                               name="total_withdrawn"
                               step="0.01"
                               min="0"
                               max="999999999.99"
                               value="{{ old('total_withdrawn', $wallet->total_withdrawn) }}"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('total_withdrawn') border-red-500 @enderror"
                               placeholder="0.00"
                               required>
                        @error('total_withdrawn')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Total amount withdrawn by the user</p>
                    </div>
                </div>

                <!-- Warning Box -->
                <div class="bg-yellow-900 bg-opacity-30 border border-yellow-600 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-3 mt-1"></i>
                        <div>
                            <h4 class="text-yellow-300 font-medium text-sm">Important Warning</h4>
                            <p class="text-yellow-200 text-xs mt-1">
                                Please be careful when editing wallet balances. Changes will affect the user's financial records and should only be made with proper authorization. Ensure all values are accurate before saving.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center pt-6 border-t border-gray-700">
                    <div class="flex items-center space-x-4">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                            <i class="fas fa-save mr-2"></i>
                            Update Wallet
                        </button>
                        <a href="{{ route('admin.wallets.show', $wallet->id) }}"
                           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-gray-750 px-6 py-4 border-t border-gray-600">
            <div class="flex flex-col sm:flex-row sm:justify-between text-sm text-gray-400">
                <div class="flex items-center mb-2 sm:mb-0">
                    <i class="fas fa-clock mr-2"></i>
                    Created: {{ $wallet->created_at->format('M d, Y g:i A') }}
                </div>
                <div class="flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    Last Updated: {{ $wallet->updated_at->format('M d, Y g:i A') }}
                </div>
            </div>
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

    // Calculate and display net worth
    function updateNetWorth() {
        const totalEarned = parseFloat(document.getElementById('total_earned').value) || 0;
        const totalWithdrawn = parseFloat(document.getElementById('total_withdrawn').value) || 0;
        const netWorth = totalEarned - totalWithdrawn;

        // You can add a net worth display element if needed
        console.log('Net Worth:', netWorth);
    }

    document.getElementById('total_earned').addEventListener('input', updateNetWorth);
    document.getElementById('total_withdrawn').addEventListener('input', updateNetWorth);
});
</script>
@endsection
