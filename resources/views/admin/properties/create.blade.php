@extends('layouts.admin')

@section('active-properties', 'menu-item-active')
@section('page-title', 'Add New Property')
@section('page-subtitle', 'Create a new property in the system.')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-gray-800 border border-gray-700 shadow-xl rounded-xl p-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-semibold text-white flex items-center">
                <i class="fas fa-plus-circle text-red-400 mr-3"></i>
                Add New Property
            </h2>
            <a href="{{ route('admin.properties.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Properties
            </a>
        </div>

        <form action="{{ route('admin.properties.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
    @csrf

    <!-- Property Type & Basic Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Property Type *</label>
            <select name="property_type" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">Select Property Type</option>
                <option value="web" {{ old('property_type') === 'web' ? 'selected' : '' }}>Web</option>
                <option value="physical" {{ old('property_type') === 'physical' ? 'selected' : '' }}>Physical</option>
            </select>
        </div>
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Status *</label>
            <select name="status" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <option value="Not Approved & Not Claimed" {{ old('status') === 'Not Approved & Not Claimed' ? 'selected' : '' }}>Not Approved & Not Claimed</option>
                @if($canSetApproved)
                    <option value="Approved" {{ old('status') === 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Not Claimed" {{ old('status') === 'Not Claimed' ? 'selected' : '' }}>Not Claimed</option>
                @endif
            </select>
            @if(!$canSetApproved)
                <p class="text-gray-400 text-xs mt-1">Only admin and super_admin can set status to 'Approved' or 'Not Claimed'</p>
            @endif
        </div>
    </div>

    <!-- Business Information -->
    <div class="border-t border-gray-600 pt-6">
        <h3 class="text-lg font-medium text-white mb-4 flex items-center">
            <i class="fas fa-building text-blue-400 mr-2"></i>
            Business Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Business Name *</label>
                <input type="text" name="business_name" value="{{ old('business_name') }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Business Email *</label>
                <input type="email" name="business_email" value="{{ old('business_email') }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Website/Domain</label>
                <input type="url" name="domain" value="{{ old('domain') }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="https://example.com">
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-gray-300 text-sm font-medium mb-2">Business Description</label>
            <textarea name="business_description" rows="3"
                      class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Enter business description...">{{ old('business_description') }}</textarea>
        </div>
    </div>

    <!-- Owner Information -->
    <div class="border-t border-gray-600 pt-6">
        <h3 class="text-lg font-medium text-white mb-4 flex items-center">
            <i class="fas fa-user text-green-400 mr-2"></i>
            Owner Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">First Name *</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Last Name *</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Password *</label>
                <input type="password" name="password"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required
                       placeholder="Minimum 8 characters">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Profile Picture</label>
                <input type="file" name="profile_picture" accept="image/*"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-gray-600 file:text-white hover:file:bg-gray-500">
                <p class="text-gray-400 text-xs mt-1">Accepted formats: JPG, JPEG, PNG, GIF (Max: 2MB)</p>
            </div>
        </div>
    </div>

    <!-- Location Information -->
    <div class="border-t border-gray-600 pt-6">
        <h3 class="text-lg font-medium text-white mb-4 flex items-center">
            <i class="fas fa-map-marker-alt text-red-400 mr-2"></i>
            Location Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">City *</label>
                <input type="text" name="city" value="{{ old('city') }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Country *</label>
                <input type="text" name="country" value="{{ old('country') }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Zip Code</label>
                <input type="text" name="zip_code" value="{{ old('zip_code') }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    <!-- Category Information -->
    <div class="border-t border-gray-600 pt-6">
        <h3 class="text-lg font-medium text-white mb-4 flex items-center">
            <i class="fas fa-tags text-yellow-400 mr-2"></i>
            Category Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Category *</label>
                <div class="space-y-2">
                    <select name="category_id" id="category_id" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="text" name="category_text" placeholder="Or type category name..." value="{{ old('category_text') }}"
                           class="w-full p-2 text-sm bg-gray-600 border border-gray-500 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-gray-400 text-xs">You can either select from dropdown or type a category name</p>
                </div>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Subcategory *</label>
                <div class="space-y-2">
                    <select name="subcategory_id" id="subcategory_id" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Subcategory</option>
                        @foreach($subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}" data-category-id="{{ $subcategory->category_id }}" {{ old('subcategory_id') == $subcategory->id ? 'selected' : '' }}>
                                {{ $subcategory->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="text" name="subcategory_text" placeholder="Or type subcategory name..." value="{{ old('subcategory_text') }}"
                           class="w-full p-2 text-sm bg-gray-600 border border-gray-500 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-gray-400 text-xs">You can either select from dropdown or type a subcategory name</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Business Size Information -->
    <div class="border-t border-gray-600 pt-6">
        <h3 class="text-lg font-medium text-white mb-4 flex items-center">
            <i class="fas fa-chart-bar text-purple-400 mr-2"></i>
            Business Size Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Annual Revenue *</label>
                <select name="annual_revenue" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Select Revenue Range</option>
                    <option value="0-10000" {{ old('annual_revenue') === '0-10000' ? 'selected' : '' }}>$0 - $10,000</option>
                    <option value="10000-99999" {{ old('annual_revenue') === '10000-99999' ? 'selected' : '' }}>$10,000 - $99,999</option>
                    <option value="100000-499999" {{ old('annual_revenue') === '100000-499999' ? 'selected' : '' }}>$100,000 - $499,999</option>
                    <option value="500000-999999" {{ old('annual_revenue') === '500000-999999' ? 'selected' : '' }}>$500,000 - $999,999</option>
                    <option value="1000000+" {{ old('annual_revenue') === '1000000+' ? 'selected' : '' }}>$1,000,000+</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Employee Count *</label>
                <select name="employee_count" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Select Employee Range</option>
                    <option value="1-10" {{ old('employee_count') === '1-10' ? 'selected' : '' }}>1-10</option>
                    <option value="11-50" {{ old('employee_count') === '11-50' ? 'selected' : '' }}>11-50</option>
                    <option value="51-200" {{ old('employee_count') === '51-200' ? 'selected' : '' }}>51-200</option>
                    <option value="201-500" {{ old('employee_count') === '201-500' ? 'selected' : '' }}>201-500</option>
                    <option value="500+" {{ old('employee_count') === '500+' ? 'selected' : '' }}>500+</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="border-t border-gray-600 pt-6">
        <h3 class="text-lg font-medium text-white mb-4 flex items-center">
            <i class="fas fa-info-circle text-indigo-400 mr-2"></i>
            Additional Information
        </h3>
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Referred By</label>
            <input type="text" name="referred_by" value="{{ old('referred_by') }}"
                   class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <!-- Plan & Document -->
    <div class="border-t border-gray-600 pt-6">
        <h3 class="text-lg font-medium text-white mb-4 flex items-center">
            <i class="fas fa-file-alt text-orange-400 mr-2"></i>
            Plan & Document
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Plan</label>
                <select name="plan_id" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">No Plan Selected</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} - ${{ $plan->price }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Document Upload</label>
                <input type="file" name="document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-gray-400 text-xs mt-1">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB)</p>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-900 border border-red-700 text-red-300 p-4 rounded-lg">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle text-red-400 mr-2"></i>
                <strong>Please fix the following errors:</strong>
            </div>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="border-t border-gray-600 pt-8 flex justify-end space-x-4">
        <a href="{{ route('admin.properties.index') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 flex items-center">
            <i class="fas fa-times mr-2"></i>
            Cancel
        </a>
        <button type="submit"
                class="bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Create Property
        </button>
    </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_id');
        const subcategorySelect = document.getElementById('subcategory_id');

        if (!categorySelect || !subcategorySelect) {
            console.error('Category or subcategory select elements not found');
            return;
        }

        // Category-Subcategory dependency function
        function filterSubcategories() {
            const selectedCategoryId = categorySelect.value;
            const subcategoryOptions = subcategorySelect.querySelectorAll('option');

            // Reset subcategory selection
            subcategorySelect.value = '';

            // Show/hide subcategory options based on selected category
            subcategoryOptions.forEach(function(option) {
                if (option.value === '') {
                    option.style.display = 'block'; // Always show "Select Subcategory"
                } else {
                    const categoryId = option.getAttribute('data-category-id');
                    option.style.display = (categoryId === selectedCategoryId) ? 'block' : 'none';
                }
            });

            // If no category selected, hide all subcategories except placeholder
            if (!selectedCategoryId) {
                subcategoryOptions.forEach(function(option) {
                    if (option.value !== '') {
                        option.style.display = 'none';
                    }
                });
            }
        }

        // Attach event listener
        categorySelect.addEventListener('change', filterSubcategories);

        // Trigger the change event on page load if category is already selected
        if (categorySelect.value) {
            filterSubcategories();
        }
    });
</script>
@endpush

@endsection
