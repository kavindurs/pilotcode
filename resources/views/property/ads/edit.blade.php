@extends('layouts.business')

@section('active-ads-manager', 'menu-item-active')
@section('title', 'Edit Ad')

@section('page-title')
    Edit Ad
@endsection

@section('page-subtitle', 'Update your advertising campaign details.')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
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
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <a href="{{ route('property.ads.show', $ad) }}" class="text-gray-400 hover:text-white transition-colors">
                        {{ Str::limit($ad->title, 20) }}
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

    <!-- Current Status Alert -->
    <div class="bg-yellow-900/20 border border-yellow-500/30 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-yellow-400 mr-2 mt-0.5"></i>
            <div class="text-sm">
                <p class="text-yellow-300 font-medium">Note about editing</p>
                <p class="text-yellow-200/80 mt-1">
                    When you update this ad, it will be resubmitted for review and approval.
                    The status will change to "pending" until reviewed by our admin team.
                </p>
            </div>
        </div>
    </div>

    <form action="{{ route('property.ads.update', $ad) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Ad Information -->
        <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
                <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                Ad Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-300 mb-2">
                        Ad Title <span class="text-red-400">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title', $ad->title) }}"
                           class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="Enter a compelling title for your ad"
                           maxlength="255"
                           required>
                    <p class="text-gray-400 text-sm mt-1">Maximum 255 characters</p>
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                        Ad Description <span class="text-red-400">*</span>
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                              placeholder="Describe your ad and what makes your business special"
                              maxlength="1000"
                              required>{{ old('description', $ad->description) }}</textarea>
                    <p class="text-gray-400 text-sm mt-1">Maximum 1000 characters</p>
                </div>

                <div class="md:col-span-2">
                    <label for="target_url" class="block text-sm font-medium text-gray-300 mb-2">
                        Target URL <span class="text-gray-500">(Optional)</span>
                    </label>
                    <input type="url"
                           name="target_url"
                           id="target_url"
                           value="{{ old('target_url', $ad->target_url) }}"
                           class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="https://example.com (where users will go when they click your ad)">
                    <p class="text-gray-400 text-sm mt-1">Optional: URL where users will be redirected when they click your ad</p>
                </div>

                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-300 mb-2">
                        Ad Image <span class="text-gray-500">(Optional)</span>
                    </label>

                    @if($ad->image_path)
                        <div class="mb-4 p-4 bg-gray-800 rounded-lg">
                            <p class="text-gray-300 text-sm mb-2">Current image:</p>
                            <img src="{{ asset('storage/' . $ad->image_path) }}"
                                 alt="{{ $ad->title }}"
                                 class="w-32 h-32 rounded-lg object-cover border border-gray-600">
                        </div>
                    @endif

                    <div class="flex items-center justify-center w-full">
                        <label for="image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-700 border-dashed rounded-lg cursor-pointer bg-gray-800 hover:bg-gray-750 transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                <p class="mb-2 text-sm text-gray-400">
                                    <span class="font-semibold">Click to upload new image</span> or drag and drop
                                </p>
                                <p class="text-xs text-gray-400">PNG, JPG, GIF up to 2MB</p>
                                @if($ad->image_path)
                                    <p class="text-xs text-gray-500 mt-1">(Leave empty to keep current image)</p>
                                @endif
                            </div>
                            <input id="image" name="image" type="file" class="hidden" accept="image/*">
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ad Configuration -->
        <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
                <i class="fas fa-cogs text-purple-400 mr-2"></i>
                Ad Configuration
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="ad_type" class="block text-sm font-medium text-gray-300 mb-2">
                        Ad Type <span class="text-red-400">*</span>
                    </label>
                    <select name="ad_type"
                            id="ad_type"
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            required>
                        <option value="">Select ad type</option>
                        <option value="banner" {{ old('ad_type', $ad->ad_type) === 'banner' ? 'selected' : '' }}>Banner Ad</option>
                        <option value="featured" {{ old('ad_type', $ad->ad_type) === 'featured' ? 'selected' : '' }}>Featured Listing</option>
                        <option value="promoted" {{ old('ad_type', $ad->ad_type) === 'promoted' ? 'selected' : '' }}>Promoted Content</option>
                    </select>
                </div>

                <div>
                    <label for="placement" class="block text-sm font-medium text-gray-300 mb-2">
                        Ad Placement <span class="text-red-400">*</span>
                    </label>
                    <select name="placement"
                            id="placement"
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            required>
                        <option value="">Select placement</option>
                        <option value="homepage" {{ old('placement', $ad->placement) === 'homepage' ? 'selected' : '' }}>Homepage</option>
                        <option value="category" {{ old('placement', $ad->placement) === 'category' ? 'selected' : '' }}>Category Pages</option>
                        <option value="search_results" {{ old('placement', $ad->placement) === 'search_results' ? 'selected' : '' }}>Search Results</option>
                        <option value="property_details" {{ old('placement', $ad->placement) === 'property_details' ? 'selected' : '' }}>Property Details</option>
                    </select>
                </div>

                <div>
                    <label for="budget" class="block text-sm font-medium text-gray-300 mb-2">
                        Total Budget <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400">$</span>
                        </div>
                        <input type="number"
                               name="budget"
                               id="budget"
                               value="{{ old('budget', $ad->budget) }}"
                               step="0.01"
                               min="0"
                               max="10000"
                               class="w-full pl-8 pr-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               placeholder="0.00"
                               required>
                    </div>
                    <p class="text-gray-400 text-sm mt-1">Maximum budget for this campaign</p>
                </div>

                <div>
                    <label for="cost_per_click" class="block text-sm font-medium text-gray-300 mb-2">
                        Cost Per Click <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400">$</span>
                        </div>
                        <input type="number"
                               name="cost_per_click"
                               id="cost_per_click"
                               value="{{ old('cost_per_click', $ad->cost_per_click) }}"
                               step="0.01"
                               min="0"
                               max="100"
                               class="w-full pl-8 pr-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               placeholder="0.00"
                               required>
                    </div>
                    <p class="text-gray-400 text-sm mt-1">Amount you'll pay for each click</p>
                </div>
            </div>
        </div>

        <!-- Campaign Duration -->
        <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
                <i class="fas fa-calendar-alt text-green-400 mr-2"></i>
                Campaign Duration
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
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-300 mb-2">
                        End Date <span class="text-red-400">*</span>
                    </label>
                    <input type="date"
                           name="end_date"
                           id="end_date"
                           value="{{ old('end_date', $ad->end_date->format('Y-m-d')) }}"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           required>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-end">
            <a href="{{ route('property.ads.show', $ad) }}"
               class="inline-flex items-center justify-center px-6 py-3 border border-gray-600 text-gray-300 font-medium rounded-lg hover:bg-gray-800 transition-all duration-200">
                <i class="fas fa-times mr-2"></i>
                Cancel
            </a>
            <button type="submit"
                    class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-save mr-2"></i>
                Update Ad
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update end date minimum when start date changes
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    startDateInput.addEventListener('change', function() {
        const startDate = new Date(this.value);
        const minEndDate = new Date(startDate);
        minEndDate.setDate(minEndDate.getDate() + 1);

        endDateInput.min = minEndDate.toISOString().split('T')[0];

        // Clear end date if it's before the new minimum
        if (endDateInput.value && new Date(endDateInput.value) <= startDate) {
            endDateInput.value = '';
        }
    });

    // File upload preview
    const imageInput = document.getElementById('image');
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // You can add image preview functionality here if needed
                console.log('Image selected:', file.name);
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush
@endsection
