@extends('layouts.admin')

@section('active-claim-invitations', 'menu-item-active')
@section('page-title', 'Business Claim Details')
@section('page-subtitle', 'Review detailed information about this business claim request.')

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-blue-900 to-purple-900 border border-gray-700 rounded-xl shadow-xl p-6 mb-8">
    <div class="flex flex-col lg:flex-row items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-white mb-2">{{ $businessClaim->business_name }}</h2>
            <p class="text-blue-200">Claim submitted on {{ $businessClaim->created_at->format('F d, Y') }}</p>
        </div>
        <div class="mt-4 lg:mt-0 flex items-center space-x-4">
            <div class="text-center">
                @switch($businessClaim->status)
                    @case('pending')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-900 text-yellow-200">
                            <i class="fas fa-clock mr-2"></i>
                            Pending Review
                        </span>
                        @break
                    @case('approved')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-900 text-green-200">
                            <i class="fas fa-check mr-2"></i>
                            Approved
                        </span>
                        @break
                    @case('rejected')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-900 text-red-200">
                            <i class="fas fa-times mr-2"></i>
                            Rejected
                        </span>
                        @break
                @endswitch
            </div>
            <a href="{{ route('admin.business-claims.index') }}"
               class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Claims
            </a>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Business Information -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6">
            <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                <i class="fas fa-building text-blue-400 mr-3"></i>
                Business Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Business Name</label>
                    <div class="text-white bg-gray-700 px-4 py-3 rounded-lg">{{ $businessClaim->business_name }}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Business Email</label>
                    <div class="text-white bg-gray-700 px-4 py-3 rounded-lg">{{ $businessClaim->business_email }}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Property Type</label>
                    <div class="text-white bg-gray-700 px-4 py-3 rounded-lg flex items-center">
                        <i class="fas {{ $businessClaim->property_type === 'web' ? 'fa-globe' : 'fa-building' }} mr-2 text-blue-400"></i>
                        {{ ucfirst($businessClaim->property_type) }} Business
                    </div>
                </div>

                @if($businessClaim->domain)
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Website Domain</label>
                        <div class="text-white bg-gray-700 px-4 py-3 rounded-lg">
                            <a href="{{ $businessClaim->domain }}" target="_blank" class="text-blue-400 hover:text-blue-300">
                                {{ $businessClaim->domain }}
                                <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Personal Information -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6">
            <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                <i class="fas fa-user text-green-400 mr-3"></i>
                Claimant Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">First Name</label>
                    <div class="text-white bg-gray-700 px-4 py-3 rounded-lg">{{ $businessClaim->first_name }}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Last Name</label>
                    <div class="text-white bg-gray-700 px-4 py-3 rounded-lg">{{ $businessClaim->last_name }}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Country</label>
                    <div class="text-white bg-gray-700 px-4 py-3 rounded-lg">{{ $businessClaim->country }}</div>
                </div>

                @if($businessClaim->zip_code)
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">ZIP Code</label>
                        <div class="text-white bg-gray-700 px-4 py-3 rounded-lg">{{ $businessClaim->zip_code }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Business Details -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6">
            <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                <i class="fas fa-chart-line text-purple-400 mr-3"></i>
                Business Details
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($businessClaim->annual_revenue)
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Annual Revenue</label>
                        <div class="text-white bg-gray-700 px-4 py-3 rounded-lg">
                            @switch($businessClaim->annual_revenue)
                                @case('1-9999')
                                    $1 - $9,999
                                    @break
                                @case('10000-99999')
                                    $10,000 - $99,999
                                    @break
                                @case('100000-999999')
                                    $100,000 - $999,999
                                    @break
                                @case('1000000+')
                                    More than $1 million
                                    @break
                                @default
                                    {{ $businessClaim->annual_revenue }}
                            @endswitch
                        </div>
                    </div>
                @endif

                @if($businessClaim->employee_count)
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Employee Count</label>
                        <div class="text-white bg-gray-700 px-4 py-3 rounded-lg">{{ $businessClaim->employee_count }} employees</div>
                    </div>
                @endif

                @if($businessClaim->category_id)
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Category</label>
                        <div class="text-white bg-gray-700 px-4 py-3 rounded-lg">Category ID: {{ $businessClaim->category_id }}</div>
                    </div>
                @endif

                @if($businessClaim->subcategory_id)
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Subcategory</label>
                        <div class="text-white bg-gray-700 px-4 py-3 rounded-lg">Subcategory ID: {{ $businessClaim->subcategory_id }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Business Document -->
        @if($businessClaim->business_document)
            <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6">
                <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                    <i class="fas fa-file-alt text-yellow-400 mr-3"></i>
                    Business Document
                </h3>

                <div class="flex items-center justify-between bg-gray-700 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-file text-yellow-400 mr-3 text-xl"></i>
                        <div>
                            <div class="text-white font-medium">Business Document</div>
                            <div class="text-gray-400 text-sm">{{ basename($businessClaim->business_document) }}</div>
                        </div>
                    </div>
                    <a href="{{ Storage::url($businessClaim->business_document) }}"
                       target="_blank"
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-download mr-2"></i>
                        Download
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Property Information -->
        @if($businessClaim->property)
            <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt text-red-400 mr-3"></i>
                    Related Property
                </h3>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Property ID</label>
                        <div class="text-white">{{ $businessClaim->property->id }}</div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Current Name</label>
                        <div class="text-white">{{ $businessClaim->property->business_name }}</div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Current Status</label>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $businessClaim->property->status === 'Approved' ? 'bg-green-900 text-green-200' : 'bg-yellow-900 text-yellow-200' }}">
                            {{ $businessClaim->property->status }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Location</label>
                        <div class="text-white text-sm">{{ $businessClaim->property->city }}, {{ $businessClaim->property->country }}</div>
                    </div>
                </div>

                <a href="{{ route('admin.properties.show', $businessClaim->property) }}"
                   class="mt-4 inline-flex items-center text-blue-400 hover:text-blue-300 text-sm">
                    <i class="fas fa-external-link-alt mr-1"></i>
                    View Property Details
                </a>
            </div>
        @endif

        <!-- Review Information -->
        @if($businessClaim->reviewed_at)
            <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-user-check text-green-400 mr-3"></i>
                    Review Information
                </h3>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Reviewed By</label>
                        <div class="text-white">{{ $businessClaim->reviewer->name ?? 'Unknown Admin' }}</div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Reviewed At</label>
                        <div class="text-white">{{ $businessClaim->reviewed_at->format('M d, Y h:i A') }}</div>
                    </div>

                    @if($businessClaim->admin_notes)
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1">Admin Notes</label>
                            <div class="text-white bg-gray-700 p-3 rounded text-sm">{{ $businessClaim->admin_notes }}</div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Actions -->
        @if($businessClaim->status === 'pending')
            <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-tools text-yellow-400 mr-3"></i>
                    Actions
                </h3>

                <div class="space-y-3">
                    <button onclick="showApproveModal({{ $businessClaim->id }}, '{{ $businessClaim->business_name }}')"
                            class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-check mr-2"></i>
                        Approve Claim
                    </button>

                    <button onclick="showRejectModal({{ $businessClaim->id }}, '{{ $businessClaim->business_name }}')"
                            class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Reject Claim
                    </button>
                </div>
            </div>
        @endif

        <!-- Claim Details -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-info-circle text-blue-400 mr-3"></i>
                Claim Details
            </h3>

            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Submitted</label>
                    <div class="text-white">{{ $businessClaim->created_at->format('M d, Y h:i A') }}</div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Time Ago</label>
                    <div class="text-white">{{ $businessClaim->created_at->diffForHumans() }}</div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Claim ID</label>
                    <div class="text-white font-mono">#{{ $businessClaim->id }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include modals from index page -->
<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-gray-800 rounded-xl p-6 max-w-md w-full mx-4 border border-gray-700">
        <h3 class="text-lg font-semibold text-white mb-4">
            <i class="fas fa-check text-green-400 mr-2"></i>
            Approve Business Claim
        </h3>
        <p class="text-gray-300 mb-4">Are you sure you want to approve the claim for <span id="approveBusiness" class="font-medium text-white"></span>?</p>

        <form id="approveForm" method="POST">
            @csrf
            <div class="mb-4">
                <label for="approve_notes" class="block text-sm font-medium text-gray-300 mb-2">Admin Notes (Optional)</label>
                <textarea id="approve_notes" name="admin_notes" rows="3"
                          class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-green-500 focus:border-transparent"
                          placeholder="Add any notes about this approval..."></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal('approveModal')"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-check mr-1"></i>
                    Approve Claim
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-gray-800 rounded-xl p-6 max-w-md w-full mx-4 border border-gray-700">
        <h3 class="text-lg font-semibold text-white mb-4">
            <i class="fas fa-times text-red-400 mr-2"></i>
            Reject Business Claim
        </h3>
        <p class="text-gray-300 mb-4">Are you sure you want to reject the claim for <span id="rejectBusiness" class="font-medium text-white"></span>?</p>

        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label for="reject_notes" class="block text-sm font-medium text-gray-300 mb-2">Rejection Reason *</label>
                <textarea id="reject_notes" name="admin_notes" rows="3" required
                          class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                          placeholder="Please provide a reason for rejection..."></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal('rejectModal')"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-times mr-1"></i>
                    Reject Claim
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showApproveModal(claimId, businessName) {
    document.getElementById('approveBusiness').textContent = businessName;
    document.getElementById('approveForm').action = `/admin/business-claims/${claimId}/approve`;
    document.getElementById('approveModal').classList.remove('hidden');
}

function showRejectModal(claimId, businessName) {
    document.getElementById('rejectBusiness').textContent = businessName;
    document.getElementById('rejectForm').action = `/admin/business-claims/${claimId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    // Clear form data
    if (modalId === 'approveModal') {
        document.getElementById('approve_notes').value = '';
    } else if (modalId === 'rejectModal') {
        document.getElementById('reject_notes').value = '';
    }
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    const modals = ['approveModal', 'rejectModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (e.target === modal) {
            closeModal(modalId);
        }
    });
});
</script>
@endsection
