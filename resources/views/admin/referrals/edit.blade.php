@extends('layouts.admin')

@section('active-referrals', 'menu-item-active')
@section('page-title', 'Edit Referral')
@section('page-subtitle', 'Update referral program settings and information.')

@section('content')
<div class="max-w-4xl mx-auto">
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
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <a href="{{ route('admin.referrals.show', $referral->id) }}" class="text-gray-400 hover:text-white transition-colors">
                        {{ $referral->referral_code }}
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">Edit</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-500 to-blue-600 flex items-center justify-center mr-3">
                        <i class="fas fa-handshake text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Edit Referral Program</h2>
                        <p class="text-gray-400 text-sm">Code: {{ $referral->referral_code }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.referrals.show', $referral->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        View Details
                    </a>
                    <a href="{{ route('admin.referrals.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="bg-red-600 text-white px-6 py-3 border-b border-gray-700">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <div>
                        <div class="font-medium">Please fix the following errors:</div>
                        <ul class="mt-1 text-sm list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('admin.referrals.update', $referral->id) }}" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column - Basic Info -->
                <div class="space-y-6">
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Referral Information
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-300 mb-2">
                                    Select User *
                                </label>
                                <select
                                    id="user_id"
                                    name="user_id"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('user_id') border-red-500 @enderror"
                                >
                                    <option value="">Choose a user</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ (old('user_id', $referral->user_id) == $user->id) ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="commission_rate" class="block text-sm font-medium text-gray-300 mb-2">
                                    Commission Rate (%) *
                                </label>
                                <input
                                    type="number"
                                    id="commission_rate"
                                    name="commission_rate"
                                    value="{{ old('commission_rate', $referral->commission_rate) }}"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('commission_rate') border-red-500 @enderror"
                                    placeholder="5.00"
                                >
                                @error('commission_rate')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-500 text-sm mt-1">The percentage commission the user will earn from referrals</p>
                            </div>

                            <div>
                                <label for="expires_at" class="block text-sm font-medium text-gray-300 mb-2">
                                    Expiration Date (Optional)
                                </label>
                                <input
                                    type="date"
                                    id="expires_at"
                                    name="expires_at"
                                    value="{{ old('expires_at', $referral->expires_at ? $referral->expires_at->format('Y-m-d') : '') }}"
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('expires_at') border-red-500 @enderror"
                                >
                                @error('expires_at')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-500 text-sm mt-1">Leave empty for no expiration</p>
                            </div>

                            <div class="flex items-center">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    {{ old('is_active', $referral->is_active) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500"
                                >
                                <label for="is_active" class="ml-2 text-sm text-gray-300">
                                    Active Referral Program
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Current Stats & Info -->
                <div class="space-y-6">
                    <!-- Current Statistics -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Current Statistics
                        </h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-gray-600">
                                <span class="text-gray-400">Total Referrals</span>
                                <span class="text-white font-medium">{{ $referral->total_referrals }}</span>
                            </div>

                            <div class="flex justify-between items-center py-2 border-b border-gray-600">
                                <span class="text-gray-400">Total Earnings</span>
                                <span class="text-green-400 font-medium">${{ number_format($referral->total_earnings, 2) }}</span>
                            </div>

                            <div class="flex justify-between items-center py-2 border-b border-gray-600">
                                <span class="text-gray-400">Referral Code</span>
                                <div class="flex items-center">
                                    <span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-mono mr-2">{{ $referral->referral_code }}</span>
                                    <button type="button" onclick="copyToClipboard('{{ $referral->referral_code }}')" class="text-gray-400 hover:text-white">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-400">Created</span>
                                <span class="text-white">{{ $referral->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Referral Links -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-link mr-2"></i>
                            Referral Links
                        </h3>

                        <div class="space-y-3">
                            <div>
                                <label class="text-xs text-gray-400 mb-1 block">User Registration Link</label>
                                <div class="flex items-center">
                                    <input type="text" value="{{ $referral->getUserReferralLink() }}" readonly
                                           class="flex-1 bg-gray-700 border border-gray-600 text-white text-xs rounded-l-lg px-2 py-1">
                                    <button type="button" onclick="copyToClipboard('{{ $referral->getUserReferralLink() }}')"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded-r-lg text-xs">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="text-xs text-gray-400 mb-1 block">Property Registration Link</label>
                                <div class="flex items-center">
                                    <input type="text" value="{{ $referral->getPropertyReferralLink() }}" readonly
                                           class="flex-1 bg-gray-700 border border-gray-600 text-white text-xs rounded-l-lg px-2 py-1">
                                    <button type="button" onclick="copyToClipboard('{{ $referral->getPropertyReferralLink() }}')"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded-r-lg text-xs">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-700">
                <a
                    href="{{ route('admin.referrals.show', $referral->id) }}"
                    class="px-6 py-2 text-gray-400 hover:text-white transition-colors"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-8 py-2 rounded-lg transition-colors font-medium"
                >
                    <i class="fas fa-save mr-2"></i>
                    Update Referral Program
                </button>
            </div>
        </form>
    </div>
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
