@extends('layouts.business')

@section('title', 'Property Profile Settings')
@section('page-title', 'Property Profile Settings')
@section('page-subtitle', 'Update your personal information')

@section('active-settings', 'bg-blue-700')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-200 p-4 mb-6 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-200 p-4 mb-6 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('property.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Hidden fields for required but non-editable data -->
        <input type="hidden" name="property_type" value="{{ $property->property_type }}">
        <input type="hidden" name="business_name" value="{{ $property->business_name }}">
        <input type="hidden" name="business_email" value="{{ $property->business_email }}">
        <input type="hidden" name="domain" value="{{ $property->domain }}">
        <input type="hidden" name="city" value="{{ $property->city }}">
        <input type="hidden" name="country" value="{{ $property->country }}">
        <input type="hidden" name="zip_code" value="{{ $property->zip_code }}">
        <input type="hidden" name="category" value="{{ $property->category }}">
        <input type="hidden" name="subcategory" value="{{ $property->subcategory }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 col-span-full">Editable Information</h3>

            <!-- Profile Picture (Editable) -->
            <div class="col-span-full flex items-center space-x-6">
                <div class="h-24 w-24 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden relative">
                    @if($property->profile_picture)
                        <img src="{{ asset('storage/' . $property->profile_picture) }}" alt="Profile picture" class="h-full w-full object-cover">
                    @else
                        <div class="flex items-center justify-center h-full w-full bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-user text-3xl"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <label for="profile_picture" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Profile Picture</label>
                    <input type="file" id="profile_picture" name="profile_picture" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Recommended size: 256x256px. JPG, PNG or GIF.</p>
                    @error('profile_picture')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- First Name (Editable) -->
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $property->first_name) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @error('first_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Last Name (Editable) -->
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $property->last_name) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @error('last_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Annual Revenue (Editable) -->
            <div>
                <label for="annual_revenue" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Annual Revenue</label>
                <select id="annual_revenue" name="annual_revenue" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="1-9999" {{ $property->annual_revenue == '1-9999' ? 'selected' : '' }}>$1-9,999</option>
                    <option value="10000-99999" {{ $property->annual_revenue == '10000-99999' ? 'selected' : '' }}>$10,000-99,999</option>
                    <option value="100000-999999" {{ $property->annual_revenue == '100000-999999' ? 'selected' : '' }}>$100,000-999,999</option>
                    <option value="1000000+" {{ $property->annual_revenue == '1000000+' ? 'selected' : '' }}>More than $1 million</option>
                </select>
                @error('annual_revenue')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Employee Count (Editable) -->
            <div>
                <label for="employee_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Number of Employees</label>
                <select id="employee_count" name="employee_count" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="1-9" {{ $property->employee_count == '1-9' ? 'selected' : '' }}>1-9</option>
                    <option value="10-49" {{ $property->employee_count == '10-49' ? 'selected' : '' }}>10-49</option>
                    <option value="50-99" {{ $property->employee_count == '50-99' ? 'selected' : '' }}>50-99</option>
                    <option value="100-499" {{ $property->employee_count == '100-499' ? 'selected' : '' }}>100-499</option>
                    <option value="500-999" {{ $property->employee_count == '500-999' ? 'selected' : '' }}>500-999</option>
                    <option value="1000-9999" {{ $property->employee_count == '1000-9999' ? 'selected' : '' }}>1,000-9,999</option>
                    <option value="10000+" {{ $property->employee_count == '10000+' ? 'selected' : '' }}>More than 10,000</option>
                </select>
                @error('employee_count')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 col-span-full mt-6">Business Information (Read-only)</h3>

            <!-- Business Type (Read-only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Business Type</label>
                <div class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    {{ $property->property_type }}
                </div>
            </div>

            <!-- Business Name (Read-only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Business Name</label>
                <div class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    {{ $property->business_name }}
                </div>
            </div>

            <!-- Business Email (Read-only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Business Email</label>
                <div class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    {{ $property->business_email }}
                </div>
            </div>

            <!-- Domain (Read-only, only if Web business) -->
            @if($property->property_type == 'Web')
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Website Domain</label>
                <div class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    {{ $property->domain ?? 'Not specified' }}
                </div>
            </div>
            @endif

            <!-- Category (Read-only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                <div class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    {{ $property->category }}
                </div>
            </div>

            <!-- Subcategory (Read-only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subcategory</label>
                <div class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    {{ $property->subcategory }}
                </div>
            </div>

            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 col-span-full mt-6">Location Information (Read-only)</h3>

            <!-- City (Read-only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">City</label>
                <div class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    {{ $property->city }}
                </div>
            </div>

            <!-- Country (Read-only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Country</label>
                <div class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    {{ $property->country }}
                </div>
            </div>

            <!-- ZIP Code (Read-only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ZIP Code</label>
                <div class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    {{ $property->zip_code }}
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-6">
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors duration-300">
                Save Profile Settings
            </button>
        </div>
    </form>
</div>
@endsection
