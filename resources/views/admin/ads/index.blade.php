@extends('layouts.admin')

@section('active-ads-manager', 'menu-item-active')
@section('page-title', 'Ads Manager')
@section('page-subtitle', 'Manage and review advertising campaigns from business owners.')

@section('content')
<div class="space-y-8">
    @if(session('success'))
        <div class="bg-green-900 border border-green-700 text-green-300 p-4 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-900 border border-red-700 text-red-300 p-4 rounded-lg mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-yellow-500/10 to-yellow-600/10 backdrop-blur-sm border border-yellow-500/20 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-200/70 text-sm font-medium">Pending Review</p>
                    <h3 class="text-2xl font-bold text-white">{{ $stats['pending'] }}</h3>
                </div>
                <div class="bg-yellow-500/20 p-3 rounded-xl">
                    <i class="fas fa-clock text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500/10 to-blue-600/10 backdrop-blur-sm border border-blue-500/20 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-200/70 text-sm font-medium">Approved</p>
                    <h3 class="text-2xl font-bold text-white">{{ $stats['approved'] }}</h3>
                </div>
                <div class="bg-blue-500/20 p-3 rounded-xl">
                    <i class="fas fa-check text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500/10 to-green-600/10 backdrop-blur-sm border border-green-500/20 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-200/70 text-sm font-medium">Active</p>
                    <h3 class="text-2xl font-bold text-white">{{ $stats['active'] }}</h3>
                </div>
                <div class="bg-green-500/20 p-3 rounded-xl">
                    <i class="fas fa-play-circle text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500/10 to-red-600/10 backdrop-blur-sm border border-red-500/20 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-200/70 text-sm font-medium">Rejected</p>
                    <h3 class="text-2xl font-bold text-white">{{ $stats['rejected'] }}</h3>
                </div>
                <div class="bg-red-500/20 p-3 rounded-xl">
                    <i class="fas fa-times text-red-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl">
        <div class="px-6 py-4 border-b border-gray-700">
            <nav class="flex space-x-8">
                <a href="{{ route('admin.ads.index', ['status' => 'pending']) }}"
                   class="pb-3 border-b-2 font-medium transition-colors duration-200 {{ $status === 'pending' ? 'border-yellow-500 text-yellow-400' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
                    <i class="fas fa-clock mr-2"></i>Pending ({{ $stats['pending'] }})
                </a>
                <a href="{{ route('admin.ads.index', ['status' => 'approved']) }}"
                   class="pb-3 border-b-2 font-medium transition-colors duration-200 {{ $status === 'approved' ? 'border-blue-500 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
                    <i class="fas fa-check mr-2"></i>Approved ({{ $stats['approved'] }})
                </a>
                <a href="{{ route('admin.ads.index', ['status' => 'active']) }}"
                   class="pb-3 border-b-2 font-medium transition-colors duration-200 {{ $status === 'active' ? 'border-green-500 text-green-400' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
                    <i class="fas fa-play-circle mr-2"></i>Active ({{ $stats['active'] }})
                </a>
                <a href="{{ route('admin.ads.index', ['status' => 'rejected']) }}"
                   class="pb-3 border-b-2 font-medium transition-colors duration-200 {{ $status === 'rejected' ? 'border-red-500 text-red-400' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
                    <i class="fas fa-times mr-2"></i>Rejected ({{ $stats['rejected'] }})
                </a>
                <a href="{{ route('admin.ads.index', ['status' => 'all']) }}"
                   class="pb-3 border-b-2 font-medium transition-colors duration-200 {{ $status === 'all' ? 'border-purple-500 text-purple-400' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
                    <i class="fas fa-list mr-2"></i>All
                </a>
            </nav>
        </div>

        <!-- Ads Table -->
        @if($ads->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Ad Details</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Business</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type & Placement</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Budget</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Performance</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($ads as $ad)
                        <tr class="hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-start space-x-3">
                                    @if($ad->image_path)
                                        <img src="{{ asset('storage/' . $ad->image_path) }}"
                                             alt="{{ $ad->title }}"
                                             class="w-12 h-12 rounded-lg object-cover border border-gray-600">
                                    @else
                                        <div class="w-12 h-12 bg-gray-700 rounded-lg flex items-center justify-center border border-gray-600">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="text-white font-medium">{{ Str::limit($ad->title, 30) }}</h4>
                                        <p class="text-gray-400 text-sm">{{ Str::limit($ad->description, 50) }}</p>
                                        <p class="text-gray-500 text-xs">Created {{ $ad->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <h4 class="text-white font-medium">{{ Str::limit($ad->property->business_name, 25) }}</h4>
                                    <p class="text-gray-400 text-sm">Property ID: {{ $ad->property_id }}</p>
                                    <p class="text-gray-500 text-xs">{{ $ad->property->business_email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100/10 text-blue-300 border border-blue-500/20">
                                        {{ ucfirst($ad->ad_type) }}
                                    </span>
                                    <div class="text-gray-400 text-sm">{{ ucfirst(str_replace('_', ' ', $ad->placement)) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $ad->getStatusBadgeClass() }}">
                                    {{ ucfirst($ad->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-300">
                                    <div>{{ $ad->start_date->format('M j, Y') }}</div>
                                    <div class="text-gray-400">to {{ $ad->end_date->format('M j, Y') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <div class="text-gray-300 font-medium">${{ number_format($ad->budget, 2) }}</div>
                                    <div class="text-gray-400">${{ number_format($ad->cost_per_click, 2) }} per click</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <div class="text-gray-300">{{ number_format($ad->total_views) }} views</div>
                                    <div class="text-gray-300">{{ number_format($ad->total_clicks) }} clicks</div>
                                    <div class="text-gray-400">{{ $ad->click_through_rate }}% CTR</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.ads.show', $ad) }}"
                                       class="text-blue-400 hover:text-blue-300 transition-colors"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($ad->status === 'pending')
                                        <button onclick="showApprovalModal({{ $ad->id }}, '{{ $ad->title }}')"
                                                class="text-green-400 hover:text-green-300 transition-colors"
                                                title="Approve Ad">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="showRejectionModal({{ $ad->id }}, '{{ $ad->title }}')"
                                                class="text-red-400 hover:text-red-300 transition-colors"
                                                title="Reject Ad">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif

                                    @if($ad->status === 'approved')
                                        <form action="{{ route('admin.ads.activate', $ad) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="text-green-400 hover:text-green-300 transition-colors"
                                                    title="Activate Ad">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($ad->status === 'active')
                                        <form action="{{ route('admin.ads.deactivate', $ad) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="text-orange-400 hover:text-orange-300 transition-colors"
                                                    title="Deactivate Ad">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                        </form>
                                    @endif


                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($ads->hasPages())
                <div class="px-6 py-4 border-t border-gray-700">
                    {{ $ads->appends(['status' => $status])->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-bullhorn text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">No ads found</h3>
                <p class="text-gray-400 mb-6 max-w-md mx-auto">
                    @if($status === 'pending')
                        No ads are currently pending review.
                    @elseif($status === 'approved')
                        No ads have been approved yet.
                    @elseif($status === 'active')
                        No ads are currently active.
                    @elseif($status === 'rejected')
                        No ads have been rejected.
                    @else
                        No ads have been submitted yet.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

<!-- Approval Modal -->
<div id="approvalModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-white mb-4">Approve Ad</h3>
        <p class="text-gray-300 mb-4">Are you sure you want to approve "<span id="approvalAdTitle"></span>"?</p>

        <form id="approvalForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label for="admin_notes" class="block text-sm font-medium text-gray-300 mb-2">
                    Admin Notes (Optional)
                </label>
                <textarea name="admin_notes"
                          id="admin_notes"
                          rows="3"
                          class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                          placeholder="Add any notes about this approval..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideApprovalModal()"
                        class="px-4 py-2 border border-gray-600 text-gray-300 rounded-md hover:bg-gray-700">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">
                    Approve Ad
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-white mb-4">Reject Ad</h3>
        <p class="text-gray-300 mb-4">Provide a reason for rejecting "<span id="rejectionAdTitle"></span>":</p>

        <form id="rejectionForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label for="rejection_reason" class="block text-sm font-medium text-gray-300 mb-2">
                    Rejection Reason <span class="text-red-400">*</span>
                </label>
                <textarea name="rejection_reason"
                          id="rejection_reason"
                          rows="4"
                          class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                          placeholder="Explain why this ad is being rejected..."
                          required></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideRejectionModal()"
                        class="px-4 py-2 border border-gray-600 text-gray-300 rounded-md hover:bg-gray-700">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                    Reject Ad
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showApprovalModal(adId, adTitle) {
    document.getElementById('approvalAdTitle').textContent = adTitle;
    document.getElementById('approvalForm').action = `/admin/ads/${adId}/approve`;
    document.getElementById('approvalModal').classList.remove('hidden');
    document.getElementById('approvalModal').classList.add('flex');
}

function hideApprovalModal() {
    document.getElementById('approvalModal').classList.add('hidden');
    document.getElementById('approvalModal').classList.remove('flex');
    document.getElementById('admin_notes').value = '';
}

function showRejectionModal(adId, adTitle) {
    document.getElementById('rejectionAdTitle').textContent = adTitle;
    document.getElementById('rejectionForm').action = `/admin/ads/${adId}/reject`;
    document.getElementById('rejectionModal').classList.remove('hidden');
    document.getElementById('rejectionModal').classList.add('flex');
}

function hideRejectionModal() {
    document.getElementById('rejectionModal').classList.add('hidden');
    document.getElementById('rejectionModal').classList.remove('flex');
    document.getElementById('rejection_reason').value = '';
}

// Close modals when clicking outside
document.getElementById('approvalModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideApprovalModal();
    }
});

document.getElementById('rejectionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideRejectionModal();
    }
});
</script>
@endpush
@endsection
