@extends('layouts.business')

@section('active-ads-manager', 'menu-item-active')
@section('title', 'Request Ad Promotion')

@section('page-title')
    Request Ad Promotion
@endsection

@section('page-subtitle', 'Request to feature your property on the homepage for specified dates.')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('property.dashboard') }}" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <a href="{{ route('property.ads.index') }}" class="text-gray-400 hover:text-white transition-colors">
                        Ads Manager
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">Request Promotion</span>
                </div>
            </li>
        </ol>
    </nav>

    @if($errors->any())
        <div class="bg-red-900 border border-red-700 text-red-300 p-4 rounded-lg mb-6">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span class="font-medium">Please fix the following errors:</span>
            </div>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Current Property Info -->
    <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
            <i class="fas fa-building text-blue-400 mr-2"></i>
            Property to be Promoted
        </h3>

        <div class="bg-gray-800/50 rounded-lg p-4">
            <div class="flex items-start space-x-4">
                <div class="w-20 h-20 bg-gray-700 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-gray-400"></i>
                </div>

                <div class="flex-1">
                    <h4 class="text-white font-medium">{{ $property->business_name }}</h4>
                    <p class="text-gray-400 text-sm">{{ $property->business_address }}</p>
                    <p class="text-gray-400 text-sm">{{ $property->city }}, {{ $property->province }}</p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('property.ads.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Promotion Period -->
        <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
                <i class="fas fa-calendar-alt text-blue-400 mr-2"></i>
                Promotion Period
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-300 mb-2">
                        Start Date <span class="text-red-400">*</span>
                    </label>
                    <input type="date"
                           name="start_date"
                           id="start_date"
                           value="{{ old('start_date') }}"
                           min="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           required>
                    <p class="text-gray-400 text-sm mt-1">When should the promotion start?</p>
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-300 mb-2">
                        End Date <span class="text-red-400">*</span>
                    </label>
                    <input type="date"
                           name="end_date"
                           id="end_date"
                           value="{{ old('end_date') }}"
                           min="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           required>
                    <p class="text-gray-400 text-sm mt-1">When should the promotion end?</p>
                </div>
            </div>

            <!-- Cost Calculator -->
            <div class="mt-6 p-4 bg-green-900/20 border border-green-700/50 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-green-300 font-medium">Promotion Cost</h4>
                        <p class="text-gray-400 text-sm">${{ number_format($dailyCost, 2) }} USD per day</p>
                    </div>
                    <div class="text-right">
                        <div class="text-green-300 text-sm">
                            <span id="total-days">0</span> days
                        </div>
                        <div class="text-green-300 text-2xl font-bold">
                            $<span id="total-cost">0.00</span> USD
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="bg-yellow-900/20 border border-yellow-700/50 rounded-xl p-6">
            <div class="flex items-start space-x-3">
                <i class="fas fa-credit-card text-yellow-400 mt-1"></i>
                <div>
                    <h4 class="text-yellow-300 font-medium mb-2">Payment Required</h4>
                    <ul class="text-gray-300 text-sm space-y-1">
                        <li>• Payment must be completed before your promotion request is submitted</li>
                        <li>• Cost is ${{ number_format($dailyCost, 2) }} USD per day for the selected promotion period</li>
                        <li>• Payment is processed securely through Genie Business Payment Gateway</li>
                        <li>• Refunds are available if your promotion is rejected by admins</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Information Note -->
        <div class="bg-blue-900/20 border border-blue-700/50 rounded-xl p-6">
            <div class="flex items-start space-x-3">
                <i class="fas fa-info-circle text-blue-400 mt-1"></i>
                <div>
                    <h4 class="text-blue-300 font-medium mb-2">How it works:</h4>
                    <ul class="text-gray-300 text-sm space-y-1">
                        <li>• Your property will be submitted for review by our admin team</li>
                        <li>• Once approved, your property will be featured on the homepage</li>
                        <li>• The promotion will be active during your selected dates</li>
                        <li>• You can track the status in your Ads Manager</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('property.ads.index') }}"
               class="px-6 py-3 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors">
                Cancel
            </a>
            <button type="submit"
                    id="submit-btn"
                    disabled
                    class="px-6 py-3 bg-gray-500 text-gray-300 rounded-lg cursor-not-allowed transition-colors flex items-center">
                <i class="fas fa-credit-card mr-2"></i>
                Pay & Submit Request
            </button>
        </div>
    </form>
</div>

<script>
// Calculate cost and enable/disable submit button
function calculateCost() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const submitBtn = document.getElementById('submit-btn');

    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);

        if (end >= start) {
            const timeDiff = end.getTime() - start.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // +1 to include both start and end days
            const dailyCost = {{ $dailyCost }};
            const totalCost = daysDiff * dailyCost;

            document.getElementById('total-days').textContent = daysDiff;
            document.getElementById('total-cost').textContent = totalCost.toFixed(2);

            // Enable submit button when valid dates are selected
            submitBtn.disabled = false;
            submitBtn.className = 'px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center';

        } else {
            // Reset if end date is before start date
            document.getElementById('total-days').textContent = '0';
            document.getElementById('total-cost').textContent = '0.00';
            submitBtn.disabled = true;
            submitBtn.className = 'px-6 py-3 bg-gray-500 text-gray-300 rounded-lg cursor-not-allowed transition-colors flex items-center';
        }
    } else {
        // Reset if dates are not selected
        document.getElementById('total-days').textContent = '0';
        document.getElementById('total-cost').textContent = '0.00';
        submitBtn.disabled = true;
        submitBtn.className = 'px-6 py-3 bg-gray-500 text-gray-300 rounded-lg cursor-not-allowed transition-colors flex items-center';
    }
}

// Ensure end date is after start date
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = this.value;
    const endDateInput = document.getElementById('end_date');
    if (startDate) {
        endDateInput.min = startDate;
        if (endDateInput.value && endDateInput.value < startDate) {
            endDateInput.value = startDate;
        }
    }
    calculateCost();
});

document.getElementById('end_date').addEventListener('change', function() {
    const endDate = this.value;
    const startDate = document.getElementById('start_date').value;
    if (startDate && endDate < startDate) {
        alert('End date must be after start date');
        this.value = startDate;
    }
    calculateCost();
});

// Initial calculation
calculateCost();
</script>
@endsection
