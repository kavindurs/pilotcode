@extends('layouts.admin')

@section('active-properties', 'menu-item-active')
@section('page-title', 'Properties Management')
@section('page-subtitle', 'Manage and monitor all properties across your platform.')

@section('content')
<div class="bg-gray-800 border border-gray-700 shadow-xl rounded-xl p-6">
    <h2 class="text-2xl font-semibold mb-6 text-white flex items-center">
        <i class="fas fa-building text-red-400 mr-3"></i>
        Properties List
    </h2>

    <!-- Tabs for Web and Physical -->
    <div class="mb-6 border-b border-gray-700">
        <nav class="-mb-px flex">
            <a href="{{ route('admin.properties.index', ['tab' => 'web']) }}"
                class="mr-8 pb-3 pr-4 border-b-2 font-medium transition-colors duration-200 {{ $tab === 'web' ? 'border-red-500 text-red-400' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
                <i class="fas fa-globe mr-2"></i>Web
             </a>
            <a href="{{ route('admin.properties.index', ['tab' => 'physical']) }}"
               class="pb-3 border-b-2 font-medium transition-colors duration-200 {{ $tab === 'physical' ? 'border-red-500 text-red-400' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
                <i class="fas fa-store mr-2"></i>Physical
            </a>
        </nav>
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
                @foreach($properties as $property)
                <tr class="hover:bg-gray-750 transition-colors duration-200 {{ $property->status === 'Not Approved' ? 'bg-yellow-900 bg-opacity-20' : '' }}">
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
                        <div class="font-medium">{{ $property->category ?? 'N/A' }}</div>
                        @if($property->subcategory)
                            <div class="text-gray-400 text-xs">{{ $property->subcategory }}</div>
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
                        <span class="px-2 py-1 text-xs rounded-full {{ $property->status === 'Approved' ? 'bg-green-900 text-green-300 border border-green-700' : 'bg-yellow-900 text-yellow-300 border border-yellow-700' }}">
                            <i class="fas {{ $property->status === 'Approved' ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                            {{ $property->status }}
                        </span>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-200">
                        <div class="text-xs">{{ $property->created_at ? $property->created_at->format('M d, Y') : 'N/A' }}</div>
                        <div class="text-gray-400 text-xs">{{ $property->created_at ? $property->created_at->format('H:i') : '' }}</div>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap text-sm">
                        <!-- Single Row with Icon-Only Buttons -->
                        <div class="flex space-x-1.5">
                            @if($property->status === 'Not Approved')
                                <!-- Approve Button -->
                                <form action="{{ route('admin.properties.approve', $property->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" title="Approve" class="bg-green-600 hover:bg-green-500 transition-colors text-white w-8 h-8 rounded-md flex items-center justify-center shadow-sm">
                                        <i class="fas fa-check text-sm"></i>
                                    </button>
                                </form>
                                <!-- Reject Button -->
                                <form action="{{ route('admin.properties.reject', $property->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" title="Reject" class="bg-red-600 hover:bg-red-500 transition-colors text-white w-8 h-8 rounded-md flex items-center justify-center shadow-sm">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </form>
                            @else
                                <!-- Placeholder for approved items to maintain alignment -->
                                <div class="w-8 h-8"></div>
                                <div class="w-8 h-8"></div>
                            @endif

                            <!-- Edit Button -->
                            <button type="button" data-edit-url="{{ route('admin.properties.edit', $property->id) }}" title="Edit" class="btn-edit bg-blue-600 hover:bg-blue-500 transition-colors text-white w-8 h-8 rounded-md flex items-center justify-center shadow-sm">
                                <i class="fas fa-edit text-sm"></i>
                            </button>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.properties.destroy', $property->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this property?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Delete" class="bg-gray-600 hover:bg-gray-500 transition-colors text-white w-8 h-8 rounded-md flex items-center justify-center shadow-sm">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
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
          <i class="fas fa-edit text-blue-400 mr-2"></i>
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
document.addEventListener("DOMContentLoaded", function(){
    const modal = document.getElementById('editModal');
    const modalBody = document.getElementById('modalBody');
    const closeModalButtons = document.querySelectorAll('.modal-close');

    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function(){
            const editUrl = this.getAttribute('data-edit-url');
            // Load the edit form via AJAX
            fetch(editUrl)
                .then(response => response.text())
                .then(html => {
                    modalBody.innerHTML = html;
                    modal.classList.remove('hidden');

                    console.log('Modal loaded successfully, initializing subcategory dropdown...');
                    // Initialize subcategory functionality after modal content is loaded
                    initializeSubcategoryDropdown();
                })
                .catch(error => console.error('Error loading edit form:', error));
        });
    });

    closeModalButtons.forEach(btn => {
        btn.addEventListener('click', function(){
            modal.classList.add('hidden');
        });
    });

    // Optionally, close modal when clicking outside the modal content
    window.addEventListener('click', function(e) {
        if(e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Function to initialize subcategory dropdown functionality
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
});
</script>
@endsection
