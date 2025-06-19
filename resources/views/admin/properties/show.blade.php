@extends('layouts.admin')

@section('active-properties', 'menu-item-active')
@section('page-title', 'Property Details')
@section('page-subtitle', 'View property information and details')

@section('content')
<div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-white mb-1">Property Details</h2>
                <p class="text-gray-300 text-sm">{{ $property->business_name }}</p>
            </div>
            <div class="mt-3 sm:mt-0 flex space-x-2">
                <a href="{{ route('admin.properties.edit', $property->id) }}"
                   class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Property
                </a>
                <a href="{{ route('admin.properties.index') }}"
                   class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Property Information -->
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Business Information -->
            <div class="bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-building text-blue-400 mr-2"></i>
                    Business Information
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-300">Business Name</label>
                        <p class="text-white">{{ $property->business_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-300">Business Email</label>
                        <p class="text-white">{{ $property->business_email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-300">Property Type</label>
                        <p class="text-white">
                            <span class="px-2 py-1 rounded-full text-xs
                                {{ $property->property_type === 'web' ? 'bg-green-800 text-green-200' : 'bg-purple-800 text-purple-200' }}">
                                {{ ucfirst($property->property_type) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-300">Status</label>
                        <p class="text-white">
                            <span class="px-2 py-1 rounded-full text-xs
                                @if($property->status === 'Approved') bg-green-800 text-green-200
                                @elseif($property->status === 'Rejected') bg-red-800 text-red-200
                                @else bg-yellow-800 text-yellow-200
                                @endif">
                                {{ $property->status }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-user text-green-400 mr-2"></i>
                    Contact Information
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-300">Contact Name</label>
                        <p class="text-white">{{ $property->first_name }} {{ $property->last_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-300">Location</label>
                        <p class="text-white">{{ $property->city }}, {{ $property->country }}</p>
                    </div>
                    @if($property->zip_code)
                    <div>
                        <label class="text-sm font-medium text-gray-300">Zip Code</label>
                        <p class="text-white">{{ $property->zip_code }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Business Details -->
            <div class="bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-chart-bar text-yellow-400 mr-2"></i>
                    Business Details
                </h3>
                <div class="space-y-3">
                    @if($property->category)
                    <div>
                        <label class="text-sm font-medium text-gray-300">Category</label>
                        <p class="text-white">{{ $property->category }}</p>
                    </div>
                    @endif
                    @if($property->subcategory)
                    <div>
                        <label class="text-sm font-medium text-gray-300">Subcategory</label>
                        <p class="text-white">{{ $property->subcategory }}</p>
                    </div>
                    @endif
                    @if($property->annual_revenue)
                    <div>
                        <label class="text-sm font-medium text-gray-300">Annual Revenue</label>
                        <p class="text-white">{{ $property->annual_revenue }}</p>
                    </div>
                    @endif
                    @if($property->employee_count)
                    <div>
                        <label class="text-sm font-medium text-gray-300">Employee Count</label>
                        <p class="text-white">{{ $property->employee_count }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-info-circle text-red-400 mr-2"></i>
                    Additional Information
                </h3>
                <div class="space-y-3">
                    @if($property->domain)
                    <div>
                        <label class="text-sm font-medium text-gray-300">Domain</label>
                        <p class="text-white">
                            <a href="https://{{ $property->domain }}" target="_blank" class="text-blue-400 hover:text-blue-300 transition-colors">
                                {{ $property->domain }}
                                <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                            </a>
                        </p>
                    </div>
                    @endif
                    @if($property->referred_by)
                    <div>
                        <label class="text-sm font-medium text-gray-300">Referred By</label>
                        <p class="text-white">{{ $property->referred_by }}</p>
                    </div>
                    @endif
                    @if($property->plan_id)
                    <div>
                        <label class="text-sm font-medium text-gray-300">Plan ID</label>
                        <p class="text-white">{{ $property->plan_id }}</p>
                    </div>
                    @endif
                    @if($property->document_path)
                    <div>
                        <label class="text-sm font-medium text-gray-300">Document</label>
                        <p class="text-white">
                            <a href="{{ Storage::url($property->document_path) }}" target="_blank" class="text-blue-400 hover:text-blue-300 transition-colors">
                                <i class="fas fa-file mr-1"></i>
                                View Document
                            </a>
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="mt-6 bg-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-clock text-purple-400 mr-2"></i>
                Timeline
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-300">Created At</label>
                    <p class="text-white">{{ $property->created_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-300">Last Updated</label>
                    <p class="text-white">{{ $property->updated_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        @if($property->status === 'Not Approved')
        <div class="mt-6 flex flex-wrap gap-3">
            <form action="{{ route('admin.properties.approve', $property->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-6 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <i class="fas fa-check mr-2"></i>
                    Approve Property
                </button>
            </form>
            <form action="{{ route('admin.properties.reject', $property->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-6 py-2 rounded-lg transition-colors duration-200 flex items-center" onclick="return confirm('Are you sure you want to reject this property?')">
                    <i class="fas fa-times mr-2"></i>
                    Reject Property
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
