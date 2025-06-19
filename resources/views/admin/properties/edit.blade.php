@extends('layouts.popup')

@section('title', 'Edit Property')

@section('content')
<form action="{{ route('admin.properties.update', $property->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    <!-- Property Type & Basic Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Property Type</label>
            <select name="property_type" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="web" {{ old('property_type', $property->property_type) === 'web' ? 'selected' : '' }}>Web</option>
                <option value="physical" {{ old('property_type', $property->property_type) === 'physical' ? 'selected' : '' }}>Physical</option>
            </select>
        </div>
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Status</label>
            <select name="status" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="Not Approved" {{ old('status', $property->status) === 'Not Approved' ? 'selected' : '' }}>Not Approved</option>
                <option value="Approved" {{ old('status', $property->status) === 'Approved' ? 'selected' : '' }}>Approved</option>
            </select>
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
                <input type="text" name="business_name" value="{{ old('business_name', $property->business_name) }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Business Email *</label>
                <input type="email" name="business_email" value="{{ old('business_email', $property->business_email) }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
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
                <input type="text" name="first_name" value="{{ old('first_name', $property->first_name) }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Last Name *</label>
                <input type="text" name="last_name" value="{{ old('last_name', $property->last_name) }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
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
                <input type="text" name="city" value="{{ old('city', $property->city) }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Country *</label>
                <input type="text" name="country" value="{{ old('country', $property->country) }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">ZIP Code</label>
                <input type="text" name="zip_code" value="{{ old('zip_code', $property->zip_code) }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    <!-- Business Categories -->
    <div class="border-t border-gray-600 pt-6">
        <h3 class="text-lg font-medium text-white mb-4 flex items-center">
            <i class="fas fa-tags text-yellow-400 mr-2"></i>
            Business Categories
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Category</label>
                <select name="category_id" id="category_id" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                                {{ old('category_id', $property->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Subcategory</label>
                <select name="subcategory_id" id="subcategory_id"
                        data-selected-subcategory="{{ old('subcategory_id', $property->subcategory_id) }}"
                        class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Subcategory</option>
                    @if($property->subcategory_id)
                        @foreach($subcategories->where('category_id', $property->category_id) as $subcategory)
                            <option value="{{ $subcategory->id }}"
                                    {{ old('subcategory_id', $property->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                                {{ $subcategory->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>

    <!-- Business Size -->
    <div class="border-t border-gray-600 pt-6">
        <h3 class="text-lg font-medium text-white mb-4 flex items-center">
            <i class="fas fa-chart-bar text-purple-400 mr-2"></i>
            Business Size
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Annual Revenue</label>
                <select name="annual_revenue" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Revenue Range</option>
                    <option value="1-9999" {{ old('annual_revenue', $property->annual_revenue) === '1-9999' ? 'selected' : '' }}>$1 - $9,999</option>
                    <option value="10000-99999" {{ old('annual_revenue', $property->annual_revenue) === '10000-99999' ? 'selected' : '' }}>$10,000 - $99,999</option>
                    <option value="100000-999999" {{ old('annual_revenue', $property->annual_revenue) === '100000-999999' ? 'selected' : '' }}>$100,000 - $999,999</option>
                    <option value="1000000+" {{ old('annual_revenue', $property->annual_revenue) === '1000000+' ? 'selected' : '' }}>$1,000,000+</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Employee Count</label>
                <select name="employee_count" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Employee Count</option>
                    <option value="1-9" {{ old('employee_count', $property->employee_count) === '1-9' ? 'selected' : '' }}>1-9 employees</option>
                    <option value="10-49" {{ old('employee_count', $property->employee_count) === '10-49' ? 'selected' : '' }}>10-49 employees</option>
                    <option value="50-249" {{ old('employee_count', $property->employee_count) === '50-249' ? 'selected' : '' }}>50-249 employees</option>
                    <option value="250-999" {{ old('employee_count', $property->employee_count) === '250-999' ? 'selected' : '' }}>250-999 employees</option>
                    <option value="1000+" {{ old('employee_count', $property->employee_count) === '1000+' ? 'selected' : '' }}>1000+ employees</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Web/Physical Specific Fields -->
    <div class="border-t border-gray-600 pt-6">
        <h3 class="text-lg font-medium text-white mb-4 flex items-center">
            <i class="fas fa-globe text-indigo-400 mr-2"></i>
            Web/Physical Details
        </h3>
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Domain (for Web properties)</label>
                <input type="text" name="domain" value="{{ old('domain', $property->domain) }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="e.g., example.com">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Document (for Physical properties)</label>
                <input type="file" name="document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @if($property->document_path)
                    <p class="text-gray-400 text-sm mt-1">
                        Current: <a href="{{ Storage::url($property->document_path) }}" target="_blank" class="text-blue-400 hover:text-blue-300">{{ basename($property->document_path) }}</a>
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="border-t border-gray-600 pt-6">
        <h3 class="text-lg font-medium text-white mb-4 flex items-center">
            <i class="fas fa-info-circle text-gray-400 mr-2"></i>
            Additional Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Referred By</label>
                <input type="text" name="referred_by" value="{{ old('referred_by', $property->referred_by) }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Plan ID</label>
                <input type="number" name="plan_id" value="{{ old('plan_id', $property->plan_id) }}"
                       class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="border-t border-gray-600 pt-6 flex justify-end space-x-3">
        <button type="button" class="modal-close px-6 py-3 bg-gray-600 hover:bg-gray-500 text-white rounded-md transition-colors">
            Cancel
        </button>
        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-md transition-colors flex items-center">
            <i class="fas fa-save mr-2"></i>
            Update Property
        </button>
    </div>
</form>

@endsection
