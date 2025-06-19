@extends('layouts.admin')

@section('active-referrals', 'menu-item-active')
@section('page-title', 'Create Referral')
@section('page-subtitle', 'Set up a new referral program for a user.')

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
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">Create New</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">Create New Referral Program</h2>
                    <p class="text-gray-400 text-sm">Set up a referral program for a user with custom commission rates</p>
                </div>
                <a href="{{ route('admin.referrals.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Referrals
                </a>
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
        <form method="POST" action="{{ route('admin.referrals.store') }}" class="p-6">
            @csrf

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
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                                    value="{{ old('commission_rate', 5) }}"
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
                                    value="{{ old('expires_at') }}"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
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
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500"
                                >
                                <label for="is_active" class="ml-2 text-sm text-gray-300">
                                    Active Referral Program
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Guidelines & Preview -->
                <div class="space-y-6">
                    <!-- Guidelines -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-lightbulb mr-2"></i>
                            Guidelines
                        </h3>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-green-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-white font-medium">Choose an active user</p>
                                    <p class="text-gray-400">Only active users can have referral programs</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-green-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-white font-medium">Set appropriate commission rates</p>
                                    <p class="text-gray-400">Typical rates range from 1% to 20% depending on your business model</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-green-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-white font-medium">Consider expiration dates</p>
                                    <p class="text-gray-400">Use expiration dates for temporary promotions or campaigns</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-white font-medium">Automatic code generation</p>
                                    <p class="text-gray-400">A unique referral code and link will be generated automatically</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-eye mr-2"></i>
                            Preview
                        </h3>

                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-md bg-gradient-to-br from-green-500 to-blue-600 flex items-center justify-center mr-3">
                                    <i class="fas fa-handshake text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-white" id="preview-user">Select a user above</div>
                                    <div class="text-sm text-gray-400" id="preview-rate">Commission: 5%</div>
                                </div>
                            </div>

                            <div class="text-sm text-gray-400" id="preview-status">Status: Active</div>
                            <div class="text-sm text-gray-400" id="preview-expiry">Expires: Never</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-700">
                <a
                    href="{{ route('admin.referrals.index') }}"
                    class="px-6 py-2 text-gray-400 hover:text-white transition-colors"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-2 rounded-lg transition-colors font-medium"
                >
                    <i class="fas fa-plus mr-2"></i>
                    Create Referral Program
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Update user preview
document.getElementById('user_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const userName = selectedOption.textContent;
    document.getElementById('preview-user').textContent =
        this.value ? userName : 'Select a user above';
});

// Update commission rate preview
document.getElementById('commission_rate').addEventListener('input', function() {
    const rate = this.value || '5';
    document.getElementById('preview-rate').textContent = `Commission: ${rate}%`;
});

// Update status preview
document.getElementById('is_active').addEventListener('change', function() {
    document.getElementById('preview-status').textContent =
        this.checked ? 'Status: Active' : 'Status: Inactive';
});

// Update expiry preview
document.getElementById('expires_at').addEventListener('change', function() {
    if (this.value) {
        const date = new Date(this.value).toLocaleDateString();
        document.getElementById('preview-expiry').textContent = `Expires: ${date}`;
    } else {
        document.getElementById('preview-expiry').textContent = 'Expires: Never';
    }
});
</script>
@endsection
