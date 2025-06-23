@extends('layouts.business')

@section('active-ads-manager', 'menu-item-active')
@section('title', 'Edit Promotion Request')

@section('page-title')
    Edit Promotion Request
@endsection

@section('page-subtitle', 'Update your property promotion dates.')

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
                    <span class="text-gray-300">Edit Request</span>
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

    <form action="{{ route('property.ads.update', $ad) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Current Status -->
        <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                Current Status
            </h3>

            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $ad->getStatusBadgeClass() }}">
                    @switch($ad->status)
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
                    @endswitch
                </span>
                <p class="text-gray-400 text-sm">
                    @if($ad->status === 'pending')
                        Your request is being reviewed by our admin team.
                    @elseif($ad->status === 'approved')
                        Your request has been approved and will be active during the specified dates.
                    @elseif($ad->status === 'active')
                        Your property is currently being promoted on the homepage.
                    @elseif($ad->status === 'rejected')
                        Your request was rejected. See details below.
                    @endif
                </p>
            </div>
        </div>

        <!-- Promotion Period -->
        <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
                <i class="fas fa-calendar-alt text-blue-400 mr-2"></i>
                Update Promotion Period
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-300 mb-2">
                        Start Date <span class="text-red-400">*</span>
                    </label>
                    <input type="date"
                           name="start_date"
                           id="start_date"
                           value="{{ old('start_date', $ad->start_date->format('Y-m-d')) }}"
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
                           value="{{ old('end_date', $ad->end_date->format('Y-m-d')) }}"
                           min="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           required>
                    <p class="text-gray-400 text-sm mt-1">When should the promotion end?</p>
                </div>
            </div>
        </div>

        <!-- Admin Notes/Rejection Reason -->
        @if($ad->admin_notes || $ad->rejection_reason)
            <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-comment text-blue-400 mr-2"></i>
                    Admin Feedback
                </h3>

                @if($ad->admin_notes)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-400 mb-2">Notes from Admin</label>
                        <div class="bg-gray-800 p-3 rounded-lg">
                            <p class="text-gray-300">{{ $ad->admin_notes }}</p>
                        </div>
                    </div>
                @endif

                @if($ad->rejection_reason)
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Rejection Reason</label>
                        <div class="bg-red-900/20 border border-red-700/50 p-3 rounded-lg">
                            <p class="text-red-300">{{ $ad->rejection_reason }}</p>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('property.ads.index') }}"
               class="px-6 py-3 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                <i class="fas fa-save mr-2"></i>
                Update Request
            </button>
        </div>
    </form>
</div>

<script>
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
});

document.getElementById('end_date').addEventListener('change', function() {
    const endDate = this.value;
    const startDate = document.getElementById('start_date').value;
    if (startDate && endDate < startDate) {
        alert('End date must be after start date');
        this.value = startDate;
    }
});
</script>
@endsection
