@extends('layouts.admin')

@section('active-claim-invitations', 'menu-item-active')
@section('page-title', 'Claim Invitations')
@section('page-subtitle', 'Manage business claim requests and approve or reject property ownership claims.')

@section('content')
<!-- Header with Statistics -->
<div class="bg-gradient-to-r from-blue-900 to-purple-900 border border-gray-700 rounded-xl shadow-xl p-6 mb-8">
    <div class="flex flex-col lg:flex-row items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-white mb-2">Business Claim Invitations</h2>
            <p class="text-blue-200">Review and manage business ownership claim requests.</p>
        </div>
        <div class="mt-4 lg:mt-0 grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="text-center">
                <div class="text-3xl font-bold text-white">{{ $claims->whereIn('status', ['pending', 'Pending'])->count() }}</div>
                <div class="text-blue-200 text-sm">Pending</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-white">{{ $claims->whereIn('status', ['approved', 'Approved'])->count() }}</div>
                <div class="text-green-200 text-sm">Approved</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-white">{{ $claims->whereIn('status', ['claimed', 'Claimed'])->count() }}</div>
                <div class="text-purple-200 text-sm">Claimed</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-white">{{ $claims->whereIn('status', ['rejected', 'Rejected'])->count() }}</div>
                <div class="text-red-200 text-sm">Rejected</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-white">{{ $claims->total() }}</div>
                <div class="text-blue-200 text-sm">Total Claims</div>
            </div>
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

<!-- Claims Table -->
<div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
    <div class="p-6 border-b border-gray-700">
        <h3 class="text-xl font-semibold text-white flex items-center">
            <i class="fas fa-hand-holding-heart text-blue-400 mr-3"></i>
            Business Claim Requests
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-900">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Business</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Claimant</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Submitted</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                @forelse($claims as $claim)
                    <tr class="hover:bg-gray-700 transition-colors duration-200">
                        <!-- Business Info -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-sm mr-3">
                                    {{ substr($claim->business_name, 0, 2) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-white">{{ $claim->business_name }}</div>
                                    <div class="text-sm text-gray-400">{{ $claim->business_email }}</div>
                                    @if($claim->property)
                                        <div class="text-xs text-blue-400">Property ID: {{ $claim->property->id }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Claimant Info -->
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-white">{{ $claim->first_name }} {{ $claim->last_name }}</div>
                            <div class="text-sm text-gray-400">{{ $claim->country }}</div>
                            @if($claim->zip_code)
                                <div class="text-xs text-gray-500">ZIP: {{ $claim->zip_code }}</div>
                            @endif
                        </td>

                        <!-- Type -->
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $claim->property_type === 'web' ? 'bg-blue-900 text-blue-200' : 'bg-green-900 text-green-200' }}">
                                <i class="fas {{ $claim->property_type === 'web' ? 'fa-globe' : 'fa-building' }} mr-1"></i>
                                {{ ucfirst($claim->property_type) }}
                            </span>
                            @if($claim->property_type === 'web' && $claim->domain)
                                <div class="text-xs text-gray-400 mt-1">{{ $claim->domain }}</div>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">
                            @switch($claim->status)
                                @case('pending')
                                @case('Pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900 text-yellow-200">
                                        <i class="fas fa-clock mr-1"></i>
                                        Pending
                                    </span>
                                    @break
                                @case('approved')
                                @case('Approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-200">
                                        <i class="fas fa-check mr-1"></i>
                                        Approved
                                    </span>
                                    @break
                                @case('rejected')
                                @case('Rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-200">
                                        <i class="fas fa-times mr-1"></i>
                                        Rejected
                                    </span>
                                    @break
                                @case('claimed')
                                @case('Claimed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-900 text-purple-200">
                                        <i class="fas fa-handshake mr-1"></i>
                                        Claimed
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-900 text-gray-200">
                                        {{ ucfirst($claim->status) }}
                                    </span>
                            @endswitch
                        </td>

                        <!-- Submitted -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-white">{{ $claim->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $claim->created_at->format('h:i A') }}</div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <!-- View Details -->
                                <a href="{{ route('admin.business-claims.show', $claim) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-md transition-colors"
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($claim->status === 'pending' || $claim->status === 'Pending')
                                    <!-- Approve Button -->
                                    <button onclick="showApproveModal({{ $claim->id }}, '{{ $claim->business_name }}')"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-green-600 hover:bg-green-700 text-white text-xs rounded-md transition-colors"
                                            title="Approve Claim">
                                        <i class="fas fa-check"></i>
                                    </button>

                                    <!-- Reject Button -->
                                    <button onclick="showRejectModal({{ $claim->id }}, '{{ $claim->business_name }}')"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white text-xs rounded-md transition-colors"
                                            title="Reject Claim">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif

                                <!-- Delete Button -->
                                <button onclick="showDeleteModal({{ $claim->id }}, '{{ $claim->business_name }}')"
                                        class="inline-flex items-center justify-center w-8 h-8 bg-gray-600 hover:bg-red-600 text-white text-xs rounded-md transition-colors"
                                        title="Delete Claim">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p class="text-lg font-medium mb-2">No business claims found</p>
                                <p class="text-sm">Business claim requests will appear here when submitted.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($claims->hasPages())
        <div class="px-6 py-4 border-t border-gray-700">
            {{ $claims->links() }}
        </div>
    @endif
</div>

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

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-gray-800 rounded-xl p-6 max-w-md w-full mx-4 border border-gray-700">
        <h3 class="text-lg font-semibold text-white mb-4">
            <i class="fas fa-trash text-red-400 mr-2"></i>
            Delete Business Claim
        </h3>
        <p class="text-gray-300 mb-4">Are you sure you want to permanently delete the claim for <span id="deleteBusiness" class="font-medium text-white"></span>?</p>
        <p class="text-red-300 text-sm mb-4">This action cannot be undone.</p>

        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal('deleteModal')"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-1"></i>
                    Delete Claim
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

function showDeleteModal(claimId, businessName) {
    document.getElementById('deleteBusiness').textContent = businessName;
    document.getElementById('deleteForm').action = `/admin/business-claims/${claimId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
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
    const modals = ['approveModal', 'rejectModal', 'deleteModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (e.target === modal) {
            closeModal(modalId);
        }
    });
});
</script>
@endsection
