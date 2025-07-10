<form id="claimEditForm" action="{{ route('admin.properties.claim-update', $property->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Business Name -->
        <div>
            <label for="business_name" class="block text-sm font-medium text-gray-300">Business Name</label>
            <input type="text" name="business_name" id="business_name" value="{{ old('business_name', $property->business_name) }}"
                   class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md bg-gray-700 text-gray-200 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" required>
            @error('business_name')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Business Email -->
        <div>
            <label for="business_email" class="block text-sm font-medium text-gray-300">Business Email</label>
            <input type="email" name="business_email" id="business_email" value="{{ old('business_email', $property->business_email) }}"
                   class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md bg-gray-700 text-gray-200 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" required>
            @error('business_email')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Property Type -->
        <div>
            <label for="property_type" class="block text-sm font-medium text-gray-300">Property Type</label>
            <select name="property_type" id="property_type" class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md bg-gray-700 text-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" required>
                <option value="web" {{ $property->property_type === 'web' ? 'selected' : '' }}>Web</option>
                <option value="physical" {{ $property->property_type === 'physical' ? 'selected' : '' }}>Physical</option>
            </select>
            @error('property_type')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- First Name -->
        <div>
            <label for="first_name" class="block text-sm font-medium text-gray-300">First Name</label>
            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $property->first_name) }}"
                   class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md bg-gray-700 text-gray-200 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" required>
            @error('first_name')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Last Name -->
        <div>
            <label for="last_name" class="block text-sm font-medium text-gray-300">Last Name</label>
            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $property->last_name) }}"
                   class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md bg-gray-700 text-gray-200 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" required>
            @error('last_name')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Country -->
        <div>
            <label for="country" class="block text-sm font-medium text-gray-300">Country</label>
            <input type="text" name="country" id="country" value="{{ old('country', $property->country) }}"
                   class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md bg-gray-700 text-gray-200 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" required>
            @error('country')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block text-sm font-medium text-gray-300">Status</label>
            @if(isset($isClaim) && $isClaim)
                <input type="hidden" name="status" value="Approved">
                <input type="text" value="Approved" readonly
                       class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md bg-gray-600 text-gray-400 shadow-sm cursor-not-allowed">
                <p class="mt-1 text-sm text-red-400">Status is automatically set to "Approved" when claiming a property.</p>
            @else
                <select name="status" id="status" class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md bg-gray-700 text-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" required>
                    <option value="Not Claimed" {{ $property->status === 'Not Claimed' ? 'selected' : '' }}>Not Claimed</option>
                    <option value="Not Approved & Not Claimed" {{ $property->status === 'Not Approved & Not Claimed' ? 'selected' : '' }}>Not Approved & Not Claimed</option>
                    <option value="Not Claimed & Rejected" {{ $property->status === 'Not Claimed & Rejected' ? 'selected' : '' }}>Not Claimed & Rejected</option>
                </select>
            @endif
            @error('status')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
            <input type="password" name="password" id="password" value="{{ old('password') }}"
                   class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md bg-gray-700 text-gray-200 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                   placeholder="Leave blank to keep current password">
            @error('password')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Domain/Document Field - Dynamic based on Property Type -->
        <div id="domain-field" style="display: {{ $property->property_type === 'web' ? 'block' : 'none' }}">
            <label for="domain" class="block text-sm font-medium text-gray-300">Domain</label>
            <input type="url" name="domain" id="domain" value="{{ old('domain', $property->domain) }}"
                   class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md bg-gray-700 text-gray-200 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                   placeholder="https://example.com">
            @error('domain')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div id="document-field" style="display: {{ $property->property_type === 'physical' ? 'block' : 'none' }}">
            <label for="document" class="block text-sm font-medium text-gray-300">Business Document</label>
            @if($property->document_path)
                <div class="mt-2 mb-2">
                    <a href="{{ Storage::url($property->document_path) }}" target="_blank" class="text-red-400 hover:text-red-300 flex items-center">
                        <i class="fas fa-file-alt mr-2"></i>
                        View Current Document
                    </a>
                </div>
            @endif
            <input type="file" name="document" id="document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                   class="mt-1 block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-red-600 file:text-white hover:file:bg-red-700">
            <p class="mt-1 text-sm text-gray-400">Upload PDF, DOC or DOCX (max 10MB)</p>
            @error('document')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- City -->
        <div>
            <label for="city" class="block text-sm font-medium text-gray-300">City</label>
            <input type="text" name="city" id="city" value="{{ old('city', $property->city) }}"
                   class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md bg-gray-700 text-gray-200 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" required>
            @error('city')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- ZIP Code -->
        <div>
            <label for="zip_code" class="block text-sm font-medium text-gray-300">ZIP Code</label>
            <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code', $property->zip_code) }}"
                   class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md bg-gray-700 text-gray-200 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" required>
            @error('zip_code')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Annual Revenue -->
        <div>
            <label for="annual_revenue" class="block text-sm font-medium text-gray-300">Annual Revenue</label>
            <select name="annual_revenue" id="annual_revenue" class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md bg-gray-700 text-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                <option value="">Select Annual Revenue</option>
                <option value="Under $10,000" {{ old('annual_revenue', $property->annual_revenue) === 'Under $10,000' ? 'selected' : '' }}>Under $10,000</option>
                <option value="$10,000 - $50,000" {{ old('annual_revenue', $property->annual_revenue) === '$10,000 - $50,000' ? 'selected' : '' }}>$10,000 - $50,000</option>
                <option value="$50,000 - $100,000" {{ old('annual_revenue', $property->annual_revenue) === '$50,000 - $100,000' ? 'selected' : '' }}>$50,000 - $100,000</option>
                <option value="$100,000 - $500,000" {{ old('annual_revenue', $property->annual_revenue) === '$100,000 - $500,000' ? 'selected' : '' }}>$100,000 - $500,000</option>
                <option value="$500,000 - $1,000,000" {{ old('annual_revenue', $property->annual_revenue) === '$500,000 - $1,000,000' ? 'selected' : '' }}>$500,000 - $1,000,000</option>
                <option value="Over $1,000,000" {{ old('annual_revenue', $property->annual_revenue) === 'Over $1,000,000' ? 'selected' : '' }}>Over $1,000,000</option>
            </select>
            @error('annual_revenue')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Employee Count -->
        <div>
            <label for="employee_count" class="block text-sm font-medium text-gray-300">Employee Count</label>
            <select name="employee_count" id="employee_count" class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md bg-gray-700 text-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                <option value="">Select Employee Count</option>
                <option value="1-5" {{ old('employee_count', $property->employee_count) === '1-5' ? 'selected' : '' }}>1-5</option>
                <option value="6-10" {{ old('employee_count', $property->employee_count) === '6-10' ? 'selected' : '' }}>6-10</option>
                <option value="11-25" {{ old('employee_count', $property->employee_count) === '11-25' ? 'selected' : '' }}>11-25</option>
                <option value="26-50" {{ old('employee_count', $property->employee_count) === '26-50' ? 'selected' : '' }}>26-50</option>
                <option value="51-100" {{ old('employee_count', $property->employee_count) === '51-100' ? 'selected' : '' }}>51-100</option>
                <option value="101-500" {{ old('employee_count', $property->employee_count) === '101-500' ? 'selected' : '' }}>101-500</option>
                <option value="Over 500" {{ old('employee_count', $property->employee_count) === 'Over 500' ? 'selected' : '' }}>Over 500</option>
            </select>
            @error('employee_count')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Category -->
        <div>
            <label for="category_id" class="block text-sm font-medium text-gray-300">Category</label>
            <select name="category" id="category_id" class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md bg-gray-700 text-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" required>
                <option value="">Select Category</option>
                @foreach($categories as $categoryOption)
                    @php
                        $isSelected = false;
                        if (is_numeric($property->category)) {
                            $isSelected = $property->category == $categoryOption->id;
                        } else {
                            $isSelected = $property->category == $categoryOption->name;
                        }
                    @endphp
                    <option value="{{ $categoryOption->id }}" {{ $isSelected ? 'selected' : '' }}>
                        {{ $categoryOption->name }}
                    </option>
                @endforeach
            </select>
            @error('category')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Subcategory -->
        <div>
            <label for="subcategory_id" class="block text-sm font-medium text-gray-300">Subcategory</label>
            <select name="subcategory" id="subcategory_id" class="mt-1 block w-full px-3 py-2 border border-gray-600 rounded-md bg-gray-700 text-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                    data-selected-subcategory="@if(is_numeric($property->subcategory)){{ $property->subcategory }}@else{{ $subcategories->where('name', $property->subcategory)->first()->id ?? '' }}@endif" required>
                <option value="">Select Subcategory</option>
                @foreach($subcategories as $subcategoryOption)
                    @php
                        $isSelected = false;
                        if (is_numeric($property->subcategory)) {
                            $isSelected = $property->subcategory == $subcategoryOption->id;
                        } else {
                            $isSelected = $property->subcategory == $subcategoryOption->name;
                        }
                    @endphp
                    <option value="{{ $subcategoryOption->id }}" {{ $isSelected ? 'selected' : '' }}>
                        {{ $subcategoryOption->name }}
                    </option>
                @endforeach
            </select>
            @error('subcategory')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Form Footer -->
    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-600">
        <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-300 bg-gray-700 border border-gray-600 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
            Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
            Update Property
        </button>
    </div>
