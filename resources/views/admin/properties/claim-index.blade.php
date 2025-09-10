@extends('layouts.admin')

@section('active-claim-business', 'menu-item-active')
@section('page-title', 'Claim Business Management')
@section('page-subtitle', 'Manage business claim requests and property ownership.')

@section('content')
<div class="bg-gray-800 border border-gray-700 shadow-xl rounded-xl p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-white flex items-center">
            <i class="fas fa-hand-holding text-red-400 mr-3"></i>
            Claim Business List
        </h2>
        @if($canAddProperty)
            <a href="{{ route('admin.properties.create') }}"
               class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-800 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Add Property
            </a>
        @else
            <div class="bg-gray-500 cursor-not-allowed text-white font-medium py-2 px-4 rounded-lg opacity-50 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Add Property (Access Restricted)
            </div>
        @endif
    </div>

    <!-- Tabs for Web and Physical -->
    <div class="mb-6 border-b border-gray-700">
        <nav class="-mb-px flex">
            <a href="{{ route('admin.properties.claim-index', ['tab' => 'web']) }}"
                class="mr-8 pb-3 pr-4 border-b-2 font-medium transition-colors duration-200 {{ $tab === 'web' ? 'border-red-500 text-red-400' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
                <i class="fas fa-globe mr-2"></i>Web
             </a>
            <a href="{{ route('admin.properties.claim-index', ['tab' => 'physical']) }}"
               class="pb-3 border-b-2 font-medium transition-colors duration-200 {{ $tab === 'physical' ? 'border-red-500 text-red-400' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
                <i class="fas fa-store mr-2"></i>Physical
            </a>
        </nav>
    </div>

    <!-- Search Form for Claimable Businesses -->
    <div class="mb-6">
        <form method="GET" action="{{ route('admin.properties.claim-index') }}" class="flex flex-col sm:flex-row gap-4">
            <input type="hidden" name="tab" value="{{ $tab }}">

            <div class="flex-1">
                <div class="relative">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Search claimable businesses by name, owner, email, or location..."
                           class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 pl-10 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors flex items-center">
                    <i class="fas fa-search mr-2"></i>Search
                </button>

                @if($search)
                <a href="{{ route('admin.properties.claim-index', ['tab' => $tab]) }}"
                   class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors flex items-center">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
                @endif
            </div>
        </form>

        @if($search)
        <div class="mt-3 text-sm text-gray-400">
            <i class="fas fa-info-circle mr-1"></i>
            Showing search results for: <span class="text-white font-medium">"{{ $search }}"</span>
            <span class="text-gray-500">| {{ $properties->total() }} claimable business(es) found</span>
        </div>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-900 border border-green-700 text-green-300 p-4 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-900">
                <tr>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Business Info</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Location</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Category</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Business Size</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Contact</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Domain/Docs</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Plan</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Created</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                @forelse($properties as $property)
                <tr class="hover:bg-gray-750 transition-colors duration-200 {{ $property->status === 'Not Approved & Not Claimed' ? 'bg-orange-900 bg-opacity-20' : '' }}">
                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-200">
                        <div class="font-medium">#{{ $property->id }}</div>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 text-xs rounded-full {{ $property->property_type === 'web' ? 'bg-blue-900 text-blue-300 border border-blue-700' : 'bg-purple-900 text-purple-300 border border-purple-700' }}">
                            <i class="fas {{ $property->property_type === 'web' ? 'fa-globe' : 'fa-store' }} mr-1"></i>
                            {{ ucfirst($property->property_type ?? 'N/A') }}
                        </span>
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-200">
                        <div class="font-medium text-white">{{ $property->business_name }}</div>
                        <div class="text-gray-400 text-xs mt-1">
                            {{ $property->first_name }} {{ $property->last_name }}
                        </div>
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-200">
                        <div>{{ $property->city }}, {{ $property->country }}</div>
                        @if($property->zip_code)
                            <div class="text-gray-400 text-xs">{{ $property->zip_code }}</div>
                        @endif
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-200">
                        <div class="font-medium flex items-center">
                            @if($property->category_name)
                                <span>{{ $property->category_name }}</span>
                                @if(!$property->is_category_active)
                                    <span class="ml-2 px-1.5 py-1 bg-yellow-500 text-black rounded-full inline-flex items-center justify-center" title="Category is inactive">
                                        <i class="fas fa-exclamation-triangle text-xs"></i>
                                    </span>
                                @endif
                            @elseif($property->category)
                                <span>{{ is_object($property->category) ? $property->category->name : $property->category }}</span>
                                @if(is_object($property->category) && !$property->category->is_active)
                                    <span class="ml-2 px-1.5 py-1 bg-yellow-500 text-black rounded-full inline-flex items-center justify-center" title="Category is inactive">
                                        <i class="fas fa-exclamation-triangle text-xs"></i>
                                    </span>
                                @endif
                            @else
                                N/A
                            @endif
                        </div>
                        @if($property->subcategory)
                            <div class="text-gray-400 text-xs flex items-center mt-1">
                                @if($property->subcategory_name)
                                    <span>{{ $property->subcategory_name }}</span>
                                    @if(!$property->is_subcategory_active)
                                        <span class="ml-2 px-1.5 py-1 bg-yellow-500 text-black rounded-full inline-flex items-center justify-center" title="Subcategory is inactive">
                                            <i class="fas fa-exclamation-triangle text-xs"></i>
                                        </span>
                                    @endif
                                @else
                                    <span>{{ is_object($property->subcategory) ? $property->subcategory->name : $property->subcategory }}</span>
                                    @if(is_object($property->subcategory) && !$property->subcategory->is_active)
                                        <span class="ml-2 px-1.5 py-1 bg-yellow-500 text-black rounded-full inline-flex items-center justify-center" title="Subcategory is inactive">
                                            <i class="fas fa-exclamation-triangle text-xs"></i>
                                        </span>
                                    @endif
                                @endif
                            </div>
                        @endif
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-200">
                        @if($property->annual_revenue)
                            <div class="text-xs">
                                <i class="fas fa-dollar-sign text-green-400 mr-1"></i>
                                {{ $property->annual_revenue }}
                            </div>
                        @endif
                        @if($property->employee_count)
                            <div class="text-xs mt-1">
                                <i class="fas fa-users text-blue-400 mr-1"></i>
                                {{ $property->employee_count }} employees
                            </div>
                        @endif
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-200">
                        <div class="text-xs">{{ $property->business_email }}</div>
                        @if($property->referred_by)
                            <div class="text-gray-400 text-xs mt-1">
                                <i class="fas fa-user-friends mr-1"></i>
                                Ref: {{ $property->referred_by }}
                            </div>
                        @endif
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-200">
                        @if(strtolower(trim($property->property_type ?? '')) === 'web')
                            @if(!empty($property->domain))
                                @php
                                    $domain_url = $property->domain;
                                    // Check if domain already has protocol
                                    if (!str_starts_with($property->domain, 'http://') && !str_starts_with($property->domain, 'https://')) {
                                        $domain_url = 'https://' . $property->domain;
                                    }
                                @endphp
                                <a href="{{ $domain_url }}" target="_blank" class="text-blue-400 hover:text-blue-300 text-xs">
                                    <i class="fas fa-external-link-alt mr-1"></i>
                                    {{ Str::limit($property->domain, 25) }}
                                </a>
                            @else
                                <span class="text-gray-500 text-xs">
                                    <i class="fas fa-globe mr-1"></i>
                                    No domain
                                </span>
                            @endif
                        @elseif(strtolower(trim($property->property_type ?? '')) === 'physical')
                            @if(!empty($property->document_path))
                                <a href="{{ Storage::url($property->document_path) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-500 text-white text-xs rounded-md transition-colors shadow-sm">
                                    <i class="fas fa-download mr-1.5"></i>
                                    Download
                                </a>
                            @else
                                <span class="text-gray-500 text-xs">
                                    <i class="fas fa-file mr-1"></i>
                                    No document
                                </span>
                            @endif
                        @else
                            <span class="text-gray-500 text-xs">
                                <i class="fas fa-question mr-1"></i>
                                Type: {{ $property->property_type ?? 'NULL' }} | Domain: {{ $property->domain ?? 'NULL' }}
                            </span>
                        @endif
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap text-sm">
                        @if($property->plan_id)
                            <span class="px-2 py-1 text-xs rounded-full bg-indigo-900 text-indigo-300 border border-indigo-700">
                                <i class="fas fa-crown mr-1"></i>
                                Plan #{{ $property->plan_id }}
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-700 text-gray-400">
                                <i class="fas fa-gift mr-1"></i>
                                Free
                            </span>
                        @endif
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($property->status === 'Not Approved & Not Claimed') bg-orange-900 text-orange-300 border border-orange-700
                            @elseif($property->status === 'Not Claimed') bg-yellow-900 text-yellow-300 border border-yellow-700
                            @elseif($property->status === 'Not Claimed & Rejected') bg-red-900 text-red-300 border border-red-700
                            @else bg-gray-700 text-gray-400 @endif">
                            <i class="fas
                                @if($property->status === 'Not Approved & Not Claimed') fa-exclamation-triangle
                                @elseif($property->status === 'Not Claimed') fa-clock
                                @elseif($property->status === 'Not Claimed & Rejected') fa-times-circle
                                @else fa-question @endif mr-1"></i>
                            {{ $property->status }}
                        </span>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-200">
                        <div class="text-xs">{{ $property->created_at ? $property->created_at->format('M d, Y') : 'N/A' }}</div>
                        <div class="text-gray-400 text-xs">{{ $property->created_at ? $property->created_at->format('H:i') : '' }}</div>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap text-sm">
                        <!-- Action buttons with role-based permissions -->
                        <div class="flex items-center space-x-1.5">
                            @if($property->status === 'Not Approved & Not Claimed')
                                @if($hasFullPermissions)
                                    <!-- Approve for Claim Button -->
                                    <form action="{{ route('admin.properties.claim-approve', $property->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Are you sure you want to approve this property for claim?');" title="Approve for Claim" class="bg-green-600 hover:bg-green-500 transition-colors text-white w-8 h-8 rounded-md flex items-center justify-center shadow-sm">
                                            <i class="fas fa-check text-sm"></i>
                                        </button>
                                    </form>
                                    <!-- Reject for Claim Button -->
                                    <form action="{{ route('admin.properties.claim-reject', $property->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Are you sure you want to reject this property for claim?');" title="Reject for Claim" class="bg-red-600 hover:bg-red-500 transition-colors text-white w-8 h-8 rounded-md flex items-center justify-center shadow-sm">
                                            <i class="fas fa-times text-sm"></i>
                                        </button>
                                    </form>
                                    <!-- Claim Property Button -->
                                    <button onclick="claimProperty({{ $property->id }})" title="Claim Property" class="bg-purple-600 hover:bg-purple-500 transition-colors text-white w-8 h-8 rounded-md flex items-center justify-center shadow-sm">
                                        <i class="fas fa-hand-holding text-sm"></i>
                                    </button>
                                @else
                                    <!-- Disabled buttons for worker/other roles -->
                                    <button type="button" title="Approve for Claim (Access Restricted)" class="bg-gray-500 cursor-not-allowed text-white w-8 h-8 rounded-md flex items-center justify-center shadow-sm opacity-50" disabled>
                                        <i class="fas fa-check text-sm"></i>
                                    </button>
                                    <button type="button" title="Reject for Claim (Access Restricted)" class="bg-gray-500 cursor-not-allowed text-white w-8 h-8 rounded-md flex items-center justify-center shadow-sm opacity-50" disabled>
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                    <button type="button" title="Claim Property (Access Restricted)" class="bg-gray-500 cursor-not-allowed text-white w-8 h-8 rounded-md flex items-center justify-center shadow-sm opacity-50" disabled>
                                        <i class="fas fa-hand-holding text-sm"></i>
                                    </button>
                                @endif
                            @elseif($property->status === 'Not Claimed')
                                @if($hasFullPermissions)
                                    <!-- Claim Property Button -->
                                    <button onclick="claimProperty({{ $property->id }})" title="Claim Property" class="bg-purple-600 hover:bg-purple-500 transition-colors text-white w-8 h-8 rounded-md flex items-center justify-center shadow-sm">
                                        <i class="fas fa-hand-holding text-sm"></i>
                                    </button>
                                @else
                                    <!-- Disabled Claim Property Button -->
                                    <button type="button" title="Claim Property (Access Restricted)" class="bg-gray-500 cursor-not-allowed text-white w-8 h-8 rounded-md flex items-center justify-center shadow-sm opacity-50" disabled>
                                        <i class="fas fa-hand-holding text-sm"></i>
                                    </button>
                                @endif
                            @endif

                            <!-- Edit Button - Available for all admin users -->
                            @if($canEdit)
                                <button type="button" data-edit-url="{{ route('admin.properties.claim-edit', $property->id) }}" title="Edit" class="btn-edit bg-blue-600 hover:bg-blue-500 transition-colors text-white w-8 h-8 rounded-md flex items-center justify-center shadow-sm">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                            @else
                                <button type="button" title="Edit (Access Restricted)" class="bg-gray-500 cursor-not-allowed text-white w-8 h-8 rounded-md flex items-center justify-center shadow-sm opacity-50" disabled>
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                            @endif

                            <!-- Delete Button - Only for admin/super_admin -->
                            @if($hasFullPermissions)
                                <form action="{{ route('admin.properties.claim-destroy', $property->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this property?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete" class="bg-gray-600 hover:bg-gray-500 transition-colors text-white w-8 h-8 rounded-md flex items-center justify-center shadow-sm">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            @else
                                <button type="button" title="Delete (Access Restricted)" class="bg-gray-500 cursor-not-allowed text-white w-8 h-8 rounded-md flex items-center justify-center shadow-sm opacity-50" disabled>
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" class="px-6 py-8 text-center text-gray-400">
                        <i class="fas fa-inbox text-4xl mb-4 text-gray-600"></i>
                        <p>No claim business properties found.</p>
                    </td>
                </tr>
                @endforelse
        </table>
    </div>

    <!-- Enhanced Pagination -->
    @if($properties->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-400">
                Showing {{ $properties->firstItem() }} to {{ $properties->lastItem() }} of {{ $properties->total() }} results
            </div>
            <div class="pagination-wrapper">
                {{ $properties->appends(request()->query())->links('custom.pagination') }}
            </div>
        </div>
    @endif
</div>

<!-- Modal for Property Edit -->
<div id="editModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
    <!-- Background overlay -->
    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
      <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
    </div>

    <!-- Modal panel -->
    <div class="inline-block align-bottom bg-gray-800 border border-gray-700 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6 max-h-screen overflow-y-auto">
      <div class="flex justify-between items-center mb-4 sticky top-0 bg-gray-800 z-10 pb-4 border-b border-gray-700">
        <h3 class="text-lg font-semibold text-white flex items-center">
          <i class="fas fa-edit text-red-400 mr-2"></i>
          Edit Property
        </h3>
        <button type="button" class="modal-close text-gray-400 hover:text-white text-2xl font-bold transition-colors">&times;</button>
      </div>
      <div id="modalBody" class="text-gray-200">
         <!-- Edit form will be loaded here via AJAX -->
      </div>
    </div>
  </div>
</div>

<script>
// Global function to initialize subcategory dropdown functionality
function initializeSubcategoryDropdown() {
    console.log('Initializing subcategory dropdown...');

    const categorySelect = document.getElementById('category_id');
    const subcategorySelect = document.getElementById('subcategory_id');

    if (!categorySelect || !subcategorySelect) {
        console.error('Required elements not found!');
        return;
    }

    console.log('Category select element:', categorySelect);
    console.log('Subcategory select element:', subcategorySelect);

    function loadSubcategories(categoryId, selectedId = null) {
        console.log('loadSubcategories called with categoryId:', categoryId, 'selectedId:', selectedId);

        // Clear current subcategories
        subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';

        if (!categoryId) {
            console.log('No categoryId provided, returning');
            return;
        }

        // Show loading state
        subcategorySelect.innerHTML = '<option value="">Loading...</option>';
        subcategorySelect.disabled = true;

        const url = `/api/subcategories/${categoryId}`;
        console.log('Fetching URL:', url);

        // Fetch subcategories via AJAX
        fetch(url)
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                console.log('Data length:', data.length);

                subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';

                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(subcategory => {
                        console.log('Adding subcategory:', subcategory);
                        const option = document.createElement('option');
                        option.value = subcategory.id;
                        option.textContent = subcategory.name;

                        if (selectedId && subcategory.id == selectedId) {
                            option.selected = true;
                            console.log('Selected subcategory:', subcategory.name);
                        }

                        subcategorySelect.appendChild(option);
                    });
                } else {
                    console.log('No subcategories found for this category');
                    subcategorySelect.innerHTML = '<option value="">No subcategories available</option>';
                }

                subcategorySelect.disabled = false;
            })
            .catch(error => {
                console.error('Error loading subcategories:', error);
                subcategorySelect.innerHTML = '<option value="">Error loading subcategories</option>';
                subcategorySelect.disabled = false;
            });
    }

    // Load subcategories when category changes
    categorySelect.addEventListener('change', function() {
        console.log('Category changed to:', this.value);
        loadSubcategories(this.value);
    });

    // Load subcategories on page load if category is already selected
    if (categorySelect.value) {
        console.log('Loading subcategories on page load for category:', categorySelect.value);
        // Get the selected subcategory ID from the data attribute
        const selectedSubcategoryId = subcategorySelect.getAttribute('data-selected-subcategory') || '';
        console.log('Selected subcategory ID from data attribute:', selectedSubcategoryId);
        loadSubcategories(categorySelect.value, selectedSubcategoryId);
    }

    console.log('Subcategory functionality initialized successfully');
}

