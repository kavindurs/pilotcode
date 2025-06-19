@extends('layouts.admin')

@section('active-subcategories', 'menu-item-active')
@section('page-title', 'Subcategories Management')
@section('page-subtitle', 'Manage all subcategories, approve, reject, edit or delete them.')

@section('content')
<div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-white">Subcategories</h2>
                <p class="text-gray-400 text-sm">Total: {{ $subcategories->total() }} subcategories</p>
            </div>

            <!-- Search & Filter Form -->
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.subcategories.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Subcategory
                </a>

                <form method="GET" action="{{ route('admin.subcategories.index') }}" class="flex items-center gap-2">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search subcategories..."
                            class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg pl-10 pr-4 py-2 w-64 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>

                    <select name="category" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $categoryFilter == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                    @if($search || $categoryFilter)
                        <a href="{{ route('admin.subcategories.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Subcategories Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-900">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Subcategory</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Parent Category</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-4 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                @forelse($subcategories as $subcategory)
                <tr class="hover:bg-gray-750 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                    <i class="fas fa-tag text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-white">{{ $subcategory->name }}</div>
                                <div class="text-sm text-gray-400">{{ $subcategory->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <span class="bg-blue-900 text-blue-300 px-2 py-1 rounded-full text-xs font-medium">
                                {{ $subcategory->category->name }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-300">
                            {{ Str::limit($subcategory->description, 100) ?: 'No description' }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($subcategory->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                <i class="fas fa-check-circle mr-1"></i>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-300">
                                <i class="fas fa-times-circle mr-1"></i>
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-400">
                        {{ $subcategory->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <!-- View Button -->
                            <a
                                href="{{ route('admin.subcategories.show', $subcategory->id) }}"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                title="View Subcategory"
                            >
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- Edit Button -->
                            <button
                                onclick="openEditModal({{ $subcategory->id }})"
                                class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                title="Edit Subcategory"
                            >
                                <i class="fas fa-edit"></i>
                            </button>

                            @if($subcategory->is_active)
                                <!-- Reject Button -->
                                <form method="POST" action="{{ route('admin.subcategories.reject', $subcategory->id) }}" class="inline">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="bg-yellow-600 hover:bg-yellow-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                        title="Deactivate Subcategory"
                                        onclick="return confirm('Are you sure you want to deactivate this subcategory?')"
                                    >
                                        <i class="fas fa-pause"></i>
                                    </button>
                                </form>
                            @else
                                <!-- Approve Button -->
                                <form method="POST" action="{{ route('admin.subcategories.approve', $subcategory->id) }}" class="inline">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                        title="Activate Subcategory"
                                        onclick="return confirm('Are you sure you want to activate this subcategory?')"
                                    >
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif

                            <!-- Delete Button -->
                            <form method="POST" action="{{ route('admin.subcategories.destroy', $subcategory->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                    title="Delete Subcategory"
                                    onclick="return confirm('Are you sure you want to delete this subcategory? This action cannot be undone.')"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-tags text-4xl text-gray-600 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-400 mb-2">No subcategories found</h3>
                            <p class="text-gray-500">
                                @if($search || $categoryFilter)
                                    No subcategories match your search criteria.
                                @else
                                    Get started by adding your first subcategory.
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($subcategories->hasPages())
    <div class="bg-gray-900 px-6 py-4 border-t border-gray-700">
        {{ $subcategories->links('custom.pagination') }}
    </div>
    @endif
</div>

<!-- Edit Subcategory Modal -->
<div id="editSubcategoryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-700">
            <h3 class="text-xl font-semibold text-white">Edit Subcategory</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="editSubcategoryForm" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_category_id" class="block text-sm font-medium text-gray-300 mb-2">Parent Category</label>
                    <select
                        id="edit_category_id"
                        name="category_id"
                        required
                        class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="edit_name" class="block text-sm font-medium text-gray-300 mb-2">Subcategory Name</label>
                    <input
                        type="text"
                        id="edit_name"
                        name="name"
                        required
                        class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
            </div>

            <div>
                <label for="edit_slug" class="block text-sm font-medium text-gray-300 mb-2">Slug</label>
                <input
                    type="text"
                    id="edit_slug"
                    name="slug"
                    required
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>

            <div>
                <label for="edit_description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                <textarea
                    id="edit_description"
                    name="description"
                    rows="3"
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                ></textarea>
            </div>

            <div class="flex items-center">
                <input
                    type="checkbox"
                    id="edit_is_active"
                    name="is_active"
                    value="1"
                    class="h-4 w-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500"
                >
                <label for="edit_is_active" class="ml-2 text-sm text-gray-300">Active</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-700">
                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="px-4 py-2 text-gray-400 hover:text-white transition-colors"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors font-medium"
                >
                    Update Subcategory
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let subcategories = @json($subcategories->items());

function openEditModal(subcategoryId) {
    const subcategory = subcategories.find(s => s.id === subcategoryId);
    if (!subcategory) return;

    document.getElementById('edit_category_id').value = subcategory.category_id;
    document.getElementById('edit_name').value = subcategory.name;
    document.getElementById('edit_slug').value = subcategory.slug;
    document.getElementById('edit_description').value = subcategory.description || '';
    document.getElementById('edit_is_active').checked = subcategory.is_active == 1;

    document.getElementById('editSubcategoryForm').action = `/admin/subcategories/${subcategoryId}`;
    document.getElementById('editSubcategoryModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editSubcategoryModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('editSubcategoryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Auto-generate slug from name
document.getElementById('edit_name').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9 -]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    document.getElementById('edit_slug').value = slug;
});
</script>
@endsection
