@extends('layouts.business')

@section('active-invitations', 'bg-blue-600')

@section('title', 'Review Invitations')
@section('page-title', 'Review Invitations')
@section('page-subtitle', 'Invite customers to leave reviews for your business')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Main Content Area -->
    <div class="lg:col-span-3">
        <!-- Plan Information Section -->
    <div class="bg-gray-900 border-l-4 border-yellow-500 text-gray-200 p-4 mb-6 rounded-r-lg shadow-md" role="alert">
        <div class="flex items-center">
            <div class="py-1">
                <i class="fas fa-info-circle text-2xl text-yellow-400 mr-4"></i>
            </div>
            <div>
                @if(isset($activePlan))
                    <p class="font-bold text-white">Current Plan: {{ $activePlan->name }}</p>

                    @if($activePlan->name === 'Free')
                        <p class="font-medium text-red-400">
                            Your Free plan does not include review invitations.
                            <a href="{{ route('plans.index') }}" class="underline text-yellow-400">Upgrade your plan</a> to start sending review invitations!
                        </p>
                    @else
                        <p class="text-gray-300">
                            Monthly Email Limit: <span class="font-semibold text-white">{{ $emailLimit }}</span> invitations
                            | Used This Month: <span class="font-semibold text-white">{{ $emailsUsed }}</span>
                            | Remaining: <span class="font-semibold text-white">{{ max(0, $emailLimit - $emailsUsed) }}</span>
                        </p>
                        @if($activePlan->name !== 'Premium')
                            <p class="mt-2 text-gray-300">Need to send more invitations? <a href="{{ route('plans.index') }}" class="underline text-yellow-400">Upgrade your plan</a> for increased limits!</p>
                        @endif
                    @endif
                @else
                    <p class="text-gray-300">Unable to determine your current plan. Please contact support.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h2 class="text-2xl font-semibold text-white">Your Review Invitations</h2>

        <div class="flex flex-col sm:flex-row gap-2">
            @if(isset($activePlan) && $activePlan->name !== 'Free' && isset($emailLimit) && isset($emailsUsed) && $emailLimit > $emailsUsed)
                <a href="{{ route('property.invitations.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-center shadow-lg">
                    <i class="fas fa-plus mr-2"></i> Send Individual Invitation
                </a>
                <button id="show-bulk-form" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-lg">
                    <i class="fas fa-file-upload mr-2"></i> Bulk Send Invitations
                </button>
            @elseif(isset($activePlan) && $activePlan->name === 'Free')
                <a href="{{ route('plans.index') }}" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors text-center shadow-lg">
                    <i class="fas fa-arrow-up mr-2"></i> Upgrade Plan to Send Review Invitations
                </a>
            @else
                <a href="{{ route('plans.index') }}" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors text-center shadow-lg">
                    <i class="fas fa-arrow-up mr-2"></i> Upgrade Plan to Send More Invitations
                </a>
            @endif
        </div>
    </div>

    <!-- Bulk Upload Form (Initially Hidden) -->
    <div id="bulk-upload-form" class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6 mb-6 hidden">
        <h3 class="text-lg font-medium text-white mb-4">Bulk Send Review Invitations</h3>
        <form action="{{ route('property.invitations.bulk.send') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="csv_file" class="block text-sm font-medium text-gray-300 mb-1">Upload CSV File</label>
                <input type="file" name="csv_file" id="csv_file" accept=".csv"
                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700"
                       required>
                <p class="mt-1 text-sm text-gray-400">
                    The CSV file should have the columns: name, email, message (optional)
                </p>
            </div>

            <div class="mb-4">
                <label for="default_message" class="block text-sm font-medium text-gray-300 mb-1">Default Message (optional)</label>
                <textarea name="default_message" id="default_message" rows="3"
                          class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                          placeholder="This will be used if no message is provided in the CSV file."></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" id="cancel-bulk" class="px-4 py-2 border border-gray-600 rounded-md text-gray-300 hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors shadow-lg">
                    <i class="fas fa-paper-plane mr-1"></i> Send Invitations
                </button>
            </div>
        </form>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-900 border-l-4 border-green-400 text-green-100 p-4 mb-6 rounded-r-lg shadow-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-900 border-l-4 border-red-400 text-red-100 p-4 mb-6 rounded-r-lg shadow-md" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Invitations List -->
    @if(isset($invitations) && count($invitations) > 0)
        <div class="bg-gray-800 border border-gray-700 shadow-lg rounded-lg overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Customer</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Sent Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Activity</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @foreach($invitations as $invitation)
                            <tr class="hover:bg-gray-750 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-white">{{ $invitation->customer_name }}</div>
                                    <div class="text-sm text-gray-400">{{ $invitation->customer_email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($invitation->status == 'sent') bg-blue-900 text-blue-200
                                        @elseif($invitation->status == 'opened') bg-yellow-900 text-yellow-200
                                        @elseif($invitation->status == 'clicked') bg-green-900 text-green-200
                                        @elseif($invitation->status == 'failed') bg-red-900 text-red-200
                                        @else bg-gray-700 text-gray-300 @endif">
                                        {{ ucfirst($invitation->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    {{ $invitation->sent_at ? date('Y-m-d', strtotime($invitation->sent_at)) : 'Not sent' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    @if($invitation->opened_at)
                                        <div><i class="fas fa-envelope-open text-yellow-400 mr-1"></i> Opened: {{ date('Y-m-d H:i', strtotime($invitation->opened_at)) }}</div>
                                    @endif

                                    @if($invitation->clicked_at)
                                        <div><i class="fas fa-mouse-pointer text-green-400 mr-1"></i> Clicked: {{ date('Y-m-d H:i', strtotime($invitation->clicked_at)) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('property.invitations.show', $invitation->id) }}" class="text-blue-400 hover:text-blue-300 mr-3 transition-colors">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <form action="{{ route('property.invitations.destroy', $invitation->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 transition-colors"
                                                onclick="return confirm('Are you sure you want to delete this invitation?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $invitations->links() }}
        </div>
    @else
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-8 text-center">
            <div class="text-gray-500 mb-4">
                <i class="fas fa-envelope-open-text text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">No Review Invitations Yet</h3>
            <p class="text-gray-400 mb-6">You haven't sent any review invitations yet.</p>
            @if(isset($activePlan) && $activePlan->name !== 'Free' && isset($emailLimit) && isset($emailsUsed) && $emailLimit > $emailsUsed)
                <a href="{{ route('property.invitations.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                    Send Your First Invitation
                </a>
            @else
                <a href="{{ route('plans.index') }}" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors shadow-lg">
                    Upgrade Plan to Send Invitations
                </a>
            @endif
        </div>
    @endif

    <!-- Information Section -->
    <div class="mt-8 bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-semibold text-white mb-4">About Review Invitations</h3>
        <div class="prose max-w-none">
            <p class="text-gray-300">Review invitations help you collect authentic reviews from your customers. Here's how it works:</p>
            <ol class="list-decimal pl-5 space-y-2 mt-3 text-gray-300">
                <li>Create personalized email invitations to send to your customers</li>
                <li>Customers receive an email with a link to leave a review</li>
                <li>Track which invitations have been opened and clicked</li>
                <li>Build a collection of genuine reviews to showcase your business</li>
            </ol>
            <p class="mt-4 text-gray-300">You can send individual invitations or upload a CSV file for bulk sending.</p>
            <div class="bg-yellow-900 border border-yellow-700 border-l-4 border-l-yellow-500 p-4 mt-4 rounded-r-lg">
                <p class="text-yellow-200">
                    <strong>Note:</strong> Only send invitations to customers who have actually used your services.
                    Unsolicited emails may violate spam regulations.
                </p>
            </div>
        </div>
    </div>

    <!-- Sample CSV Template Section -->
    <div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-medium text-white mb-2">CSV Template</h3>
        <p class="text-gray-400 mb-4">For bulk sending, use this CSV format. <a href="#" class="text-blue-400 hover:underline">Download template</a></p>

        <div class="bg-gray-700 p-4 rounded-lg overflow-x-auto">
            <table class="min-w-full border border-gray-600">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b border-r border-gray-600 bg-gray-600 text-gray-200">name</th>
                        <th class="px-4 py-2 border-b border-r border-gray-600 bg-gray-600 text-gray-200">email</th>
                        <th class="px-4 py-2 border-b border-gray-600 bg-gray-600 text-gray-200">message</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-2 border-b border-r border-gray-600 text-white">John Smith</td>
                        <td class="px-4 py-2 border-b border-r border-gray-600 text-white">john@example.com</td>
                        <td class="px-4 py-2 border-b border-gray-600 text-gray-300">Thanks for visiting our store! We'd love to hear about your experience.</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border-b border-r border-gray-600 text-white">Jane Doe</td>
                        <td class="px-4 py-2 border-b border-r border-gray-600 text-white">jane@example.com</td>
                        <td class="px-4 py-2 border-b border-gray-600 text-gray-300"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <!-- Right Sidebar -->
    <div class="lg:col-span-1">
        <!-- Invitation Statistics Card -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-chart-line mr-2 text-green-400"></i>
                Invitation Statistics
            </h3>
            <div class="space-y-4">
                <div class="bg-gray-700 rounded-lg p-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm text-gray-300">Total Sent</span>
                        <span class="text-sm font-medium text-blue-400">{{ isset($invitations) ? $invitations->where('status', 'sent')->count() : 0 }}</span>
                    </div>
                    <div class="w-full bg-gray-600 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ isset($invitations) && $invitations->count() > 0 ? ($invitations->where('status', 'sent')->count() / $invitations->count()) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div class="bg-gray-700 rounded-lg p-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm text-gray-300">Clicked</span>
                        <span class="text-sm font-medium text-green-400">{{ isset($invitations) ? $invitations->where('status', 'clicked')->count() : 0 }}</span>
                    </div>
                    <div class="w-full bg-gray-600 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ isset($invitations) && $invitations->count() > 0 ? ($invitations->where('status', 'clicked')->count() / $invitations->count()) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div class="bg-gray-700 rounded-lg p-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm text-gray-300">Success Rate</span>
                        <span class="text-sm font-medium text-yellow-400">
                            {{ isset($invitations) && $invitations->count() > 0 ? round(($invitations->where('status', 'clicked')->count() / $invitations->count()) * 100) : 0 }}%
                        </span>
                    </div>
                    <p class="text-xs text-gray-400">Click-through rate</p>
                </div>
            </div>
        </div>

        <!-- Invitation Best Practices -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-lightbulb mr-2 text-yellow-400"></i>
                Best Practices
            </h3>
            <div class="space-y-3">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-400 mr-3 mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="text-sm text-gray-300">Send invitations within 24-48 hours of service</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-400 mr-3 mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="text-sm text-gray-300">Personalize messages with customer names</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-400 mr-3 mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="text-sm text-gray-300">Keep messages brief and friendly</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-400 mr-3 mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="text-sm text-gray-300">Follow up if no response after 1 week</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-rocket mr-2 text-purple-400"></i>
                Quick Actions
            </h3>
            <div class="space-y-3">
                @if(isset($activePlan) && $activePlan->name !== 'Free' && isset($emailLimit) && isset($emailsUsed) && $emailLimit > $emailsUsed)
                    <a href="{{ route('property.invitations.create') }}"
                       class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Send New Invitation
                    </a>
                    <button id="show-bulk-form-btn" class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        <i class="fas fa-upload mr-2"></i>
                        Bulk Upload
                    </button>
                @else
                    <a href="{{ route('plans.index') }}"
                       class="w-full flex items-center justify-center px-4 py-3 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors">
                        <i class="fas fa-arrow-up mr-2"></i>
                        Upgrade Plan
                    </a>
                @endif
                <a href="{{ route('property.reviews') }}"
                   class="w-full flex items-center justify-center px-4 py-3 bg-gray-700 text-gray-300 rounded-md hover:bg-gray-600 transition-colors">
                    <i class="fas fa-star mr-2"></i>
                    View Reviews
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showBulkFormBtn = document.getElementById('show-bulk-form');
        const showBulkFormBtnSidebar = document.getElementById('show-bulk-form-btn');
        const cancelBulkBtn = document.getElementById('cancel-bulk');
        const bulkUploadForm = document.getElementById('bulk-upload-form');

        if (showBulkFormBtn) {
            showBulkFormBtn.addEventListener('click', function() {
                bulkUploadForm.classList.remove('hidden');
                showBulkFormBtn.classList.add('hidden');
            });
        }

        if (showBulkFormBtnSidebar) {
            showBulkFormBtnSidebar.addEventListener('click', function() {
                bulkUploadForm.classList.remove('hidden');
                if (showBulkFormBtn) showBulkFormBtn.classList.add('hidden');
            });
        }

        if (cancelBulkBtn) {
            cancelBulkBtn.addEventListener('click', function() {
                bulkUploadForm.classList.add('hidden');
                if (showBulkFormBtn) showBulkFormBtn.classList.remove('hidden');
            });
        }
    });
</script>
@endpush
