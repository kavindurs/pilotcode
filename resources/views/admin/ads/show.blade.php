@extends('layouts.admin')

@section('active-ads-manager', 'menu-item-active')
@section('page-title', 'Ad Details')
@section('page-subtitle', 'Review and manage ad request details.')

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

    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.ads.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-600 hover:text-white transition-all duration-300">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Ads Manager
        </a>

        <!-- Action Buttons -->
        <div class="flex gap-2">
            @if($ad->status === 'pending')
                <form action="{{ route('admin.ads.approve', $ad) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-300 flex items-center">
                        <i class="fas fa-check mr-2"></i>
                        Approve
                    </button>
                </form>
                <form action="{{ route('admin.ads.reject', $ad) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-300 flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Reject
                    </button>
                </form>
            @elseif($ad->status === 'approved')
                <form action="{{ route('admin.ads.activate', $ad) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-300 flex items-center">
                        <i class="fas fa-play mr-2"></i>
                        Activate
                    </button>
                </form>
            @elseif($ad->status === 'active')
                <form action="{{ route('admin.ads.deactivate', $ad) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors duration-300 flex items-center">
                        <i class="fas fa-pause mr-2"></i>
                        Deactivate
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Ad Details Card -->
    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column - Basic Information -->
            <div class="space-y-6">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-6">Ad Information</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Title</label>
                            <div class="bg-gray-700/50 border border-gray-600 rounded-lg p-3 text-white">
                                {{ $ad->title }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                            <div class="bg-gray-700/50 border border-gray-600 rounded-lg p-3 text-white min-h-[100px]">
                                {{ $ad->description }}
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Start Date</label>
                                <div class="bg-gray-700/50 border border-gray-600 rounded-lg p-3 text-white">
                                    {{ $ad->start_date ? \Carbon\Carbon::parse($ad->start_date)->format('M d, Y') : 'Not set' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">End Date</label>
                                <div class="bg-gray-700/50 border border-gray-600 rounded-lg p-3 text-white">
                                    {{ $ad->end_date ? \Carbon\Carbon::parse($ad->end_date)->format('M d, Y') : 'Not set' }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                            <div class="flex items-center">
                                @if($ad->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-900 text-yellow-300 border border-yellow-600">
                                        <i class="fas fa-clock mr-2"></i>
                                        Pending Review
                                    </span>
                                @elseif($ad->status === 'approved')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-900 text-blue-300 border border-blue-600">
                                        <i class="fas fa-check mr-2"></i>
                                        Approved
                                    </span>
                                @elseif($ad->status === 'active')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-900 text-green-300 border border-green-600">
                                        <i class="fas fa-play mr-2"></i>
                                        Active
                                    </span>
                                @elseif($ad->status === 'rejected')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-900 text-red-300 border border-red-600">
                                        <i class="fas fa-times mr-2"></i>
                                        Rejected
                                    </span>
                                @elseif($ad->status === 'paused')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-700 text-gray-300 border border-gray-600">
                                        <i class="fas fa-pause mr-2"></i>
                                        Paused
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Created</label>
                            <div class="bg-gray-700/50 border border-gray-600 rounded-lg p-3 text-white">
                                {{ $ad->created_at->format('M d, Y \a\t g:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Business and Media -->
            <div class="space-y-6">
                <!-- Business Information -->
                <div>
                    <h3 class="text-xl font-bold text-white mb-4">Business Information</h3>
                    <div class="bg-gray-700/30 border border-gray-600 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-building text-white text-lg"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-semibold">{{ $ad->property->business_name }}</h4>
                                <p class="text-gray-300 text-sm">{{ $ad->property->business_email }}</p>
                            </div>
                        </div>

                        <div class="space-y-2 text-sm">
                            @if($ad->property->business_phone)
                                <div class="flex items-center text-gray-300">
                                    <i class="fas fa-phone mr-2 w-4"></i>
                                    {{ $ad->property->business_phone }}
                                </div>
                            @endif
                            @if($ad->property->business_address)
                                <div class="flex items-center text-gray-300">
                                    <i class="fas fa-map-marker-alt mr-2 w-4"></i>
                                    {{ $ad->property->business_address }}
                                </div>
                            @endif
                            @if($ad->property->business_website)
                                <div class="flex items-center text-gray-300">
                                    <i class="fas fa-globe mr-2 w-4"></i>
                                    <a href="{{ $ad->property->business_website }}" target="_blank" class="text-blue-400 hover:text-blue-300">
                                        {{ $ad->property->business_website }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Ad Media -->
                @if($ad->image_path)
                <div>
                    <h3 class="text-xl font-bold text-white mb-4">Ad Media</h3>
                    <div class="bg-gray-700/30 border border-gray-600 rounded-lg p-4">
                        <img src="{{ Storage::url($ad->image_path) }}"
                             alt="{{ $ad->title }}"
                             class="w-full h-48 object-cover rounded-lg">
                    </div>
                </div>
                @endif

                <!-- Target URL -->
                @if($ad->target_url)
                <div>
                    <h3 class="text-xl font-bold text-white mb-4">Target URL</h3>
                    <div class="bg-gray-700/30 border border-gray-600 rounded-lg p-4">
                        <a href="{{ $ad->target_url }}" target="_blank" class="text-blue-400 hover:text-blue-300 break-all">
                            {{ $ad->target_url }}
                        </a>
                    </div>
                </div>
                @endif

                <!-- Ad Performance (if active) -->
                @if($ad->status === 'active')
                <div>
                    <h3 class="text-xl font-bold text-white mb-4">Performance</h3>
                    <div class="bg-gray-700/30 border border-gray-600 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-blue-400">{{ $ad->clicks ?? 0 }}</div>
                                <div class="text-gray-300 text-sm">Clicks</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-green-400">{{ $ad->impressions ?? 0 }}</div>
                                <div class="text-gray-300 text-sm">Impressions</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>    <!-- Admin Notes Section -->
    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-6">
        <h3 class="text-xl font-bold text-white mb-4">Admin Notes</h3>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Internal Notes (not visible to business owner)
            </label>
            <div class="w-full bg-gray-700/50 border border-gray-600 rounded-lg px-3 py-2 text-white min-h-[100px]">
                {{ $ad->admin_notes ?: 'No admin notes added yet.' }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-save functionality for admin notes
    let noteTimeout;
    document.getElementById('admin_notes').addEventListener('input', function() {
        clearTimeout(noteTimeout);
        noteTimeout = setTimeout(() => {
            // You can implement auto-save functionality here if needed
        }, 2000);
    });
</script>
@endpush
