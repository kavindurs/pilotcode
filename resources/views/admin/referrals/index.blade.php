@extends('layouts.admin')

@section('active-referrals', 'menu-item-active')
@section('page-title', 'Referrals Management')
@section('page-subtitle', 'Manage all referral programs, activate, deactivate, edit or delete them.')

@section('content')
<div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-white">Referrals</h2>
                <p class="text-gray-400 text-sm">Total: {{ $referrals->total() }} referral programs</p>
            </div>

            <!-- Search & Filter Form -->
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.referrals.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Referral
                </a>

                <form method="GET" action="{{ route('admin.referrals.index') }}" class="flex items-center gap-2">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search referrals..."
                            class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg pl-10 pr-4 py-2 w-64 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>

                    <select name="status" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="expired" {{ $status == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                    @if($search || $status)
                        <a href="{{ route('admin.referrals.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-600 text-white px-6 py-3 border-b border-gray-700">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Global Referral Rate Section -->
    <div class="px-6 py-4 border-b border-gray-700 bg-gray-750">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center mr-3">
                    <i class="fas fa-percentage text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Global Referral Rate</h3>
                    <p class="text-gray-400 text-sm">Current system-wide referral commission rate</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-2xl font-bold text-green-400">{{ $referralRate ? number_format($referralRate->rate, 2) : '10.00' }}%</div>
                    <div class="text-gray-400 text-sm">Default Rate</div>
                </div>
                <button onclick="showEditRateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Rate
                </button>
            </div>
        </div>
    </div>

    @if($referrals->count() > 0)
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-750 border-b border-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Referral Code</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Commission Rate</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Statistics</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Expires</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($referrals as $referral)
                        <tr class="bg-gray-800 hover:bg-gray-750 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-white">{{ $referral->user->name }}</div>
                                        <div class="text-sm text-gray-400">{{ $referral->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-mono">{{ $referral->referral_code }}</span>
                                    <button onclick="copyToClipboard('{{ $referral->referral_code }}')" class="ml-2 text-gray-400 hover:text-white">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-green-400 font-medium">{{ $referral->commission_rate }}%</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <div class="text-white">{{ $referral->total_referrals }} referrals</div>
                                    <div class="text-gray-400">${{ number_format($referral->total_earnings, 2) }} earned</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($referral->is_active)
                                    @if($referral->expires_at && $referral->expires_at->isPast())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-600 text-white">
                                            <i class="fas fa-clock mr-1"></i>
                                            Expired
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-600 text-white">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Active
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-600 text-white">
                                        <i class="fas fa-pause-circle mr-1"></i>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-300">
                                    @if($referral->expires_at)
                                        {{ $referral->expires_at->format('M d, Y') }}
                                    @else
                                        <span class="text-green-400">Never</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.referrals.show', $referral->id) }}"
                                       class="text-blue-400 hover:text-blue-300 transition-colors p-1"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.referrals.edit', $referral->id) }}"
                                       class="text-yellow-400 hover:text-yellow-300 transition-colors p-1"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($referral->is_active)
                                        <form method="POST" action="{{ route('admin.referrals.deactivate', $referral->id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-orange-400 hover:text-orange-300 transition-colors p-1"
                                                    title="Deactivate"
                                                    onclick="return confirm('Are you sure you want to deactivate this referral?')">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.referrals.activate', $referral->id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-green-400 hover:text-green-300 transition-colors p-1"
                                                    title="Activate">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.referrals.destroy', $referral->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-400 hover:text-red-300 transition-colors p-1"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this referral? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-700">
            {{ $referrals->appends(request()->query())->links('admin.partials.pagination') }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-700 mb-4">
                <i class="fas fa-handshake text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-white mb-2">No Referrals Found</h3>
            <p class="text-gray-400 mb-6">
                @if($search || $status)
                    No referrals match your current search criteria.
                @else
                    Start by creating your first referral program.
                @endif
            </p>
            @if(!$search && !$status)
                <a href="{{ route('admin.referrals.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Create First Referral
                </a>
            @else
                <a href="{{ route('admin.referrals.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-list mr-2"></i>
                    View All Referrals
                </a>
            @endif
        </div>
    @endif
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show a temporary notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        notification.textContent = 'Referral code copied!';
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 2000);
    });
}

function showEditRateModal() {
    document.getElementById('editRateModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function hideEditRateModal() {
    document.getElementById('editRateModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('editRateModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideEditRateModal();
            }
        });
    }
});
</script>

<!-- Edit Referral Rate Modal -->
<div id="editRateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-2xl max-w-md w-full">
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700 rounded-t-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-percentage mr-2"></i>
                    Edit Global Referral Rate
                </h3>
                <button onclick="hideEditRateModal()" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.referrals.update-rate') }}" class="p-6">
            @csrf
            <div class="mb-6">
                <label for="rate" class="block text-sm font-medium text-gray-300 mb-2">
                    Referral Commission Rate (%)
                </label>
                <input
                    type="number"
                    id="rate"
                    name="rate"
                    value="{{ $referralRate ? $referralRate->rate : '10.00' }}"
                    min="0"
                    max="100"
                    step="0.01"
                    required
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="10.00"
                >
                <p class="text-gray-500 text-sm mt-2">This rate applies to all new referrals. Existing referrals keep their individual rates.</p>
            </div>

            <div class="flex items-center justify-end gap-4">
                <button
                    type="button"
                    onclick="hideEditRateModal()"
                    class="px-6 py-2 text-gray-400 hover:text-white transition-colors"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors font-medium"
                >
                    <i class="fas fa-save mr-2"></i>
                    Update Rate
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