</form>

<script>
// Function to close modal
function closeModal() {
    const modal = document.getElementById('editModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Handle property type change to show/hide domain and document fields
function togglePropertyFields() {
    const propertyTypeSelect = document.getElementById('property_type');
    const domainField = document.getElementById('domain-field');
    const documentField = document.getElementById('document-field');

    console.log('togglePropertyFields called');
    console.log('Property type select:', propertyTypeSelect);
    console.log('Domain field:', domainField);
    console.log('Document field:', documentField);

    if (!propertyTypeSelect || !domainField || !documentField) {
        console.log('Elements not found, retrying in 500ms...');
        setTimeout(togglePropertyFields, 500);
        return;
    }

    const propertyType = propertyTypeSelect.value;
    console.log('Property type changed to:', propertyType);

    if (propertyType === 'web') {
        domainField.style.display = 'block';
        documentField.style.display = 'none';
        console.log('✅ Showing domain field, hiding document field');
    } else if (propertyType === 'physical') {
        domainField.style.display = 'none';
        documentField.style.display = 'block';
        console.log('✅ Hiding domain field, showing document field');
    } else {
        domainField.style.display = 'none';
        documentField.style.display = 'none';
        console.log('✅ Hiding both fields');
    }
}

// Initialize property type functionality
function initializePropertyTypeHandler() {
    console.log('🔄 Initializing property type handler...');

    const propertyTypeSelect = document.getElementById('property_type');
    if (propertyTypeSelect) {
        // Remove any existing event listeners to prevent duplicates
        propertyTypeSelect.removeEventListener('change', togglePropertyFields);

        // Add new event listener
        propertyTypeSelect.addEventListener('change', function() {
            console.log('🔄 Property type dropdown changed');
            togglePropertyFields();
        });

        // Initialize on page load
        togglePropertyFields();

        console.log('✅ Property type handler initialized successfully');
    } else {
        console.error('❌ Property type select element not found!');
        // Retry after a short delay
        setTimeout(initializePropertyTypeHandler, 300);
    }
}

// Initialize when this script loads (for modal context)
setTimeout(function() {
    console.log('🚀 Starting initialization...');
    initializePropertyTypeHandler();

    // Initialize subcategory dropdown functionality if the function exists
    if (typeof initializeSubcategoryDropdown === 'function') {
        console.log('🚀 Initializing subcategory dropdown...');
        initializeSubcategoryDropdown();
    }
}, 200);

// Also try again after a longer delay to ensure everything is loaded
setTimeout(function() {
    console.log('🔄 Secondary initialization attempt...');
    initializePropertyTypeHandler();
}, 1000);

// Handle AJAX form submission for modal
document.addEventListener('DOMContentLoaded', function() {
    // Use event delegation for form submission since the form is loaded dynamically
    document.addEventListener('submit', function(e) {
        if (e.target && e.target.id === 'claimEditForm') {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.textContent;

            // Show loading state
            submitButton.disabled = true;
            submitButton.textContent = 'Updating...';

            // Set CSRF token header
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': token
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert('Property updated successfully!');

                    // Close modal
                    closeModal();

                    // Reload page to show updated data
                    location.reload();
                } else {
                    alert('Error updating property: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating property. Please try again.');
            })
            .finally(() => {
                // Reset button state
                submitButton.disabled = false;
                submitButton.textContent = originalButtonText;
            });
        }
    });
});
</script>