// Global function for claiming properties
function claimProperty(propertyId) {
    if (confirm('Are you sure you want to claim this property? This will open the edit form with status set to Approved.')) {
        // Load the edit form via AJAX with claim parameter
        const editUrl = `/admin/claim-business/${propertyId}/edit?claim=true`;
        const modal = document.getElementById('editModal');
        const modalBody = document.getElementById('modalBody');

        // Update modal title for claim action
        document.querySelector('#editModal h3').innerHTML = '<i class="fas fa-hand-holding text-red-400 mr-2"></i>Claim Property - Edit Details';

        fetch(editUrl)
            .then(response => response.text())
            .then(html => {
                modalBody.innerHTML = html;
                modal.classList.remove('hidden');

                console.log('Claim modal loaded successfully, initializing form functionality...');
                // Initialize subcategory functionality after modal content is loaded
                initializeSubcategoryDropdown();

                // Initialize property type handler if the function exists
                if (typeof initializePropertyTypeHandler === 'function') {
                    initializePropertyTypeHandler();
                }
            })
            .catch(error => console.error('Error loading claim form:', error));
    }
}

// Global function to close modal
function closeModal() {
    const modal = document.getElementById('editModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

document.addEventListener("DOMContentLoaded", function(){
    const modal = document.getElementById('editModal');
    const modalBody = document.getElementById('modalBody');
    const closeModalButtons = document.querySelectorAll('.modal-close');

    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function(){
            const editUrl = this.getAttribute('data-edit-url');

            // Reset modal title for edit action
            document.querySelector('#editModal h3').innerHTML = '<i class="fas fa-edit text-red-400 mr-2"></i>Edit Property';

            // Load the edit form via AJAX
            fetch(editUrl)
                .then(response => response.text())
                .then(html => {
                    modalBody.innerHTML = html;
                    modal.classList.remove('hidden');

                    console.log('Edit modal loaded successfully, initializing form functionality...');
                    // Initialize subcategory functionality after modal content is loaded
                    initializeSubcategoryDropdown();

                    // Initialize property type handler if the function exists
                    if (typeof initializePropertyTypeHandler === 'function') {
                        initializePropertyTypeHandler();
                    }
                })
                .catch(error => console.error('Error loading edit form:', error));
        });
    });

    closeModalButtons.forEach(btn => {
        btn.addEventListener('click', function(){
            closeModal();
        });
    });

    // Close modal when clicking outside the modal content
    window.addEventListener('click', function(e) {
        if(e.target === modal) {
            closeModal();
        }
    });
});
</script>
@endsection
